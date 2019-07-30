<?php

namespace modules\request\controllers;

use modules\request\models\Offer;
use modules\av100\models\Offer as av100Offer;

class Offers_api extends \base\core\Controller {
    
    public $baseModel = 'modules\request\models\Request';

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
    
    public function action_get() {
        $id = json_decode(filter_input(INPUT_GET, 'id'));
        if ($id > 0) {
            $model = Offer::findOne(['offer_id' => $id]);
            if (!is_object($model)) {
                $mav100 = av100Offer::findOne(['id' => $id]);
                if (is_object($mav100)) {
                    $p_a = $this->addPhone($mav100->avid);
                    $mav100->phone = $p_a['phone'];
                    $mav100->name = $p_a['name'];
                    $mav100->save();

                    $model = new Offer();
                    $model->offer_id = $id;
                    $model->status = 1;
                    $model->avid = $mav100->avid;
                    $model->year = $mav100->year;
                    $model->price = $mav100->price;
                    $model->source = $mav100->source;
                    $model->url = $mav100->url;
                    $model->marka = $mav100->marka;
                    $model->model = $mav100->model;
                    $model->city = $mav100->city;
                    $model->descr = $mav100->descr;
                    $model->delta = $mav100->delta;
                    $model->credate = $mav100->credate;
                    $model->phone = $p_a['phone'];
                    $model->name = $p_a['name'];
                    $model->save();
                    
                }
            }
            return json_encode(['status' => 'ok', 'data' => $model->fields_one]);
        }
        return json_encode(['status' => 'error', 'message' => 'error id']);
    }

    private function addPhone($avid) {
        $token = \A::$app->config['api_av100']['token'];
        $json = $this->getRequest("http://data.av100.ru/offer.ashx?key={$token}&command=data&listid={$avid}");
        $result = json_decode($json);
        if (!$result->error) {
            foreach ($result->result->ListContact as $key => $value) {
                return ['name' =>  $value->Contactface, 'phone' => $value->Phone];
            }
        }
        return ['name' =>  '', 'phone' => ''];
    }

    private function getRequest($request) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $request);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $retValue = curl_exec($ch);
        curl_close($ch);
        return $retValue;
    }

}
