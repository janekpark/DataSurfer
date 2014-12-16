<?php
class Config 
{
	private $_config = NULL;
	private $_filepath = NULL;
	
	public function __construct($filepath)
	{
		$this->_filepath = $filepath;
		$this->load();
	}
	
	private function load()
	{
		if ($this->_config === NULL)
		{
			if(!file_exists($this->_filepath))
			{
				throw new Exception('Configuration file not found.');
			} else 
			{
				$this->_config = parse_ini_file($this->_filepath);
			}
		}
	}
	
	public function get($key)
	{
		if ($this->_config === NULL)
		{
			throw new Exception('Configuration file is not loaded');
		}
		
		if (isset($this->_config[$key]))
		{
			return $this->_config[$key];
		} else 
		{
			throw new Exception('Variable ' . $key . ' does not exist in configuration file');
		}
	}
}
?>