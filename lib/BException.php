<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wangshaobo
 * Date: 13-6-4
 * Time: 下午2:09
 * To change this template use File | Settings | File Templates.
 */

class BException extends Exception{
    var $msg;
    function __construct($msg){
        $this->msg = $msg;
    }
    function __toString(){

        $test = "<div style='margin:0 auto;width:800px;border: #2f381d 1px solid;padding: 10px;margin-top: 30px;'>";
        $test = $test."<div style='background: #f7ffea;padding:10px 4px;;font-size:18px;margin:3px;border:dotted 1px #2f381d'>$this->msg</div>";
        $trace = debug_backtrace();
        $i = 0;
        while(isset($trace[$i])){

            $files =   explode("\\",$trace[$i]['file']);
            $temp  = $this->geneItem($trace[$i], $files[count($files) - 1], $trace[$i]['line']);
            $test .= $temp;
            $i++;
        }
        $test .= "</div>";

        return $test;//.$this->msg;
    }
    function geneItem($function, $file, $line){
        $test  = '';
        $test .=  "<div style='padding:4px;border:1px dashed #cccccc;margin:3px;background:#fefcb7;margin-top:10px;'><b>function: </b>".$function. "</div>";
        $test .=  "<div style='padding:4px;border:1px dashed #cccccc;margin:3px;'><b>file: </b>".$file."</div>";
        $test .=  "<div style='padding:4px;border:1px dashed #cccccc;margin:3px;'><b>line: </b>".$line. "</div>";
        return $test;
    }
    function parseException($e){
        $html = "<div style='margin:0 auto;width:800px;border: #2f381d 1px solid;padding: 10px;margin-top: 30px;'>";
        $html = $html."<div style='background: #f7ffea;padding:10px 4px;;font-size:18px;margin:3px;border:dotted 1px #2f381d'>".$e->msg."</div>";
        $trace = debug_backtrace();
        $i = 0;


        $trace = $e->getTrace();

        foreach($trace as $t){

            $html .= "<div style='padding:6px 4px; background: #eceded;margin: 10px 0;border-top: 1px dashed #a3a6a8'>";
            $temp  = $this->geneItem($t['function'], $t['file'], $t['line']);
            $html .= $temp;
            $html .= "</div>";
        }

        $html .= "</div>";
        return $html;

    }
}