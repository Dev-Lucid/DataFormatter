<?php
# Copyright 2013 Mike Thorn (github: WasabiVengeance). All rights reserved.
# Use of this source code is governed by a BSD-style
# license that can be found in the LICENSE file.

global $__dfm;
$__dfm=array(
	'hooks'=>array(),
);

class dfm
{
	function call_hook($hook,$p0=null,$p1=null,$p2=null,$p3=null,$p4=null,$p5=null,$p6=null)
	{
		global $__dfm;
		if(isset($__dfm['hooks'][$hook]))
			$__dfm['hooks'][$hook]($p0,$p1,$p2,$p3,$p4,$p5,$p6);
	}
	
	function log($to_write)
	{
		global $__dfm;		
		if(isset($__dfm['hooks']['log']))
		{
			$to_write=(is_object($to_write) || is_array($to_write))?print_r($to_write,true):$to_write;
			$__dfm['hooks']['log']('DFM: '.$to_write);
		}
	}
	
	function init($config = array())
	{
		global $__dfm;
		foreach($config as $key=>$value)
		{
			if(is_array($value))
			{
				foreach($value as $subkey=>$subvalue)
				{
					if(is_numeric($subkey))
						$__dfm[$key][] = $subvalue;
					else
						$__dfm[$key][$subkey] = $subvalue;
				}

			}
			else
				$__dfm[$key] = $value;
		}	
	}
		
	public static function deinit()
	{
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
	
	public static function __callStatic($action,$params)
	{
		$new = dfm::construct()->$action($params[0],$params[1],$params[2],$params[3]);
		return $new;
	}

	public function __call($action,$params)
	{
		array_unshift($params,$action);
		dfm::log('added format function: '.$action);
		$this->actions[] = $params;
		return $this;
	}
	
	public function apply($in)
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
				case 'build_epoch':
					$months = array(
						'jan'=>1,
						'january'=>1,
						'feb'=>2,
						'february'=>2,
						'mar'=>3,
						'march'=>3,
						'apr'=>4,
						'april'=>4,
						'may'=>5,
						'may'=>5,
						'jun'=>6,
						'june'=>6,
						'jul'=>7,
						'july'=>7,
						'aug'=>8,
						'august'=>8,
						'sep'=>9,
						'september'=>9,
						'oct'=>10,
						'october'=>10,
						'nov'=>11,
						'november'=>11,
						'dec'=>12,
						'december'=>12,
					);
					if(array_key_exists($months[strtolower($action[1])]))
					{
						$action[1] = $months[strtolower($action[1])];
					}
					$action[1] = intval($action[1]);
					$action[2] = intval($action[2]);
					$action[3] = intval($action[3]+1);
					
					$out = gmmktime(0,0,0,$action[1],$action[2],$action[3]);
					
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