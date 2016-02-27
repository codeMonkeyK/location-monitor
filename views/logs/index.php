<?php
	use yii\helpers\Html;
	use yii\widgets\LinkPager;
?>
<h1>Logs</h1>
<ul>
<?php foreach ($logs as $log): ?>
    <li>
        <?= $log->id?>:
        <?= Html::encode("'{$log->log}'") ?>
    </li>
<?php endforeach; ?>
</ul>

<!--The LinkPager widget displays a list of page buttons. Clicking on any of them will refresh the country data in the corresponding page.-->
<?= LinkPager::widget(['pagination' => $pagination]) ?>
