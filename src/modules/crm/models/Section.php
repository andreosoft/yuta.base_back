<?php

namespace modules\crm\models;

class Section extends \modules\activity\models\Model {

    public $_user, $_building;

    public $fields = [
        'id' => null,
        'building_id' => null,
        'user_id' => null,
        'createdon' => null,
        'name' => null
    ];
    
    public static $create_q = " 
            DROP TABLE IF EXISTS crm_sections;
            CREATE TABLE crm_sections (
            `id` INT not null primary key AUTO_INCREMENT, 
            `building_id` INT,
            `user_id` INT, 
            `createdon` DATETIME,
            `name` varchar(255)
            );";

    public static function table_name() {
        return 'crm_sections';
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

    public function get_building() {
        if (!is_object($this->_building)) {
            $this->_building = Building::findOne(['id' => $this->building_id]);
        }
        return $this->_building;
    }
}
