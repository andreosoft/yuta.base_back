<?php

namespace modules\av100\console_controllers;

use modules\av100\models\Offer;

class Api extends \base\core\Controller {

    public $token, $cmd;

    public function __construct() {
        $this->token = \A::$app->config['api_av100']['token'];
        $this->cmd = \A::$app->config['api_av100']['cmd'];
    }

    public function action_upload() {
        print "------------------Start!!!--------------------\n";
        $this->importModel();
        print "------------------Stop!!!---------------------\n";
        return;
    }

    private function importModel() {
        $r = "http://data.av100.ru/offer.ashx?key={$this->token}{$this->cmd}";
        // print $r;die();
        $json = $this->getRequest($r);
        $result = json_decode($json);

        if (!$result->error) {
            foreach ($result->result->ListOffer as $value) {
                if ($this->addOffer($value)) {
                    break;
                }
            }
        } else {
            print $json . "\n";
        }
    }

    private function addOffer($value) {

        if (is_object(Offer::findOne(['avid' => trim($value->ID)]))) {
            return false;
        }
        
        $model = new Offer();
        $model->avid = $value->ID;
        $model->year = $value->Year;
        $model->price = $value->Price;
        $model->source = $value->Source;
        $model->url = $value->Url;
        $model->marka = $value->Marka;
        $model->model = $value->Model;
        $model->city = $value->City;
        $model->descr = $value->Descr;
        $model->delta = $value->Delta;
        $model->status = 0;
        $model->credate = $this->convertDate($value->Credate);
        $model->save();
        print "Add new offer: {$model->marka} {$model->model} {$model->name} {$model->phone}\n";
        return false;
    }

    private function convertDate($date) {
        $i = explode(' ', $date);
        $d = explode('.', $i[0]);
        $t = explode(':', $i[1]);
        $time = mktime(intval($t[0]), intval($t[1]), intval($t[2]), intval($d[1]), intval($d[0]), intval($d[2]));
        return date('Y-m-d H:i:s', $time);
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
