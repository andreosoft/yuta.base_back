<?php
namespace modules\users\models;

use base\Mail;

class UserRegistaration extends User {

    public $_rppassword, $_password;
    
    public function __construct() {
        $this->createdon = date('Y-m-d H:i:s', time());
        $this->status = 1;
        $this->role = 1;
    }
    
    public static function labels() {
        return [
            'login' => 'Адрес электронной почты',
            'password' => 'Пароль',
            'rppassword' => 'Повторите пароль',
            'name' => 'Имя',
            'phone' => 'Телефон',
            
        ];
    }
    
   public function validate() {
        $res = true;
        if ($this->login == '') {
            $this->validators['login'] = ['type' => 'is-invalid', 'message' => 'Это поле обязательно'];
            $res = false;
        } elseif (!filter_var($this->login, FILTER_VALIDATE_EMAIL)) {
             $this->validators['login'] = ['type' => 'is-invalid', 'message' => 'Поле должно быть email адресом'];
            $res = false;
        } elseif (is_object(User::findOne(['login' => $this->login]))) {
             $this->validators['login'] = ['type' => 'is-invalid', 'message' => 'Пользователь с таким email уже зарегистрирован'];
            $res = false;
        }
        
        if ($this->password == '') {
            $this->validators['password'] = ['type' => 'is-invalid', 'message' => 'Это поле обязательно'];
            $res = false;
        } elseif (strlen($this->password) < 6) {
             $this->validators['password'] = ['type' => 'is-invalid', 'message' => 'Пароль должен быть не менее 6 символов'];
            $res = false;
        }
        if ($this->rppassword == '') {
            $this->validators['rppassword'] = ['type' => 'is-invalid', 'message' => 'Это поле обязательно'];
            $res = false;
        } elseif ($this->password != $this->rppassword){
            $this->validators['rppassword'] = ['type' => 'is-invalid', 'message' => 'Пароли не совпадают'];
            $res = false;
        }
        return $res;
    }
    
    public function set_rppassword($d) {
        $this->_rppassword = $d;
    }

    public function get_rppassword() {
        return $this->_rppassword;
    }
    
    public function set_password($d) {
        $this->_password = $d;
        $this->hash = password_hash($d, PASSWORD_BCRYPT);
    }

    public function get_password() {
        return $this->_password;
    }
    
    public function save() {
        parent::save();
//        $this->sendEmail();
        return true;
    }
    
    public function sendEmail() {
        $mail = new Mail();
        $txt = "Регистрация на ".\A::$app->config['name']."\n";
        $txt .= "Ваши регистрационные данные: \n";
        $txt .= $this->getLabel('login').': '. $this->login."\n";
        $txt .= $this->getLabel('pass').': '. $this->_password ."\n";
        $txt .= $this->getLabel('name').': '. $this->name."\n";
        $txt .= $this->getLabel('surname').': '. $this->surname."\n";
        $txt .= $this->getLabel('tel').': '. $this->tel."\n";
        $txt .= $this->getLabel('adress').': '. $this->adress."\n";
        $mail->sendMessage($this->login, 'Регистрация на '.\A::$app->config['name'], $txt);
    }

}
