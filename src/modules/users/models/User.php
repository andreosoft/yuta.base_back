<?php

namespace modules\users\models;

class User extends BaseUser {

    public $_password;

    public function set_password($d) {
        $this->_password = $d;
        $this->hash = password_hash($d, PASSWORD_BCRYPT);
    }

}
