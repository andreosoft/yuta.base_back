<?php

namespace modules\crm\controllers;

class Report extends \base\core\Controller {

    public function preAction($action) {
        $this->corsHeaders();
        if (\A::$app->user()->role < 1) {
            header('HTTP/1.0 403 Forbidden');
            echo 'You are forbidden!';
            exit();
        }       
    }
    
    public function action_options() {
        $this->corsHeaders();
    }
    
    public function action_gen1() {
        $q = "
        SELECT crm_apartments.status AS x, count(crm_apartments.id) AS y1
        FROM crm_apartments 
        GROUP BY status
        ";
        $data = \A::$app->db()->query($q)->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode(['status' => 'ok', 'data' => $data]);
    }
    
    public function action_gen2() {
        $q = "
        SELECT crm_deals.status AS x, count(crm_deals.id) AS y1
        FROM crm_deals 
        GROUP BY status
        ";
        $data = \A::$app->db()->query($q)->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode(['status' => 'ok', 'data' => $data]);
    }
    
    public function action_gen3() {
        $q = "
        SELECT crm_contacts.f2 AS x, count(crm_contacts.id) AS y1
        FROM crm_contacts 
        GROUP BY f2
        ";
        $data = \A::$app->db()->query($q)->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode(['status' => 'ok', 'data' => $data]);
    }
    
    public function action_gen4() {
        $q = "
        SELECT users.name AS x, count(crm_deals.id) AS y1
        FROM crm_deals 
        LEFT JOIN crm_contacts ON crm_deals.contact_id = crm_contacts.id
        LEFT JOIN users ON crm_contacts.manager_id = users.id
        GROUP BY users.name
        ";
        $data = \A::$app->db()->query($q)->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode(['status' => 'ok', 'data' => $data]);
    }
    
}
