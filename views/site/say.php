<?php
use yii\helpers\Html;
?>
<h1>Hello <?= Html::encode($target) ?></h1>
<!-- Note: We encode the target variable to secure the URL parameter argument from malicious code.-->
<p>Welcome to your Yii2 demonstration application.</p>
