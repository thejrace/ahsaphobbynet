<?php

	require '../inc/init.php';

	if( $_POST ) {

		// ajax output degiskenleri
		$DATA  = "";
		$OK    = 1;
		$TEXT  = "";
		$PRICE = -1;
		$PRICE_EACH_LETTER = -1;

		$Product = new Product( Input::get("item_id") );
		if( !$Product->exists() ) die;
		$PRODUCT_ID = $Product->get_details("id");

		switch( Input::get('type') ){
			case 'variant_change':
				$Variant = new Variant(false);
				$DATA = $Variant->create_next_select( Input::escape($_POST) );
				if( !$DATA ){
					$Variant_Product = new Variant_Product( array( Input::get("code_check"), $PRODUCT_ID ) );
					// bandwidth olayi icin crop details yap urundeki gibi
					$DATA = $Variant_Product->get_details();
				}
				
			break;

			case 'editor':

				$Image = new AH_Editor_Image( Input::get("text"), Input::get("font"), Input::get("old_img") );
				if( !$Image->generate() ){
					$OK = 0;
					$TEXT = $Image->get_return_text();
				} else {
					// inputlar
					$variant_count 	= Input::get("var_count");
					$variant_code  	= Input::get("var_code");
					$var_code_count = 0;
					if( $variant_code != "null" ) $var_code_count = count( explode( "-", $variant_code ) );
					// tum varyantlar seçilmişse fiyat vs işlemleri yapiyoruz
					// tum varyantlari secmeden editorun resmi guncelleyebilir kullanici
					if( $var_code_count > 0 && $variant_count == $var_code_count ){
						$Variant_Product = new Variant_Product( array( $variant_code, $PRODUCT_ID ) );
						// Varyant ürünü de kontrol ediyoruz
						// JS den geldigi icin guvenemiyorum ahah
						if( $Variant_Product->exists() ){
							$Editor_Product = new AH_Editor_Product;
							$Editor_Product->calculate_price( $Variant_Product->get_details("price_1"), $Image->get_letter_count() );
							$PRICE = $Editor_Product->get_price();
							$PRICE_EACH_LETTER = $Variant_Product->get_details("price_1");
						} else {
							$OK = 0;
							$TEXT = "Böyle bir varyant yok";
						}
					}

					$DATA = array(
						"price" 		=> $PRICE,
						"price_each"    => $PRICE_EACH_LETTER,
						"img_src"       => $Image->get_url(),
						"old_img"       => $Image->get_old_img(),
						"letter_count"  => $Image->get_letter_count()
					);
				}

			break;
		}


		$output = json_encode(array(
			"data"         => $DATA, 		 // datatable
			"ok"           => $OK,	    	 // istek tamam mi
			"text" 		   => $TEXT,    	 // bildirim
			"oh"           => $_POST
		));
		echo $output;
		die;

	}