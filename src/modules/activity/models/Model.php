<?php

namespace modules\activity\models;

class Model extends \base\core\Model {
    
    public function save($info = '') {
        $model = $this->gen_model($info);
        if ($this->id === null) {
            $model->action = 'create';
        } else {
            $model->action = 'update';
        }
        $res = parent::save();
        $model->table_id = $this->id;
        $model->save();
        return $res;
    }

    public function delete($info = '') {
        $model = $this->gen_model($info);
        $model->action = 'delete';
        $model->table_id = $this->id;
        $res = parent::delete();
        $model->save();
        return $res;
    }

    private function gen_model($info) {
        $model = new Activity();
        $model->table_name = static::table_name();
        $model->info = $info;
        return $model;
    }
}