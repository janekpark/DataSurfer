<?php
require 'Config.php';

class Query {
	
	private static $instance;
	
	private $db_server = null;
	private $database = null;
	private $uid = null;
	private $pwd = null;
	private $connectionInfo = array();
	
	private function __construct()
	{
		$config = new Config("api\configuration.ini");
		
		$this->db_server = $config->get('db_server');
		$this->uid = $config->get('user');
		$this->pwd = $config->get('password');
		$this->database = $config->get('database');
	}
	
	public static function getInstance()
	{
		if (is_null(self::$instance))
		{
			self::$instance = new self();
		}
		
		return self::$instance;
	}
	
	public function getZonesAsJson($sql, $series_id, $geotype)
	{
		$json = array ();
	
		$db = pg_connect("dbname={$this->database} host={$this->db_server} user={$this->uid} password={$this->pwd}");
		
		$result = pg_query_params($db, $sql, array($series_id, $geotype));
		$result_array = pg_fetch_all($result);
		
        $json = json_encode($result_array);
	 
        pg_free_result($result);
	    pg_close($db);
	 
        return $json;
	}
	
	public function getResultAsJson($sql, $datasource_id, $geotype, $geozone) 
	{
		$json = array ();
		
        $php_to_pg = array(
        	"int2" => "int",
        	"int4" => "int",
        	"int8" => "int",
        	"float4" => "float",
        	"float8" => "float",
        	"numeric" => "float"
        );
		
		$db = pg_connect("dbname={$this->database} host={$this->db_server} user={$this->uid} password={$this->pwd}");
	   
	    $result = pg_query_params($db, $sql, array($datasource_id, $geotype, $geozone));
	    $result_array = pg_fetch_all($result);
	    $mod_array = array();
	  
		
	    foreach($result_array as $row)
	    {
	    	for($j=0;$j<count($row);$j++)
	    	{
	    		if (array_key_exists(pg_field_type($result, $j), $php_to_pg))
	    		{
	    			$field_type = pg_field_type($result, $j);
	    			$field_name = pg_field_name($result, $j);
	    			eval("\$row[\$field_name] = (".$php_to_pg[$field_type].") \$row[\$field_name];");
	    		}
	    	}
	    	
	    	array_push($mod_array, $row);
	    }
	    
	    $json = json_encode($mod_array);
	    
	    pg_free_result($result);
	    pg_close($db);
	    
	    return $json;
	}
	/*
	public function getResultAsJsonACS($sql, $datasource_id, $geotype, $geozone) 
	{
		$json = array ();
		
		$php_to_pg = array(
			"int2" => "int",
			"int4" => "int",
			"int8" => "int",
			"float4" => "float",
			"float8" => "float",
			"numeric" => "float"
			);
		
		$db = pg_connect("dbname={$this->database} host={$this->db_server} user={$this->uid} password={$this->pwd}");
		
		$result = pg_query_params($db, $sql, array($datasource_id, $geotype, $geozone));
		
		$result_array = pg_fetch_all($result);
		$mod_array = array();
		
		foreach($result_array as $row)
		{
			for($j=0;$j<count($row);$j++)
			{
				if (array_key_exists(pg_field_type($result, $j), $php_to_pg))
				{
					$field_type = pg_field_type($result, $j);
					$field_name = pg_field_name($result, $j);
					eval("\$row[\$field_name] = (".$php_to_pg[$field_type].") \$row[\$field_name];");
				}
			}
			
			array_push($mod_array, $row);
		}
		
		$json = json_encode($mod_array);
		
		pg_free_result($result);
		pg_close($db);
		
		return $json;
	}
	*/
	public function getResultAsArray($sql, $datasource_id, $geotype, $zonelist) 
	{
		$db = new PDO("pgsql:dbname={$this->database};host={$this->db_server};user={$this->uid};password={$this->pwd}");
		$stmt = $db->prepare($sql);
    
		if( isset( $datasource_id )){	
		  $stmt->bindParam(':datasource_id', $datasource_id);
    }
    
	//	$stmt->bindParam(':geotype', $geotype);
	//	$stmt->bindParam(':zonelist', $zonelist);
    
    
		$stmt->bindParam(':geotype', $geotype, PDO::PARAM_STR);
		$stmt->bindParam(':zonelist', $zonelist, PDO::PARAM_STR);
	 // echo $sql.$datasource_id.$geotype.$zonelist;
   // exit;
		$records = array();
		if ($stmt->execute()) {
			while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
				$records[] = $row;
			}
		}
		
		$results = null;
		$stmt = null;
		$db = null;
		
		return $records;
	}
  
}
?>