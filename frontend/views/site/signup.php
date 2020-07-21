<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-signup">
    <h1>Регистрация</h1>
    <div class="row">
        <div class="col-lg-5 regisration-form">

            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
           <!--  <div class="form-group"> -->
            <div id="regisration-form-wrapper">
                <div class="col-md-12">
                    <p id="error-registration"></p>
                </div>
                <div class="col-md-12">
                    <strong>Ваше имя</strong>
                </div>
                <div class="col-md-6">     
                    <?= $form->field($model, 'username')->textInput(['placeholder' => 'Имя'])->label(false) ?>
                </div>
                <div class="col-md-6">  
                    <?= $form->field($model, 'surname')->textInput(['placeholder' => 'Фамилия'])->label(false) ?>
                </div>
                <div class="col-md-12">
                    <?php $arrGender = ['1' => 'женщина', '0' =>'мужчина']; ?>
                     <?= $form->field($model, 'gender', ['inline'=>true, 'enableLabel'=>true])->radioList($arrGender, [
                            'item' => function ($index, $label, $name, $checked, $value) {
                                 return '<label class="label-gender' . ($checked ? ' active' : '') . '">' . Html::radio($name, $checked, ['value' => $value, 'class' => 'input-gender']) . $label . '</label>';
                            },
                        ]
                        ) ?>    
                </div>

                <div class="col-md-12">   
                    <?= $form->field($model, 'phone')->textInput(['id' => 'phone', 
                        'style' => 'display: block;  !important;',
                        'labelOptions' => ['style' => 'display: block;  !important;']
                    ]) ?>
                </div>
                <?php 
                    $arrDay = [
                        '01' => 1, '02' => 2, '03' => 3, '04' => 4,
                        '05' => 5, '06' => 6,  '07' => 7, '08' => 8,
                        '09' => 9, '10' => 10, '11' => 11, '12' => 12,
                        '13' => 13, '14' => 14, '15' => 15, '16' => 16,
                        '17' => 17, '18' => 18, '19' => 19,  '20' => 20,
                        '21' => 21, '22' => 22, '23' => 23,  '24' => 24,
                        '25' => 25, '26' => 26, '27' => 27, '28' => 28,
                        '29' => 29, '30' => 30, '31' => 31
                    ]; 

                    $arrMonth = [
                        '01' => 'Январь', '02' => 'Февраль', '03' => 'Март', '04' => 'Апрель',
                        '05' => 'Май', '06' => 'Июнь','07' => 'Июль', '08' => 'Август',
                        '09' => 'Сентябрь', '10' => 'Октябрь', '11' => 'Ноябрь', '12' => 'Декабрь'
                    ]; 

                    $arrYear = [
                        '2020' => 2020, '2019' => 2019,
                        '2018' => 2018, '2017' => 2017,
                        '2016' => 2016, '2015' => 2015,
                        '2014' => 2014, '2013' => 2013,
                        '2012' => 2012, '2011' => 2011, '2010' => 2010,
                        '2009' => 2009, '2008' => 2008, '2007' => 2007,
                        '2006' => 2006, '2005' => 2005, '2004' => 2004,
                        '2003' => 2003, '2002' => 2002, '2001' => 2001,
                        '2000' => 2000, '1999' => 1999, '1998' => 1998,
                        '1997' => 1997, '1996' => 1996, '1995' => 1995,
                        '1994' => 1994, '1993' => 1993, '1992' => 1992,
                        '1991' => 1991, '1990' => 1990,
                        '1989' => 1989, '1988' => 1988, '1987' => 1987, 
                        '1986' => 1986, '1985' => 1985, '1984' => 1984,
                    ]; 

                    $checkboxTemplate =  '<label>{input}{label}</label>{error}';
                ?>
                <div>
                    <div class="col-md-12">
                        <label>Дата рождения</label>
                    </div>
                    <div class="col-md-4">                        
                        <?= $form->field($model, 'day')->dropDownList($arrDay)->label(false) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'month')->dropDownList($arrMonth)->label(false) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'year')->dropDownList($arrYear)->label(false) ?>
                    </div>
                </div>

                <div class="col-md-12">
                    <p><?= $form->field($model, 'email')->textInput(['placeholder' => 'На этот адрес мы отправим пароль']) ?></p>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'agree', ['template' => $checkboxTemplate])->checkbox(
                        [
                            'class' => 'checkbox-agree', 
                            'labelOptions' => ['class' => 'agree-label', 'style' => 'padding-left: 10px;']
                        ], false)->label('Я принимаю условия  <a href="/docs/pconf.pdf" target="_blank" class="label-agree">соглашения</a> ') 
                    ?>
                </div>
        
                <div class="col-md-12">
                    <div class="form-group">
                        <?= Html::submitButton('Зарегистрироваться', [
                                'class' => 'btn submit-button-registration', 
                                'name' => 'signup-button', 
                                'id' => 'submit-button-registration'
                        ]) ?>
                    </div>
                </div>
                <div id="sucsess-registration"></div>

            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
 

<script>
jQuery(function($){
    $("#phone").intlTelInput({
      initialCountry: "auto",
      separateDialCode: true,
      preferredCountries: ["ru", "us", "gb"],
      
      geoIpLookup: function(callback) {
        $.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
          var countryCode = (resp && resp.country) ? resp.country : "";
          callback(countryCode);
        });
      },
      utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/js/utils.js"
    });

    $('#phone').on('focus', function() {
        var $this = $(this),
        // Get active country's phone number format from input placeholder attribute
        activePlaceholder = $this.attr('placeholder'),
        newMask = activePlaceholder.replace(/[1-9]/g, "0");
        $this.mask(newMask);
    });
});

</script>

