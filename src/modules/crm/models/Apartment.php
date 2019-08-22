<?php

namespace modules\crm\models;

class Apartment extends \modules\activity\models\Model {

    public $_user, $_floor, $_plan;

    public $fields = [
        'id' => null,
        'floor_id' => null,
        'plan_id' => null,
        'number' => null,
        'rooms' => null,
        'price' => null,
        'user_id' => null,
        'createdon' => null,
        'info' => null,
        'type_id' => null,
        'square' => null,
        'status' => null,
        'is_studio' => null
    ];
    
    public static $create_q = " 
            DROP TABLE IF EXISTS crm_apartments;
            CREATE TABLE crm_apartments (
            `id` INT not null primary key AUTO_INCREMENT, 
            `floor_id` INT,
            `type_id` INT,
            `plan_id` INT,
            `number` varchar(255),
            `rooms` INT,
            `price` decimal(12,2),
            `square` decimal(12,3),
            `status` INT,
            `is_studio` INT(1),
            `user_id` INT, 
            `createdon` DATETIME,
            `info` TEXT
        );";

    public static function table_name() {
        return 'crm_apartments';
    }

    public function validate() {
        return true;
    }

    public function save($info = '') {
        if ($this->id == null) {
            $this->user_id = \A::$app->user()->id;
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
