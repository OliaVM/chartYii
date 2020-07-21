<?php

use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use yii\helpers\Html;


?>

<div>
        <?php $form = ActiveForm::begin([
            'enableClientValidation' => false,
            'options' => ['enctype' => 'multipart/form-data'],
        ]); ?>

        
        <?= Tabs::widget([  
            'items' => [
                [
                    'label' => 'Загрузить файл',
                    'content' => $this->render('_content_tab', ['model' => $model, 'form' => $form, 'url' => $url, 'image' => $image]),
                    'active' => true
                ],
            ],
        ]); ?>

        <?php ActiveForm::end(); ?>
</div>