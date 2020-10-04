<?php

	define("MAIN_DIR",    "C:/xampp2/htdocs/ahsaphobbynet/");
	define("WEB_ADDR",    "http://ahsaphobbynet.test/");
	define("ADMIN_DIR",    MAIN_DIR . "admin/");
	define("SITE_DIR",     MAIN_DIR . "site/");
	define("COMMON_DIR",   MAIN_DIR . "common/");
	define("CLASS_DIR",    COMMON_DIR . "class/");
	define("TEMPLATE_DIR", COMMON_DIR . "template/");
	define("RES_DIR",      MAIN_DIR . "res/");
	define("RES_IMG_AH_DIR",      MAIN_DIR . "res/img/ahsaphobby/");
	define("RES_IMG_STATIC_DIR",      MAIN_DIR . "res/img/static/");
	define("RES_IMG_STATIC_WEB",      WEB_ADDR . "res/img/static/");

	ini_set('error_log', "C:/xampp2/htdocs/ahsaphobbynet/inc/error.log");

	function autoload_main_classes($class_name){
		$file = CLASS_DIR . $class_name. '.php';
	    if (file_exists($file)) require_once($file);
	}

	spl_autoload_register( 'autoload_main_classes' );