<?php

	require "../inc/init.php";
	// require CLASS_DIR . "DB.php";
	// require CLASS_DIR . "DBTable.php";
	// require CLASS_DIR . "DataTable.php";
	// require CLASS_DIR . "Fetch.php";
	// require CLASS_DIR . "Pagination.php";
	// require CLASS_DIR . "Input.php";
	// require CLASS_DIR . "Category.php";
	// require CLASS_DIR . "BonibonSwitch.php";
	// require CLASS_DIR . "Validation.php";
	// require CLASS_DIR . "InputErrorHandler.php";

	if( $_POST ){
		// Form kontrolu
		$Validation = new Validation( new InputErrorHandler );
		$TEMPLATE_KEY = "template";
		// DB den datayi al
		if( Input::get($TEMPLATE_KEY) != "" ){
			$TEMPLATE = Input::get($TEMPLATE_KEY);
			$Fetch = new Fetch( $TEMPLATE );
			$Product_Table = new DataTable( $TEMPLATE );
			$Product_Table->set_settings( Input::escape( $_POST ) );
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
			'category_name'			=> array( array( "req" => true, "unique" => array( DBT_CATEGORIES, Input::get("item_id") ) ) ),
			'order'  				=> array( array( "req" => true, "pozNumerik" => true ) )
		);

		switch( Input::get("type") ){
			// Hizli duzenle
			case 'QuickEdit':

				$Category = new Category( Input::get("item_id") );
				if( $Category->exists() ) {

					// Formu gonder
					if( Input::get("action") == "request_form" ) {
						// Table guncellenmeyecek
						$ReloadTable = false;
						// Urunun bilgileri form template icin
						$DATA = $Category->get_cropped_details();
					} else {
						// Formu kontrol et
						$Validation->check_v2( Input::escape($_POST), $INPUT_LIST );

						if( $Validation->failed() ){
							$OK = false;
							// JS de fonksiyona uygun formata cevir
							$input_output = $Validation->errors()->js_format();
						} else {
							// Urun duzenleme 
							if( !$Category->edit( Input::escape($_POST) ) ){
								$OK = false;
							}
						}
					}
				}
			break;

			case 'QuickAdd':
				
				$Validation->check_v2( Input::escape($_POST), $INPUT_LIST );
				
				if( $Validation->failed() ){
					$OK = false;
					// JS de fonksiyona uygun formata cevir
					$Validation->errors()->js_format_ref($input_output);
				} else {
					$Category = new Category;
					// Ekle
					if( !$Category->add( Input::escape($_POST) ) ){
						$OK = false;
					}
				}
			break;

			case 'BonibonSwitch':
				// $Bonibon = new BonibonSwitch(Input::get("func"), "Category");
				// $Bonibon->action( Input::get("data"), Input::get("state") );
				$Category = new Category( Input::get("data") );
				if( !$Category->bonibon( Input::get("func"), Input::get("state") ) ){
					$OK = false;
					$ReloadTable = false;
				}
			break;

			// alt kategorilerini de silcez
			case 'DeleteItem':
				$Category = new Category( Input::get("item_id") );
				if( $Category ) {
					if( !$Category->delete() ){
						$OK = false;
					}
				}
			break;

		}

		if( $ReloadTable ) {
			if( isset($Category) ) $TEXT = $Category->get_return_text();
			// Al datalari
			$Fetch->get_data( $FetchType, $Product_Table->get_settings(), $FetchSQL );
			// Datatable init					
			$Product_Table->create( $Fetch->get_results(), $Fetch->get_record_count() );
			$DATA  = $Product_Table->show_table();
			$PAGIN = $Product_Table->show_pagination();
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