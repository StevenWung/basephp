<?
    class Controller{
        
        function __construct(){
        	
            global $cfg;
            $route = new Router();
            p("--------------------------------");
            p("route  = $route->controller");
            p("action = $route->action");
            p("--------------------------------");
            $ctrl_file = $cfg['sys']['ctrl_dir'].'/'.$route->controller.'_c.php';
            _p();


            if(!file_exists($ctrl_file) && isset($cfg["sys"]["one_entry"])){
                $entries = explode('/', $cfg["sys"]["one_entry"]);
                if(count($entries)<2){
                    Error::err("Entry Error");
                }
                $class_name = $entries[0];
                $class_method = $entries[1];
                include_once $cfg['sys']['ctrl_dir'].'/'.$class_name.'_c.php';
                $class = new $class_name();

                $class->$class_method($route->controller);
                die();
            	#Error::sys_err('404');
            	//error("this is a error");;
            }

          	//file exists
            include_once $ctrl_file;
            
            if(!class_exists($route->controller)){
            	error::sys_err('404');
            	//error("control file error");	
            }
            $class_name = $route->controller;

            $methods = get_class_methods($class_name);
 
            $method_name = $route->action ;

            //var_dump($method_name);die();
            //var_dump($_GET);die();
            if(in_array($method_name, $methods)){
            	if($class_name == $method_name){
                    $ctrl = new $route->controller($route->param);
                    if(is_callable(array($ctrl, '__init'))){
                        $ctrl->__init($route->param);
                    }
                    $ctrl->{$method_name}($route->param);
            	}
            	else{
                    $ctrl = new $route->controller();//
                    if(is_callable(array($ctrl, '__init'))){
                        $ctrl->__init($route->param);
                    }
                    $ctrl->{$method_name}($route->param);
                }
            }else{
            	#error::sys_err('404');
				if ( count($route->param) == 0){
					$route->param[] = $method_name;
				}else{
			    	array_unshift($route->param, $method_name);
				}
                $ctrl = new $route->controller($route->param);
                if(is_callable(array($ctrl, '__init'))){
                    $ctrl->__init($route->param);
                }

                $index = "index";
                
                $cfg_route = $cfg["cfg"]["direct_route"];
                if( in_array($index, $methods) ){
                	if(in_array($class_name, $cfg_route)){
                		//in route config
                		$ctrl->{$index}($route->param);
                	}
                	//$ctrl->{$index}($route->param);	
                }else{
                	//error::sys_err('404');
                }
              	//var_dump($route->param);die();
            }
                        
            #response::set_m_server();
        }
    }