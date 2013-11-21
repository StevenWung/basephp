<?php
class Error {
	static function err($msg, $type = 0){
		die("<h1>$msg</h1>");
		//echo file_get_contents('../../error/404.html');
	}
	static function errfile($file){
		require_once("error/".$file.'.html');
	}
	static function show_error(){
		global $cfg;
		if ( $cfg["sys"]["gdb_trace"] != true ){
			return;
		}
		$test = "";  
		$trace = debug_backtrace();  
		$i = 0;  
		while(isset($trace[$i]))  
		{  
		      
		    $files =   explode("\\",$trace[$i]['file']);  
		    $test .=  "<hr>FILE: ".$files[count($files) - 2]."/".$files[count($files) - 1];  
		    $test .=  "<br>LINE: ".$trace[$i]['line'];  
		    $test .=  "<br>FUNC: ".$trace[$i]['function'];  
		    $i++;  
		} 		
		echo $test;
	}
	static function sys_err($type){
		self::show_error();
		switch($type){
			case '404':{
				header("HTTP/1.1 404");
				//self::err('Page Not Found!');	
				self::errfile('404');
				break;			
			}
			case '403':{
				header("HTTP/1.1 403");
				self::err('Fobidden!');	
				break;			
			}
			case '400':{
						
			}
            case '500':{
                header("HTTP/1.1 500");
                self::err('system error!');
                break;
            }
			case 'request':{
				header("HTTP/1.1 400");
				self::err('Bad Request!');
				break;		
			}
			case 'dberror':{
				self::err('db connect error');
				break;		
			}
			case 'module':{
				self::err('module error');
				break;		
			}
			case 'login':{
				echo 'login';
				break;		
			}
			default:{
				self::err('Error');
			}
			
		}
		die();
	}
}
