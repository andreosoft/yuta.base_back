<?php

namespace base\rest;

class RestParentController extends RestController {

    public function get_all() {
        $table_name = (new $this->baseModel)->table_name();
        $parent_id = json_decode(filter_input(INPUT_GET, 'parent_id'));
        $sort = json_decode(filter_input(INPUT_GET, 'sort'));
        if (is_object($sort)) {
            $sort = "{$sort->key} {$sort->order}";
        } else {
            $sort = $this->sortDefault;
        }
        $fs = json_decode(filter_input(INPUT_GET, 'filters'));
        $f_arr = $this->getFilters($fs);
        $f_arr[] = "$table_name.parent_id = '$parent_id'";
        $f_str = " WHERE " . implode(' AND ', $f_arr);
        $q = "SELECT $table_name.* FROM $table_name $f_str ORDER BY $sort";
        $els = (new $this->baseModel)->findManyByQuery($q);
        $res = [];
        foreach ($els as $el) {
            $res[] = $el->fields_many;
        }
        $resP = [];
        $q = "SELECT $table_name.* FROM $table_name WHERE id = '$parent_id'";
        $els = \A::$app->db()->query($q)->fetchAll(\PDO::FETCH_ASSOC);
        if (!empty($els[0])) {
            $resP = $els[0];
        }
        return json_encode(['status' => 'ok', 'data' => $res, 'parent' => $resP]);
    }

    public function action_fitch_parents() {
        $table_name = (new $this->baseModel)->table_name();
        $q = "SELECT $table_name.id AS value, $table_name.name AS text FROM $table_name WHERE is_parent = '1' ORDER BY name asc";
        $els = \A::$app->db()->query($q)->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode(['status' => 'ok', 'data' => array_merge([['value' => '0', 'text' => 'Нет']], $els)]);
    }
}
