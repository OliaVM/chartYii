<?php

use miloschuman\highcharts\Highcharts;

$this->title = 'My Yii Application';
?>
<div class="site-index">

   <?php 
  		if (isset($error)) {
  			echo $error;
  		}
  		if (isset($arrDate)& isset($arrProfit)) {
  			echo Highcharts::widget([
			   'options' => [
				  'title' => ['text' => 'Chart'],
				  'xAxis' => [
					 'categories' => $arrDate
				  ],
				  'yAxis' => [
					 'title' => ['text' => 'profit']
				  ],
				  'series' => [
					 //['name' => 'Your profit', 'data' => $arrProfit],
					 ['name' => 'Your balance', 'data' => $arrBalance],
				  ]
			   ]
			]);
  		}
		
	?>
        
</div>
