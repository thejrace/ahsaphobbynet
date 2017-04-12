<?php
	
	// $time_start = microtime(true);
	require "inc/init.php";
	
	// ilk kontrol
	if( Input::get("pid") == "" ) die;
	$Product = new Product( Input::get("pid") );
	if( !$Product->exists() ) die;

	// Sayfanin bilgilerini al
	$PAGE     = new Page("edit_product");
	$TITLE    = $PAGE->get_title();
	$SUBTITLE = $PAGE->get_subtitle();

	// Reloadda bosalt post datalari
	Session::destroy("temp_post");
	$C_Tree = new Category_Tree;
	$P_INFO = $Product->get_details();
	$SEARCH_CATEGORIES = $C_Tree->categories_select_menu_selected( $P_INFO["category"] );
	
	// bunuda class yapabilirim belki ama belki
	$POST_OUTPUT = "";
	$OK = 1;

	// 2007536
	// 1908415 keysiz
	$INPUT_LIST = array(
		"category_id" 			=> array( array( "req" => true, "not_zero" => true ),  0 ),
		'product_name'			=> array( array( "req" => true, "unique" => array( DBT_PRODUCTS, Input::get("pid") ) ), "" ),
		'price_1'  				=> array( array( "req" => true, "not_zero" => true, "pozNumerik" => true),  0.000 ),
		'price_2'  				=> array( array( "pozNumerik" => true ),  0.000 ),
		'price_3' 				=> array( array( "pozNumerik" => true ),  0.000 ),
		'pure_price_1'  		=> array( array(),  0.000 ),
		'pure_price_2'  		=> array( array(),  0.000 ),
		'pure_price_3' 			=> array( array(),  0.000 ),
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
	$Page_Inputs->change_def_vals( $P_INFO );

	// Ekleme
	if( $_POST ){
		// Ajax handle
		if( Input::get("ajax_req") == true ){
			switch( Input::get("type") ){
				case 'remove_variant':
					if( !$Product->remove_variant( Input::get("item_id") ) ){
						$OK = 0;
					}
				break;

				case 'define_variant':
					if( !$Product->define_variant( Input::get("item_id") ) ){
						$OK = 0;
					}	
				break;

				case 'update_variant_order':
					if( !$Product->update_variant_order( Input::get("v") ) ){
						$OK = 0;
					}

				break;
			}
			$TEXT = $Product->get_return_text();

			// Duzenlemeden sonra urunun varyantlarini tekrar al
			// datatable guncellemesi icin
			$Product->variant_defs();
			$DEFINED_VARIANTS = array();
			if( $Product->has_defined_variant() ){
				$DEFINED_VARIANTS = $Product->get_product_variant_defined();
			}
			$UNDEFINED_VARIANTS = $Product->get_product_variant_undefined();

			// Tanımlanmamislar
			$Undef_Table = new DataTable("product_undefined_variants");
			$Undef_Table->set_settings( array() );
			$Undef_Table->create( $UNDEFINED_VARIANTS, count($UNDEFINED_VARIANTS) );

			// Tanimlanmislar
			$Def_Table = new DataTable("product_defined_variants");
			$Def_Table->set_settings( array() );
			$Def_Table->create( $DEFINED_VARIANTS, count($DEFINED_VARIANTS) );

			$ajax_output = json_encode( array(
				'ok'   => $OK,
				'text' => $TEXT,
				'def_table' => $Def_Table->show_table(),
				'undef_table' => $Undef_Table->show_table()
			));
			echo $ajax_output;
			die;
			
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
						$Prod = new Product( Input::get("pid") );
						$P_INFO = $Prod->get_details();
						$SEARCH_CATEGORIES = $C_Tree->categories_select_menu_selected( $P_INFO["category"] );
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
	if( Session::exists("temp_post") ) {
		$Page_Inputs->change_def_vals( Session::get("temp_post") );
	}

	$INPUTS = $Page_Inputs->get_all();
	// ilk acilista ki varyant tablolari
	$Product->variant_defs();
	$DEFINED_VARIANTS = array();
	if( $Product->has_defined_variant() ){
		$DEFINED_VARIANTS = $Product->get_product_variant_defined();
	}
	$UNDEFINED_VARIANTS = $Product->get_product_variant_undefined();

	// Header
	include "inc/header.php";

	// Template
	include $PAGE->show_template();

	// Footer
	include "inc/footer.php";

	// $time_end = microtime(true);

	// $exec_time = $time_end - $time_start;
	// echo "Proc: " . $exec_time;