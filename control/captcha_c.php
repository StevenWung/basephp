<?php 
	
	class captcha extends ControllerBase
	{
		public $lazy = "true";
		function get($param) {
			 
			 	session_start();
			 	
				$color = Request::get_get("color");
				$color = $color == null?'/df.png':"/d1f.png";
			 
				if( Request::refer() == null){
					error::sys_err('request');
				}
 	  			$captcha_config = array(
					'code' => '',
					'min_length' => 4,
					'max_length' => 4,
					'png_backgrounds' => array(dirname(__FILE__) . $color),
					'fonts' => array(dirname(__FILE__) . '/times_new_yorker.ttf'),
					'characters' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',
					'min_font_size' => 15,
					'max_font_size' => 15,
					'color' => '#6F4007',
					'angle_min' => 0,
					'angle_max' => 5,
					'shadow' => true,
					'shadow_color' => '#CCC',
					'shadow_offset_x' => -2,
					'shadow_offset_y' => 2
				);
					 		
				
				
				srand(microtime() * 100);
				unset($_SESSION['captcha']);
				$_SESSION['captcha'] = get_rand_string(5);
				$captcha_config['code'] = $_SESSION['captcha'];
		 
				
				// Use milliseconds instead of seconds
				
				
				// Pick random background, get info, and start captcha
				$background = $captcha_config['png_backgrounds'][rand(0, count($captcha_config['png_backgrounds']) -1)];
				list($bg_width, $bg_height, $bg_type, $bg_attr) = getimagesize($background);
				
				//$bg_width = 20;
				//$bg_height
				$captcha = imagecreatefrompng($background);
				//$captcha = @imagecreate(68, 26, "#DBE3DB");
	
				$color = hex2rgb($captcha_config['color']);
				
				
				$color = imagecolorallocate($captcha, $color['r'], $color['g'], $color['b']);
				
				// Determine text angle
				$angle = rand( $captcha_config['angle_min'], $captcha_config['angle_max'] ) * (rand(0, 1) == 1 ? -1 : 1);
				
				// Select font randomly
				$font = $captcha_config['fonts'][rand(0, count($captcha_config['fonts']) - 1)];
				
				// Verify font file exists
				if( !file_exists($font) ) throw new Exception('Font file not found: ' . $font);
				
				//Set the font size.
				$font_size = rand($captcha_config['min_font_size'], $captcha_config['max_font_size']);
				$text_box_size = imagettfbbox($font_size, $angle, $font, $captcha_config['code']);
				
				// Determine text position
				$box_width = abs($text_box_size[6] - $text_box_size[2]);
				$box_height = abs($text_box_size[5] - $text_box_size[1]);
				$text_pos_x_min = 0;
				$text_pos_x_max = ($bg_width) - ($box_width);
				//$text_pos_x = rand($text_pos_x_min, $text_pos_x_max);			
				$text_pos_x = rand(0, 5);
				$text_pos_y_min = $box_height;
				$text_pos_y_max = ($bg_height) - ($box_height / 2);
				//$text_pos_y = rand($text_pos_y_min, $text_pos_y_max);
				$text_pos_y = rand(20, 22);
				
				//echo "$text_pos_x_min, $text_pos_x_max";die();
					// Draw shadow
				if( $captcha_config['shadow'] ){
					$shadow_color = hex2rgb($captcha_config['shadow_color']);
				 	$shadow_color = imagecolorallocate($captcha, $shadow_color['r'], $shadow_color['g'], $shadow_color['b']);
					imagettftext($captcha, $font_size, $angle, $text_pos_x + $captcha_config['shadow_offset_x'], $text_pos_y + $captcha_config['shadow_offset_y'], $shadow_color, $font, $captcha_config['code']);	
				}
				
				// Draw text
				imagettftext($captcha, $font_size, $angle, $text_pos_x, $text_pos_y, $color, $font, $captcha_config['code']);	
				//echo 'asdfasdf';die();
				// Output image
				header("Content-type: image/png");
				imagepng($captcha);
				//die($_SESSION['captcha']);
			 
		}
		function get1($param) {
			$this->captcha($param);
		}
 
	
	}