<?php
	use yii\helpers\Html;
	
	$this->registerJsFile('/web/js/ip.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>

<p>Locations of logged IP addresses:</p>

<?php $i = 0; foreach ($locs as $loc): ?>

	<ul>
	    <li class="top"><label>Ip location</label>: <?= Html::encode($loc) ?></li>
	   	<li class="bottom"><label>Ip count</label>: <?= Html::encode($cnts[$i]) ?></li>
	   	<li class="bottom"><label>Status codes</label>:
		   	<?php foreach ($status[$i] as $statusCode): ?>
		   		<?= Html::encode($statusCode) ?>, 
		   	<?php endforeach; ?>
		   	<?php $i++?>
	   	</li>
	</ul>
<?php endforeach; ?>