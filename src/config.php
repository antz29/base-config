<?php
namespace Base\Config;

class Config extends Node {
	
	private $_env;

	public function __construct($file,$env = 'shared') 
	{
		$this->_env = $env;

		$config = include($file);
		$this->_data = $this->getConfigForEnvironment($config);
	}

	public function setModule($module) 
	{
		if (!isset($this->_data['modules'][$module]['config'])) return;
		$config = $this->_data['modules'][$module]['config'];
		$config = $this->getConfigForEnvironment($config);
		$this->_data = $this->mergeArray($this->_data,$config);
	}

	private function getConfigForEnvironment($config)
	{
		$env = $this->_env;
		$data = isset($config['shared']) ? $config['shared'] : array();

		if (isset($config[$env])) $data = $this->mergeArray($data,$config[$env]);

		return $data;
	}

	private function mergeArray($arr1, $arr2)
	{
		foreach($arr2 as $key => $value)
		{
			if(array_key_exists($key, $arr1) && is_array($value)) {
				$arr1[$key] = $this->mergeArray($arr1[$key], $arr2[$key]);
			}
			else {
				$arr1[$key] = $value;
			}
		}

		return $arr1;
	}	

}
