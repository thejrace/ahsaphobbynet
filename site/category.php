<?php

	require "inc/init.php";
	$Page = new Page("web_category");
	// $TITLE = $Page->get_title();

	$TEMPLATE = 'category';
	$AJAX_REQ = SITE_AJAX_URL . 'ajax_category.php';

	// giris kontrolleri
	if( Input::get('katid') == '' ) header('Location: index.php');
	$Category = new Category( Input::get('katid') );
	if( !$Category->exists() ) header('Location: index.php');

	$CATEGORY_DATA = $Category->get_details();

	// Alt kategori vitrin html i
	$C_Tree = new Category_Tree;
	$C_Tree->create_level_one( $Category->get_details("id"), false );
	$C_Tree->create_sub_categories_page();
	$SUB_CATEGORY_SHOWCASE = $C_Tree->show_html();
	unset($C_Tree);

	// default listeleme ayarlari
	$List_Settings = array( "page" => 1, "direction" => "ASC", "rrp" => 20, "orderby" => "id" );
	$Product_List = new Product_List( Input::get('katid') );
	$Product_List->set_settings( $List_Settings );
	$Product_List->create();

	// template degiskenleri
	$CATEGORY_NAME = $CATEGORY_DATA['category_name'];
	$LIST = $Product_List->show();
	$PAGIN = $Product_List->get_pagination();
	// meta ve og taglari
	$TITLE 			 = $CATEGORY_DATA["category_name"];
	$TAG_TITLE       = $CATEGORY_DATA["title"];
	$TAG_KEYWORDS    = $CATEGORY_DATA["tags"];
	$TAG_DESCRIPTION = $CATEGORY_DATA["details"];
	// breadcrumb olustur
	$BC = new Breadcrumb( array( 'type' => $TEMPLATE, 'item_id' => $CATEGORY_DATA['id'] ) );
	$BREADCRUMB = $BC->get();

	// Header
	include HEADER_TEMP;

	// Table
	include $Page->show_template();

	// // Footer
	include FOOTER_TEMP;