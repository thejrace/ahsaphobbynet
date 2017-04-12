<?php

	require "../inc/init.php";
	require CLASS_DIR . "DB.php";
	require CLASS_DIR . "DBTable.php";
	require CLASS_DIR . "DataTable.php";
	require CLASS_DIR . "Fetch.php";
	require CLASS_DIR . "Pagination.php";
	require CLASS_DIR . "Input.php";
	require CLASS_DIR . "DataTable_Products.php";
	require CLASS_DIR . "Category_Tree.php";
	require CLASS_DIR . "Product.php";
	require CLASS_DIR . "Validation.php";
	require CLASS_DIR . "InputErrorHandler.php";

	if( $_POST ){

		// Default datatable ayarlari
		$DTSettings = array( "page" => 1, "direction" => "ASC", "rrp" => 5, "orderby" => "id" );
		// Ajax return degiskenleri
		$DATA  = "";
		$PAGIN = "";
		$OK    = true;
		$TEXT  = "";

		// Fetch icin gerekli parametreler default ayar bu
		// Sadece search icin degisiyorlar
		$FetchType = "reload";
		$FetchSQL  = false;

		// Al datalari
		$Fetch->get_data( $FetchType, $DTSettings, $FetchSQL );
		// Datatable init					
		$Product_Table->create( $Fetch->get_results(), $Fetch->get_record_count(), $DTSettings );
		$DATA  = $Product_Table->show_table();
		$PAGIN = $Product_Table->show_pagination();

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