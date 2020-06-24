<?php

namespace modules\crm\models;

class Calls extends \modules\activity\models\Model {

    public $_user;

    public $fields = [
        'id' => null,
        'phone' => null,
        'status' => null,
        'direction' => null,
        'user_id' => null,
        'createdon' => null,
        'contact_id' => null,
        'uuid' => null,
        'calldatetime' => null,
        'info' => null
    ];
    
    public static $create_q = " 
            DROP TABLE IF EXISTS crm_calls;
            CREATE TABLE crm_calls (
            `id` INT not null primary key AUTO_INCREMENT, 
            `status` varchar(20),
            `direction` INT,
            `contact_id` INT,
            `user_id` INT, 
            `createdon` DATETIME,
            `uuid` varchar(255),
            `calldatetime` DATETIME,
            `phone` varchar(255),
            `info` TEXT
            );";

    public static function table_name() {
        return 'crm_calls';
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
