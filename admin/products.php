<?php

	require "inc/init.php";

	$TEMPLATE = "products";
	$AJAX_REQ = "ajax/ajax_products.php";

	// Sayfanin bilgilerini al
	$PAGE     = new Page($TEMPLATE);
	$TITLE    = $PAGE->get_title();
	$SUBTITLE = $PAGE->get_subtitle();

	// Default datatable ayarlari
	$DTSettings = array( "page" => 1, "direction" => "ASC", "rrp" => 50, "orderby" => "id" );
	// Datatable init
	$Product_Table = new DataTable($TEMPLATE);
	$Product_Table->set_settings( $DTSettings );

	// Arama icin kategori select
	// Alt kategori ürünleri bulmak için ( CURRENT )
	$C_Tree = new Category_Tree;

	// DB den datayi al
	$Fetch = new Fetch($TEMPLATE);

	// row header
	$ROW_HEADER = "Tüm ürünler";

	// Kategorilerden gelindiyse getle gelen kategorinin
	// ürünlerini listele
	if( Input::get("katid") != "" ) {
		$CURRENT = Input::get("katid");
		$Category = new Category( $CURRENT );
		if( $Category->exists() ) {
			// aramadaki kategori selecti seçili yap, asagida
			// create() fonksiyonu calistiriyorum query icin
			// CTree->list degisiyor, o yuzden en ustte bunu yapiyorum
			$SEARCH_CATEGORIES = $C_Tree->categories_select_menu_selected( $CURRENT );
			// Asıl kategori ve alt kategorisi olanlarinda urunlerini listelemek icin
			// alt kategorileri de buluyorum
			$C_Tree->create( $CURRENT, true );
			$cat_count = count( $C_Tree->get_list() );
			// row header x kategorisi ürünleri
			$ROW_HEADER = "'". $Category->get_details("category_name") . "' kategorisi ürünleri";
			// Her alt kategorini dahil edebilecegim sql cumlesini olusturuyorum ( Where category = ? || category = ? ) vs..
			$Fetch->get_data( "search", $Product_Table->get_settings(), array( " WHERE " . Common::array_to_sql( $cat_count, 'category', '||')  , $C_Tree->get_list() ) );
		} else {
			// Eğer gecersiz kategori idsi geldiyse tümünü listele
			$Fetch->get_data( "default", $Product_Table->get_settings(), false );
		}
	} else {
		$CURRENT = "";
		// Default kategori select
		$SEARCH_CATEGORIES = $C_Tree->categories_select_menu_default();
		// Default, getsiz tüm ürünleri listele
		$Fetch->get_data( "default", $Product_Table->get_settings(), false );
	}

	$Product_Table->create( $Fetch->get_results(), $Fetch->get_record_count() );
	$DATA_TABLE = $Product_Table->show_table(); 
	$PAGIN = $Product_Table->show_pagination(); 

	


	// print_r( Session::get("hunb123ar") );
	// print_r( Session::get("hederoy") );

	// Header
	include "inc/header.php";

	// Table
	include $PAGE->show_template();

	// Footer
	include "inc/footer.php";
