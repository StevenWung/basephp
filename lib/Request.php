<?php
	class Request {
		function request() {
			;
		}
		static function refer() {
			if(isset($_SERVER['HTTP_REFERER'])){
					return $_SERVER['HTTP_REFERER'];
			}
			else{
					return null;
			}
		}
		static function is_ajax(){
		 
			if(isset( $_SERVER['HTTP_X_REQUESTED_WITH']) && strstr( $_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'])){
				return true;
			}
			return false;
			
		}
		static function get_post($v=null, $escape=false){
			if($v){
				if( isset( $_POST[$v] ) ){
					if($escape==false)
						return inject_check($_POST[$v]);
					else
						return $_POST[$v];
				}else{
					return null;
				}
			}else{
				return $_POST;    
			}
			
		}
		static function get_get($v=null, $escape=true){
			
			if($v){
				if( isset( $_GET[$v] ) ){
					if($escape==false)
						return inject_check($_GET[$v]);
					else
						return $_GET[$v];
				}else{
					return null;
				}
			}else{
				return $_GET;    
			}
			
		}
		public static function get_cookie($name = NULL){
			if($name){
				if( isset( $_COOKIE[$name] ) ){
					return $_COOKIE[$name] ;
				}else{
					return null;
				}
			}else{
				return $_COOKIE;    
			}
		}
		public static function get_session($name = NULL){
			if($name){
				if( isset( $_SESSION[$name] ) ){
					return $_SESSION[$name] ;
				}else{
					return null;
				}
			}else{
				return $_SESSION;    
			}
		}
		function get_param($idx, $safe=true){
			
			$params = $_GET;
			$temp = '';
			if( isset( $params[$idx] ) ){
				$temp = $params[$idx];
			}else{
				return null;
			}
			if( $safe )
				return inject_check(urldecode($temp));
			else
				return urldecode($temp);
		}
	}