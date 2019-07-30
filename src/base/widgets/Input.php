<?php
namespace base\widgets;

class Input {

    static public function hidden($model, $attr) {
        $str = '<input name="' . get_class($model) . '[' . $attr . ']' . '" type="hidden"  value="' . $model->{$attr} . '" />';
        return $str;
    }
    
    static public function password($model, $attr, $options = []) {
        return self::text($model, $attr, $options, 'password');
    }

    static public function text($model, $attr, $options = [], $type = 'text') {
        $use_lable = true;
        $placeholder = '';
        $disabled = '';
        if (isset($options['placeholder'])) {
            $placeholder = ' placeholder="' . $options['placeholder'] . '" ';
        }
        if (isset($options['disabled'])) {
            $disabled = ' disabled="' . $options['disabled'] . '" ';
        }
        if (isset($options['nolable'])) {
            $use_lable = false;
        }
        $str = '';
        $str .= '<div class="form-group">';
        if ($use_lable) {
            $str .= '<label>' . $model->getLabel($attr) . ':</label>';
        }
        $str .= '<input name="' . get_class($model) . '[' . $attr . ']' . '" ' . $disabled . $placeholder . ' type="'.$type.'" class="form-control  '.$model->getValidatorType($attr).'" value="' . $model->{$attr} . '" />';
        $str .= '<div class="invalid-feedback">'.$model->getValidatorMessage($attr).'</div>';
        $str .= '</div>';
        return $str;
    }

    static public function textarea($model, $attr, $options = []) {
        $use_lable = true;
        $placeholder = '';
        if (isset($options['placeholder'])) {
            $placeholder = ' placeholder="' . $options['placeholder'] . '" ';
        }
        if (isset($options['nolable'])) {
            $use_lable = false;
        }
        $str = '';
        $str .= '<div class="input-group '.$model->getValidatorType($attr).'">';
        if ($use_lable) {
            $str .= '<label>' . $model->getLabel($attr) . ':</label>';
        }
        $str .= '<textarea name="' . get_class($model) . '[' . $attr . ']' . '" class="input-text" '.$placeholder.'>' . $model->{$attr} . '</textarea>';
        $str .= '<div class="info-block">'.$model->getValidatorMessage($attr).'</div>';
        $str .= '</div>';
        return $str;
    }

    static public function select($model, $attr, $array, $prompt = '') {
        $o = '';
        if (!empty($prompt)) {
            $o = '<option value="" selected="selected" disabled>'.$prompt.'</option>';
        }
        foreach ($array as $k => $v) {
            $o .= '<option value="' . $k . '" ' . ($k == $model->{$attr} ? 'selected' : '') . '>' . $v . '</option>';
        }

        $str = '';
        $str .= '<div class="input-group">';
        $str .= '<label>' . $model->getLabel($attr) . ':</label>';
        $str .= '<select name="' . get_class($model) . '[' . $attr . ']' . '" class="input-text">' . $o . '</select>';
        $str .= '</div>';
        return $str;
    }
    
    static public function select2($model, $attr, $array, $prompt = '') {
        $o = '';
        if (!empty($prompt)) {
            $o = '<option value="" selected="selected" disabled>'.$prompt.'</option>';
        }
        
        foreach ($array as $k => $v) {
            $o .= '<option value="' . $k . '" ' . ($k == $model->{$attr} ? 'selected' : '') . '>' . $v . '</option>';
        }

        $str = '';
        $str .= '<div class="input-group '.$model->getValidatorType($attr).'">';
        $str .= '<label>' . $model->getLabel($attr) . ':</label>';
        $str .= '<select name="' . get_class($model) . '[' . $attr . ']' . '" class="input-text select2">' . $o . '</select>';
        $str .= '<div class="info-block">'.$model->getValidatorMessage($attr).'</div>';
        $str .= '</div>';
        return $str;
    }

    static public function checkbox($model, $attr, $value) {
        $str = '';
        $str .= '<div class="input-group">';
        $str .= '<label>' . $model->getLabel($attr) . ':</label>';
        $str .= '<input type="checkbox" name="' . get_class($model) . '[' . $attr . ']' . '" ' . ($value == $model->{$attr} ? 'checked' : '') . ' class="input-text" value="' . $value . '"/>';
        $str .= '</div>';
        return $str;
    }

}
