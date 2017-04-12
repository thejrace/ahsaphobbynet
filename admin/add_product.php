<?php

	require "inc/init.php";

	// Sayfanin bilgilerini al
	$PAGE     = new Page("add_product");
	$TITLE    = $PAGE->get_title();
	$SUBTITLE = $PAGE->get_subtitle();

	// Reloadda bosalt post datalari
	Session::destroy("temp_post");

	$C_Tree = new Category_Tree;
	if( Input::get("katid") != "" ){
		$CURRENT = Input::get("katid");
		$SEARCH_CATEGORIES = $C_Tree->categories_select_menu_selected( Input::get("katid") );
	} else {
		$CURRENT = 0;
		$SEARCH_CATEGORIES = $C_Tree->categories_select_menu_default();
	}

	$Product = new Product;

	// bunuda class yapabilirim belki ama belki
	$POST_OUTPUT = "";
	$OK = 1;

	$INPUT_LIST = array(
		"category_id" 			=> array( array( "req" => true, "not_zero" => true ),  0 ),
		'product_name'			=> array( array( "req" => true, "unique" => array( DBT_PRODUCTS, Input::get("item_id") ) ), "" ),
		'price_1'  				=> array( array( "req" => true, "not_zero" => true, "pozNumerik" => true),  0.000 ),
		'price_2'  				=> array( array( "pozNumerik" => true ),  0.000 ),
		'price_3' 				=> array( array( "pozNumerik" => true ),  0.000 ),
		'stock_amount'  		=> array( array( "pozNumerik" => true ),  0 ), 
		'stock_code'  			=> array( array(),  "" ), 
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
		'material' 				=> array( array(),  "" ),
		'similar_products' 		=> array( array(),  "" )
	);
	
	$Page_Inputs = new Page_Inputs( $INPUT_LIST );

	// Ekleme
	if( $_POST ){

		$Validation = new Validation( new InputErrorHandler );
		// Formu kontrol et
		$Validation->check_v2( Input::escape($_POST), $INPUT_LIST );

		if( $Validation->failed() ){
			// Inputlari tut
			Session::set("temp_post", Input::escape($_POST) );
			$OK = 0;
			// JS de fonksiyona uygun formata cevir
			// JS de bir tane fonksiyon ayarlamak lazim
			$POST_OUTPUT = $Validation->errors()->js_string_format();
		} else {	
			// Resim handle sonra ekle
			if( $Product->handle_pictures( $_FILES, false ) ){
				if( $Product->add( Input::escape($_POST) ) ){
					Session::destroy("temp_post");
				} else {
					$OK = 0;
				}
			} else{
				$OK = 0;
			}
			$POST_OUTPUT = $Product->get_return_text();
		}
	}

	

	// Hata olursa sessiondan input vallari al tekrar yazmasinlar
	if( Session::exists("temp_post") ) {
		$Page_Inputs->change_def_vals( Session::get("temp_post") );
	}

	$INPUTS = $Page_Inputs->get_all();

	// Header
	include "inc/header.php";
	// echo $POST_OUTPUT;
	
	// Template
	include $PAGE->show_template();

	// Footer
	include "inc/footer.php";


	/*
	

	$Validation->check( Input::escape($_POST), array(
			'category_id' => array(
				'req' => true,
				'not_zero' => true
			),
			'product_name' => array(
				'req'  => true
			),
			'price_1'      => array(
				'req'        => true,
				'pozNumerik' => true,
				'not_zero' => true
			),
			'price_2'      => array(
				'pozNumerik' => true
			),
			'price_3'      => array(
				'pozNumerik' => true
			),
			'stock_amount'   => array(
				'numerik'    => true
			),
			'desi' 	=> array(
				'numerik'    => true
			),
			'kdv_percentage'     => array(
				'numerik'    => true
			),
			'sale_percentage'     => array(
				'numerik'    => true
			),
			'shipment_cost'     => array(
				'numerik'    => true
			)
		));

	*/