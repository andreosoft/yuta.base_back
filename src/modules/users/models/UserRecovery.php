<?php

namespace modules\users\models;
use base\core\Mail;

class UserRecovery extends BaseUser {

    public $_password;

    public $fields = [
        'id' => '',
        'login' => '',
        'hash' => ''
        ];
    
    public function validate() {
        $res = true;
        if ($this->login == '') {
            $this->validators['login'] = ['type' => 'error', 'message' => 'Это поле обязательно'];
            $res = false;
            $this->_errors = $this->validators['login']['message'];
        } elseif (!filter_var($this->login, FILTER_VALIDATE_EMAIL)) {
            $this->validators['login'] = ['type' => 'error', 'message' => 'Поле должно быть email адресом'];
            $res = false;
            $this->_errors = $this->validators['login']['message'];
        }
        return $res;
    }

    public function recovery() {
        $m = self::findOne(['login' => $this->login]);
        if (is_object($m)) {
            $this->id = $m->id;
            $this->_password = $this->generatePassword();
            $this->hash = password_hash($this->_password, PASSWORD_BCRYPT);
            $this->save();
            $this->sendEmail();
        }
        return true;
    }

    public function sendEmail() {
        $mail = new Mail();
        $txt = "Восстановление пароля  для пользователя: $this->login.\n";
        $txt .= "Ваш новый временный пароль: $this->_password\n";
        $mail->sendMessage($this->login, 'Восстановление пароля', $txt);
    }

    public function generatePassword($length = 10) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $count = mb_strlen($chars);

        for ($i = 0, $result = ''; $i < $length; $i++) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }

        return $result;
    }
}