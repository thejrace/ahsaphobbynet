<?php

	/******* KLASORLER *******/
	//define("MAIN_DIR", "/home/ahsaphobby.net/httpdocs/v2/");
	define("MAIN_DIR", "C:/xampp2/htdocs/ahsaphobbynet/");

	// admin klasörleri
	define("ADMIN_DIR", MAIN_DIR . "admin/");
	define("ADMIN_INC_DIR", ADMIN_DIR . "inc/");
	define("ADMIN_AJAX_DIR", ADMIN_DIR . "ajax/");

	// site klasörleri
	define("SITE_DIR",  MAIN_DIR . "site/");
	define("SITE_INC_DIR",  SITE_DIR . "inc/");
	define("SITE_AJAX_DIR",  SITE_DIR . "ajax/");

	// common klasorleri
	define("COMMON_DIR",   MAIN_DIR . "common/");
	define("CLASS_DIR",    COMMON_DIR . "class/");
	define("TEMPLATE_DIR", COMMON_DIR . "template/");

	// resources img, js, css, font
	define("RES_DIR",      MAIN_DIR . "res/");
	define("RES_IMG_DIR",      MAIN_DIR . "res/img/");
	define("RES_FONT_DIR",      MAIN_DIR . "res/font/");
	define("RES_JS_DIR",      MAIN_DIR . "res/js/");
	define("RES_CSS_DIR",      MAIN_DIR . "res/css/");

	// fontlar
	define("RES_FONT_FORM_DIR", RES_FONT_DIR . "form/" );

	// resimler ana klasorler
	define("RES_IMG_STATIC_DIR",      RES_IMG_DIR . "static/");
	define("RES_IMG_AH_DIR",      RES_IMG_DIR . "ahsaphobby/");
	define("RES_IMG_AH_EDITOR_PREV_DIR",      RES_IMG_DIR . "ahsaphobby/editor_previews/");
	define("RES_IMG_AH_EDITOR_PREV_CART_DIR",      RES_IMG_DIR . "ahsaphobby/editor_previews/cart/");
	// ürün resimleri klasörü
	define("RES_IMG_PRODUCT_IMG_DIR",      RES_IMG_STATIC_DIR . "product_img/");
	// kategori resimleri klasörü
	define("RES_IMG_CATEGORY_IMG_DIR",      RES_IMG_STATIC_DIR . "category_img/");

		/******* URL *******/
	define("MAIN_URL",    "http://ahsaphobbynet.test/");
	
	// site url leri
	define("SITE_URL",    MAIN_URL . "site/");
	define("SITE_AJAX_URL",    MAIN_URL . "site/ajax/");
	define("CATEGORY_URL", MAIN_URL . "kategori/" );
	define("PRODUCT_URL", MAIN_URL . "urun/" );
	define("LOGIN_URL", MAIN_URL . "giris-yap" );
	define("REGISTER_URL", MAIN_URL . "kayit-ol" );
	define("LOGOUT_URL", MAIN_URL . "cikis" );

	// admin url leri
	define("ADMIN_URL",   MAIN_URL . "admin/");
	define("ADMIN_AJAX_URL",   ADMIN_URL . "ajax/");
	define("ADMIN_ADD_PRODUCT_URL",   MAIN_URL . "add_product.php/");
	define("ADMIN_EDIT_PRODUCT_URL",   MAIN_URL . "edit_product.php/");
	define("ADMIN_PRODUCTS_URL",   MAIN_URL . "products.php/");
	define("ADMIN_CATEGORIES_URL",   MAIN_URL . "categories.php/");

	// res url
	define("RES_URL",      MAIN_URL . "res/");
	define("RES_IMG_URL",      RES_URL . "img/");
	define("RES_JS_URL",      RES_URL . "js/");
	define("RES_CSS_URL",      RES_URL . "css/");

	// resimler ana url
	define("RES_IMG_STATIC_URL",      RES_IMG_URL . "static/");
	define("RES_IMG_AH_URL",      RES_IMG_URL . "ahsaphobby/");
	define("RES_IMG_AH_EDITOR_PREV_URL",      RES_IMG_AH_URL . "editor_previews/");
	define("RES_IMG_AH_EDITOR_PREV_CART_URL",      RES_IMG_AH_EDITOR_PREV_URL . "cart/");
	// ürün resimleri url
	define("RES_IMG_PRODUCT_IMG_URL",      RES_IMG_STATIC_URL . "product_img/");
	// kategori resimleri url
	define("RES_IMG_CATEGORY_IMG_URL",      RES_IMG_STATIC_URL . "category_img/");


	// Error output log
	ini_set('error_log', ADMIN_INC_DIR . "error.log");

	// Otomatik class include
	function autoload_main_classes($class_name){
		$file = CLASS_DIR . $class_name. '.php';
	    if (file_exists($file)) require_once($file);
	}
	spl_autoload_register( 'autoload_main_classes' );