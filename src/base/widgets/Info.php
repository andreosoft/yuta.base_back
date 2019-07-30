<?php
namespace widgets;

class Info {
    static public function gen($model, $data) {
        $r = '';
        foreach ($data as $el) {
            $r .= '<p>';
            $r .= $model->getLabel($el);
            $r .= ': <b>'.$model->{$el}.'</b>';
            $r .= '</p>';
        }
        return $r;
    }
}