<?php
use yii\helpers\Html;
//use milano\tinymce\TinyMce;
use yii\helpers\Url;

?>
 
<div>
	<?php echo $form->field($model, 'typeOfFile')->dropDownList([
	    '0' => 'html',
	    '1' => 'xml'  
	])->label('Формат файла') ?>
	<?php echo $form->field($model, 'numberType')->textInput(['placeholder' => 3])->label('Номер колонки, содержащей  тип операции type') ?>

	<?php echo $form->field($model, 'numberDate')->textInput(['placeholder' => 2])->label('Номер колонки, содержащей  open time') ?>
	<?php echo $form->field($model, 'numberProfit')->textInput(['placeholder' => 14])->label('Номер колонки, содержащей profit') ?>

	<?php echo $form->field($model, 'nameBalance')->textInput(['placeholder' => "balance"])->label('Название операции типа balance') ?>
	<?php echo $form->field($model, 'nameBuyStop')->textInput(['placeholder' => "buy stop"])->label('Название операции типа buy stop') ?>
	<?php echo $form->field($model, 'nameBuy')->textInput(['placeholder' => "buy"])->label('Название операции типа buy') ?>
	<?php echo $form->field($model, 'nameSell')->textInput(['placeholder' => "sell"])->label('Название операции типа sell') ?>
</div>



