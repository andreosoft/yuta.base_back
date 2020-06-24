<?php

namespace base\core;

class User {

    public $_info;
    public $login, $role, $id, $params = [];

    public function info() {
        return $this->_info;
    }

    public static function isGuest() {
        if (\A::$app->user()->role > 0) {
            return false;
        }
        return true;
    }

    public static function getUser() {
        $api_token = filter_input(INPUT_GET, 'token');

        $u = null;
        
        if (strlen($api_token) > 30) {
            $u = \modules\users\models\User::findOne(['api_key' => $api_token]);
        }
        if (!is_object($u) || $u->status != 2) {
            $token = isset(apache_request_headers()['Authorization']) ? apache_request_headers()['Authorization'] : '';
            if ($token == '') {
                return self::guest();
            }
            $u = \modules\users\models\User::findOne(['token' => $token]);
            if (!is_object($u) || $u->status != 2) {
                return self::guest();
            }
        }
        $o = new self;
        $o->login = $u->login;
        $o->role = $u->role;
        $o->id = $u->id;
        $o->_info = $u;
        return $o;
    }

    public static function guest() {
        $o = new self;
        $o->login = 'guest';
        $o->role = 0;
        $o->id = 0;
        $o->_info = '';
        return $o;
    }

}
