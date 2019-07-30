<?php

namespace base\core;

class View {

    public function renderFile($file, $params = []) {
        $file = "$file.php";
        return $this->renderPhpFile($file, $params);
    }
    
    public function render($file, $params = []) {
        \A::$app->_isajax = false;
        return $this->renderFile($file, $params);
    }

    public function renderPhpFile($_file_, $_params_ = []) {
        ob_start();
        ob_implicit_flush(false);
        extract($_params_, EXTR_OVERWRITE);
        require($_file_);

        return ob_get_clean();
    }

    public function renderAjax($file, $params = []) {
        \A::$app->_isajax = true;
        return $this->renderFile($file, $params);
    }
}
