 
<?php
use yii\helpers\Html;
?>
<p>You have entered the following information:</p>

<ul>
    <li><label>Ip location</label>: <?= Html::encode($loc) ?></li>
    <li><label>Ip count</label>: <?= Html::encode($cnt) ?></li>
</ul>