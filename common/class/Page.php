<?php

class Page {

	protected $list = array(
		"products" => array(
			"title"    => "Ürünler",
			"subtitle" => "Ürünler",
			"template" => "template_admin_products.php"
		),
		"start" => array(
			"title" => "Başlangıç",
			"subtitle" => ""
		),
		"add_product" => array(
			"title"    => "Ürün Ekle",
			"subtitle" => "Ürünler",
			"template" => "template_admin_add_product.php"
		),
		"edit_product" => array(
			"title" => "Ürün Düzenle",
			"subtitle" => "Ürünler",
			"template" => "template_admin_edit_product.php"
		),
		"categories" => array(
			"title" => "Kategoriler",
			"subtitle" => "Ürünler",
			"template" => "template_admin_categories.php"
		),
		"variants" => array(
			"title"    => "Varyant Sistemi",
			"subtitle" => "Ürünler",
			"template" => "template_admin_variants.php"
		),
		"sub_variants" => array(
			"title"    => "Alt Varyantlar",
			"subtitle" => "Ürünler",
			"template" => "template_admin_sub_variants.php"
		),
		"add_variant" => array(
			"title"    => "Varyant Ekle",
			"subtitle" => "Ürünler"
		),
		"showcase" => array(
			"title" => "Vitrin",
			"subtitle" => "Ürünler",
			"template" => "template_admin_showcase.php"
		),
		"variant_products" => array(
			"title" => "Ürün Varyantları",
			"subtitle" => "Ürünler",
			"template" => "template_admin_variant_products.php"
		),
		"edit_variant_product" => array(
			"title" => "Varyant Detaylı Düzenle",
			"subtitle" => "Ürünler",
			"template" => "template_admin_edit_variant_product.php"
		),
		"upload_picture" => array(
			"title" => "Resim Yükle",
			"subtitle" => "",
			"template" => "template_admin_upload_picture.php"
		),
		"web_index" => array(
			"title" => "AhsapHobby - Hobi & Sanatsal",
			"subtitle" => "Anasayfa",
			"template" => "template_site_index.php"
		),
		"web_category" =>  array(
			"title" => "AhsapHobby - Hobi & Sanatsal | Kategoriler",
			"subtitle" => "Anasayfa",
			"template" => "template_site_category.php"
		),
		"web_product" =>  array(
			"title" => "AhsapHobby - Hobi & Sanatsal | Ürün İncele",
			"subtitle" => "Ürün",
			"template" => "template_site_product.php"
		),
		"login" =>  array(
			"title" => "AhsapHobby - Hobi & Sanatsal | Giriş",
			"subtitle" => "Giriş",
			"template" => "template_site_login.php"
		),
		"register" =>  array(
			"title" => "AhsapHobby - Hobi & Sanatsal | Kayıt",
			"subtitle" => "Kayıt",
			"template" => "template_site_register.php"
		),
		"deleted_products" =>  array(
			"title" => "Silinmiş Ürünler",
			"subtitle" => "Ürünler",
			"template" => "template_admin_deleted_products.php"
		),
		"users" =>  array(
			"title" => "Üyeler",
			"subtitle" => "Kullanıcılar",
			"template" => "template_users.php"
		)

	);
	protected $page, $title, $subtitle;

	// Burda language ayarinidi al ona gore sub ve titlelari istenen dilde gonder
	public function __construct( $p ){
		$this->page = $this->list[$p];
		$this->title = $this->page["title"];
		$this->subtitle = $this->page["subtitle"];
	}

	public function show_template(){
		return TEMPLATE_DIR . $this->page["template"];
	}

	public function get_title(){
		return $this->title;
	}
	public function get_subtitle(){
		return $this->subtitle;
	}

}