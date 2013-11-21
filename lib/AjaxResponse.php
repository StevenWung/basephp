<?php
/**
 * Created by JetBrains PhpStorm.
 * User: StevenWung
 * Date: 13-7-9
 * Time: 上午12:14
 * To change this template use File | Settings | File Templates.
 */

class AjaxResponse {
    var $param;
    function __construct($obj){
        $this->param = $obj;
        $this->param['cmd'] = 'dummy';
        $this->param['status'] = 'ok';
    }
    function render(){
        echo json_encode($this->param);
    }
}