<?php

namespace modules\db\models;

class Model extends \modules\activity\models\Model {

    public $fields = [];
    public $_fields_info;
    private $addfields = false;

    public function get_fields_info() {
        if (!is_array($this->_fields_info)) {
            $t = static::table_name();
            $q = "SELECT db_structure.* FROM db_structure WHERE table_name = '$t' ";
            $data = \A::$app->db()->query($q)->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($data as $value) {
                $this->_fields_info[$value['field_name']] = $value;
            }
        }
        return $this->_fields_info;
    }

    private function add_fields() {
        $t = static::table_name();
        $q = "SELECT db_structure.field_name FROM db_structure WHERE table_name = '$t' ";
        $data = \A::$app->db()->query($q)->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($data as $value) {
            if (!array_key_exists($value['field_name'], $this->fields)) {
                $this->fields[$value['field_name']] = null;
            }
        }
        $this->addfields = true;
    }

    public function __set($name, $value) {
        $m_name = 'set_' . $name;
        if (method_exists($this, $m_name)) {
            $this->$m_name($value);
        }
        if (!$this->addfields) {
            $this->add_fields();
        }
        if (array_key_exists($name, $this->fields)) {
            $this->fields[$name] = $value;
        }
    }

}
