<?php
require_once("lib/config.php");
//require_once("lib/function.php");
require_once("generateJson.php");

$page_title = "SANDAG Data Surfer | Contact Us";
$geozonesql = "select distinct geozone from dim.mgra where series = $1 and  geotype = $2 group by geozone order by geozone;";


$geotypes = array("jurisdiction",
	"region" ,
	"zip" ,
	"msa" ,
	"sra" ,
	"tract" ,
	"elementary" ,
	"secondary" ,
	"unified" ,
	"college" ,
	"sdcouncil" ,
	"supervisorial" ,
	"cpa" );
//$geotypes = array(
//	"region" ,
//	"zip" ,
//	"msa" ,
//	"sra" ,
//	
//	"elementary" ,
//	"secondary" ,
//	"unified" ,
//	"college" ,
//	"sdcouncil" ,
//	"supervisorial" ,
//	"cpa" );
//$geotypes = array("jurisdiction");

$isSend = false;

//if (isset($_POST) && count($_POST) > 0) {
	//if ($_SERVER["REQUEST_METHOD"] == "POST") {
	//if($_POST['txtDatasource'] && $_POST['txtYear'] && $_POST['txtGeotype'] && $_POST['ddlGeoZone']){
	//$datasource = $_POST['txtDatasource'];
	//$year =  $_POST['ddlYear'];
	//$geotype = $_POST['ddlGeoType'];
	//$zone = ucwords( $_POST['ddlGeoZone']);
$datasource = "censusacs";
$year = "2010";

foreach( $geotypes as $geotype){
	$geozoneJson =  Query::getInstance()->getZonesAsJson($geozonesql,13, $geotype);
	$geozoneJsonDataObject = json_decode($geozoneJson);
	
	$zones = array();
	
	foreach( $geozoneJsonDataObject as $eachZone ){
		array_push( $zones, ucwords($eachZone->geozone));
	}	
	
	//var_dump( $zones);
	//delete  all geozone file
	//******* 1 Race-Ethnicity **************
	$raceEthnicity_all_file_name = strtolower(join("_", array('raceEthnicity', "censusacs", $year, $geotype, "all")).".json");
	$raceEthnicity_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$raceEthnicity_all_file_path = join(DIRECTORY_SEPARATOR, array($raceEthnicity_file_dir, $raceEthnicity_all_file_name));
	if (file_exists($raceEthnicity_all_file_path))
		unlink($raceEthnicity_all_file_path);
	
	//******* 2. Age and Sex **************
	$ageSex_all_file_name = strtolower(join("_", array('ageSex', "censusacs", $year, $geotype, "all")).".json");
	$ageSex_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$ageSex_all_file_path = join(DIRECTORY_SEPARATOR, array($ageSex_file_dir, $ageSex_all_file_name));
	if(file_exists($ageSex_all_file_path))
		unlink($ageSex_all_file_path);
	
	$ageSexMedian_all_file_name = strtolower(join("_", array('ageSexMedian', "censusacs", $year, $geotype, "all")).".json");
	$ageSex_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$ageSexMedian_all_file_path = join(DIRECTORY_SEPARATOR, array($ageSex_file_dir, $ageSexMedian_all_file_name));
	if(file_exists($ageSexMedian_all_file_path))
	unlink($ageSexMedian_all_file_path);
	
	//******* 3 MaritalStatus **************
	$maritalStatus_all_file_name = strtolower(join("_", array('maritalStatus', "censusacs", $year, $geotype, "all")).".json");
	$maritalStatus_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$maritalStatus_all_file_path = join(DIRECTORY_SEPARATOR, array($maritalStatus_file_dir, $maritalStatus_all_file_name));
	if (file_exists($maritalStatus_all_file_path))
		unlink($maritalStatus_all_file_path);
	
	//******* 4 HouseholdGroupQuarters **************
	$householdGroupQtrs_all_file_name = strtolower(join("_", array('householdGroupQtrs', "censusacs", $year, $geotype, "all")).".json");
	$householdGroupQtrs_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$householdGroupQtrs_all_file_path = join(DIRECTORY_SEPARATOR, array($householdGroupQtrs_file_dir, $householdGroupQtrs_all_file_name));
	if (file_exists($householdGroupQtrs_all_file_path))
		unlink($householdGroupQtrs_all_file_path);
	
	//******* 5 Age, Race-Ethnicity **************
	$ethnicityAgeGroup_all_file_name = strtolower(join("_", array('ethnicityAgeGroup', "censusacs", $year, $geotype, "all")).".json");
	$ethnicityAgeGroup_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$ethnicityAgeGroup_all_file_path = join(DIRECTORY_SEPARATOR, array($ethnicityAgeGroup_file_dir, $ethnicityAgeGroup_all_file_name));
	if (file_exists($ethnicityAgeGroup_all_file_path))
		unlink($ethnicityAgeGroup_all_file_path);
	
	$ethnicityAgeGroupMedian_all_file_name = strtolower(join("_", array('ethnicityAgeGroupMedian', "censusacs", $year, $geotype, "all")).".json");
	$ethnicityAgeGroupMedian_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$ethnicityAgeGroupMedian_all_file_path = join(DIRECTORY_SEPARATOR, array($ethnicityAgeGroupMedian_file_dir, $ethnicityAgeGroupMedian_all_file_name));
	if (file_exists($ethnicityAgeGroupMedian_all_file_path))
		unlink($ethnicityAgeGroupMedian_all_file_path);
	
	//******* 6 LanguageAt Home **************
	$languageSpoken_all_file_name = strtolower(join("_", array('languageSpoken', "censusacs", $year, $geotype, "all")).".json");
	$languageSpoken_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$languageSpoken_all_file_path = join(DIRECTORY_SEPARATOR, array($languageSpoken_file_dir, $languageSpoken_all_file_name));
	if (file_exists($languageSpoken_all_file_path))
		unlink($languageSpoken_all_file_path);
	
	//******* 7 Educational Attainment **************
	$educationalAttainment_all_file_name = strtolower(join("_", array('educationalAttainment', "censusacs", $year, $geotype, "all")).".json");
	$educationalAttainment_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$educationalAttainment_all_file_path = join(DIRECTORY_SEPARATOR, array($educationalAttainment_file_dir, $educationalAttainment_all_file_name));
	if (file_exists($educationalAttainment_all_file_path))
		unlink($educationalAttainment_all_file_path);

	//******* 8 SchoolEnrollment **************
	$schoolEnrollment_all_file_name = strtolower(join("_", array('schoolEnrollment', "censusacs", $year, $geotype, "all")).".json");
	$schoolEnrollment_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$schoolEnrollment_all_file_path = join(DIRECTORY_SEPARATOR, array($schoolEnrollment_file_dir, $schoolEnrollment_all_file_name));
	if (file_exists($schoolEnrollment_all_file_path))
		unlink($schoolEnrollment_all_file_path);
	
	//******* 10 Household TypePresenceUnder 18 **************
	$householdsTypeByUnder18_all_file_name = strtolower(join("_", array('householdsTypeByUnder18', "censusacs", $year, $geotype, "all")).".json");
	$householdsTypeByUnder18_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$householdsTypeByUnder18_all_file_path = join(DIRECTORY_SEPARATOR, array($householdsTypeByUnder18_file_dir, $householdsTypeByUnder18_all_file_name));
	if (file_exists($householdsTypeByUnder18_all_file_path))
		unlink($householdsTypeByUnder18_all_file_path);

	//******* 11 HousingUnitsType **************
	$housingUnitsType_all_file_name = strtolower(join("_", array('housingUnitsType', "censusacs", $year, $geotype, "all")).".json");
	$housingUnitsType_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$housingUnitsType_all_file_path = join(DIRECTORY_SEPARATOR, array($housingUnitsType_file_dir, $housingUnitsType_all_file_name));
	if (file_exists($housingUnitsType_all_file_path))
		unlink($housingUnitsType_all_file_path);

	//******* 12 House Value **************
	$housingValue_all_file_name = strtolower(join("_", array('housingValue', "censusacs", $year, $geotype, "all")).".json");
	$housingValue_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
		$housingValue_all_file_path = join(DIRECTORY_SEPARATOR, array($housingValue_file_dir, $housingValue_all_file_name));
	if (file_exists($housingValue_all_file_path))
		unlink($housingValue_all_file_path);

	$housingValueMedian_all_file_name = strtolower(join("_", array('housingValueMedian', "censusacs", $year, $geotype, "all")).".json");
	$housingValueMedian_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$housingValueMedian_all_file_path = join(DIRECTORY_SEPARATOR, array($housingValue_file_dir, $housingValueMedian_all_file_name));
	if (file_exists($housingValueMedian_all_file_path))
		unlink($housingValueMedian_all_file_path);

	//******* 13 Yr House Built **************
	$yrHouseBuilt_all_file_name = strtolower(join("_", array('yrHouseBuilt', "censusacs", $year, $geotype, "all")).".json");
	$yrHouseBuilt_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$yrHouseBuilt_all_file_path = join(DIRECTORY_SEPARATOR, array($yrHouseBuilt_file_dir, $yrHouseBuilt_all_file_name));
	if (file_exists($yrHouseBuilt_all_file_path))
		unlink($yrHouseBuilt_all_file_path);
	
	//******* 14 House Tenure and OccupRoom **************
	$houseTenureOccupied_all_file_name = strtolower(join("_", array('houseTenureOccupied', "censusacs", $year, $geotype, "all")).".json");
	$houseTenureOccupied_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$houseTenureOccupied_all_file_path = join(DIRECTORY_SEPARATOR, array($houseTenureOccupied_file_dir, $houseTenureOccupied_all_file_name));
	if (file_exists($houseTenureOccupied_all_file_path))
		unlink($houseTenureOccupied_all_file_path);
	
	//******* 15 ContractRent **************
	$contractRent_all_file_name = strtolower(join("_", array('contractRent', "censusacs", $year, $geotype, "all")).".json");
	$contractRent_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$contractRent_all_file_path = join(DIRECTORY_SEPARATOR, array($contractRent_file_dir, $contractRent_all_file_name));
	if (file_exists($contractRent_all_file_path))
		unlink($contractRent_all_file_path);
	
	$contractRentMedian_all_file_name = strtolower(join("_", array('contractRentMedian', "censusacs", $year, $geotype, "all")).".json");
	$contractRentMedian_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$contractRentMedian_all_file_path = join(DIRECTORY_SEPARATOR, array($contractRentMedian_file_dir, $contractRentMedian_all_file_name));
	if (file_exists($contractRentMedian_all_file_path))
		unlink($contractRentMedian_all_file_path);

	//******* 16 GrossRent Household Income **************
	$grossRentHouseholdIncome_all_file_name = strtolower(join("_", array('grossRentHouseholdIncome', "censusacs", $year, $geotype, "all")).".json");
	$grossRentHouseholdIncome_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$grossRentHouseholdIncome_all_file_path = join(DIRECTORY_SEPARATOR, array($grossRentHouseholdIncome_file_dir, $grossRentHouseholdIncome_all_file_name));
	if (file_exists($grossRentHouseholdIncome_all_file_path))
		unlink($grossRentHouseholdIncome_all_file_path);

	//******* 17 Vehicle Availability **************
	$vehicleAvailability_all_file_name = strtolower(join("_", array('vehicleAvailability', "censusacs", $year, $geotype, "all")).".json");
	$vehicleAvailability_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$vehicleAvailability_all_file_path = join(DIRECTORY_SEPARATOR, array($vehicleAvailability_file_dir, $vehicleAvailability_all_file_name));
	if (file_exists($vehicleAvailability_all_file_path))
		unlink($vehicleAvailability_all_file_path);

	//******* 18 Place of work( CountyLevel) **************
	$placeofWork_all_file_name = strtolower(join("_", array('placeofWork', "censusacs", $year, $geotype, "all")).".json");
	$placeofWork_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$placeofWork_all_file_path = join(DIRECTORY_SEPARATOR, array($placeofWork_file_dir, $placeofWork_all_file_name));
	if (file_exists($placeofWork_all_file_path))
		unlink($placeofWork_all_file_path);
	
	//******* 19 Transportation to Work **************
	$transportationtoWork_all_file_name = strtolower(join("_", array('transportationtoWork', "censusacs", $year, $geotype, "all")).".json");
	$transportationtoWork_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$transportationtoWork_all_file_path = join(DIRECTORY_SEPARATOR, array($transportationtoWork_file_dir, $transportationtoWork_all_file_name));
	if (file_exists($transportationtoWork_all_file_path))
		unlink($transportationtoWork_all_file_path);

	//******* 20 Travel Time to Work **************
	$travelTimetoWork_all_file_name = strtolower(join("_", array('travelTimetoWork', "censusacs", $year, $geotype, "all")).".json");
	$travelTimetoWork_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$travelTimetoWork_all_file_path = join(DIRECTORY_SEPARATOR, array($travelTimetoWork_file_dir, $travelTimetoWork_all_file_name));
	if (file_exists($travelTimetoWork_all_file_path))
		unlink($travelTimetoWork_all_file_path);
	
	$travelTimetoWorkAverage_all_file_name = strtolower(join("_", array('travelTimetoWorkAverage', "censusacs", $year, $geotype, "all")).".json");
	$travelTimetoWorkAverage_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$travelTimetoWorkAverage_all_file_path = join(DIRECTORY_SEPARATOR, array($travelTimetoWorkAverage_file_dir, $travelTimetoWorkAverage_all_file_name));
	if (file_exists($travelTimetoWorkAverage_all_file_path))
		unlink($travelTimetoWorkAverage_all_file_path);

	//******* 21 Employment Status **************
	$employmentStatus_all_file_name = strtolower(join("_", array('employmentStatus', "censusacs", $year, $geotype, "all")).".json");
	$employmentStatus_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$employmentStatus_all_file_path = join(DIRECTORY_SEPARATOR, array($employmentStatus_file_dir, $employmentStatus_all_file_name));
	if (file_exists($employmentStatus_all_file_path))
		unlink($employmentStatus_all_file_path);

	//******* 22 Occupation **************
	$occupation_all_file_name = strtolower(join("_", array('occupation', "censusacs", $year, $geotype, "all")).".json");
	$occupation_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$occupation_all_file_path = join(DIRECTORY_SEPARATOR, array($occupation_file_dir, $occupation_all_file_name));
	if (file_exists($occupation_all_file_path))
		unlink($occupation_all_file_path);

	//******* 23 Industry **************
	$industry_all_file_name = strtolower(join("_", array('industry', "censusacs", $year, $geotype, "all")).".json");
	$industry_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$industry_all_file_path = join(DIRECTORY_SEPARATOR, array($industry_file_dir, $industry_all_file_name));
	if (file_exists($industry_all_file_path))
		unlink($industry_all_file_path);

	//******* 24 Household Income **************
	$householdIncome_all_file_name = strtolower(join("_", array('householdIncome', "censusacs", $year, $geotype, "all")).".json");
	$householdIncome_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$householdIncome_all_file_path = join(DIRECTORY_SEPARATOR, array($householdIncome_file_dir, $householdIncome_all_file_name));
	if (file_exists($householdIncome_all_file_path))
		unlink($householdIncome_all_file_path);
	
	$householdIncomeMedian_all_file_name = strtolower(join("_", array('householdIncomeMedian', "censusacs", $year, $geotype, "all")).".json");
	$householdIncomeMedian_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$householdIncomeMedian_all_file_path = join(DIRECTORY_SEPARATOR, array($householdIncomeMedian_file_dir, $householdIncomeMedian_all_file_name));
	if (file_exists($householdIncomeMedian_all_file_path))
		unlink($householdIncomeMedian_all_file_path);

	//******* 25 Earnings and Income **************
	$earningsAndIncome_all_file_name = strtolower(join("_", array('earningsAndIncome', "censusacs", $year, $geotype, "all")).".json");
	$earningsAndIncome_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$earningsAndIncome_all_file_path = join(DIRECTORY_SEPARATOR, array($earningsAndIncome_file_dir, $earningsAndIncome_all_file_name));
	if (file_exists($earningsAndIncome_all_file_path))
		unlink($earningsAndIncome_all_file_path);

	//******* 26 Ratio Income to Poverty Level **************
	$ratioIncomePovertyLevel_all_file_name = strtolower(join("_", array('ratioIncomePovertyLevel', "censusacs", $year, $geotype, "all")).".json");
	$ratioIncomePovertyLevel_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$ratioIncomePovertyLevel_all_file_path = join(DIRECTORY_SEPARATOR, array($ratioIncomePovertyLevel_file_dir, $ratioIncomePovertyLevel_all_file_name));
	if (file_exists($ratioIncomePovertyLevel_all_file_path))
		unlink($ratioIncomePovertyLevel_all_file_path);

	//******* 27 Poverty Status **************
	$povertyStatus_all_file_name = strtolower(join("_", array('povertyStatus', "censusacs", $year, $geotype, "all")).".json");
	$povertyStatus_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$povertyStatus_all_file_path = join(DIRECTORY_SEPARATOR, array($povertyStatus_file_dir, $povertyStatus_all_file_name));
	if (file_exists($povertyStatus_all_file_path))
		unlink($povertyStatus_all_file_path);

	//******* 28 PovertyStatus- FamType Children **************
	$povertyStatusByFamilyType_all_file_name = strtolower(join("_", array('povertyStatusByFamilyType', "censusacs", $year, $geotype, "all")).".json");
	$povertyStatusByFamilyType_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$povertyStatusByFamilyType_all_file_path = join(DIRECTORY_SEPARATOR, array($povertyStatusByFamilyType_file_dir, $povertyStatusByFamilyType_all_file_name));
	if (file_exists($povertyStatusByFamilyType_all_file_path))
		unlink($povertyStatusByFamilyType_all_file_path);


	foreach( $zones as $zone){
		
		$zone = mb_convert_case($zone, MB_CASE_TITLE, 'utf-8');
		if( strpos( $zone, "32Nd") !== false ){
			
			$newzone = str_replace( "32Nd", "32nd", $zone );
			$zone = $newzone;
			
		}
		echo "geotype is  " . $geotype . " : " . $zone . "\r\n";
		generateACSjson($datasource, $year, $geotype, $zone);
		
	}
	
	//prepend and append '[]' on all geozone file
	
	//******* 1 Race-Ethnicity **************
	
	$fileContents = file_get_contents($raceEthnicity_all_file_path);
	file_put_contents($raceEthnicity_all_file_path,'['. preg_replace( '/},$/','}]', $fileContents));
		
	//******* 2. Age and Sex **************
	
	$fileContents = file_get_contents($ageSex_all_file_path);
	file_put_contents($ageSex_all_file_path,'['. preg_replace( '/},$/','}]', $fileContents));
		
	$fileContents = file_get_contents($ageSexMedian_all_file_path);
	file_put_contents($ageSexMedian_all_file_path,'['. preg_replace( '/},$/','}]', $fileContents));
	
	//******* 3 MaritalStatus **************
	
	$fileContents = file_get_contents($maritalStatus_all_file_path);
	file_put_contents($maritalStatus_all_file_path,'['. preg_replace( '/},$/','}]', $fileContents));
	
	//******* 4 HouseholdGroupQuarters **************
	
	$fileContents = file_get_contents($householdGroupQtrs_all_file_path);
	file_put_contents($householdGroupQtrs_all_file_path,'['. preg_replace( '/},$/','}]', $fileContents));
	
	//******* 5 Age, Race-Ethnicity **************
	
	$fileContents = file_get_contents($ethnicityAgeGroup_all_file_path);
	file_put_contents($ethnicityAgeGroup_all_file_path,'['. preg_replace( '/},$/','}]', $fileContents));
	
	$fileContents = file_get_contents($ethnicityAgeGroupMedian_all_file_path);
	file_put_contents($ethnicityAgeGroupMedian_all_file_path,'['. preg_replace( '/},$/','}]', $fileContents));
	
	//******* 6 LanguageAt Home **************
	
	$fileContents = file_get_contents($languageSpoken_all_file_path);
	file_put_contents($languageSpoken_all_file_path,'['. preg_replace( '/},$/','}]', $fileContents));
	
	//******* 7 Educational Attainment **************
	
	$fileContents = file_get_contents($educationalAttainment_all_file_path);
	file_put_contents($educationalAttainment_all_file_path,'['. preg_replace( '/},$/','}]', $fileContents));
	//******* 8 SchoolEnrollment **************

	$fileContents = file_get_contents($schoolEnrollment_all_file_path);
	file_put_contents($schoolEnrollment_all_file_path,'['. preg_replace( '/},$/','}]', $fileContents));
	
	//******* 10 Household TypePresenceUnder 18 **************

	$fileContents = file_get_contents($householdsTypeByUnder18_all_file_path);
	file_put_contents($householdsTypeByUnder18_all_file_path,'['. preg_replace( '/},$/','}]', $fileContents));
	
	//******* 11 HousingUnitsType **************
	
	$fileContents = file_get_contents($housingUnitsType_all_file_path);
	file_put_contents($housingUnitsType_all_file_path,'['. preg_replace( '/},$/','}]', $fileContents));
	
	//******* 12 House Value **************
	
	$fileContents = file_get_contents($housingValue_all_file_path);
	file_put_contents($housingValue_all_file_path,'['. preg_replace( '/},$/','}]', $fileContents));
	
	$fileContents = file_get_contents($housingValueMedian_all_file_path);
	file_put_contents($housingValueMedian_all_file_path,'['. preg_replace( '/},$/','}]', $fileContents));
	
	//******* 13 Yr House Built **************
	
	$fileContents = file_get_contents($yrHouseBuilt_all_file_path);
	file_put_contents($yrHouseBuilt_all_file_path,'['. preg_replace( '/},$/','}]', $fileContents));
	
	//******* 14 House Tenure and OccupRoom **************
	
	$fileContents = file_get_contents($houseTenureOccupied_all_file_path);
	file_put_contents($houseTenureOccupied_all_file_path,'['. preg_replace( '/},$/','}]', $fileContents));
	
	//******* 15 ContractRent **************

	$fileContents = file_get_contents($contractRent_all_file_path);
	file_put_contents($contractRent_all_file_path,'['. preg_replace( '/},$/','}]', $fileContents));
	
	$fileContents = file_get_contents($contractRentMedian_all_file_path);
	file_put_contents($contractRentMedian_all_file_path,'['. preg_replace( '/},$/','}]', $fileContents));
	
	//******* 16 GrossRent Household Income **************
	
	$fileContents = file_get_contents($grossRentHouseholdIncome_all_file_path);
	file_put_contents($grossRentHouseholdIncome_all_file_path,'['. preg_replace( '/},$/','}]', $fileContents));
	
	//******* 17 Vehicle Availability **************
	
	$fileContents = file_get_contents($vehicleAvailability_all_file_path);
	file_put_contents($vehicleAvailability_all_file_path,'['. preg_replace( '/},$/','}]', $fileContents));
	
	//******* 18 Place of work( CountyLevel) **************
	
	$fileContents = file_get_contents($placeofWork_all_file_path);
	file_put_contents($placeofWork_all_file_path,'['. preg_replace( '/},$/','}]', $fileContents));
	
	//******* 19 Transportation to Work **************
	
	$fileContents = file_get_contents($transportationtoWork_all_file_path);
	file_put_contents($transportationtoWork_all_file_path,'['. preg_replace( '/},$/','}]', $fileContents));
	
	//******* 20 Travel Time to Work **************
	
	$fileContents = file_get_contents($travelTimetoWork_all_file_path);
	file_put_contents($travelTimetoWork_all_file_path,'['. preg_replace( '/},$/','}]', $fileContents));
	
	$fileContents = file_get_contents($travelTimetoWorkAverage_all_file_path);
	file_put_contents($travelTimetoWorkAverage_all_file_path,'['. preg_replace( '/},$/','}]', $fileContents));
	
	//******* 21 Employment Status **************

	$fileContents = file_get_contents($employmentStatus_all_file_path);
	file_put_contents($employmentStatus_all_file_path,'['. preg_replace( '/},$/','}]', $fileContents));
	
	//******* 22 Occupation **************
	
	$fileContents = file_get_contents($occupation_all_file_path);
	file_put_contents($occupation_all_file_path,'['. preg_replace( '/},$/','}]', $fileContents));
	
	//******* 23 Industry **************
	
	$fileContents = file_get_contents($industry_all_file_path);
	file_put_contents($industry_all_file_path,'['. preg_replace( '/},$/','}]', $fileContents));
	
	//******* 24 Household Income **************

	$fileContents = file_get_contents($householdIncome_all_file_path);
	file_put_contents($householdIncome_all_file_path,'['. preg_replace( '/},$/','}]', $fileContents));
	// median income
	$fileContents = file_get_contents($householdIncomeMedian_all_file_path);
	file_put_contents($householdIncomeMedian_all_file_path,'['. preg_replace( '/},$/','}]', $fileContents));
	
	//******* 25 Earnings and Income **************
	
	$fileContents = file_get_contents($earningsAndIncome_all_file_path);
	file_put_contents($earningsAndIncome_all_file_path,'['. preg_replace( '/},$/','}]', $fileContents));
	
	//******* 26 Ratio Income to Poverty Level **************
	
	$fileContents = file_get_contents($ratioIncomePovertyLevel_all_file_path);
	file_put_contents($ratioIncomePovertyLevel_all_file_path,'['. preg_replace( '/},$/','}]', $fileContents));
	
	//******* 27 Poverty Status **************
	
	$fileContents = file_get_contents($povertyStatus_all_file_path);
	file_put_contents($povertyStatus_all_file_path,'['. preg_replace( '/},$/','}]', $fileContents));
	
	//******* 28 PovertyStatus- FamType Children **************
	
	$fileContents = file_get_contents($povertyStatusByFamilyType_all_file_path);
	file_put_contents($povertyStatusByFamilyType_all_file_path,'['. preg_replace( '/},$/','}]', $fileContents));
}
//}
?>
<?php
//include("head.php");
?>

