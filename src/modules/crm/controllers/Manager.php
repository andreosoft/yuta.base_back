<?php

namespace modules\crm\controllers;

class Manager extends \base\core\Controller  {


    public function preAction($action) {
        $this->corsHeaders();
        if (\A::$app->user()->role < 1) {
            header('HTTP/1.0 403 Forbidden');
            echo 'You are forbidden!';
            exit();
        }       
    }

    public function action_buildings() {

        $q = "
        SELECT crm_building.*, crm_objects.name AS object_name
        FROM crm_building 
        LEFT JOIN crm_objects ON crm_objects.id = crm_building.object_id 
        ";
        $data = \A::$app->db()->query($q)->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode(['status' => 'ok', 'data' => $data]);
    }

    public function action_list() {
        $parent = filter_input(INPUT_GET, 'parent_id');
        $sort = json_decode(filter_input(INPUT_GET, 'sort'));
        if (is_object($sort) && isset($sort->key) && $sort->key != '' && isset($sort->order) && $sort->order != '') {
            $sort = "{$sort->key} {$sort->order}";
        } else {
            $sort = 'apartment_int ASC';
        }

        $q = "
        SELECT 
            crm_apartments.*,
            crm_apartments.id AS apartment_id,
            CAST(crm_apartments.number AS UNSIGNED) AS apartment_int,
            crm_apartments.number AS apartment,
            crm_apartments.price AS price,
            crm_apartments.square AS square,
            crm_apartments.status AS status,
            crm_apartments.rooms AS rooms,
            crm_sections.name AS section,
            crm_floors.floor AS floor,
            crm_building.name AS building,
            crm_objects.name AS object
        FROM crm_apartments 
        LEFT JOIN crm_floors ON crm_floors.id = crm_apartments.floor_id 
        LEFT JOIN crm_sections ON crm_sections.id = crm_floors.section_id 
        LEFT JOIN crm_building ON crm_building.id = crm_sections.building_id 
        LEFT JOIN crm_objects ON crm_objects.id = crm_building.object_id 
        WHERE 
            crm_building.id = '$parent'
        ORDER BY $sort
        ";
        $data = \A::$app->db()->query($q)->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode(['status' => 'ok', 'data' => $data]);
    }

    public function action_tile() {
        $parent = filter_input(INPUT_GET, 'parent_id');
        $q = "
        SELECT
            crm_apartments.id AS apartment_id,
            crm_apartments.number AS apartment,
            crm_apartments.price AS price,
            crm_apartments.square AS square,
            crm_apartments.status AS status,
            crm_apartments.rooms AS rooms,
            crm_sections.name AS section,
            crm_floors.floor AS floor,
            crm_building.name AS building
        FROM crm_apartments
        LEFT JOIN crm_floors ON crm_floors.id = crm_apartments.floor_id 
        LEFT JOIN crm_sections ON crm_sections.id = crm_floors.section_id 
        LEFT JOIN crm_building ON crm_building.id = crm_sections.building_id 
        WHERE 
            crm_building.id = '$parent'
        ORDER BY
            section ASC, floor DESC, apartment ASC
        ";
        $data = \A::$app->db()->query($q)->fetchAll(\PDO::FETCH_ASSOC);
        
        $res = [];
        $max_floor = 1;
        $min_floor = 1;
        foreach ($data as $el) {
            $res[$el['section']][$el['floor']][$el['apartment']] = $el;
            if ($max_floor <= $el['floor']) { $max_floor = $el['floor']; }
            if ($min_floor >= $el['floor']) { $min_floor = $el['floor']; }
        }
        $sec = [];
        foreach ($res as $key => $el) {
            $sec[] = $key;
        }
        $d = [
            'aparts' => $res,
            'sec' => $sec, 
            'floor' => ['max_floor' => $max_floor, 'min_floor' => $min_floor]
        ];

        return json_encode(['status' => 'ok', 'data' => $d]);
    }
}
