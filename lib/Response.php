<?php
    class Response
    {
    	public static $title;
        public  static function  set_cookie($name, $value){
        	setcookie($name,$value);
        }
        public  static function  set_session($name, $value){
        	$_SESSION[$name] = $value;
        }
        
        public static function get_session($name){
        	if(isset($_SESSION[$name]))
        		return $_SESSION[$name] ;
        	else
        		return null;
        }
        public static function set_m_server() {
        	header('Server:ping.com\r\n');
        }
        public static function set_title($ttl){
        	self::$title = $ttl;
        	
        	
        }
	public static function destroy_session(){
		unset($_SESSION);
		$_SESSION = null;
		session_destroy();
	}
    }