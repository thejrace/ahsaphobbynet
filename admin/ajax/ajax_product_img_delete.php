<?php
	require "../inc/init.php";

	// Ürün ve varyantın resimlerini silen ajax handler
	// Kategori ikonlarida burdan siliyorum

	// tablo reload su an sadece kategori icon silmede var
	$Table_Reload = false;
	// urun ya da varyant
	$TEMPLATE = Input::get("template");
	
	// ürün
	if( $TEMPLATE == "product" ){
		$Item = new Product( Input::get("item_id") );
	// varyant ürün
	} else if( $TEMPLATE == 'variant') {
		$Item = new Variant_Product( Input::get("item_id") );
	// kategori
	} else if( $TEMPLATE == 'category' ){
		$Item = new Category( Input::get("item_id") );
		$Table_Reload = true;
	}

	// Gelen id de item var mi
	// tum silinecek objectlerde ortak method bu
	if( $Item->exists() ) {
		$OK = 1;
		$TEXT = "";

		// silme 
		if( !$Item->delete_picture_file( Input::get("picture_index") ) ){
			$OK = 0;
		}
	} else {
		$OK = 0;
		$TEXT = "Böyle bir ürün yok.";
	}
	// bildirim mesajini classlardan al
	$TEXT = $Item->get_return_text();

	// buffer beybi
	$output = json_encode( array(
		'ok' => $OK,
		'text' => $TEXT,
		'table_reload' => $Table_Reload
	)); 

	echo $output;