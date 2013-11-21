<?php
	class  ControllerBase extends Template
	{
        var $controller = '';
        var $get  = null;
        var $post = null;
        var $method = 'GET';
        var $__constructed = false;
        function __construct(){
            $this->controller = get_class($this);
            //session_start();
            if( $this->__constructed == false ){
                parent::__construct();
                global $cfg;
                //default assignment
                $this->assign('title', $cfg["site"]["site_name"]);
                $this->assign('keywords', $cfg["site"]["keywords"]);
                $this->assign('description', $cfg["site"]["description"]);
                $this->__constructed = true ;
            }
        }
        function __init($p){
            self::__construct();
            $this->get = $p;
            $this->post = $_POST;
            $this->method = $_SERVER['REQUEST_METHOD'];
            $this->session = &$_SESSION;
        }
        function desession($p){
            $this->session[$p] = null;
            unset( $this->session[$p] );
        }
        function get_seg($idx, $def=null){
            if(isset($this->get['seg_'.$idx])){
                return $this->get['seg_'.$idx];
            }else{
                if($def==null){
                    throw new BException('No Param Found');
                }else{
                    return $def;
                }
            }
        }

	}