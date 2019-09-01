<?php

/* @var $this yii\web\View */

use yii\helpers\Html;


$this->title = 'Получи свой приз';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-getgift">
    <h1><?= Html::encode($this->title) ?></h1>
<div class="center-block" id="response">

    <?= Html::a('получить подарочек','', [
        'title' => Yii::t('yii', 'Close'),
        'class'=>'btn btn-success',
        'onclick'=>"$.ajax({
            type     :'GET',
            cache    : false,
            dataType : 'html',
            url  : '/site/gifts',
            success  : function(response) {
              $('#response').html(response);
               
            }
            });return false;",
    ]);

    ?>

</div>

</div>
