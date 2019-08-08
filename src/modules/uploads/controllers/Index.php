<?php

namespace modules\uploads\controllers;
use modules\uploads\models\Upload;
class Index extends \base\core\Controller {

    public function preAction($action) {
        $this->corsHeaders();
        if (\A::$app->user()->role < 1) {
            header('HTTP/1.0 403 Forbidden');
            echo 'You are forbidden!';
            exit();
        }
    }

    public function action_options() {
        $this->corsHeaders();
    }

    public function action_post() {
        $res = $this->copy_file();
        if ($res !== false) {
            $model = new Upload();
            $model->name = $_FILES['file']['name'];
            $model->file = $res;
            $model->type_id = 1;
            $model->save();
            return json_encode(['status' => 'ok', 'data' => $model->fields]);
        }
        return json_encode(['status' => 'error', 'massage' => 'upload error']);
    }
    
    private function copy_file($fileDir = '') {
        $localDir = UPLOADS;
        if (!is_dir($localDir . '/' . $fileDir)) {
            mkdir($localDir . '/' . $fileDir, 0775, true);
        }
        $fileInfo = pathinfo($_FILES['file']['name']);
        $fileName = md5($fileInfo['filename'].time()) . '.' . $fileInfo['extension'];
        $uploadfile = $localDir . '/' . $fileDir . '/' . $fileName;
        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
            return $fileDir . '/' . $fileName ;
        }
        return false;
    }
    
}
