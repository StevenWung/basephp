<?php
	class Template extends Smarty
	{
        var $session  = null;
        var $layout   = true;
        var $classDir = true;
		function __construct(){
			global $cfg;
                        $this->template_dir = ($cfg['sys']['view_dir']);
			#$this->template_dir = APP_ROOT . $cfg['sys']['view_dir'];
			$this->cache_dir    = $cfg['sys']['view_dir_tmp'];
			$this->compile_dir  = $cfg['sys']['view_dir_tmp'];
            		$this->setCompileDir($cfg['sys']['view_dir_tmp']);


			$this->registerPlugin('function','paging','smarty_function_paging');

			$this->registerPlugin('function','ft','smarty_function_formattime');
			$this->registerPlugin('function','fd','smarty_function_formatdate');
			$this->registerPlugin('function','fdd','smarty_function_formatddate');
			$this->registerPlugin('function','cut','smarty_function_cut');
			$this->registerPlugin('function','tmdiff','smarty_function_difftime');
			$this->registerPlugin('function','esctags','smarty_function_esctags');
			$this->registerPlugin('function','ajax','smarty_function_ajax');
            $this->registerPlugin('function','js', 'smarty_function_js');
            $this->registerPlugin('function','css', 'smarty_function_css');
            $this->registerPlugin('function','progressbar', 'smarty_function_progress');
            $this->registerPlugin('function','slider', 'smarty_function_slider');
            $this->registerPlugin('function','route', 'smarty_function_route');
            $this->registerPlugin('function','r', 'smarty_function_route');

            //session
		}	
		function set($k, $v){
			parent::assign($k, $v);
		}
		function show($var){
			parent::display($var);
		}

        function render($template, $object){
            global $cfg;
            if( is_array($object) ){
                $class = get_called_classname();

                $this->set('ctrl', $this->controller);
                foreach( $object as $k => $v ){
                    $this->set($k, $v);
                }
                if( $this->classDir == true ){
                    $this->layout && $this->show("hdr.html");
                    $this->show("${class}/${template}.html");
                    $this->layout && $this->show("ft.html");
                }
                else{
                    $this->layout && $this->show("hdr.html");
                    $this->show("${template}.html");
                    $this->layout && $this->show("ft.html");
                }
            }else{
                throw BException('error');
            }
        }
        function renderPart($template, $object){
            global $cfg;
            if( is_array($object) ){
                $this->set('ctrl', $this->controller);

                $class = get_called_classname();

                foreach( $object as $k => $v ){
                    $this->assign($k, $v);
                }
                if( $this->classDir == true ){
                    $this->show("${class}/${template}.html");
                }
                else{
                    $this->show("${template}.html");
                }
            }else{
                throw BException('error');
            }
        }
	}


	