<?php

namespace modules\crm\models;

class Contact extends \modules\db\models\Model {

    public $_user, $_deals;

    public $fields = [
        'id' => null,
        'user_id' => null,
        'createdon' => null,
        'name' => null,
        'phone' => null,
        'status' => null,
        'manager_id' => null
    ];
    
    public static $create_q = " 
            DROP TABLE IF EXISTS crm_contacts;
            CREATE TABLE crm_contacts (
            `id` INT not null primary key AUTO_INCREMENT, 
            `user_id` INT, 
            `createdon` DATETIME,
            `name` varchar(255), 
            `address` TEXT,
            `status` INT,
            `manager_id` INT
            );";

    public static function table_name() {
        return 'crm_contacts';
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

    public function delete($info = '') {
        foreach ($this->apartments as $apartment) {
            $apartment->delete($info = '');
        }
        return parent::delete($info = '');
    }

    public function get_deals() {
        if (!is_object($this->_deals)) {
            $this->_deals = Deal::findMany(['contact_id' => $this->id]);
        }
        return $this->_deals;
    }
    
    public function get_user() {
        if (!is_object($this->_user)) {
            $this->_user = User::findOne(['id' => $this->user_id]);
        }
        return $this->_user;
    }

    public function get_fields_one() {
        $res = $this->fields;
//        foreach ($this->deals as $deal) {
//            $res['deals'][] = $deal->fields;
//        }
        return $res;
    }
}
