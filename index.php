<?
    //
	date_default_timezone_set("PRC");

    include_once("config.inc.php");
    include_once("lib/functions.php");
    include_once('lib/App.php');
    include_once('lib/gdb.php');

    $app = new App();
    $app->run();
