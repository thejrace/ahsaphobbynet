<?php

	require "../inc/init.php";

	if( $_POST ){


		// Direk veritabanınından data almadigim icin
		// normal FETCH, DATATABlE mantıgı yok bunda

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


		// Showcase init
		// category_id boşsa yeni veya anasayfa vitrini
		$Showcase = new Showcase( Input::get("showcase_type"), Input::get("category_id") );

		// Datatable init
		$Table = new DataTable("showcase");
		$Table->set_settings( array() );
	
		switch( Input::get("type") ){
			case 'save_showcase':
				// $Showcase = new Showcase( Input::get("showcase_type"), "" );
				if( $Showcase->save_showcase( Input::get("u") ) ){
					$TEXT = "Değişiklikler kaydedildi.";
				} else {
					$OK = false;
				}

			break;
		}

		if( $ReloadTable ) {
			// Vitrindeki urunleri listeleyip, tabloyu olusturu
			$Showcase->get_showcase_data();
			$SC_Data = $Showcase->get_products();
			// Datatable init					
			$Table->create( $SC_Data, count($SC_Data) );
			$DATA  = $Table->show_table();
			$PAGIN = $Table->show_pagination();
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