<?php

namespace modules\crm\models;

class Deal extends \modules\activity\models\Model {

    public $_user, $_contact, $_apartment;

    public $fields = [
        'id' => null,
        'user_id' => null,
        'contact_id' => null,
        'apartment_id' => null,
        'createdon' => null,
        'info' => null,
        'status' => null,
    ];
    
    public static $create_q = " 
            DROP TABLE IF EXISTS crm_deals;
            CREATE TABLE crm_deals (
            `id` INT not null primary key AUTO_INCREMENT, 
            `contact_id` INT,
            `apartment_id` INT,
            `user_id` INT, 
            `createdon` DATETIME,
            `info` TEXT,
            `status` INT
            );";

    public static function table_name() {
        return 'crm_deals';
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

    public function get_contact() {
        if (!is_object($this->_contact)) {
            $this->_contact = Contact::findOne(['id' => $this->contact_id]);
        }
        return $this->_contact;
    }

    public function get_apartment() {
        if (!is_object($this->_apartment)) {
            $this->_apartment = Apartment::findOne(['id' => $this->apartment_id]);
        }
        return $this->_apartment;
    }
}
