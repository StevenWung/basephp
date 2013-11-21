<?php

class test extends ControllerBase
{
    public function index(){

        $name = $_POST['text'];
        $temp = array(
            'status'=>'login',
            'html'=> $name,
        );

        echo json_encode($temp);
    }
    public function testdb(){
        //$db = new UserInfo();

        //print_r($db->fetchAll());
        //echo $db->num();
        $this->render('test',array(
            
        ));
    }
    public function testt(){
        //$this->classDir = false;
        $this->render('test',array(

        ));
    }
}