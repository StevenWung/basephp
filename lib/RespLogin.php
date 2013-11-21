<?php
/**
 * Created by JetBrains PhpStorm.
 * User: StevenWung
 * Date: 13-7-9
 * Time: 上午12:22
 * To change this template use File | Settings | File Templates.
 */

class RespLogin extends AjaxResponse{
    function __construct($obj=null){
        parent::__construct($obj);
        $this->param['status']  = 'failure';
        $this->param['cmd']     = 'login';
    }
}