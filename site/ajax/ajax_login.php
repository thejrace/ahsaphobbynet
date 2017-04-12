<?php
	require '../inc/init.php';

	if( $_POST ){
		// ajax output degiskenleri
		$OK    = 1;
		$TEXT  = "";
		$input_output = array();

		$REDIRECT_TO = MAIN_URL . Input::get("return_url");

		$INPUT_LIST = array(
			"email" 				=> array( array( "req" => true, "email" => true )  ,"" ),
			'pass'  				=> array( array( "req" => true ),  "" )
		);

		$Validation = new Validation( new InputErrorHandler );
		// Formu kontrol et
		$Validation->check_v2( Input::escape($_POST), $INPUT_LIST );

		if( $Validation->failed() ){
			$OK = 0;
			$input_output = $Validation->errors()->js_format();

		} else {

			$Log = new Login( Input::escape($_POST) );
			if( !$Log->success() ){
				$OK = 0;
			} else {
				$User = new User( $Log->get_user_id() );
			}
			
			$TEXT = $Log->get_return_text();

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