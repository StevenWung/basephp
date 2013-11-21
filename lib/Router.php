<?
    class Router
    {
    	var $uri ;
    	var $script;
        var $controller = NULL;
        var $action		= NULL;
        var $param 		= array();
        var $route      = NULL;
    	function __construct(){
            $this->uri    = $_SERVER["REQUEST_URI"];
            $this->route  = $_SERVER["QUERY_STRING"];
            $this->script = $_SERVER["SCRIPT_NAME"];
            $this->parse_route();
        }
        function parse_route(){
        	$url = preg_replace('/\/$/i', '', $this->route);
        	$tmp = explode('/', $url);

        	unset($_GET);
        	$rqt = $this->uri;
        	if($rqt != ($temp=preg_replace('/[^\\?]*\\?/i', '', $rqt))){
        		parse_str($temp, $_GET);
        	}
        	$count = count($tmp);
        	if( $count == 0 ){
        		$this->controller = "index";
        		$this->action 	  = "index";
        		return ;
        	}
        	
        	if(!is_control($tmp[0]))
        		$this->controller = "index";
        	else 
        		$this->controller = $tmp[0]==""?"index":$tmp[0];

        	$this->controller = preg_replace('/\\?.*/i','',$this->controller);
        	
        	if( $count == 1 ){
        		$this->action = "index";
        		return ;
        	}
        	$actionTemp = preg_replace('/\\?.*/i','',$tmp[1]);
        	$this->action = $tmp[1]==""?"index":$actionTemp;

        	$seg_idx = 0;
        	for($i=2;$i<$count;$i++){
        		if(strstr($tmp[$i], "&")){
        			$ps = explode('&', $tmp[$i]);
        			for( $j=0; $j < count($ps); $j++ ){
        				$kv = explode('=', $ps[$j]);
        				$this->param[$kv[0]] = trim($kv[1]);
        			}
        			continue;
        		}elseif(strstr($tmp[$i], "=")){
        			$kv = explode('=', $tmp[$i]);
        			$this->param[$kv[0]] = trim($kv[1]);
        			continue;
        		}
        		$this->param['seg_'.$seg_idx] = trim($tmp[$i]);
                $seg_idx++;
        	}

            #gdb::debug($this->param);

            if( $this->param == null && empty($_GET)){

            }elseif($this->param==null){
                $this->param = $_GET;
            }elseif( empty($_GET) ){

            }else{
                $this->param = array_merge($_GET, $this->param);
            }



        }
        
    }