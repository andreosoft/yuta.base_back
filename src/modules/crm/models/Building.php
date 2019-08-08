<?php

namespace modules\crm\models;

class Building extends \modules\activity\models\Model {

    public $_user, $_object, $_sections;

    public $fields = [
        'id' => null,
        'object_id' => null,
        'user_id' => null,
        'createdon' => null,
        'name' => null,
        'image' => null
    ];
    
    public static $create_q = " 
            DROP TABLE IF EXISTS crm_building;
            CREATE TABLE crm_building (
            `id` INT not null primary key AUTO_INCREMENT, 
            `object_id` INT,
            `user_id` INT, 
            `createdon` DATETIME,
            `name` varchar(255),
            `image` varchar(255)
            );";

    public static function table_name() {
        return 'crm_building';
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
        foreach ($this->sections as $section) {
            $section->delete($info = '');
        }
        return parent::delete($info = '');
    }

    public function get_user() {
        if (!is_object($this->_user)) {
            $this->_user = User::findOne(['id' => $this->user_id]);
        }
        return $this->_user;
    }

    public function get_sections() {
        if (!is_object($this->_sections)) {
            $this->_sections = Section::findMany(['building_id' => $this->id]);
        }
        return $this->_sections;
    }

    public function get_object() {
        if (!is_object($this->_object)) {
            $this->_object = Object::findOne(['id' => $this->object_id]);
        }
        return $this->_object;
    }

    public function get_fields_one() {
        $res = $this->fields;
        $res['object'] = $this->object->fields;
        return $res;
    }
}
