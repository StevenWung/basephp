<?
    //    
    $cfg = array();

    $root_dir = dirname($_SERVER['PHP_SELF'])=='\\'?'/':dirname($_SERVER['PHP_SELF']);
    $root_dir = $root_dir[strlen($root_dir) -1] == '/'? $root_dir:$root_dir.'/';
	//debug
    define('DGB_SCRIPT' 	, 1);
    define('DGB_HTML'   	, 2);
    define('DGB_TEMPLATE' 	, 3);
    define('APP_ROOT', $root_dir);
    define('APP_DIR', dirname(__FILE__));
    #echo APP_ROOT;
    //---db---
    $cfg["db"]["prefix"]        	= "";
    $cfg["db"]["charset"]       	= "utf8";
    $cfg["db"]["resultype"]     	= MYSQL_ASSOC;

	if(1){
		$cfg["db"]["dbname"]        	= "hua";
		$cfg["db"]["username"]      	= "root";
		$cfg["db"]["password"]      	= "123456";
	}else{
		$cfg["db"]["dbname"]        	= "db";
		$cfg["db"]["username"]      	= "user";
		$cfg["db"]["password"]      	= "123456";
	}
	$cfg["db"]["port"]           	= "1";
    //---smarty---
    $cfg["smty"]["smart_dir"]   	= "plugins/smty/Smarty.class.php";
	
 	 
    //---system---
    $cfg["sys"]["one_entry"]    	= "index/link";
    $cfg["sys"]["timeszone"]    	= "Asia/Shanghai";
    $cfg["sys"]["gzip"]         	= false;
    $cfg["sys"]["debug"]        	= true;
    $cfg["sys"]["gdb_level"]		= DGB_TEMPLATE;	// 1 print 2
    $cfg["sys"]["gdb_trace"]		= false;
    $cfg["sys"]["allow_cache"]    	= true;
    $cfg["sys"]["cache_dir"]    	= "cache/";
    $cfg["sys"]["ctrl_dir"]     	= "control/";
    $cfg["sys"]["view_dir"]     	= "view/";
    $cfg["sys"]["view_dir_tmp"]     = "cache/c/";
    $cfg["sys"]["view_dir_cache"]	= "cache/c/";

    $cfg["sys"]["asset"]	        = "asset";
    
    $cfg['sys']['avatar']			= "avatars/";
    $cfg['sys']['imgurl']			= "imgurl/";

    //---site---
    $cfg["site"]["site_name"]	    = "SiteName";
    $cfg["site"]["keywords"]	    = "SiteName";
    $cfg["site"]["description"]	    = "";

    $cfg["cfg"]["direct_route"] 	= array('u');
    
    define("SMARTY_DIR", "plugins/smty/");
    
    @date_default_timezone_set($cfg['sys']['timezone']);
    @ini_set("magic_quotes_runtime", 0);
    
    include_once $cfg["smty"]["smart_dir"]; 
    
?>