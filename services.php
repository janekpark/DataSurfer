<?php
require_once("lib/config.php");
require_once("lib/function.php");
if($_POST){
	$url = API_URL;
	$type_chart = isset($_POST['type_chart'])?$_POST['type_chart']:null;
	$url_des = isset($_POST['url_des'])?$_POST['url_des']:null;
	if($type_chart){
		if($url_des){
			callApi($url_des);
		}
	}else{
		$source_type = isset($_POST['source_type'])?$_POST['source_type']:null;
		$year = isset($_POST['year'])?$_POST['year']:null;
		$geography_type = isset($_POST['geography_type'])?$_POST['geography_type']:null;
		$location = isset($_POST['location'])?$_POST['location']:null;
		if(isset($source_type)){
			$url.= "/".$source_type;
		}
		if(isset($year)){
			$url.= "/".$year;
		}
		if(isset($geography_type)){
			$url.= "/".$geography_type;
		}
		if(isset($location)){
			$url.= "/".$location;
		}
		callApi($url);
	}
}
?>