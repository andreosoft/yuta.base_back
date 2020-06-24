<?php

namespace modules\crm\models;

class ContactComments extends \modules\db\models\Model {

    public $_user;

    public $fields = [
        'id' => null,
        'user_id' => null,
        'createdon' => null,
        'contact_id' => null,
        'content' => null,
        'status' => null,
    ];
    
    public static $create_q = " 
            DROP TABLE IF EXISTS crm_contacts_comments;
            CREATE TABLE crm_contacts_comments (
            `id` INT not null primary key AUTO_INCREMENT, 
            `user_id` INT, 
            `createdon` DATETIME,
            `contact_id` INT, 
            `content` TEXT,
            `status` INT
            );";

    public static function table_name() {
        return 'crm_contacts_comments';
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
        return parent::delete($info = '');
    }

    
    public function get_user() {
        if (!is_object($this->_user)) {
            $this->_user = User::findOne(['id' => $this->user_id]);
        }
        return $this->_user;
    }
}
