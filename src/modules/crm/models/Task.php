<?php

namespace modules\crm\models;
use modules\users\models\User;

class Task extends \modules\db\models\Model {

    public $_user, $_contact, $_deal, $_manager;
    public $fields = [
        'id' => null,
        'user_id' => null,
        'createdon' => null,
        'name' => null,
        'status' => null,
        'type' => null,
        'contact_id' => null,
        'deal_id' => null,
        'date_task' => null,
        'manager_id' => null,
        'comment' => null,
    ];
    public static $create_q = " 
            DROP TABLE IF EXISTS crm_tasks;
            CREATE TABLE crm_tasks (
            `id` INT not null primary key AUTO_INCREMENT, 
            `user_id` INT, 
            `name` VARCHAR(255),
            `createdon` DATETIME,
            `status` INT,
            `type` INT,
            `contact_id` INT,
            `deal_id` INT,
            `date_task` DATE,
            `manager_id` INT,
            `comment` TEXT
            );";

    public static function table_name() {
        return 'crm_tasks';
    }

    public function validate() {
        return true;
    }

    public function save($info = '') {
        if ($this->id == null) {
            $this->user_id = \A::$app->user()->id;
            $this->createdon = date('Y-m-d H:i:s', time());
        }
        if ($this->manager_id == null) {
            $this->manager_id = \A::$app->user()->id;
        }
        return parent::save($info = '');
    }

    public function get_contact() {
        if (!is_object($this->_contact)) {
            $this->_contact = Contact::findOne(['id' => $this->contact_id]);
        }
        return $this->_contact;
    }

    public function get_manager() {
        if (!is_object($this->_manager)) {
            $this->_manager = User::findOne(['id' => $this->manager_id]);
        }
        return $this->_manager;
    }

    public function get_user() {
        if (!is_object($this->_user)) {
            $this->_user = User::findOne(['id' => $this->user_id]);
        }
        return $this->_user;
    }

    public function get_deal() {
        if (!is_object($this->_deal)) {
            $this->_deal = Deal::findOne(['id' => $this->deal_id]);
        }
        return $this->_deal;
    }

    public function get_fields_one() {
        $res = $this->fields;
        if (isset($this->contact->fields)) {
            $res['contact'] = $this->contact->name;
        }
        if (isset($this->deal->fields)) {
            $res['deal'] = $this->deal->name;
        }
        if (isset($this->user->fields)) {
            $res['user'] = $this->user->name;
        }
        if (isset($this->manager->fields)) {
            $res['manager'] = $this->manager->name;
        }
        return $res;
    }

}
