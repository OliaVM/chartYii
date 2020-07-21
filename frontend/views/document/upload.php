<?php

use yii\helpers\Html;

?>

<div >
    <h1>page</h1>
    <?= $this->render('_form', [
        'model' => $model,
        'url' => $url,
        'image' => $image
    ]) ?>
</div>
