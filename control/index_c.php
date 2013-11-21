<?php

class index extends ControllerBase
{
    var $var = 'true';
    function __construct(){
        parent::__construct();
    }
    public function index(){
        $x = 0 / 0;
        $this->render('index', array(
            'hello'=>'bb',
        ));
    }
}