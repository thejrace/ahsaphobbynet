<?php
	
	// Farklı tiplerde resim upload
	require "inc/init.php";

	$TEMPLATE = "upload_picture";

	// Sayfanin bilgilerini al
	$PAGE     = new Page($TEMPLATE);
	$TITLE    = $PAGE->get_title();
	$SUBTITLE = $PAGE->get_subtitle();

	$ROW_HEADER = "Resim yükle";


	if( $_POST ){
		// calisiyor 
		$Upload = new File_Upload;
		$upload_settings = array('target_dir' => RES_IMG_CATEGORY_IMG_DIR, 'file_name'  => 'hudurgan' );
		if( $Upload->set_settings( $upload_settings ) ) {
			if( !$Upload->handle_upload( $_FILES ) ) {
				echo $Upload->get_return_text();
				$OK = 0;
			}
		}
	}
	

	// Header
	include "inc/header.php";

	// Table
	include $PAGE->show_template();

	// Footer
	include "inc/footer.php";