<?php
	use yii\helpers\Html;
	use yii\widgets\LinkPager;
?>
<h1>Locations</h1>
<ul>
<?php foreach ($locations as $location): ?>
    <li>
        <?= $location->id?>:
        <?= Html::encode("'{$location->loc}'") ?>
    </li>
<?php endforeach; ?>
</ul>

<!--The LinkPager widget displays a list of page buttons. Clicking on any of them will refresh the country data in the corresponding page.-->
<?= LinkPager::widget(['pagination' => $pagination]) ?>
