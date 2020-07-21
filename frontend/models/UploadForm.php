<?php

namespace frontend\models;

use yii\base\Model;
use yii\web\UploadedFile;

// Загружаем на сервер изображение

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $documentFile;

    public function rules()
    {
        return [
            [['documentFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'html, xml'], 
        ];
    }
    
    public function upload($path)
    {
        if ($this->validate()) {
            $uploaddir = '../../frontend/web' . $path;
            if (!file_exists($uploaddir)) {
                mkdir($uploaddir, 0755, true);
            }
            $this->documentFile->saveAs($uploaddir . $this->documentFile->baseName . '.' . $this->documentFile->extension); 
            return true;
        } else {
            return false;
        }
    }
}