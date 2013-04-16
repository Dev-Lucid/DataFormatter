<?php
# Copyright 2013 Mike Thorn (github: WasabiVengeance). All rights reserved.
# Use of this source code is governed by a BSD-style
# license that can be found in the LICENSE file.

global $__dfm;
$__dfm=array(
	'hooks'=>array(),
	'log_hook'=>null,
);

class dfm
{
	function init($config = array())
	{
		global $__dfm;
		foreach($config as $key=>$value)
		{
			if(is_array($value))
			{
				foreach($value as $subkey=>$subvalue)
				{
					$__dfm[$key][$subkey] = $subvalue;
				}
			}
			else
				$__dfm[$key] = $value;
		}	
	}
	
	function __construct()
	{
		$this->actions = array();
	}
	
	public static function construct()
	{
		$obj = new dfm();
		return $obj;
	}

	function log($string_to_log)
	{
		global $__dfm;
		if(!is_null($__dfm['log_hook']))
		{
			$__dfm['log_hook']('DFM: '.$string_to_log);
		}
	}
	
	public function __call($action,$params)
	{
		array_unshift($params,$action);
		dfm::log('added format function: '.$action);
		$this->actions[] = $params;
		return $this;
	}
	
	public function apply($in=null)
	{
		global $__dfm;
		$out = $in;
		dfm::log('Formatter started on '.$in);
		foreach($this->actions as $action)
		{
			dfm::log('applying '.$action[0].' to '.$out);
			switch($action[0])
			{
				case 'ifnull':
					$out = (is_null($out))?$action[1]:$out;
					break;
				case 'ifblank':
					$out = ($out.'' == '')?$action[1]:$out;
					break;
				case 'int':
					$out = intval($out);
					break;
				case 'float':
					$out = floatval($out);
					break;
				case 'checked2bool':
					$out = ($in == 'on');
					break;
				case 'add':
					$out += $action[1];
					break;
				case 'subtract':
					$out -= $action[1];
					break;
				case 'epoch':
					$out = date($action[1],$out);
					break;
				default:
					if(isset($__dfm[$action[1]]))
					{
						$out = $__dfm[$action[1]]($out);
					}
					else
					{
						throw new Exception('DFM: Could not find format function '.$action[1]);
					}
					break;
			}
		}
		return $out;
	}
}

?>