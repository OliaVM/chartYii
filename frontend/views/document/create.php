<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;

?>

<div>
        <?php 
        $form = ActiveForm::begin([
            'enableClientValidation' => false,
            'options' => ['enctype' => 'multipart/form-data'], 
        ]); ?>


        <?= 
            Tabs::widget([  
            'items' => [
                [
                    'label' => 'Информация о файле',
                    'content' => $this->render('_create_tab', ['model' => $model, 'form' => $form]),
                    'active' => true
                ],
            ],
        ]); ?>


        <?php echo $this->render('/layouts/_buttons', ['model' => $model]); ?>

        <?php ActiveForm::end(); ?>

</div>

