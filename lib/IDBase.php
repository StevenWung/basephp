<?php
	abstract class IDBase{
		abstract function connect($host, $port, $user, $pass);
		abstract function fetchOne($sql);
		abstract function fetchAll($sql);
        abstract function limit($n);
        abstract function offset($n);
        abstract function __get($data);
		abstract function query($sql);
	}