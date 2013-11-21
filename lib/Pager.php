<?php
	class Pager {
		
		var $count = 0;
		var $current_page = 0;
		var $pagesize = 0;
		var $pager = null;
		var $pages = 0;
		
		var $start_p = 0;
		
		function __construct($count, $current_page, $pagesize){
			
			$this->count = $count;
			$this->current_page = $current_page;
			$this->pagesize = $pagesize;
			
			$this->pages = ceil($count / $pagesize);
			$this->start_p = $this->pagesize * $this->current_page;
		}
		
		function g(){
			
			$allpgs = $this->pages;
			$cp = $this->current_page;
			$start = 0;
			$end   = 0;
			$show_first = false;
			$show_last  = false;
			if( $allpgs <= 10){
				$start = 0;
				$end = $allpgs;
			}else{
				if($cp<=5){
					$start = 0;
					$end = $start + 10;
					$show_last = true;
				}elseif($allpgs - $cp <= 5){
					$end = $allpgs;
					$start = $allpgs-10;
					$show_first = true;
				}else{
					$end = $cp + 5;
					$start = $cp - 5;
					
					$show_first = true;
					$show_last  = true;
				}
			}
			
			if( $show_first ){
				$page['id'] = 0;
				$page['linkable']= true;
				$page['class'] = 'p';
				$page['txt']= 1;
				$this->pager[] = $page;
				
				$page['id'] = 0;
				$page['linkable']= false;
				$page['class'] = 'p';
				$page['txt']= "..";
				$this->pager[] = $page;
			}
			for($i=$start;$i<$end;$i++){
					$page['id'] = $i;
					$page['linkable']= true;
					$page['class'] = $i==$cp?'current':'p';
					$page['txt']= $i+1;
					$this->pager[] = $page;
			}
			if( $show_last ){
				
				$page['id'] = 0;
				$page['linkable']= false;
				$page['class'] = 'p';
				$page['txt']= "..";
				$this->pager[] = $page;
				
				$page['id'] = $allpgs - 1;
				$page['linkable']= true;
				$page['class'] = 'p';
				$page['txt']= $allpgs;
				$this->pager[] = $page;
			}
			
			
			
			
		}
		
		function p(){
			$this->g();
			
			return $this->pager;
		}
		
		
		
		
		
		
		
		
	}