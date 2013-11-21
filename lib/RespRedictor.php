<?php
/**
 * Created by JetBrains PhpStorm.
 * User: StevenWung
 * Date: 13-7-8
 * Time: 下午9:58
 * To change this template use File | Settings | File Templates.
 */

class RespRedictor extends AjaxResponse{

    function __construct($obj){
        parent::__construct($obj);
        $this->param['status']  = 'ok';
        $this->param['cmd']     = 'redirect';
    }

}