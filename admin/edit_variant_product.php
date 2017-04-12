<?php
	
	// $time_start = microtime(true);
	require "inc/init.php";

	$TEMPLATE = "edit_variant_product";

	// ilk kontrol
	if( Input::get("pid") == "" ) die;
	$Product = new Variant_Product( Input::get("pid") );
	if( !$Product->exists() ) die;

	// Sayfanin bilgilerini al
	$PAGE     = new Page($TEMPLATE);
	$TITLE    = $PAGE->get_title();
	$SUBTITLE = $PAGE->get_subtitle();

	// Reloadda bosalt post datalari
	Session::destroy("temp_post");
	$P_INFO = $Product->get_details();
	$SEARCH_CATEGORIES = "";

	// bunuda class yapabilirim belki ama belki
	$POST_OUTPUT = "";
	$OK = 1;

	// 2007536
	// 1908415 keysiz
	$INPUT_LIST = array(
		'product_name'			=> array( array(), "" ),
		'price_1'  				=> array( array( "req" => true, "not_zero" => true, "pozNumerik" => true),  0.000 ),
		'price_2'  				=> array( array( "pozNumerik" => true ),  0.000 ),
		'price_3' 				=> array( array( "pozNumerik" => true ),  0.000 ),
		'pure_price_1'  		=> array( array(),  0.000 ),
		'pure_price_2'  		=> array( array(),  0.000 ),
		'pure_price_3' 			=> array( array(),  0.000 ),
		'stock_amount'  		=> array( array( "pozNumerik" => true ),  0 ), 
		'stock_code'  			=> array( array( "unique" => array( DBT_VARIANT_PRODUCTS, Input::get("pid") )),  "" ), 
		'desi' 					=> array( array( "pozNumerik" => true ),  0.000 ),
		'status' 				=> array( array(),  1 ),
		'kdv_included' 			=> array( array(),  0 ),
		'kdv_percentage' 		=> array( array( "pozNumerik" => true ),  18 ),
		'sale_percentage'  		=> array( array( "pozNumerik" => true ),  0 ),
		'shipment_cost'		 	=> array( array( "pozNumerik" => true ),  0 ),
		'shipment_system_cost' 	=> array( array(),  0 ),
		'seo_keywords' 			=> array( array(),  "" ),
		'seo_title'				=> array( array(),  "" ),
		'seo_details' 			=> array( array(),  "" ),
		'details' 				=> array( array(),  "" ),
		'picture_1'	 			=> array( array(),  "" )
	);
	
	$Page_Inputs = new Page_Inputs( $INPUT_LIST );
	$Page_Inputs->change_def_vals( $P_INFO );

	// Ekleme
	if( $_POST ){
		// Ajax handle
		if( Input::get("ajax_req") == true ){

			
		} else {

			$Validation = new Validation( new InputErrorHandler );
			// Formu kontrol et
			$Validation->check_v2( Input::escape($_POST), $INPUT_LIST );

			if( $Validation->failed() ){	
				Session::set("temp_post", Input::escape($_POST) );
				$OK = 0;
				$POST_OUTPUT = $Validation->errors()->js_string_format();
			} else {
				// Resim handle sonra ekle
				if( $Product->handle_pictures( $_FILES, Input::get("pid") ) ){
					if( $Product->edit( Input::escape($_POST) ) ){
						Session::destroy("temp_post");	
						// Ürün düzenlendikten sonra güncellenmiş halini template yazdırmak için tekrar
						// product objesini oluştur.
						$Prod = new Variant_Product( Input::get("pid") );
						$P_INFO = $Prod->get_details();
						$Page_Inputs->change_def_vals( $P_INFO );

					} else {
						$OK = 0;
					}
				} else{
					$OK = 0;
				}
				$POST_OUTPUT = $Product->get_return_text();
			}

		}
	}

	// Hata olursa sessiondan input vallari al tekrar yazmasinlar
	if( Session::exists("temp_post") && Session::get("temp_post") != array() ) {
		$Page_Inputs->change_def_vals( Session::get("temp_post") );
	}

	$INPUTS = $Page_Inputs->get_all();

	// Header
	include "inc/header.php";

	// Template
	include $PAGE->show_template();

	// Footer
	include "inc/footer.php";

	// $time_end = microtime(true);

	// $exec_time = $time_end - $time_start;
	// echo "Proc: " . $exec_time;