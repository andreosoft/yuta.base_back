<?php

namespace modules\request\controllers;

use modules\request\models\Offer;
use modules\request\models\Request;

class Request_api extends \base\core\Controller {

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

    public function action_post() {
        $id = json_decode(filter_input(INPUT_GET, 'id'));
        $type = json_decode(filter_input(INPUT_GET, 'type'));
        $data = json_decode(file_get_contents('php://input'), true);
        if ($id > 0) {
            $model = new Request();
            $model->crm_offers_id = $id;
            $model->info = $data['info'];
            if ($data['eventon']['date'] == '') {
                if ($type == 101) {
                    $model->eventon = date('Y-m-d H:i:s', time() + 86400);
                } else {
                    $model->eventon = date('Y-m-d H:i:s', time());
                }
            } else {
                $model->eventon = $data['eventon']['date'] . ' ' . $data['eventon']['time'];
            }
            $model->status = $type;
            $model->save();
            return json_encode(['status' => 'ok']);
        }
        return json_encode(['status' => 'error', 'message' => 'error id']);
    }

    public function action_get_offer() {
        $id = json_decode(filter_input(INPUT_GET, 'id'));
        if ($id > 0) {
            $f_arr[] = "crm_offers.offer_id = '$id'";
            $where = "WHERE " . implode(' AND ', $f_arr);
            $sort = "id DESC";
            $q = "SELECT 
                crm_request.*, users.name as user
                FROM crm_request
                LEFT JOIN crm_offers ON crm_request.crm_offers_id = crm_offers.id
                LEFT JOIN users ON crm_request.createdby_id = users.id
                $where
                ORDER BY $sort";
            $data = \A::$app->db()->query($q)->fetchAll(\PDO::FETCH_ASSOC);
            return json_encode(['status' => 'ok', 'data' => $data]);
        }
    }
    
    public function action_get() {
        $id = json_decode(filter_input(INPUT_GET, 'id'));
        $sort = json_decode(filter_input(INPUT_GET, 'sort'));
        $user_id = \A::$app->user()->id;
        if (is_object($sort)) {
            $sort = "{$sort->key} {$sort->order}";
        } else {
            $sort = "id DESC";
        }
        $fs = json_decode(filter_input(INPUT_GET, 'filters'));
        $f_arr = $this->getFilters($fs);
        if ($id > 0) {
            $f_arr[] = "crm_request.status = '$id'";
            $f_arr[] = "users.id = '$user_id'";
            $where = "WHERE " . implode(' AND ', $f_arr);
            $limit = "30";
            $q = "SELECT 
                crm_offers.*, users.name as user, crm_request.info AS info, crm_request.eventon AS eventon
                FROM (SELECT MAX(crm_request.id) as id FROM crm_request GROUP BY crm_request.crm_offers_id) AS t
                LEFT JOIN crm_request ON t.id = crm_request.id
                LEFT JOIN crm_offers ON crm_request.crm_offers_id = crm_offers.id
                LEFT JOIN users ON crm_request.createdby_id = users.id
                $where
                ORDER BY $sort LIMIT $limit";
            $data = \A::$app->db()->query($q)->fetchAll(\PDO::FETCH_ASSOC);
            return json_encode(['status' => 'ok', 'data' => $data]);
        }
    }

    public function getFilters($fs) {
        $f_ar = [];
        if (is_object($fs)) {
            foreach ($fs as $key => $val) {
                if ($val === "")
                    continue;
                switch ($key) {
                    case 'id':
                        $f_ar[] = "`$key` = '$val'";
                        break;
                }
            }
        }
        return $f_ar;
    }

}
