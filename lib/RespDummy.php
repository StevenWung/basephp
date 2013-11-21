<?php
/**
 * Created by JetBrains PhpStorm.
 * User: StevenWung
 * Date: 13-7-9
 * Time: 上午12:11
 * To change this template use File | Settings | File Templates.
 */
class RespDummy extends AjaxResponse{
    function __construct($obj){
        parent::__construct($obj);
        $this->param['cmd']     = 'dummy';
    }
}