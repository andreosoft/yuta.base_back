<?php

namespace modules\request\models;

use modules\av100\models\Offer as OfferAv100;

class Offer extends \base\core\Model {
    
    public $_offer;
    
    public static $create_q = " 
            DROP TABLE IF EXISTS `crm_offers`;
            CREATE TABLE `crm_offers` (
            id              INT not null primary key AUTO_INCREMENT,
            createdon       DATETIME,
            createdby_id    INT,
            offer_id        INT,
            status          INT DEFAULT 1,
            info_phone1     VARCHAR(60),
            info_phone2     VARCHAR(60),
            info_whatsapp   VARCHAR(60),
            info_email      VARCHAR(60),
            info_telegram   VARCHAR(60),
            info_viber      VARCHAR(60),
            info_skype      VARCHAR(60),
            info            TEXT
            ) CHARACTER SET utf8 COLLATE utf8_general_ci;
            CREATE INDEX crm_offers_offer_id ON crm_offers(offer_id);
            CREATE INDEX crm_offers_phone_status ON crm_offers(status);
            CREATE INDEX crm_offers_createdon ON crm_offers(createdon);
            CREATE INDEX crm_offers_createdby_id ON crm_offers(createdby_id);";
    
    public $fields = [
        'id' => null,
        'name' => null,
        'offer_id' => null,
        'createdon' => null,
        'createdby_id' => null,
        'status' => null,
        'info_phone1' => null,
        'info_phone2' => null,
        'info_whatsapp' => null,
        'info_email' => null,
        'info_telegram' => null,
        'info_viber' => null,
        'info_email' => null,
        'info_skype' => null,
        'info' => null,
        'avid' => null,
        'year' => null,
        'price' => null,
        'source' => null,
        'url' => null,
        'marka' => null,
        'model' => null,
        'city' => null,
        'descr' => null,
        'delta' => null,
        'status' => null,
        'credate' => null,
        'phone' => null,
        'name' => null,
    ];

    public static function table_name() {
        return 'crm_offers';
    }

    public function validate() {
        return true;
    }
    
    public function get_offer() {
        if (!is_object($this->_offer)) {
            $this->_offer = OfferAv100::findOne(['id' => $this->offer_id]);
        }
        return $this->_offer;
    }
    
    public function save() {
        if ($this->id === null) {
            $this->fields['createdon'] = date('Y-m-d H:i:s', time());
            $this->fields['createdby_id'] = \A::$app->user()->id;
        }
        return parent::save();
    }
    
    public function get_fields_one() {
        return ['offer' => $this->fields, 'av100' => $this->offer->fields];
    }

    public function get_fields_many() {
        return $this->fields;
    }
}
