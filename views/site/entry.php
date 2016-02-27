 
<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<!--Yii provides many such widgets to help you quickly build complex and dynamic views. As you will learn later, writing a new widget is also extremely easy. You may want to turn much of your view code into reusable widgets to simplify view development in future.-->

<?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'email') ?>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>