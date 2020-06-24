<?php

namespace modules\db\models;

class Structure extends \modules\activity\models\Model {

    public static $db_fields = [
        'string' => 'VARCHAR(255)',
        'text' => 'TEXT',
        'int' => 'INT',
        'date' => 'DATE',
        'money' => "DECIMAL(11,2)",
        'select' => 'VARCHAR(255)'
    ];
    
    public $_user;
    public $struct = [
        'id' => null,
        'user_id' => null,
        'user_role' => null,
        'createdon' => null,
        'updatedon' => null,
        'table_name' => null,
        'field_name' => null,
        'can_edit' => null,
        'name' => null,
        'sort' => null,
        'group_name' => null,
        'type' => null,
        'data' => ['type' => 'json'],
        'info' => ['type' => 'json']
    ];
    public static $create_q = " 
            DROP TABLE IF EXISTS db_structure;
            CREATE TABLE db_structure (
            `id` INT not null primary key AUTO_INCREMENT, 
            `user_id` INT, 
            `user_role` INT, 
            `can_edit` INT,
            `sort` INT,
            `createdon` DATETIME,
            `updatedon` DATETIME,
            `name` VARCHAR(255),
            `group_name` VARCHAR(255),
            `type` VARCHAR(20),
            `table_name` VARCHAR(255),
            `field_name` VARCHAR(255),
            `data` TEXT,
            `info` TEXT
            );";

    public static function table_name() {
        return 'db_structure';
    }

    public function validate() {
        return true;
    }

    public function save($info = '') {
        if ($this->id == null) {
            $this->user_id = \A::$app->user()->id;
            $this->createdon = date('Y-m-d H:i:s', time());
            $this->field_name = $this->new_name();
            $this->can_edit = true;
        }
        $this->updatedon = date('Y-m-d H:i:s', time());
        $id = parent::save($info = '');
        $this->after_save();
        return $id;
    }

    public function get_user() {
        if (!is_object($this->_user)) {
            $this->_user = User::findOne(['id' => $this->user_id]);
        }
        return $this->_user;
    }

    public function new_name() {
        $q = "SELECT {$this->table_name()}.* FROM {$this->table_name()} WHERE table_name = '{$this->table_name}' AND field_name REGEXP '^f[[:digit:]]*$' ORDER BY id desc LIMIT 1";
        $data = \A::$app->db()->query($q)->fetchAll(\PDO::FETCH_ASSOC)[0];
        if (is_array($data)) {
            $name = 'f' . (intval(str_replace("f", "", $data['field_name'])) + 1);
        } else {
            $name = 'f1';
        }
        return $name;
    }

    public function after_save() {
        $q = "SELECT table_name, column_name FROM information_schema.columns WHERE table_name LIKE '%{$this->table_name}%' AND column_name LIKE '%{$this->field_name}%' ";
        $data = \A::$app->db()->query($q)->fetchAll(\PDO::FETCH_ASSOC);
        if (is_array($data) && isset($data[0])) {
            $q = "ALTER TABLE $this->table_name MODIFY COLUMN $this->field_name " . self::$db_fields[$this->type];
            \A::$app->db()->exec($q);
        } else {
            $q = "ALTER TABLE $this->table_name ADD COLUMN $this->field_name " . self::$db_fields[$this->type];
            \A::$app->db()->exec($q);
        }
    }

    public function delete($info = '') {
        parent::delete($info = '');
        $q = "ALTER TABLE $this->table_name DROP COLUMN $this->field_name";
        \A::$app->db()->exec($q);
        return true;
    }

}
