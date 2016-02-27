 
<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
?>
<h1>Countries</h1>
<ul>
<?php foreach ($countries as $country): ?>
    <li>
        <?= Html::encode("{$country->name} ({$country->code})") ?>:
        <?= $country->population ?>
    </li>
<?php endforeach; ?>
</ul>

<!--The LinkPager widget displays a list of page buttons. Clicking on any of them will refresh the country data in the corresponding page.-->
<?= LinkPager::widget(['pagination' => $pagination]) ?>
