<?php
	require "../inc/init.php";
	// require CLASS_DIR . "DB.php";
	// require CLASS_DIR . "DBTable.php";
	// require CLASS_DIR . "DataTable.php";
	// require CLASS_DIR . "Fetch.php";
	// require CLASS_DIR . "Variant_Product.php";
	// require CLASS_DIR . "Pagination.php";
	// require CLASS_DIR . "Input.php";
	// require CLASS_DIR . "Validation.php";
	// require CLASS_DIR . "InputErrorHandler.php";


	if( $_POST ){
		// Form kontrolu
		$Validation = new Validation( new InputErrorHandler );

		$TEMPLATE_KEY = "template";
		$TEMPLATE = "";
		
		// DB den datayi al
		if( Input::get($TEMPLATE_KEY) != "" ){
			$TEMPLATE = Input::get($TEMPLATE_KEY);
			$Fetch = new Fetch( $TEMPLATE );
			$V_Table = new DataTable( $TEMPLATE );
			$V_Table->set_settings( Input::escape( $_POST ) );
		} 
		// Input error larini tutan array
		$input_output = array();

		// Table guncellenecek mi kontrol icin
		// Default true
		$ReloadTable = true;

		// Ajax return degiskenleri
		$DATA  = "";
		$PAGIN = "";
		$OK    = true;
		$TEXT  = "";

		// Fetch icin gerekli parametreler default ayar bu
		$FetchType = "reload";
		$FetchSQL  = false;

		$INPUT_LIST = array(
			'product_name'			=> array( array( "req" => true ) ),
			'id'					=> array( array( "req" => true, "pozNumerik" => true )),
			'price_1'  				=> array( array( "req" => true, "not_zero" => true, "pozNumerik" => true) ),
			'price_2'  				=> array( array( "pozNumerik" => true ) ),
			'price_3' 				=> array( array( "pozNumerik" => true ) ),
			'desi' 					=> array( array( "pozNumerik" => true ) ),
			'stock_code'  			=> array( array( "unique" => array( DBT_VARIANT_PRODUCTS, Input::get("item_id") )) ), 
			'kdv_percentage' 		=> array( array( "req" => true, "pozNumerik" => true ) ),
			'stock_amount'  		=> array( array( "pozNumerik" => true ) ), 
			'parent'				=> array( array( "req" => true ) )
		);

		$Validation->check_v2( Input::escape( $_POST ), $INPUT_LIST  );
		// ERROOOORRR
		if( $Validation->failed() ){
			$OK = false;
			$Reload_Table = false;
			// JS de fonksiyona uygun formata cevir sonlarina da item id yi ekle
			// Ilk ekleme formunda item id olmadigi icin bu sekilde yapabiliyorum sonuna _ID eklemeden
			$input_output = $Validation->errors()->js_format( Input::get("item_id") );
			// Varyant düzenlemede
		} else {
			// Problem yok devam
			switch( Input::get("type") ){
				//Ekle
				case 'AddVariant':
					$Product = new Variant_Product;
					if( !$Product->add( $_POST ) ){
						$OK = false;
					}
				break;
				// Düzenle
				case 'VariantEdit':
					$Product = new Variant_Product( Input::get("item_id") );
					if( $Product->exists() ){		
						if( !$Product->quick_edit( $_POST ) ){
							$OK = false;
						}
					}
				break;
				//Sil
				case 'DeleteItem':
					$Product = new Variant_Product( Input::get("item_id") );
					if( !$Product->delete() ){
						$OK = false;
					}
				break;

				case 'BonibonSwitch':
					$Product = new Variant_Product( Input::get("data") );
					if( !$Product->bonibon( Input::get("func"), Input::get("state") ) ){
						$OK = false;
						$ReloadTable = false;
					}
				break;

			}

		}

		if( $ReloadTable ) {
			if( isset($Product) ) $TEXT = $Product->get_return_text();
			// Al datalari
			$Fetch->get_data( $FetchType, $V_Table->get_settings(), $FetchSQL );
			// Datatable init					
			$V_Table->create( $Fetch->get_results(), $Fetch->get_record_count() );
			$DATA  = $V_Table->show_table();
			$PAGIN = $V_Table->show_pagination();
		}

		$output = json_encode(array(
			"data"         => $DATA, 		 // datatable
			"pagin"        => $PAGIN,   	 // table sayfalam
			"ok"           => $OK,	    	 // istek tamam mi
			"text" 		   => $TEXT,    	 // bildirim
			"table_reload" => $ReloadTable,  // table update edilecek mi
			"inputret"     => $input_output, // form input errorlari
			"oh"           => $_POST
		));

		echo $output;
		die;

	}