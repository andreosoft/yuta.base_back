<?php

namespace modules\crm\models;

class PlanFloor extends \modules\activity\models\Model {

    public $_user;

    public $fields = [
        'id' => null,
        'name' => null,
        'image' => null,
        'user_id' => null,
        'createdon' => null,
        'info' => null,
        'coords' => null,
        'color' => null
    ];
    
    public static $create_q = " 
            DROP TABLE IF EXISTS crm_palns_floor;
            CREATE TABLE crm_palns_floor (
            `id` INT not null primary key AUTO_INCREMENT, 
            `name` varchar(255),
            `image` varchar(255),
            `user_id` INT, 
            `createdon` DATETIME,
            `info` TEXT,
            `coords` TEXT,
            `color` varchar(255)
            );";

    public static function table_name() {
        return 'crm_palns_floor';
    }

    public function validate() {
        return true;
    }

    public function save($info = '') {
        if ($this->id == null) {
            $this->createdby_id = \A::$app->user()->id;
            $this->createdon = date('Y-m-d H:i:s', time());
        }
        return parent::save($info = '');
    }
    
    public function get_user() {
        if (!is_object($this->_user)) {
            $this->_user = User::findOne(['id' => $this->user_id]);
        }
        return $this->_user;
    }

    public function get_floor() {
        if (!is_object($this->_floor)) {
            $this->_floor = Floor::findOne(['id' => $this->floor_id]);
        }
        return $this->_floor;
    }

    public function get_plan() {
        if (!is_object($this->_plan)) {
            $this->_plan = Floor::findOne(['id' => $this->plan_id]);
        }
        return $this->_plan;
    }
}
