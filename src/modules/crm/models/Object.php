<?php

namespace modules\crm\models;

class Object extends \modules\activity\models\Model {

    public $_user;

    public $fields = [
        'id' => null,
        'user_id' => null,
        'createdon' => null,
        'name' => null,
        'address' => null,
        'class' => null,
        'status' => null,
        'image' => null
    ];
    
    public static $create_q = " 
            DROP TABLE IF EXISTS crm_objects;
            CREATE TABLE crm_objects (
            `id` INT not null primary key AUTO_INCREMENT, 
            `user_id` INT, 
            `createdon` DATETIME,
            `name` varchar(255), 
            `address` TEXT,
            `class` varchar(255),
            `status` INT,
            `image` VARCHAR(255)
            );";

    public static function table_name() {
        return 'crm_objects';
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
}
