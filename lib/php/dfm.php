<?php
# Copyright 2013 Mike Thorn (github: WasabiVengeance). All rights reserved.
# Use of this source code is governed by a BSD-style
# license that can be found in the LICENSE file.

global $__dfm;
$__dfm=array(
	'log_hook'=>null,
);

class dfm
{
	function __construct()
	{
		$this->actions = array();
	}
	
	public static function construct()
	{
		$obj = new dfm();
		return $obj;
	}
	
	public function __call($action,$params)
	{
		array_unshift($params,$action);
		$this->actions[] = $params;
		return $this;
	}
	
	public function apply($in=null)
	{
		global $__dfm;
		$out = $in;
		#print_r($this->actions);
		foreach($this->actions as $action)
		{
			#echo("testing: ".$action[0]."\n");
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
			}
		}
		return $out;
	}
}

?>