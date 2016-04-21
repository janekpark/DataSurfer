<!DOCTYPE html>
<?php 
	$urlBase = "http://$_SERVER[HTTP_HOST]";
	$datasource = $data['datasource'];
	$headerStmt;
	
	if ($datasource == 'forecast')
		$headerStmt = "Select Forecast Series:";
	elseif($datasource == 'census')
		$headerStmt = "Select Census Year:";
	elseif($datasource == 'censusacs')
		$headerStmt = "Select Census ACS Year:";
	elseif($datasource == 'estimate')
		$headerStmt = "Select Estimate Year:";
	  
?>
<html>
    <head>
        <title>SANDAG Data Surfer API</title>
    </head>
    <body>
    	<h1><?php echo $headerStmt ?></h1>
    	<ul>
    		<?php foreach($data['series'] as $series): ?>
    			<li><?php echo $urlBase.'/api/'.$datasource.'/'.$series;?></li>
    		<?php endforeach;?>
    	</ul>
    </body>
</html>