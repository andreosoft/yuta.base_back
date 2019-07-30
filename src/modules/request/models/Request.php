<?php

namespace modules\request\models;

class Request extends \base\core\Model {
    
    public static $create_q = " 
            DROP TABLE IF EXISTS `crm_request`;
            CREATE TABLE `crm_request` (
            `id` INT not null primary key AUTO_INCREMENT,
            crm_offers_id INT,
            createdon DATETIME,
            createdby_id INT,
            eventon DATETIME,
            status INT DEFAULT 0,
            info TEXT
            ) CHARACTER SET utf8 COLLATE utf8_general_ci;
            CREATE INDEX crm_request_crm_offers_id ON crm_request(crm_offers_id);
            CREATE INDEX crm_request_eventon ON crm_request(eventon);
            CREATE INDEX crm_request_createdon ON crm_request(createdon);
            CREATE INDEX crm_request_createdby_id ON crm_request(createdby_id);";
    
    public $fields = [
        'id' => null,
        'crm_offers_id' => null,
        'createdon' => null,
        'createdby_id' => null,
        'status' => null,
        'eventon' => null,
        'info' => null
    ];

    public static function table_name() {
        return 'crm_request';
    }

    public function validate() {
        return true;
    }
    
    public function save() {
        if ($this->id === null) {
            $this->fields['createdon'] = date('Y-m-d H:i:s', time());
            $this->fields['createdby_id'] = \A::$app->user()->id;
        }
        return parent::save();
    }
    
    public function getFieldsOne() {
        return $this->fields;
    }

    public function getFieldsMany() {
        return $this->fields;
    }
}
