<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>
<div class="row">
    <h3>Введите данные или подтвердите правльность введенных данных</h3>
    <br/>


    <div class="col-lg-5">
        <?php $form = ActiveForm::begin(['id' => 'adress-form']); ?>

        <?= $form->field($model, 'address')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'firstName')->textInput() ?>
        <?= $form->field($model, 'lastName')->textInput() ?>


        <? try {
            echo $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), [
                'mask' => '+7 (999) 999 99 99',
            ]);
        } catch (Exception $e) {
        } ?>
        <div class="form-group">
            <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name' => 'success-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
