<?php
	require "../inc/init.php";
	require CLASS_DIR . "DB.php";
	require CLASS_DIR . "DBTable.php";
	require CLASS_DIR . "DataTable.php";
	require CLASS_DIR . "Fetch.php";
	require CLASS_DIR . "Variant.php";
	require CLASS_DIR . "Pagination.php";
	require CLASS_DIR . "Input.php";
	require CLASS_DIR . "Validation.php";
	require CLASS_DIR . "InputErrorHandler.php";

	if( $_POST ){
		// Form kontrolu
		$Validation = new Validation( new InputErrorHandler );
		$TEMPLATE_KEY = "template";

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

		switch( Input::get("type") ){
			case 'QuickAdd':
				$Variant = new Variant(true);	
				$Validation->check( Input::escape($_POST), array(
					'variant_name' => array(
						'req'  => true,
						'unique' => DBT_SUB_VARIANT
					)
				));
				if( $Validation->failed() ){
					$OK = false;
					// JS de fonksiyona uygun formata cevir
					$Validation->errors()->js_format_ref($input_output);
				} else {
					if( $Variant->add( Input::escape($_POST) ) ){
						$TEXT = "Varyant Eklendi";
					} else {
						$OK = false;
					}
				}
			break;

			case 'QuickEdit':
				$Variant = new Variant( true, Input::get("item_id") );
				if( $Variant->exists() ){
					if( Input::get("action") == "request_form" ){
						$ReloadTable = false;
						$DATA = $Variant->get_cropped_details();
					} else {
						// Formu kontrol et
						$Validation->check( Input::escape($_POST), array(
							'variant_name' => array(
								'req'  => true,
								'unique_edit' => array( DBT_SUB_VARIANT, Input::get("item_id") )
							)
						));

						if( $Validation->failed() ){
							$OK = false;
							// JS de fonksiyona uygun formata cevir
							$Validation->errors()->js_format_ref($input_output);
						} else {
							// Urun duzenleme 
							if( $Variant->edit( Input::escape($_POST) ) ){
								$TEXT = "Değişiklikler kaydedildi.";
							} else {
								$OK = false;
							}
						}
					}
				}
			break;
		}

		if( $ReloadTable ) {
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
			"oh"           => $DATA //$_POST
		));

		echo $output;
		die;

	}