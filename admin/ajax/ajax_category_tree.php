<?php
	
	require "../inc/init.php";
	// require CLASS_DIR . "DB.php";
	// require CLASS_DIR . "Category_Tree.php";
	// require CLASS_DIR . "Input.php";

	if( $_POST ){
		// Arama formunda bulunan kategori select i icin
		// Urun eklerken
		$C_Tree = new Category_Tree;

		$output = json_encode(array(
			"data" => $C_Tree->categories_select_menu_update( Input::get("data") ),
			"oh"   => ""
		));

		echo $output;
		die;
	}