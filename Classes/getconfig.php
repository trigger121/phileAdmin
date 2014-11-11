<?php 
	function setConfig($previousconfig){
		$string = array();
		
		foreach($previousconfig as $opt => $configoptions){
			if(is_array($configoptions)){
				$string[] = '$config["'.$opt.'"] = '.grab($configoptions);
				
			}
			else{
				$string[] = '$config["'.$opt.'"] = "'.$configoptions.'"';
			}	
		}
		$newconfig = implode($string,';');
		return $newconfig;
	}
	function grab($array){
		$newarr = array();
		foreach($array as $key => $value){
			foreach($value as $val => $v){
				//var_dump($val);
				$newarr[] =  'array('.$val.'=>'.$v.'),';
			}
		}
		return implode($newarr,',');
	}
?>