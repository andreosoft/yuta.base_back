<?php

namespace modules\crm\models;

class Floor extends \modules\activity\models\Model {

    public $_user, $_section, $_apartments;

    public $fields = [
        'id' => null,
        'section_id' => null,
        'floor' => null,
        'user_id' => null,
        'createdon' => null,
        'name' => null
    ];
    
    public static $create_q = " 
            DROP TABLE IF EXISTS crm_floors;
            CREATE TABLE crm_floors (
            `id` INT not null primary key AUTO_INCREMENT, 
            `section_id` INT,
            `floor` INT,
            `user_id` INT, 
            `createdon` DATETIME,
            `name` varchar(255)
            );";

    public static function table_name() {
        return 'crm_floors';
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

    public function get_apartments() {
        if (!is_object($this->_apartments)) {
            $this->_apartments = Apartment::findMany(['floor_id' => $this->id]);
        }
        return $this->_apartments;
    }
    
    public function get_user() {
        if (!is_object($this->_user)) {
            $this->_user = User::findOne(['id' => $this->user_id]);
        }
        return $this->_user;
    }

    public function get_section() {
        if (!is_object($this->_section)) {
            $this->_section = Section::findOne(['id' => $this->section_id]);
        }
        return $this->_section;
    }

}
