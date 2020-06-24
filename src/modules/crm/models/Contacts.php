<?php

namespace modules\crm\models;

class Contacts extends \modules\db\models\Model {

    public $_user;

    public $fields = [
        'id' => null,
        'user_id' => null,
        'createdon' => null,
        'name' => null,
        'phone' => null,
        'status' => null,
    ];
    
    public static $create_q = " 
            DROP TABLE IF EXISTS crm_contacts;
            CREATE TABLE crm_contacts (
            `id` INT not null primary key AUTO_INCREMENT, 
            `user_id` INT, 
            `createdon` DATETIME,
            `name` varchar(255), 
            `address` TEXT,
            `status` INT
            );";

    public static function table_name() {
        return 'crm_contacts';
    }

    public function validate() {
        return true;
    }

    public function save() {
        if ($this->id === null) {
            $this->user_id = \A::$app->user()->id;
            $this->createdon = date('Y-m-d H:i:s', time());
        }
        parent::save();
        return true;
    }
    
    public function get_user() {
        if (!is_object($this->_user)) {
            $this->_user = User::findOne(['id' => $this->user_id]);
        }
        return $this->_user;
    }
}
