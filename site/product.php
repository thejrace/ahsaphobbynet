<?php

	require "inc/init.php";
	$Page = new Page("web_product");
	// $TITLE = $Page->get_title();

	// ajax degiskenleri
	$TEMPLATE = 'product';
	$AJAX_REQ = SITE_AJAX_URL . 'ajax_product.php';
	$EDITOR_REQ = SITE_AJAX_URL . 'ajax_generate_editor_image.php';
	// giris kontrolleri
	if( Input::get('pid') == '' ) header('Location: index.php');
	$Product = new Product( Input::get('pid') );
	if( !$Product->exists() ) header('Location: index.php');

	$PRODUCT_DATA = $Product->get_details();

	// Meta taglar ve template degiskenleri
	$TITLE 			 = $PRODUCT_DATA["product_name"];
	$TAG_TITLE       = $PRODUCT_DATA["seo_title"];
	$TAG_KEYWORDS    = $PRODUCT_DATA["seo_keywords"];
	$TAG_DESCRIPTION = $PRODUCT_DATA["seo_details"];

	// resim slider
	$SLIDER = $Product->create_product_page_slider_template();

	// variant select default template
	$VARIANTS = $Product->create_product_page_variant_template();
	// js icin
	$VARIANT_COUNT = count( $Product->get_product_variant_defined() );

	$Cat = new Category($PRODUCT_DATA["category"]);
	$CATEGORY_NAME = $Cat->get_details("category_name");

	// breadcrumb olustur
	$BC = new Breadcrumb( array( 'type' => $TEMPLATE, 'item_id' => $PRODUCT_DATA['category'], 'item_name' => $PRODUCT_DATA['product_name'] ) );
	$BREADCRUMB = $BC->get();

	// Header
	include HEADER_TEMP;

	// ?content_id => cart_contents deki ID

	// varcode icin variant-product var mi kontrol
	// eger olmayan bir kod giderse js sacmalamasin diye
	$VARIANT_ARRAY = array();
	$CART_CONTENT_ID = 0;
	if( Input::get("varcode") != "" ){
		$Variant_Product = new Variant_Product( array( Input::get("varcode"), Input::get("pid") ) );
		if( $Variant_Product->exists() ){
			$VARIANT_ARRAY = explode( "-", Input::get("varcode") );
		}
		// cart content de kontrol edilecek
		$CART_CONTENT_ID = Input::get("content_id");
	}
	$JS_EDIT_VAR_CODE = Common::array_php_to_js( "edit_var_code", $VARIANT_ARRAY );

	// Table
	include $Page->show_template();

	// // Footer
	include FOOTER_TEMP;