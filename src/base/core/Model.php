<?php

namespace base\core;

use PDO;

class Model {

    public $fields = [];
    public $struct = [];
    public $validators = [];
    public $_errors;
    public $_isnew;
    private $data = [];

    public function __construct() {
        foreach ($this->struct as $key => $value) {
            $this->fields[$key] = null;
        }
    }

    public function __get($name) {
        $m_name = 'get_' . $name;
        if (method_exists($this, $m_name)) {
            return $this->$m_name();
        }

        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        if (array_key_exists($name, $this->fields)) {
            return $this->fields[$name];
        }
    }

    public function __set($name, $value) {
        $m_name = 'set_' . $name;
        if (method_exists($this, $m_name)) {
            $this->$m_name($value);
        }

        if (array_key_exists($name, $this->fields)) {
            $this->fields[$name] = $value;
        }
    }

    public static function table_name() {
        return '';
    }

    public static function labels() {
        return [
        ];
    }

    public function load($array, $useclass = false) {
        if ($useclass) {
            $class = get_called_class();
            if (isset($array[$class]) && is_array($array[$class])) {
                foreach ($array[$class] as $name => $value) {
                    $this->__set($name, $value);
                }
                return true;
            }
        } else {
            if (is_array($array)) {
                foreach ($array as $name => $value) {      
                    $this->__set($name, $value);
                }
                return true;
            }
        }
        return false;
    }

    public function validate() {
        return true;
    }

    public function transform_out_db($key, $value) {
        if (array_key_exists($key, $this->struct) && is_array($this->struct[$key]) && array_key_exists('type', $this->struct[$key])) {
            switch ($this->struct[$key]['type']) {
                case 'json':
                    return json_decode($value, true);
            }
        }
        return $value;
    }

    public function transform_in_db($key, $value) {
        if (array_key_exists($key, $this->struct) && is_array($this->struct[$key]) && array_key_exists('type', $this->struct[$key])) {
            switch ($this->struct[$key]['type']) {
                case 'json':
                    return json_encode($value);
            }
        }
        return $value;
    }

    public function set($array) {
        foreach ($array as $key => $value) {
            $this->fields[$key] = $this->transform_out_db($key, $value);
        }
        return true;
    }

    public function is_new() {
        if ($this->_isnew == null) {
            if (isset($this->fields['id']) && $this->fields['id'] == '') {
                $this->_isnew = true;
            } else {
                $this->_isnew = false;
            }
        }
        return $this->_isnew;
    }

    public function save() {
        $t = static::table_name();
        if (isset($this->fields['id']) && $this->fields['id'] != '') {
            $this->_isnew = false;
            $str = '';
            $i = 0;
            $val_ins = [];
            foreach ($this->fields as $n => $v) {
                if ($v === null)
                    continue;
                $v = $this->transform_in_db($n, $v);
                if ($i > 0) {
                    $str .= ', ';
                }
                $i++;
                $str .= "`$n` = ?";
                $val_ins[] = $v;
            }
            if (!empty($str)) {
                $q = "UPDATE `$t` SET $str WHERE `id` = '{$this->fields['id']}'";
//                print_r($q);die();
//                print_r($this->fields);print_r($val_ins);die();
                \A::$app->db()->prepare($q)->execute($val_ins);
            }
        } else {
            $this->_isnew = true;
            $i = 0;
            $str_n = '';
            $str_v = '';
            $val_ins = [];
            foreach ($this->fields as $n => $v) {
                $v = $this->transform_in_db($n, $v);
                if ($v === null)
                    continue;
                if ($i > 0) {
                    $str_n .= ', ';
                    $str_v .= ', ';
                }
                $i++;
                $str_n .= "`$n`";
                $str_v .= "?";
                $val_ins[] = $v;
            }
            if (!empty($str_n)) {
                $q = "INSERT INTO `$t` ($str_n) VALUES ($str_v)";
//                 print_r($this->fields);print_r($val_ins);die();
                \A::$app->db()->prepare($q)->execute($val_ins);
            }
            $this->fields['id'] = \A::$app->db()->lastInsertId();
        }

        return $this->fields['id'];
    }

    public function delete() {
        $t = static::table_name();
        $q = "DELETE FROM `$t` WHERE `id` = '$this->id'";
        \A::$app->db()->exec($q);
        return true;
    }

    public function delete_all() {
        $t = static::table_name();
        $q = "TRUNCATE TABLE `$t`";
        \A::$app->db()->exec($q);
        return true;
    }

    public static function findOne($d) {

        $t = static::table_name();
        $q = (new Query())->select()->from($t)->where($d)->get();
        $data = \A::$app->db()->query($q)->fetchAll(\PDO::FETCH_ASSOC);

        $class = get_called_class();
        $m = new $class();
        if (isset($data[0])) {
            $m->set($data[0]);
            return $m;
        }
        return null;
    }

    public static function findMany($w = '') {

        $t = static::table_name();
        $q = (new Query())->select()->from($t)->where($w)->get();
//        print_r ($q);
        $data = \A::$app->db()->query($q)->fetchAll(\PDO::FETCH_ASSOC);

        $class = get_called_class();
        $m = [];
        foreach ($data as $d) {
            $o = new $class();
            $o->set($d);
            $m[] = $o;
        }

        return $m;
    }

    public static function findManyByQuery($q) {

        $data = \A::$app->db()->query($q)->fetchAll(PDO::FETCH_ASSOC);

        $class = get_called_class();
        $m = [];
        foreach ($data as $d) {
            $o = new $class();
            $o->set($d);
            $m[] = $o;
        }

        return $m;
    }

    public function getLabel($attr) {
        if (isset(static::labels()[$attr])) {
            $label = static::labels()[$attr];
        } else {
            $label = ucwords($attr);
        }
        return $label;
    }

    public function errors() {
        return $this->_errors;
    }

    public function getValidatorMessage($attr) {
        if (isset($this->validators[$attr]['message'])) {
            return $this->validators[$attr]['message'];
        }
    }

    public function getValidatorType($attr) {
        if (isset($this->validators[$attr]['type'])) {
            return $this->validators[$attr]['type'];
        }
    }

    public function get_fields_many() {
        return $this->fields;
    }
               
    public function get_fields_one() {
        return $this->fields;
    }

}