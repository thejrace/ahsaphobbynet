<?php
	
	require '../inc/init.php';

	if( $_POST ){

		// ajax output degiskenleri
		$DATA  = array();
		$OK    = 1;
		$TEXT  = "";
		$TOTAL_PRICE = 0;
		$UPDATE = 0;


		/*
		* JS den sepete urun eklemede gelecek veriler
		*	@product_id		
		*	@variant_code	
		*	@qty
		*	@text
		*	$img
		*	@cart_content_id -> duzenlenen sepet urunun id si
		*	
		*/

		switch( Input::get("type") ){

			// urun ekleme - duzenleme
			case 'item_req':

				$INPUT_LIST = array(
					"pid" 					=> array( array( "req" => true, "pozNumerik" => true )  ,"" ),
					'qty'  					=> array( array( "req" => true, "pozNumerik" => true),  "" )
				);

				$Validation = new Validation( new InputErrorHandler );
				// Formu kontrol et
				$Validation->check_v2( Input::escape($_POST), $INPUT_LIST );

				if( $Validation->failed() ){
					$OK = 0;
					$TEXT = $Validation->errors()->js_string_format();
				} else {
					$Cart = new Cart( $IS_GUEST );
					// duzenleme - ekleme ayni fonksiyon
					if( $Cart->add_item( Input::escape( $_POST ) ) ){
						$DATA = $Cart->get_item_details();
					} else {
						$TEXT = "Bir sorun oluştu. Lütfen tekrar deneyin.";
					}
				}
			break;

			case 'load_items':
				$Cart = new Cart( $IS_GUEST );
				$DATA = $Cart->get_items();
				$TOTAL_PRICE = $Cart->get_total_price();
			break;

			case 'delete_item':
				$Cart = new Cart( $IS_GUEST );
				if( $Cart->delete_item( Input::get("item_id") ) ){
					$DATA = $Cart->get_items();
					$TOTAL_PRICE = $Cart->get_total_price();
				}
			break;
		}

		$output = json_encode(array(
			"data"         => $DATA, 		 // datatable
			"total_price"  => $TOTAL_PRICE,		
			"ok"           => $OK,	    	 // istek tamam mi
			"update"       => $UPDATE,	    	 // update mi ekleme mi
			"text" 		   => $TEXT,    	 // bildirim
			"oh"           => $_POST
		));
		echo $output;
		die;
	}