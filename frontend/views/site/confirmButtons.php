<?php
use yii\helpers\Html;

?>

<div  class="row">
    <h3 ><?echo $this->params['message']
        ?>
    <br/>
        <br/>
        <br/>
    </h3>
    <?= Html::a('Принять','', [
        'title' => Yii::t('yii', 'Close'),
        'class'=>'btn btn-success',
        'onclick'=>"$.ajax({
            type     :'GET',
            cache    : false,
            dataType : 'html',
            url  : '/site/apply-gift',
            success  : function(response) {
              $('#response').html(response);
                
            }
            });return false;",
    ]);

    ?>

    <? if(Yii::$app->session['gift']['type']==1) {
        echo Html::a('Перевести в баллы', '', [
            'title' => Yii::t('yii', 'Close'),
            'class' => 'btn btn-success',
            'onclick' => "$.ajax({
            type     :'GET',
            cache    : false,
            dataType : 'html',
            url  : '/site/apply-convert-to-bonus',
            success  : function(response) {
              $('#response').html(response);
                
            }
            });return false;",
        ]);
    };

    ?>

    <?= Html::a('Отказаться','', [
        'title' => Yii::t('yii', 'Close'),
        'class'=>'btn btn-danger',
        'onclick'=>"$.ajax({
            type     :'GET',
            cache    : false,
            url  : '/site/deny-gift',
            success  : function(response) {
              $('#response').html(response);
                
            }
            });return false;",
    ]);

    ?>

</div>

