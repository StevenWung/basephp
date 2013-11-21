<?php

    function get_called_classname(){
        if(function_exists('get_called_class1')){
            return get_called_class();
        }
        $t = debug_backtrace(); $t = $t[1];

        #var_dump($t);die();
        if (isset($t['object']) && $t['object'] instanceof $t['class']){
            #var_dump(get_class($t['object']));
            #die();
            return get_class($t['object']);
        }
        return false;
    }

	$script= "";
	function get_script($dir){
    	global $script;
    	$files = scandir($dir);
    	$files = array_diff($files, array('.', '..', '.svn'));
    	foreach($files as $file) {
			$file = $dir . "/" . $file;
			if(is_dir($file)) {
				get_script($file);
			} else {
				if(strstr($file , "functions.php"))continue;
				//p($file);
				$script .= file_get_contents($file);
				//$script .= php_strip_whitespace($file);
			}
		}
    }
    function gen_rtm(){
    	global $script ;
	   
    	global $cfg;
    	$rtm = $cfg["sys"]["cache_dir"]."/~r.php";
  		
		get_script("./lib");
    	file_put_contents($rtm, '<?php' . str_replace(array("<?php", "", "<?"), '', $script));
	}
	function p($str){
		global $cfg;
		if($cfg['sys']['gdb_level'] != DGB_SCRIPT)
			return;
		if(is_null($str)){
			echo "<br>";
			return;
		}
		echo $str."<br>";
	}
	function is_control($var){
		if($var!="")
			return !is_numeric($var[0]);
	}
	function _p(){
		global $cfg;
		if($cfg['sys']['gdb_level'] == DGB_SCRIPT)
			echo "<br>--------------------------------<br>";
	}
	function error($var){
		die($var);
	}
	function print_array($var){
		global $cfg;
		if($cfg['sys']['gdb_level'] != DGB_SCRIPT)
			return;
		echo "<br>---------array----------<br>";
		if(!is_null($var)){
			foreach ($var as $k => $v){
				echo "#    $k ==> $v <br>";
			}	
		}
		echo "------------------------<br><br>";
	}
	
	function alert($param){
		echo "<script>alert('$param');</script>";
	}
	
 
	function validate_email($str)
	{
		 
		
		if(preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/" , $str))
		{
			return true;
		}
		/*
		if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $str)) return 0;
		list($usr, $domain) = split('@', $str);
		if (!@checkdnsrr($domain, 'MX')) return 1;
		*/
		return false;
	}
	
	function goto_url($v){
		echo "<script>window.location='$v';</script>";
	}
	
	function goback(){
		echo "<script>history.go(-1);</script>";
	}
	
	function get_rand_string($c) {
		$sed = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
		$str = '';
		for($i=0;$i<$c;$i++){
			$sedi = rand(0, strlen($sed)-1);
			$str .= $sed[$sedi];
		}
		return $str;
	}
	
	
	
	if( !function_exists('hex2rgb') ) {
		function hex2rgb($hex_str, $return_string = false, $separator = ',') {
			$hex_str = preg_replace("/[^0-9A-Fa-f]/", '', $hex_str); // Gets a proper hex string
			$rgb_array = array();
			if( strlen($hex_str) == 6 ) {
				$color_val = hexdec($hex_str);
				$rgb_array['r'] = 0xFF & ($color_val >> 0x10);
				$rgb_array['g'] = 0xFF & ($color_val >> 0x8);
				$rgb_array['b'] = 0xFF & $color_val;
			} elseif( strlen($hex_str) == 3 ) {
				$rgb_array['r'] = hexdec(str_repeat(substr($hex_str, 0, 1), 2));
				$rgb_array['g'] = hexdec(str_repeat(substr($hex_str, 1, 1), 2));
				$rgb_array['b'] = hexdec(str_repeat(substr($hex_str, 2, 1), 2));
			} else {
				return false;
			}
			return $return_string ? implode($separator, $rgb_array) : $rgb_array;
		}
	}
	function __autoload($param) {
		require_once 'module/'.$param;;
	}


	function print_result($result, $count = 0)
	{
		$i=0;
		$j=0;
		$rno=0;
		$keys=array();
		echo "<table border='0' cellpadding='1' cellspacing='1'  style='background:#CCC;margin:4px;font-size:12px;'>\r";
		while(1)
		{
			if( gettype($result) == 'resource')
			{
				
				$row = mysql_fetch_assoc($result);
			}
			else
			{
				if(($row = $j++==0?current($result):next($result))==null)
				{
					break;
				}
			}
			if($row == null)
			{
				break;
			}	
		//while(($row = mysql_fetch_array($result,MYSQL_ASSOC))!=null)
		//{
			echo "<tr>\r";
			echo "<td style='background:#aaa;'>&nbsp;$i&nbsp;</td>\r";
			foreach($row as $header => $field)
			{
				if($i==0)
				{
					$keys[]=$header;
				}
				if ( $header == 'url_id')
				{
					//$field = search_dict(C_RELEASE_PATH, TYPE_URL, $field);
				}
				echo "<td style='background:#FFF;'>&nbsp;$field&nbsp;</td>\r";
			}
			if( $i++ >= $count && $count != 0)
			{
				break;	
			}
			
			echo "</tr>\r";
		}
		echo "<tr>\r";
		echo "<td></td>\r";
		for($i=0;$i<count($keys);$i++)
		{
			echo "<td style='background:#FCC;text-align:center;'>&nbsp;$keys[$i]&nbsp;</td>\r";
		}
		echo "</tr>\r";
		echo "</table>\r";
	}
	function create_jx($c, $f, $pr='dummy', $ini=false, $echo = true) {
		$str = "<script>\n";
		$str .= "function ${c}_${f}(d){\n";
		$str .=  "if(d==null) \n";
		$str .=  	"$.get('/${c}/${f}/', null, function(data, textStatus){ ${pr}(decodeURI(data)); });\n";
		$str .=  " else \n";
		$str .=  	"$.get('/${c}/${f}/'+d, null, function(data, textStatus){ ${pr}(decodeURI(data)); });\n";
		$str .=  "}";
		if( $ini ){$str .=  "\$(function(){ ${c}_${f}(); });\n";}
		$str .=  "</script>\n";
		if( $echo ){
			echo $str;
		}
		else{
			return  $str;
		}
	}
	
	function include_jquery(){
		global $cfg;
		echo "<script src='/".$cfg["sys"]["view_dir"]."/js/Jquery.js'></script>\n";
	}
	
	function create_ajax($funame, $proc="dumy", $need_echo=true)
	{
	 	if($proc == 'dumy')
	 		$proc = $funame."_back";
		$str  = "<script language='javascript'>";
		$str .= " function $funame(param, pdata)";
		$str .= " {";
		$str .= " if(pdata==null)";
		$str .= " $.get(";
		$str .= " param, null,";
		$str .= " function (data, textStatus){";
		$str .= " dt=$.trim(data);if(dt==''){return;}";
		$str .= " $proc(dt);";
		$str .= " });";
		$str .= " else";
		$str .= " $.post(";
		$str .= " param, pdata,";
		$str .= " function (data, textStatus){";
		$str .= " dt=$.trim(data);if(dt==''){return;}";
		$str .= " $proc(dt);";
		$str .= " });";
		$str .= "}";
		$str .= "</script>";
		
	 	if($need_echo)
	 		echo $str;
	 	else
	 		return $str;
	}
	function db_read($rt){
		if( is_bool($rt) ){
			debug_print_backtrace();
		}
		return mysql_fetch_array($rt, MYSQL_ASSOC);
	}
	function db_rows($rt){
		return mysql_num_rows($rt);
	}
	function db_af_rows($rt=null){
		if( $rt )
			return mysql_affected_rows($rt);
		else
			return mysql_affected_rows();
	}
	
	function isLogin(){
		if( request::get_session('login') == true ){
			return true;
		}else{
			return false;
		}
	}
	function check_login(){
		if( request::get_session('login') == true ){
			return true;
		}else{
			return false;
		}
	}
	function check_login_jump(){
		if( check_login() != true ){
			alert('请先登陆!');
			goto_url("/");
		}
	}
	function check_login_jx(){
		if( check_login() != true ){
			error::sys_err("400");
		}
	}
	function check_post(){
		if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
			return true;
		}
		return false;
	}
	function check_ajax(){
		if( !request::is_ajax() ){
			error::sys_err("400");
			die();
		}
	}
	function check_param_count($p, $c){
		if( count($p) < $c){
			error::sys_err('request');
			die();
		}
	}
	
	function genpass($pass){
		return md5(md5($pass)+$pass);
	}
	
	
	function sys2html($sys){
		$temp = str_replace("\r\n", "<br>", $sys);
		return $temp;
	}
	function db_get_array($rt){
		$temp = array();
		while($rs=db_read($rt)){
			$temp[] = $rs;
		}
		return $temp;
	}
	
	function difftime($time){
		return $time;
		$timediff = time() - strtotime($time);
		//echo $timediff;
		if( $timediff < 60)
			$datetime = '刚刚';	
		if( $timediff > 60)
			$datetime = (int)($timediff/60).'分钟前';
		if( $timediff > 3600)
			$datetime = (int)($timediff/3600).'小时前';	
		if( $timediff > 3600*24 )
			$datetime = $time;
		return $datetime;
	}
	
	function extract_json($rt, $keys){
		$data = "[";
		foreach($rt as $row){
			$data.="{";
			foreach($keys as $k){
				$data.=$k.":'".$row[$k]."',";
			}
			$data.="},";
		}
		$data.="]";
		return $data;
	}
	
	function get_param($params, $idx, $safe=true, $default=null){
		$temp = '';
		if( isset( $params[$idx] ) ){
			$temp = $params[$idx];
		}else{
			return $default;
		}
		if( $safe )
			return inject_check(urldecode($temp));
		else
			return urldecode($temp);
	}
	function inject_check($str) {  
    	$str =  str_replace("'", "＇", $str);
    	$str =  str_replace("&", "", $str);
    	$str =  str_replace(">", "&gt;", $str);
    	$str =  str_replace("<", "&lt;", $str);
    	$str =  str_replace("\"", "“", $str);
    	$str =  str_replace("or", "", $str);
    	$str =  str_replace("and", "", $str);
    	
    	return $str;
	}
    function inject_resume($str) {
        $str =  str_replace("＇", "'",$str);
        $str =  str_replace("", "&", $str);
        $str =  str_replace("&gt;", ">", $str);
        $str =  str_replace("&lt;", "<", $str);
        $str =  str_replace("“", "\"", $str);
        $str =  str_replace("", "or", $str);
        $str =  str_replace("", "and", $str);

        return $str;
    }
    function smarty_function_cut($params){
        $str  = $params['str'];
        $size = $params['s'];
        //echo mb_strlen($str,'utf8')."----".$size;
        if(mb_strlen($str,'utf8') > $size){
            return mb_substr($str, 0, $size, 'utf8')."...";
        }
        return $str;
    }
    function smarty_function_paging($params,&$smarty){
        $pager = $params['pg'];
        if( isset($params['param']) ){
            $param = $params['param'];
        }else{
            $param = "";
        }
        if( $pager == null )return;
        foreach($pager as $k=>$v){
            if( $v['linkable'] == false ){
                echo "<a href='#this' class='".$v['class']."'>".$v['txt']."</a>";
            }else{
                echo "<a href='?p=".$v['id']."&$param' class='".$v['class']."'>".$v['txt']."</a>";
            }
        }
    }
    function smarty_function_formattime($params){
        $date = $params['date'];
        return date('m/d H:i',strtotime($date));
    }
    function smarty_function_formatddate($params){
        $date = $params['date'];
        return date('Y-m-d',strtotime($date));
    }
    function smarty_function_formatdate($params){
        $date = $params['date'];
        return date('m-d',strtotime($date));
    }

    function smarty_function_difftime($params){
        $date = $params['date'];
        echo difftime($date);
    }
    function smarty_function_esctags($params){
        $str = $params['str'];
        echo strip_tags($str);
    }
    function smarty_function_js($params){
        $file = $params['file'];
        echo "<script src='".APP_ROOT."asset/js/${file}.js'></script>";
    }
    function smarty_function_css($params){
        $file = $params['file'];
        echo '<style type="text/css" media="all" >';
        echo '@import "'. APP_ROOT."asset/css/${file}.css" .'";';
        echo '</style>';
        //echo "<link type='text/css' media='all' href='".APP_ROOT."asset/css/${file}.css' />";
    }
    function smarty_function_ajax($params){
        $file = $params['file'];
        echo "<script src='".APP_ROOT."asset/js/${file}.js'></script>";
    }
    function smarty_function_progress($params){
        $id = $params['id'];
        echo "<div class='progressbar progress-striped active' id='$id'><div class='bar' id='${id}_inner' style='width: 0%;'></div></div>";
    }
    function smarty_function_slider($params){

        $data = $params['data'];
        $id   = isset($params['id'])?$params['id']:'slider';
        echo "\n\t\t<!--slider box-[$id]-begin-->\n";
        echo "\t\t<div id='$id' class='slider'>\n";
        echo "\t\t\t<div class='box'>\n";
        foreach($data as $row){
            //picture link title
            $picture = $row['picture'];
            $link    = isset($row['link'])?$row['link']:'';
            $title   = $row['title'];
            echo "\t\t\t<div class='content'>\n";
            if( $link == '' ){
                echo "\t\t\t<img src='.$picture.'  />\n";
            }else{
                echo "\t\t\t<a href='$link' target='__blank'><img src='$picture'  /></a>\n";
            }
            echo "\t\t\t<div class='caption'>\n";
            echo "\t\t\t    <div class='caption_bg'></div>\n";
            if( $link == '' ){
                echo "\t\t\t    <div class='caption_text'><a href='#this'>$title</a></div>\n";;
            }else{
                echo "\t\t\t    <div class='caption_text'><a href='$link' target='__blank'>$title</a></div>\n";;
            }
            echo "\t\t\t</div>\n";;
            echo "\t\t\t</div>\n";;
        }
        echo "\t\t\t</div>\n";;
        echo "\t\t</div>\n";;
        echo "\t\t<script type='text/javascript'>$(function(){ var slider = new Util.Slider({ id:'$id'});});</script>\n";;
        echo "\t\t<!--slider box-[$id]-end-->\n";
    }
    function smarty_function_route($params){
        if(isset($params['c']))
            $c = $params['c'];
        else{
            $c = '';
        }
        echo APP_ROOT.$c;
    }
    function sort_objects_by_tuijian($a, $b) {

        if(isset($a['top'])&&(int)($a['top'])!=0) return -1;
        if(isset($b['top'])&&(int)($b['top'])!=0) return 1;
        $a_star = str_replace('-', '', $a['star']);
        $b_star = str_replace('-', '', $b['star']);
        if((int)$a_star == (int)$b_star){ 
            $r = rand(-1,1);
            return $r; 
        }
        return ((int)$a_star > (int)$b_star) ? -1 : 1;
    }