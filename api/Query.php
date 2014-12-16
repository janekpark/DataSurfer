<?php
require 'Config.php';

class Query {
	
	private static $instance;
	
	private $db_server = null;
	private $connectionInfo = array();
	
	private function __construct()
	{
		$config = new Config("configuration.ini");
		$this->db_server = $config->get('db_server');
		
		$uid = $config->get('user');
		$pwd = $config->get('password');
		$database = $config->get('database');
		
		$this->connectionInfo['UID'] = $uid;
		$this->connectionInfo['PWD'] = $pwd;
		$this->connectionInfo['Database'] = $database;
	}
	
	public static function getInstance()
	{
		if (is_null(self::$instance))
		{
			self::$instance = new self();
		}
		
		return self::$instance;
	}
	
	
	public function getResultAsJson($sql, $params) {
		$json = array ();
		
		try {
			$conn = sqlsrv_connect ( $this->db_server, $this->connectionInfo );
			
			if ($params != null) {
				$stmt = sqlsrv_query ( $conn, $sql, $params );
			} else {
				$stmt = sqlsrv_query ( $conn, $sql );
			}
			
			do {
				while ( $row = sqlsrv_fetch_array ( $stmt, SQLSRV_FETCH_ASSOC ) ) {
					$json [] = $row;
				}
			} while ( sqlsrv_next_result ( $stmt ) );
			
			sqlsrv_free_stmt ( $stmt );
			sqlsrv_close ( $conn );
		} catch ( Exception $e ) {
			echo $e->message;
		}
		
		return json_encode ( $json );
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