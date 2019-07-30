<?php

namespace modules\content\models;

class Content extends \base\core\Model {

    public $_user, $_parent;

    public $fields = [
        'id' => null,
        'user_id' => null,
        'createdon' => null,
        'is_parent' => null,
        'parent_id' => null,
        'name' => null,
        'content' => null,
        'intro' => null,
        'status' => null,
        'image' => null,
        'url' => null
    ];
    
    public static $create_q = " 
            DROP TABLE IF EXISTS contents;
            CREATE TABLE contents (
            id INT not null primary key AUTO_INCREMENT, 
            user_id INT, 
            createdon DATETIME,
            is_parent INT,
            parent_id INT,
            name varchar(255), 
            content TEXT,
            intro TEXT,
            status INT,
            image VARCHAR(255),
            url VARCHAR(255)
            );";

    public static function table_name() {
        return 'contents';
    }

    public function validate() {
        return true;
    }

    public function save() {
        if ($this->id === null) {
            $this->user_id = \A::$app->user()->id;
            $this->createdon = date('Y-m-d H:i:s', time());
        }
        parent::save();
        return true;
    }
    
    public function get_user() {
        if (!is_object($this->_user)) {
            $this->_user = User::findOne(['id' => $this->user_id]);
        }
        return $this->_user;
    }
    
    public function get_parent() {
        if (!is_object($this->_parent)) {
            $this->_parent = self::findOne(['id' => $this->parent_id]);
        }
        return $this->_parent;
    }
    
    public function get_fields_one() {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'createdon' => $this->createdon,
            'is_parent' => $this->is_parent,
            'parent_id' => $this->parent_id,
            'name' => $this->name,
            'content' => $this->content,
            'intro' => $this->intro,
            'status' => $this->status,
            'image' => $this->image,
            'url' => $this->url
        ];
    }

    public function get_fields_many() {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'createdon' => $this->createdon,
            'is_parent' => $this->is_parent,
            'parent_id' => $this->parent_id,
            'name' => $this->name,
            'status' => $this->status,
            'image' => $this->image,
            'url' => $this->url,
            'content' => $this->content,
        ];
    }
}
