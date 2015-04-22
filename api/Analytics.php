<?php
require 'Slim/Middleware.php';

class AnalyticsMiddleware extends \Slim\Middleware
{
	private $db_server = null;
	private $connectionInfo = array();
	
	public function __construct()
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
	
	public function call()
	{
		$app = $this->app;
		$request = $app->request;
		
		$resourceUri = $request->getResourceUri();
		$ip = $request->getIp();
		$referrer = $request->getReferrer();
		$userAgent = $request->getUserAgent();

		//echo $resourceUri;
		//echo $ip;
		//echo $referrer;
		//echo $userAgent;
		
		$sql = 'INSERT INTO app.usage (accessed, ip, resource_uri, referrer, user_agent) VALUES (CURRENT_TIMESTAMP,?,?,?,?)';

		$conn = sqlsrv_connect ( $this->db_server, $this->connectionInfo );
		if($conn === false)
		{
			$this->next->call();
		}
		
		$stmt = sqlsrv_prepare($conn, $sql, array(&$ip, &$resourceUri, &$referrer, &$userAgent));
		if(!$stmt)
		{
			$this->next->call();
		}
		
		sqlsrv_execute($stmt);
		
		$this->next->call();
	}
}

?>