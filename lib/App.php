<?
    class App
    {
    	
        function __construct(){
            global $cfg;

            if( $cfg['sys']['gdb_level'] == DGB_TEMPLATE ){
                set_exception_handler(array($this, 'exception'));
                set_error_handler(array($this, 'error'));
            }
            spl_autoload_register(array($this, _autoload));
            //gbd
            $cfg['sys']['debug'] ? error_reporting(E_ALL) : error_reporting(0);
            //set_exception_handler(array($this, 'exception'));
        }
        function error(){

        }
        function exception($e){
            $ex = new BException('');
            echo $ex->parseException($e);

            return;
            $test = "";
            $trace = debug_backtrace();
            $i = 0;
            while(isset($trace[$i]))
            {

                $files =   explode("\\",$trace[$i]['file']);
                $test .=  "<br>FILE: ".$files[count($files) - 2]."/".$files[count($files) - 1];
                $test .=  "<br>LINE: ".$trace[$i]['line'];
                $test .=  "<br>FUNC: ".$trace[$i]['function'];
                $i++;
            }
            echo $test;
        }
        function _autoload($class){
            if( file_exists("lib/${class}.php")){
                include_once "lib/${class}.php";
                return ;
            }
            if( file_exists("module/${class}.php")){
                include_once "module/${class}.php";
            }
        }
        function run(){
            new Controller();
        }
        public static function needCache(){
            global $cfg;
            if( $cfg["sys"]["allow_cache"] != true ){
                return false;
            }

            if( file_exists(  $cfg["sys"]["cache_dir"].'/~r.php') ){
                include_once( $cfg["sys"]["cache_dir"].'/~r.php' );
                return false;
            }else{
                return true;
            }
        }
    }