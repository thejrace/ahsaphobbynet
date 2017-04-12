<?php
	require '../inc/init.php';

	if( $_POST ){

		// ajax output degiskenleri
		$OK    = 1;
		$TEXT  = "";
		$input_output = array();

		$REDIRECT_TO = MAIN_URL . Input::get("return_url");

		$INPUT_LIST = array(
			"email" 				=> array( array( "req" => true, "email" => true, "unique" => array( DBT_USERS, Input::get("email") ) )  ,"" ),
			'name'  				=> array( array( "req" => true ),  "" ),
			'pass_1'  				=> array( array( "req" => true ),  "" ),
			'pass_2'  				=> array( array( "req" => true, "matches" => "pass_1" ),  "" )
		);


		$Validation = new Validation( new InputErrorHandler );
		// Formu kontrol et
		$Validation->check_v2( Input::escape($_POST), $INPUT_LIST );

		if( $Validation->failed() ){
			$OK = 0;
			$input_output = $Validation->errors()->js_format();
		} else {
			$Reg = new Register( Input::escape($_POST) );
			if( !$Reg->success() ) $OK = 0;
			$TEXT = $Reg->get_return_text() . " " . Input::get("email") . " adresine aktivasyon epostası gönderildi.";
		}

		$output = json_encode(array(
			"ok"           => $OK,	    	 // istek tamam mi
			"text" 		   => $TEXT,    	 // bildirim
			"inputret"     => $input_output, // form input errorlari,
			"redirect"     => $REDIRECT_TO,
			"oh"           => $TEXT
		));

		echo $output;
		die;

	}