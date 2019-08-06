<?php

namespace modules\users\models;

class BaseUser extends \modules\activity\models\Model {

    public static $create_q = " 
            DROP TABLE IF EXISTS `users`;
            CREATE TABLE `users` (
            id INT not null primary key AUTO_INCREMENT, 
            createdon DATETIME,
            createdby_id INT,
            org_id INT,
            status INT,
            hash varchar(255), 
            token varchar(255),
            login varchar(255), 
            name varchar(255),
            surname varchar(255), 
            role INT,
            phone VARCHAR(255),
            firm VARCHAR(255), 
            image VARCHAR(255), 
            address text);";
    
    public $fields = [
        'id' => null,
        'login' => null,
        'createdon' => null,
        'createdby_id' => null,
        'org_id' => null,
        'status' => null,
        'hash' => null,
        'token' => null,
        'name' => null,
        'surname' => null,
        'role' => null,
        'phone' => null,
        'firm' => null,
        'image' => null,
        'address' => null
    ];

    public static function table_name() {
        return 'users';
    }

    public function __construct() {
//        
    }

    public function get_fields_one() {
        return [
            'id' => $this->id,
            'login' => $this->login,
            'name' => $this->name,
            'surname' => $this->surname,
            'phone' => $this->phone,
            'role' => $this->role,
            'status' => $this->status
        ];
    }

    public function get_fields_many() {
        return [
            'id' => $this->id,
            'login' => $this->login,
            'name' => $this->name,
            'phone' => $this->phone,
            'role' => $this->role,
            'status' => $this->status
        ];
    }

    public function save($info = '') {
        if ($this->id == null) {
            $this->createdby_id = \A::$app->user()->id;
            $this->createdon = date('Y-m-d H:i:s', time());
        }
        return parent::save($info = '');
    }

}
