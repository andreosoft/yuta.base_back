<?php

namespace models;

use base\Mail;

class Tiket extends \base\Model {

    public $_user;


    public $fields = [
        'id' => null,
        'user_id' => null,
        'createdon' => null,
        'name' => null,
        'theme' => null,
        'message' => null,
        'status' => null,
    ];
    
    public static $create_q = " 
            DROP TABLE IF EXISTS tikets;
            CREATE TABLE tikets (
            id INT not null primary key AUTO_INCREMENT, 
            user_id INT, 
            createdon DATETIME,
            name varchar(255), 
            theme TEXT,
            message TEXT,
            status INT);";

    public static function table_name() {
        return 'tikets';
    }

    public function validate() {
        return true;
    }

    public function save() {
        if ($this->id === null) {
            $this->user_id = \A::$app->user()->id;
            $this->createdon = date('Y-m-d H:i:s', time());
            $this->status = 1;
        }
        parent::save();
        $this->sendEmail();
        return true;
    }
    
    public function get_user() {
        if (!is_object($this->_user)) {
            $this->_user = User::findOne(['id' => $this->user_id]);
        }
        return $this->_user;
    }

    public function sendEmail() {
        $mail = new Mail();
        $txt =  "<p>Название Основного Юридического лица: <b>{$this->user->org->name}</b><p> ";
        $txt .= "<p>Контактное лицо: <b>{$this->name}</b></p>";
        $txt .= "<p>Электронный адрес: <b>{$this->user->login}</b></p>";
        $txt .= "<p>Время, дата: <b>{$this->createdon}</b></p>";
        $txt .= "<p>Тема: <b>{$this->theme}</b></p>";
        $txt .= "<p>Текст сообщения: <b>{$this->message}</b></p>";

        $mail->sendMessage($this->user->mainUser->managerEmail, 'Новый тикет', $txt, true);
    }
}
