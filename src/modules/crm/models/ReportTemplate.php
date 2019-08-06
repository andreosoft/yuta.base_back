<?php

namespace modules\crm\models;

class ReportTemplate extends \modules\activity\models\Model {

    public $_user;

    public $fields = [
        'id' => null,
        'name' => null,
        'data' => null,
        'type' => null,
        'status' => null,
        'price' => null,
        'user_id' => null,
        'createdon' => null,
        'info' => null
    ];
    
    public static $create_q = " 
            DROP TABLE IF EXISTS crm_reports_template;
            CREATE TABLE crm_reports_template (
            `id` INT not null primary key AUTO_INCREMENT, 
            `name` varchar(255),
            `data` TEXT,
            `type` INT,
            `user_id` INT, 
            `createdon` DATETIME,
            `status` INT,
            `info` TEXT
        );";

    public static function table_name() {
        return 'crm_reports_template';
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
}
