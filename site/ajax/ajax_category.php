<?php

	require '../inc/init.php';

	if( $_POST ){
		// ajax output degiskenleri
		$DATA  = "";
		$PAGIN = "";
		$OK    = 1;
		$TEXT  = "";

		// guncellenmis listeyi olustur
		$Product_List = new Product_List( Input::get('katid') );
		// filtre ayarlarini al
		$Product_List->set_filters( Input::escape($_POST) );
		// goruntuleme ayarlari
		$Product_List->set_settings( Input::escape($_POST) );
		$Product_List->create();

		// gonder gitsin
		$DATA  = $Product_List->show();
		$PAGIN = $Product_List->get_pagination();


		$output = json_encode(array(
			"data"         => $DATA, 		 // datatable
			"pagin"        => $PAGIN,   	 // table sayfalam
			"ok"           => $OK,	    	 // istek tamam mi
			"text" 		   => $TEXT,    	 // bildirim
			"oh"           => $Product_List->get_settings()//$_POST
		));
		echo $output;
		die;
	}