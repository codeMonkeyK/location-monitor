<?php
	use yii\helpers\Html;
	
	$this->registerCss('
		.location-lbl {
			color : blue;
		}

		.bottom {
			display : none;
		}

		.glyphicon-chevron-up {
			display : none;
		}

		.glyphicon-chevron-down {
			display : visible;
		}
	');

	$this->registerJs('$(document).ready(function() {

		$(".location-lbl").on("click", function() {
			$(event.target).parent().next().slideToggle("fast");
			if ($(event.target).prop("class").includes("glyphicon-chevron-down") && $(event.target).css("display") == "inline-block") {
				$(event.target).next().css("display", "inline-block");
				$(event.target).css("display", "none");
			} else {
				$(event.target).prev().css("display", "inline-block");
				$(event.target).css("display", "none");	
			}
		});

		$(".location-lbl").on("mouseenter", function() {
			$(event.target).css("cursor", "pointer")
		});

		$(".location-lbl").on("mouseleave", function() {
			$(event.target).css("cursor", "default")
		});

	})'
	);
?>

<div class="row">

	<div style="float:left">
		<h4><strong>Logged IP Addresses</strong></h4>

		<ol>
			<?php $i = 0 ?>
			<?php foreach ($locs as $loc): ?>
			    <li><label> &nbsp IP location</label>: <?= Html::encode($loc['loc']) ?> - (count <?= Html::encode($loc['cnt']) ?>) &nbsp<span class="location-lbl glyphicon glyphicon-chevron-down" aria-hidden="true"> </span><span class="location-lbl glyphicon glyphicon-chevron-up" aria-hidden="true"> </span></li>
		   		<ul class='bottom'>
				   	<li><label>Status codes</label>:
					   	<?php $j = 0 ?>
					   	<?php foreach ($loc['status'] as $statusCode): 
					   		if ($j < sizeof($loc['status']) - 1) {
					   	?>		<?= Html::encode($statusCode) ?>,
						<?php } else {?>
					   			<?= Html::encode($statusCode) ?>
					   	<?php } 
					   		$j++;
					   	?>
					   	<?php endforeach; ?>
				   	</li>
				   	<li><label>IP Addresses</label>:
					   	<?php $j = 0 ?>
					   	<?php foreach ($loc['ips'] as $ip): 
					   		if ($j < sizeof($loc['ips']) - 1) {
					   	?>		<?= Html::encode($ip) ?>,
						<?php } else {?>
					   			<?= Html::encode($ip) ?>
					   	<?php } 
					   		$j++;
					   	?>
					   	<?php endforeach; ?>
				   	</li>
			   	</ul>
			   <?php $i++ ?>
			<?php endforeach; ?>
		</ol>

		<h4><strong>404 Errors</strong></h4>

		<ol>
			<?php $i = 0 ?>
			<?php foreach ($invalidLocs as $loc): ?>
			    <li><label> &nbsp IP location</label>: <?= Html::encode($loc['loc']) ?> - (count <?= Html::encode($loc['cnt']) ?>) &nbsp<span class="location-lbl glyphicon glyphicon-chevron-down" aria-hidden="true"> </span><span class="location-lbl glyphicon glyphicon-chevron-up" aria-hidden="true"> </span></li>
		   		<ul class='bottom'>
				   	<li><label>Status codes</label>:
					   	<?php $j = 0 ?>
					   	<?php foreach ($loc['status'] as $statusCode): 
					   		if ($j < sizeof($loc['status']) - 1) {
					   	?>		<?= Html::encode($statusCode) ?>,
						<?php } else {?>
					   			<?= Html::encode($statusCode) ?>
					   	<?php } 
					   		$j++;
					   	?>
					   	<?php endforeach; ?>
				   	</li>
				   	<li><label>IP Addresses</label>:
					   	<?php $j = 0 ?>
					   	<?php foreach ($loc['ips'] as $ip): 
					   		if ($j < sizeof($loc['ips']) - 1) {
					   	?>		<?= Html::encode($ip) ?>,
						<?php } else {?>
					   			<?= Html::encode($ip) ?>
					   	<?php } 
					   		$j++;
					   	?>
					   	<?php endforeach; ?>
				   	</li>
			   	</ul>
			   <?php $i++ ?>
			<?php endforeach; ?>
		</ol>
	</div>

	<div  style="float:right">
		<br/>
	    <?php
	    	use scotthuangzl\googlechart\GoogleChart;

	    	$locationData = [];

	    	$obj = ["location", "count"];
	        array_push($locationData, $obj);

	    	foreach ($locs as $loc):
	    		$obj = [$loc['loc'], $loc['cnt']];
	            array_push($locationData, $obj);
	    	endforeach;

	    	echo GoogleChart::widget(array('visualization' => 'PieChart',
		        'data' => $locationData,
		        'options' => array('title' => 'Percent Hits per Location')));
	    ?>
	</div>
</div>

