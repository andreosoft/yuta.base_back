<?php

namespace modules\activity\models;

class Mfileds extends \base\core\Model
{

    public $_user;

    public $fields = [
        'id' => null,
        'table_name' => null,
        'name' => null,
        'const' => null,
        'type' => null,
        'max' => null,
        'min' => null,
        'viewindex' => null,
        'required' => null,
        'def' => null,
        'sort' => null,
        'lable' => null,
        'block' => null,
        'elements' => null,
        'template' => null,
        'placeholder' => null,
        'user_id' => null,
        'createdon' => null,
        'updatedon' => null,
        'info' => null

    ];

    public static $create_q = " 
            DROP TABLE IF EXISTS mrecord_fields;
            CREATE TABLE mrecord_fields (
            `id` INT not null primary key AUTO_INCREMENT, 
            `user_id` INT, 
            `createdon` DATETIME,
            `table_name` varchar(255), 
            `action` varchar(255),
            `table_id` INT,
            `info` TEXT
            );";

    public static function table_name()
    {
        return 'mrecord_fields';
    }

    public function validate()
    {
        return true;
    }

    public function save()
    {
        if ($this->id === null) {
            $this->user_id = \A::$app->user()->id;
            $this->createdon = date('Y-m-d H:i:s', time());
        }
        parent::save();
        return true;
    }

    public function get_user()
    {
        if (!is_object($this->_user)) {
            $this->_user = User::findOne(['id' => $this->user_id]);
        }
        return $this->_user;
    }
}
