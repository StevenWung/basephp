<?php
class DataBase{//} extends IDBase {

	private  $_sql ;
    private  $_conn;

    private  $_tbl       = '';
	private  $_where     = '';
    private  $_order_by  = '';
    private  $_limit     = '';
    private  $_offset    = '';
    private  $_prefix    = '';
    private  $_hdr       = '';
    private  $_sh        = null;
    private  $_rt        = null;
    private  $_executed  = false;
	//----------------------
	
	function __construct($tbl='') {
		global $cfg;
		
		$this->_cfg = $cfg;
		$this->_prefix = $cfg['db']['prefix'];

        $username = $cfg['db']['username'];
        $password = $cfg['db']['password'];

        $host = $cfg["db"]["host"];

        $this->_conn = new PDO("mysql:host=$host; dbname=".$cfg['db']['dbname'].";charset=utf8", $username , $password );
        $this->_tbl = get_called_classname();
        if( ($this->_tbl == __CLASS__ || $this->_tbl == "Db" )){
            if( $tbl == '' ){
                throw new BException('Table name must be specified!');
            }else{
                $this->_tbl = $tbl;
            }
        }else{
            $this->_tbl = get_called_classname();
        }
	}	
	function __destruct() {
	}
	function where($param) {
		if( $this->_where == '' ){
			$this->_where  = ' WHERE '.$param;
		}
		return $this;
	}
	function limit($n){
		$this->_limit = $n;
		return $this;
	}
    function offset($n){
        $this->_offset = $n;
        return $this;
    }
    //#
    private function geneSql(){
        if( $this->_hdr == '' ){
            $sql  = "select * from ".$this->_tbl;
        }else{
            $sql  = "select ".$this->_hdr." from ".$this->_tbl;
        }
        //where
        if( $this->_where != '' ){
            $sql .= " ".$this->_where;
        }
        //
        if( $this->_offset != '' ){
            $sql .= " limit ".$this->_offset;
        }
        if( $this->_offset != '' ){
            $sql .= " , ".$this->_offset;
        }
        $this->_sql = $sql ;
        return $sql;
    }
    private function geneResult($rt){
        return new Data($this->_sql, $rt, count($rt));
    }
    function query($sql){
        $this->_sql = $sql;
        $this->_sh = $this->_conn->prepare($sql);
        $this->_sh->execute();
        $this->_rt = $this->_sh->fetchAll();
        return $this->geneResult($this->_rt);
    }
    function fetchAll(){

        $sql = $this->geneSql();
        $this->_sh = $this->_conn->prepare($sql);
        $this->_sh->setFetchMode(PDO::FETCH_ASSOC);
        $this->_sh->execute();
        $this->_rt = $this->_sh->fetchAll();
        return $this->geneResult($this->_rt);
    }
    function header($hd){
        $this->_hdr = $hd;
    }
    function fetchFirst(){
        if( $this->_sh ){
            $this->_rt = $this->_sh->fetch();
        }else{
            $sql = $this->geneSql();
            $this->_sh = $this->_conn->prepare($sql);
            $this->_sh->setFetchMode(PDO::FETCH_ASSOC);
            $this->_sh->execute();
            $this->_rt = $this->_sh->fetch();
        }
        if( $this->_rt )
            return $this->geneResult(array($this->_rt));
        else
            return $this->geneResult(null);
    }
    function close(){
        $this->_sh->close();
    }
    private function trigger(){
        $tbl = $this->tbl;
        $pre = $this->prefix;
        $whr = $this->where;
        $sql = "select * from ${pre}${$tbl}";
    }
}


class Data{
    var $sql;
    var $data;
    var $row;
    function __construct($_sql,$_data, $_row){
        $this->sql  = $_sql;
        $this->data = $_data;
        $this->row  = $_row;

    }
}