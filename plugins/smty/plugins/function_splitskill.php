<?php
/*
 * Created on 2012-3-10
 * 
 * youxue.me@copyright ==> Steven Wungthaubo@gmail.com
 */
  function smarty_function_splitskills($params,&$smarty){
  	$skills = $params['sks'];
  	$skills = explode(",", $skills);
  	foreach($skills as $k=>$v){
  		$kvs = explode(':', $v);
  		if($v=="")break;
  		foreach($v as $id=>$name){
  			echo "$name ";
  		} 
  	}
  }