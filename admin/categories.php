<?php

	require "inc/init.php";

	$TEMPLATE = "categories";
	$AJAX_REQ = "ajax/ajax_categories.php";
	$POST_OUTPUT = "";
	$OK = 1;

	if( $_POST ){

		// kategori ikon upload
		if( Input::get('type') == 'upload_icon' && Input::get('item_id') != '' ){
			// Dosya upload icin yeni class
			$Upload = new File_Upload;
			$upload_settings = array(
				'target_dir' => RES_IMG_CATEGORY_IMG_DIR,
				'file_name'  => 'category-' . Input::get('item_id'),
				'resize'     => true,
				'image_x_ratio' => true,
				'height' => 117
			);
			
			if( $Upload->set_settings( $upload_settings ) ) {
				if( $Upload->handle_upload( $_FILES ) ) {
					$Category = new Category( Input::get('item_id') );
					if( $Category->part_edit( array( 'icon' => 1 ) ) ) {
						// hersey tamam
						$POST_OUTPUT = $Category->get_return_text();
					}
				// upload da bi sıkıntı oldu	
				} else {
					$POST_OUTPUT = $Upload->get_return_text();
					$OK = 0;
				}
			}
			// isim bitti class'ı unset et
			if( isset($Category)) unset($Category);
		}
	}

	// Sayfanin bilgilerini al
	$PAGE     = new Page($TEMPLATE);
	$TITLE    = $PAGE->get_title();
	$SUBTITLE = $PAGE->get_subtitle();

	// Default datatable ayarlari
	$DTSettings = array( "page" => 1, "direction" => "ASC", "rrp" => 30, "orderby" => "id" );
	// Datatable init
	$Table = new DataTable($TEMPLATE);
	$Table->set_settings( $DTSettings );

	// DB den datayi al
	$Fetch = new Fetch($TEMPLATE);

	// Ana kategoriler default olarak ayarli
	// Get varsa altlari listeliyorum
	// Kategorinin varolup olmadigini da kontrol ediyoruz tabi
	// Yoksa ana kategorileri listele
	$CURRENT = "";
	$CATEGORY_HEADER = " Ana Kategoriler ";
	if( Input::get("katid") != "" )
		$CURRENT = Input::get("katid");
		$Category = new Category( $CURRENT );
		if( !$Category->exists() ) {
			$CURRENT = "";
		} else {
			// Alt kategoriler gösterilirken info ve üst kategoriye dönme linki
			// Row header' a yazdiriyorum.
			$CatDetails = $Category->get_details();
			$CATEGORY_HEADER = $CatDetails["category_name"] . " alt kategorileri. <a href='categories.php?katid=".$CatDetails["parent"]."'>[ Üst kategoriye dön ]</a> ";
		}
		
	$Fetch->get_data( "search", $Table->get_settings(), array( " WHERE parent = ?", array( $CURRENT )  ) );

	$Table->create( $Fetch->get_results(), $Fetch->get_record_count() );
	$DATA_TABLE = $Table->show_table(); 
	$PAGIN = $Table->show_pagination(); 


	// Header
	include "inc/header.php";

	// Table
	include $PAGE->show_template();

	// Footer
	include "inc/footer.php";