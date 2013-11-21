<?php
	class mailbase{
            
            var $sock ;
            public function mailbase(){
                $this->sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if ( $this->sock == null){
			echo "no";
			return -1;
		}
            }
            
            function connect($server, $username, $password){		
                    $c_hello = "HELO server";
                    $c_login = "AUTH LOGIN";
                    $ip = gethostbyname($server);
                    
                    //connect
                    socket_connect($this->sock, $ip, 25) or die("connect error");
                    if($this->s_read() != 220 ){
                            return -1;
                    }
                    //hello
                    $this->s_send($c_hello);
                    if($this->s_read() != 250 ){
                            return -1;
                    }
                    
                    //login
                    $this->s_send($c_login);
                    if($this->s_read() != 334 ){
                            return -1;
                    }
                    
                    //user
                    $name_code = base64_encode($username);
                    $this->s_send($name_code);
                    if($this->s_read() != 334 ){
                            return -1;
                    }
                    
                    //pass
                    $pass_code = base64_encode($password);
                    $this->s_send($pass_code);
                    if($this->s_read() != 235 ){
                            return -1;
                    }
                    return 0;
            }
            function sendmail($from , $to , $subject, $content, $server_name="网站监控"){
                
                    
                    
                    $tos = explode(",", $to);
                    $subject = @iconv("UTF-8","gbk",$subject);
                    $content = @iconv("UTF-8","gbk",$content);
                    foreach($tos as $val){
                            $c_from = "MAIL FROM: <$from>";
                            $c_to   = "RCPT TO: <$val>";
                            $c_data = "DATA";
                            
                            $name = @iconv("utf-8", "gbk", $server_name);;
                            $body   = "From: $name<$from>\r\n";
                            $body  .= "To: $to\r\n";
                            $body  .= "Subject: $subject\r\n";
                            $body  .= "X-Mailer: IwebMailer\r\n";
                            $body  .= "Mime-Version: 1.0\r\nContent-Type: text/html; Charset=gbk\r\n\r\n";
                            
                            $body  .= "<meta http-equiv='Content-Type' content='text/html;charset=gbk'>$content\r\n.";
                            
                            //send from 
                            $this->s_send($c_from);
                            if($this->s_read() != 250 ){
                                    die("error [c-from]");
                                    return -1;
                            }
                            //send to 
                            $this->s_send($c_to);
                            if($this->s_read() != 250 ){
                                    die("error [c-to]");
                                    return -1;
                            }
                            //send data flag
                            $this->s_send($c_data);
                            if($this->s_read() != 354 ){
                                    die("error [c-data]");
                                    return -1;
                            }
                            //send body
                            $this->s_send($body);
                            if($rt = $this->s_read() != 250 ){
                                    die("error [c-body][code:$rt]");
                                    return -1;
                            }
                    }	
                    return 0;
            }
            function close(){
                    $c_quit = "QUIT";
                    $this->s_send($c_quit);
                    if($this->s_read() != 221 ){
                            return -1;
                    }		
                    socket_close($this->sock);
                    return 0;
            }
            function s_send($msg){
                    $msg = $msg."\r\n";
                    $re = socket_write($this->sock, $msg, strlen($msg));
                    //echo "write code : [$re] <br>";
            }
            
            function s_read(){
                    $rt = socket_read($this->sock,255);//."<br>";
                    return (int)$rt;
            }
            
        
        
        }


	/*
	$server   = "mail.iwebcompass.com";
	$username = "noreply@iwebcompass.com";
	$password = "sasdfasdf";
	
	$from = $username;
	$to   = $username;
	
	$mail = new mailx();
	if(-1 == $mail->connect($server, $username, $password))
	{
		echo "connection error!<br>";
	}
	
	$body = "<h1>这是一个简单的测试</h1>";
	if(-1 == $mail->sendmail($from, "wangshaobo@iwebcompass.com,lixiaoliang@iwebcompass.com", "注意", "你在干什么"))
	{
		echo "sending error!<br>";
	}
 	$mail->close();
	
	*/

     