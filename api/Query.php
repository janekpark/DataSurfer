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
		$config = new Config("configuration.ini");
		
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
	
	
	public function getResultAsJson($sql, $datasource_id, $geotype, $geozone) {
		$json = array ();
		
		$db = new PDO("pgsql:dbname={$this->database};host={$this->db_server};user={$this->uid};password={$this->pwd}");
		$stmt = $db->prepare($sql);
			
		$stmt->bindParam(':datasource_id', $datasource_id);
		$stmt->bindParam(':geotype', $geotype);
		$stmt->bindParam(':geozone', $geozone);
		
		$stmt->execute();
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		$json = json_encode($results);
		
		$results = null;
		$stmt = null;
		$db = null;

		return $json;
	}
	
	public function getResultAsSheet($sql, $params, &$sheet) 
	{
		$conn = sqlsrv_connect ( $this->db_server, $this->connectionInfo );
		
	 	$stmt = sqlsrv_query ( $conn, $sql, $params );
 	
	 	$fieldMeta = sqlsrv_field_metadata($stmt);
 	
	 	for($i=0;$i < count($fieldMeta); $i++)
 		{
 			$sheet->getCellByColumnAndRow($i,1)->setValue($fieldMeta[$i]["Name"]);
 		}
 	
 		$counter = 2;
 		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
 		{
 			for($i=0;$i < count($row); $i++)
 				$sheet->getCellByColumnAndRow($i,$counter)->setValue($row[$i]);
 			$counter++;
 		}
 	
 		sqlsrv_free_stmt($stmt);
	}
}
?>