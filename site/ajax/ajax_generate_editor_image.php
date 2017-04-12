<?php

	require '../inc/init.php';

	// ajax output degiskenleri
	$DATA  = "";
	$OK    = 1;
	$TEXT  = "";
	$IMGDATA = array();

	// varyant fiyat kontrol

	$Image = new AH_Editor_Image( Input::get("text"), Input::get("font"), Input::get("old_img") );
	if( !$Image->generate() ){
		$OK = 0;
		$TEXT = $Image->get_return_text();
	} else {
		$IMGDATA = array(
			"src" => $Image->get_url(),
			"old_img" => $Image->get_old_img(),
			"letter_count" => $Image->get_letter_count()
		);
		// $DATA = array(
		// 	"price_each_letter" => 
		// 	"final_price" =>
		// );
	}
	// echo '<br><img src="'.$Image->get_url().'" />';

	if( $_POST ){
		$output = json_encode(array(
			"img_data"     => $IMGDATA, 	 // datatable
			"data" 		   => $DATA,
			"ok"           => $OK,	    	 // istek tamam mi
			"text" 		   => $TEXT,    	 // bildirim
			"oh"           => $_POST
		));
		echo $output;
		die;
	}