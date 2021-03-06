<?php

namespace modules\uploads\models;

class Upload extends \modules\activity\models\Model {

    public $_user;

    public $fields = [
        'id' => null,
        'user_id' => null,
        'createdon' => null,
        'name' => null,
        'file' => null,
        'info' => null,
        'status' => null,
        'type_id' => null,
        'group_id' => null,
        'table_name' => null,
        'table_row_id' => null
    ];
    
    public static $create_q = " 
            DROP TABLE IF EXISTS uploads;
            CREATE TABLE uploads (
            `id` INT not null primary key AUTO_INCREMENT, 
            `user_id` INT, 
            `createdon` DATETIME,
            `name` varchar(255), 
            `file` varchar(255),
            `info` TEXT,
            `status` INT,
            `type_id` INT,
            `group_id` INT,
            `table_name` varchar(255),
            `table_row_id` INT
            );";

    public static function table_name() {
        return 'uploads';
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
