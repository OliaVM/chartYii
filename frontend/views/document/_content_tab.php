<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\models\UploadForm;

?>
 

<div>
<?php
    echo \kartik\file\FileInput::widget([
            'model' => new UploadForm(),
            'attribute' => 'documentFile', 
            'name' => 'documentFile', 
            'options'=>[    
            ],
            'pluginOptions' => [
                'allowedFileExtensions' => ['xml', 'html'],
    	        'uploadUrl' => Url::to(['/document/upload']),
                'browseClass' => 'btn btn-success',
                'uploadClass' => 'btn btn-info',
                'removeClass' => 'btn btn-danger',
            ]                
    ]); 
?>
</div>





