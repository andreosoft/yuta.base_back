<?php

namespace modules\crm\models;

class Report extends \modules\activity\models\Model {

    public $_user, $_template;

    public $fields = [
        'id' => null,
        'template_id' => null,
        'type' => null,
        'status' => null,
        'params' => null,
        'user_id' => null,
        'createdon' => null,
        'info' => null
    ];
    
    public static $create_q = " 
            DROP TABLE IF EXISTS crm_reports;
            CREATE TABLE crm_reports (
            `id` INT not null primary key AUTO_INCREMENT, 
            `template_id` INT,
            `type` INT,
            `status` INT,
            `params` TEXT,
            `user_id` INT, 
            `createdon` DATETIME,
            `info` TEXT
        );";

    public static function table_name() {
        return 'crm_reports';
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

    public function get_template() {
        if (!is_object($this->_template)) {
            $this->_template = ReportTemplate::findOne(['id' => $this->template_id]);
        }
        return $this->_template;
    }
}
