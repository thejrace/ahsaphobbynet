<?php

	require_once "init.php";

	// if(!Admin_Main::ALOGIN_KONTROL_FLAG()){
	// 	if( $_SERVER["SCRIPT_NAME"] != "/admin/index.php" ){
	// 		header("HTTP/1.1 403 Forbidden");
	// 		die("Forbidden<script type='text/javascript'>setTimeout(function(){window.location.href = 'http://ahsaphobby.net/dev/admin/giris-yap'; }, 1000);</script>");
	// 	} else {
	// 		header("Location: giris-yap");
	// 	}
		
	// }

	
	$navMenu = array(

		0 => array(
			"baslik" => "Başlangıç",
			"ico"    => "menu-ico-baslangic",
			"url"    => "index.php",
			"tree"   => false
		),
		1 =>  array( 
			"baslik" => "Ürünler",
			"ico"    => "menu-ico-baslangic",
			"url"    => "",
			"tree"   => true,
			"sub"    => array(
				1 => array(
					"baslik" => "Ürünler",
					"url"    => "products.php"
				),
				2 => array(
					"baslik" => "Ürün Ekle",
					"url"    => "add_product.php"
				),
				3 => array(
					"baslik" => "Kategoriler",
					"url"    => "categories.php"
				),
				4 => array(
					"baslik" => "Vitrin",
					"url"    => "showcase.php"
				),
				5 => array(
					"baslik" => "Varyant Sistemi",
					"url"    => "variants.php"
				),
				6 => array(
					"baslik" => "Silinmiş Ürünler",
					"url"    => "deleted_products.php"
				)
			)
		),

		2 => array(
			"baslik" => "Kullanıcılar",
			"ico"    => "menu-ico-ayar",
			"url"    => "",
			"tree"   => true,
			"sub"    => array(
				1 => array(
					"baslik" => "Üyeler",
					"url"    => "users.php"
				),
				2 => array(
					"baslik" => "İstatistikler",
					"url"    => "tema.php"
				)
			)		
		),

		3 => array(
			"baslik" => "Site Ayarları",
			"ico"    => "menu-ico-ayar",
			"url"    => "app/ayarlar/",
			"tree"   => true,
			"sub"    => array(
				1 => array(
					"baslik" => "Genel Ayarlar",
					"url"    => "genel.php"
				),
				2 => array(
					"baslik" => "Tema Ayarları",
					"url"    => "tema.php"
				),
				3 => array(
					"baslik" => "Veritabanı Ayarları",
					"url"    => "veritabani.php"
				),
				4 => array(
					"baslik" => "Dosya Yönetimi",
					"url"    => "dosya-yonetimi.php"
				),
				5 => array(
					"baslik" => "SEO Ayarları",
					"url"    => "seo.php"
				),
				6 => array(
					"baslik" => "Ödeme Ayarları",
					"url"    => "odeme-ayarlari.php"
				)
			)		
		)

	);


	function headerNav($array, $title, $titleSub){
		$html = "";

				// Array'ın başlık kısmıyla, sayfanını $titleSub aynı olmazsa aktif class eklenmez.


		foreach($array as $key => $val){

					// Aktif olan sayfanını nav ına eklenen aktif class
			$classTree = "";
					// Eğer sayfa başlıkları tutuyorsa aktif class 'ı ekle
			if($titleSub == $val["baslik"]){
				$classTree = "aktif";
			}

					// Eğer menünün alt navları varsa farklı html yazdırıyoruz.
			if($val["tree"]){

				$html .= '

				<li class="tree-menu '.$classTree.'">
					<a href="" class="menu-item">
						<p class="'.$val["ico"].'">'.$val["baslik"].'<i class="nav-ok-sag"></i></p>
					</a>
					<ul class="sub-tree">

						';

						// Her sub menü için ul.sub-tree nin altına <li> 'ler ekliyoruz.
						// Eğer aktifse gene aynı şekilde $cc class ını yazdırıyoruz.
						foreach($val["sub"] as $sub){
							

							$cc = "";
							
							if($title == $sub["baslik"]){
								$cc = 'class="aktif"';
							}

							$html .= '	

							<li '.$cc.'><a href="'. ADMIN_URL . $val["url"] . $sub["url"] .'">'.$sub["baslik"].'</a></li>
							';
						}
						
						$html .= '</ul></li>';

					} else {

						// Sub menüsü olmayan navlar için direk <li> yazdırıyoruz.
						$html .= '

						<li class="'.$classTree.'"><a href="'. ADMIN_URL .$val["url"].'" class="menu-item">
							<p class="'.$val["ico"].'">'.$val["baslik"].'</p>
						</a>
					</li>

					';

				}


			}

			return $html;

	}

	



?>

<!DOCTYPE html>
<html>
    <head>
    	<meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- IE render en son versiyona gore -->
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1">

        <title>Admin</title>

        <link rel="stylesheet" type="text/css" href="<?php echo RES_CSS_URL ?>reset.css" />
        <!--[if lt IE 9]>
			<link rel="stylesheet" type="text/css" href="<?php echo RES_CSS_URL ?>admin-ie.css" />
			<link rel="stylesheet" type="text/css" href="<?php echo RES_CSS_URL ?>form_elements-ie.css" />
		<![endif]-->
        <link rel="stylesheet" type="text/css" href="<?php echo RES_CSS_URL ?>admin.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo RES_CSS_URL ?>form_elements.css" />
        <script src="<?php echo RES_JS_URL ?>common.js"></script>
        <!--<script src="<?php echo RES_JS_URL ?>jquery.js"></script>-->
        <script src="<?php echo RES_JS_URL ?>main_admin.js"></script>



        <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
		<link rel="icon" type="image/png" href="/android-chrome-192x192.png" sizes="192x192">
		<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon-180x180.png">

		<meta property="og:title" content="AhsapHobby Hobi & Sanatsal">
	    <meta property="og:type" content="website">
	    <meta property="og:url" content="http://ahsaphobby.net/">
	    <meta property="og:site_name" content="AhsapHobby.net">
	    <meta property="og:description" content="Ahsap, pleks hobi urunleri ve malzemeler.">

<!-- 	    <meta name="twitter:card" content="summary_large_image">
	    <meta name="twitter:site" content="@realfavicon">
	    <meta name="twitter:creator" content="@realfavicon">
	    <meta name="twitter:title" content="Your generated favicon">
	    <meta name="twitter:description" content="Your favicon is ready. Download the pictures, copy the HTML code in your pages and you're done.">
	    <meta name="twitter:image:src" content="http://realfavicongenerator.net/favicon_generator_twitter_card.png"> -->

		
    </head>
    <body>
    <div id="popup-overlay"></div>
    <div id="popup"></div>
    <div id="ajax-notf"></div>
    

    <div class="wrapper">   

    	 <div class="header">

	    	<div class="logo">
	    		<img src="<?php echo RES_IMG_STATIC_URL ?>header_logo.png" alt="jesterweb" />
	    	</div>
	    	<a href="" class="sol-icerik-toggle"><img src="<?php echo RES_IMG_STATIC_URL ?>nav_toggle.png" /></a>

	    	<div class="header-bildirim-bg">
	    		<ul class="header-bildirim">
	    			<li>
	    				<a href="" class="header-bildirim-ico"><p class="mesaj-ico"></p>
	    					<span class="header-bildirim-sayi">99</span>
	    				</a>
	    			</li>
	    			<li>
	    				<a href="" class="header-bildirim-ico"><p class="hareket-ico"></p>
	    					<span class="header-bildirim-sayi">99</span>
	    				</a>
	    				
	    			</li>
	    			<li>
	    				<a href="" class="header-bildirim-ico"><p class="bildirim-ico"></p>
	    					<span class="header-bildirim-sayi">99</span>
	    				</a>

	    			</li>
	    		</ul>
	    	</div>

	    </div>


    	<div class="content-cont">
			<div class="sol-icerik">
				<div class="nav-user">
					<img class="avatar" src="<?php echo RES_IMG_STATIC_URL ?>avatar.png" />
					<div class="info">
						<span class="nav-username">Jester Race</span>
						<span class="nav-logout"><a href="">çıkış yap</a></span>
					</div>
				</div> 

				<div class="nav-menu">
					<ul>
						<?php $h = headerNav($navMenu, $TITLE, $SUBTITLE); echo $h; ?>
					</ul>
				</div>

		    </div><!-- ASIDE SOL -->

	   		<div class="sag-icerik">
	   			<div class="sayfa-baslik">
	   				<h2><?php echo $TITLE;  ?> <small><?php echo $SUBTITLE; 

	   				?></small></h2>
	   			</div>

	   			<div class="sayfa-icerik">