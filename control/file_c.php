<?php

class file extends ControllerBase
{

    function __construct(){
        parent::__construct();
    }
    function index(){
        echo "Nothing But";
    }
    public function upload(){
        $upload_dir = 'asset/upload';
        $valid_extensions = array('gif', 'png', 'jpeg', 'jpg');
        $filename = $_SERVER['HTTP_X_FILE_NAME'];
        $Upload = new FileUpload('upfile');
        $result = $Upload->handleUpload($upload_dir, $valid_extensions);

        if (!$result) {
            echo json_encode(array('success' => false, 'msg' => $Upload->getErrorMsg()));
        } else {
            echo json_encode(array('success' => true, 'file' => $Upload->getFileName()));
        }
    }
    public function upprogress(){
        if (isset($_REQUEST['getkey'])){
            exit(json_encode(array('key' => uniqid())));
        }elseif (isset($_REQUEST['progresskey'])){
            $status = apc_fetch('upload_'.$_REQUEST['progresskey']);
        }else{
            exit(json_encode(array('success' => false)));
        }
        //
        $pct = 0; $size = 0;

        if (is_array($status)) {
            if (array_key_exists('total', $status) && array_key_exists('current', $status)) {
                if ($status['total'] > 0) {
                    $pct = round( ( $status['current'] / $status['total']) * 100 );
                    $size = round($status['total'] / 1024);
                }
            }
        }
        echo json_encode(array('success' => true, 'pct' => $pct, 'size' => $size));
    }
}