<?php
namespace base\core;

class Query {

    public $_where;
    public $_select;
    public $_from;
    
    public function get() {
        return $this->_select.$this->_from.$this->_where;
    }
    
    public function select($d = '') {
        if (empty($this->_select)) {
            $this->_select = 'SELECT ';
        }
        if (empty($d)) {
            $this->_select .= '*';
        }
        return $this;
    }

    public function from($d) {
        if (empty($this->_from)) {
            $this->_from = ' FROM ';
        }
        $this->_from .= " `$d`";
        return $this;
    }
    
    public function where($d) {
        if (empty($d)) {
            return $this;
        }
        if (is_array($d)) {
            $s = '';
            $i = 0;
            foreach ($d as $key => $val) {
                if ($i > 0) {
                    $s .= ' AND ';
                }
                $i++;
                $s .= "`$key` = '$val'";
            }
        } else {
            $s = $d;
        }
        if (!empty($this->_where)) {
            $this->_where .= ' AND ' . $s;
        } else {
            $this->_where = ' WHERE ' . $s;
        }
        return $this;
    }

}
