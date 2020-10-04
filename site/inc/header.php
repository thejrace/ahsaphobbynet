<?php
	/*$Meta_Tags = new Meta_Tags;
	// eger aktif sayfalarda ekstra taglar tanimlanmamisa
	// hata vermemesi icin bos string yap
	if( !isset( $TAG_TITLE ) && !isset( $TAG_KEYWORDS ) && !isset( $TAG_DESCRIPTION ) ){
		$TAG_TITLE       = "";
		$TAG_KEYWORDS    = "";
		$TAG_DESCRIPTION = "";
	}
	// taglari olustur
	$Meta_Tags->create_tags( array( 'title' => $TAG_TITLE, 'keywords' => $TAG_KEYWORDS, 'description' => $TAG_DESCRIPTION ) );

	$META = $Meta_Tags->get_meta();
	$OG   = $Meta_Tags->get_og();*/


	$RETURN_URL = substr( SITE_URL . substr( $_SERVER["REQUEST_URI"], 1 ), 33 );

	// header menuler icin
	$C_Tree = new Category_Tree;


	// echo Guest::get_id();

?>
<!DOCTYPE html>
<html>
    <head>
    	<meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- IE render en son versiyona gore -->
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1">

        <link rel="stylesheet" type="text/css" href="<?php echo MAIN_URL ?>res/css/reset.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo MAIN_URL ?>res/css/ahsaphobby.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo MAIN_URL ?>res/css/form_elements.css" />
        <!--[if lt IE 9]>
			<link rel="stylesheet" type="text/css" href="<?php echo MAIN_URL ?>res/css/ahsaphobby-ie.css" />
		<![endif]-->
		<script src="<?php echo MAIN_URL ?>res/js/common.js"></script>
        <!-- <script src="http://ahsaphobbynet.test/res/js/jquery.js"></script> -->
        <script src="<?php echo MAIN_URL ?>res/js/main.js"></script>
        <script src="<?php echo MAIN_URL ?>res/js/main_admin.js"></script>
       <!--  <script type="text/javascript" src="http://ahsaphobby.net/resources/js/prototype.js"></script> -->
        <!-- <meta name='description' content="<?php echo $META['description'] ?>">
		<meta name='keywords' content="<?php echo $META['keywords'] ?>">
		<meta name='title' content="<?php echo $META['title'] ?>">

		<<meta property="og:title" content="<?php echo $OG['title'] ?>">
		<meta property='og:keywords' content="<?php echo $OG['keywords'] ?>">
	    <meta property="og:type" content="<?php echo $OG['type'] ?>">
	    <meta property="og:url" content="<?php echo $OG['url'] ?>">
	    <meta property="og:site_name" content="<?php echo $OG['site_name'] ?>">
	    <meta property="og:description" content="<?php echo $OG['description'] ?>"> -->

       	<link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
		<link rel="icon" type="image/png" href="/android-chrome-192x192.png" sizes="192x192">
		<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon-180x180.png">

        <title><?php echo $TITLE; ?></title>
    </head>
    <body>
    				
    	<div id="popup-overlay"></div>
    	<div id="popup" ></div>
    	<div class="wrapper">

    		<div class="page">
			    
			    <div class="main-header">


			    	<?php if( $LOGGEDIN ){ ?>

			    	<div class="nav-header-top-cont clearfix">
			    		<div class="container">

			    		<!-- MOBIL  -->
			    			<div class="mobile-header clearfix">
					    		
					    		<div class="mobile-home">
					    			<button class="btn-ico" id="btn-mobile-home">
					    				<span></span>
					    			</button>
					    		</div>

					    		<div class="mobile-logo-cont">
					    			<a href="" class="mobile-logo"></a>
					    		</div>
					    		<div class="mobile-buton-cont">
					    			
					    			<ul tabdiv="mobile-tab" class="tab state-btn clearfix">
										<li class="tab-btn fleft btn-ico btn-toggle" id="btn-mobile-menu"><span></span></li>
										<li class="tab-btn fleft btn-ico btn-toggle" id="btn-mobile-search"><span></span></li>
										<li class="tab-btn fleft btn-ico btn-toggle show-cart" id="btn-mobile-cart"><span></span></li>
									</ul>

					    		</div>

					    		<div class="mobile-tab mobile-menu clear">
					    			<ul>
					    				<li>
					    					<a href="" class="main-dropdown-btn has-sub">
					    						Ürünler
					    					</a>
					    					<ul class="header-full main-cat">
					    						<?php
					    							
					    							$C_Tree->create_mobile_menu();

					    							echo $C_Tree->show_html();

					    						?>
					    					</ul>
					    				</li>
					    				<li><a href="">Blog</a></li>
					    				<li>
					    					<a href="" class="main-dropdown-btn has-sub">
					    						Hesabım
					    					</a>

											<ul class="header-full main-cat">
												<li><a href="">Hesap Ayarlarım</a></li>
												<li><a href="">Listelerim</a></li>
												<li><a href="<?php echo LOGOUT_URL ?>?return_url=<?php	echo $RETURN_URL ?>">Çıkış Yap</a></li>
											</ul>				    					
					    					
					    				</li>
					    			</ul>
					    		</div>
					    		<div class="mobile-tab mobile-search clear">
					    			<div class="input-control">
										<input type="text" class="fleft" name="search_query" id="search" placeholder="Arama..." />
										<button type="button" class="fleft">ARA</button>
									</div>
					    		</div>
					    		<div class="mobile-tab mobile-cart clear">
					    			<div class="cart-container">
					    				<div class="header-section clearfix">
					    					<div class="cart-notf"></div>
					    				</div>
					    				<div class="cart-list">
					    					<ul class="cart-list-ul">
					    					</ul>
					    				</div>
					    				<div class="bottom-section">
					    					<div class="cart-summary"><span>Toplam : <span style="display:inline" class="cart-total">0</span></span></div>
					    					<div class="cart-buttons">
					    						<a href="" class="cart-btn checkout">SİPARİŞ VER</a>
					    						<a href="" class="cart-btn gotocart">SEPETE GİT</a>
					    					</div>
					    				</div>
					    			</div>
					    		</div>
					    		
					    	</div>
					    <!-- MOBIL  -->


			    			<ul class="header-links clearfix">
			    				<li>
			    					<a href="" >
			    						<span class="fs-11">İndirimdekiler</span>
			    					</a>
			    				</li>
			    				<li>
			    					<a href="" >
			    						<span class="fs-11">Ücretsiz Kargolu ürünler</span>
			    					</a>
			    				</li>
			    				<li>
			    					<a href="" >
			    						<span class="fs-11">Çok Satanlar</span>
			    					</a>
			    				</li>
			    			</ul>

			    			<ul class="header-user-cont clearfix">

			    				<li>
			    					<!-- LOGIN KONTROL -->
			    					<a href="" class="dropdown-btn ">

			    						<span class="fs-13" ><?php echo Session::get("user_name") ?></span>
			    						<i class="small ok-down-bold" ></i>
			    					</a>
			    					<ul class="dropdown-menu" id="dropdown-uye">
			    						<li><a href="">Hesabım</a></li>
			    						<li><a href="">Siparişlerim</a></li>
			    						<li><a href="<?php echo LOGOUT_URL ?>?return_url=<?php	echo $RETURN_URL ?>">Çıkış Yap</a></li>
			    					</ul>
			    				</li>


					<?php } else { ?>
			    		
			    	<div class="nav-header-top-cont clearfix">
			    		<div class="container">

			    		<!-- MOBIL  -->
			    			<div class="mobile-header clearfix">
					    		
					    		<div class="mobile-home">
					    			<button class="btn-ico" id="btn-mobile-home">
					    				<span></span>
					    			</button>
					    		</div>

					    		<div class="mobile-logo-cont">
					    			<a href="" class="mobile-logo"></a>
					    		</div>
					    		<div class="mobile-buton-cont">
					    			
					    			<ul tabdiv="mobile-tab" class="tab state-btn clearfix">
										<li class="tab-btn fleft btn-ico btn-toggle" id="btn-mobile-menu"><span></span></li>
										<li class="tab-btn fleft btn-ico btn-toggle" id="btn-mobile-search"><span></span></li>
										<li class="tab-btn fleft btn-ico btn-toggle show-cart" id="btn-mobile-cart"><span></span></li>
									</ul>
					    		</div>

					    		<div class="mobile-tab mobile-menu clear">
					    			<ul>
					    				<li>
					    					<a href="" class="main-dropdown-btn has-sub">
					    						Ürünler
					    					</a>
					    					<ul class="header-full main-cat">
					    						<?php
					    							
					    							$C_Tree->create_mobile_menu();

					    							echo $C_Tree->show_html();

					    						?>
					    					</ul>
					    				</li>
					    				<li><a href="">Blog</a></li>
					    				<li>
					    					<a href="" class="main-dropdown-btn has-sub">
					    						Hesabım
					    					</a>
											<ul class="header-full main-cat">
												<li><a href="<?php echo LOGIN_URL ?>?return_url=<?php	echo $RETURN_URL ?>">Giriş Yap</a></li>
								    			<li><a href="<?php echo REGISTER_URL ?>?return_url=<?php	echo $RETURN_URL ?>">Kayıt Ol</a></li>
											</ul>				    					
					    					
					    				</li>
					    			</ul>
					    		</div>
					    		<div class="mobile-tab mobile-search clear">
					    			<div class="input-control">
										<input type="text" class="fleft" name="search_query" id="search" placeholder="Arama..." />
										<button type="button" class="fleft">ARA</button>
									</div>
					    		</div>
					    		<div class="mobile-tab mobile-cart clear">
					    			<div class="cart-container">
					    				<div class="header-section clearfix">
					    					<div class="cart-notf"></div>
					    				</div>
					    				<div class="cart-list">
					    					<ul class="cart-list-ul">
					    					</ul>
					    				</div>
					    				<div class="bottom-section">
					    					<div class="cart-summary"><span>Toplam : <span style="display:inline" class="cart-total">0</span></span></div>
					    					<div class="cart-buttons">
					    						<a href="" class="cart-btn checkout">SİPARİŞ VER</a>
					    						<a href="" class="cart-btn gotocart">SEPETE GİT</a>
					    					</div>
					    				</div>
					    			</div>
					    		</div>

					    	</div>
					    <!-- MOBIL  -->


			    			<ul class="header-links clearfix">
			    				<li>
			    					<a href="" >
			    						<span class="fs-11">İndirimdekiler</span>
			    					</a>
			    				</li>
			    				<li>
			    					<a href="" >
			    						<span class="fs-11">Ücretsiz Kargolu ürünler</span>
			    					</a>
			    				</li>
			    				<li>
			    					<a href="" >
			    						<span class="fs-11">Çok Satanlar</span>
			    					</a>
			    				</li>
			    			</ul>

			    			<ul class="header-user-cont clearfix">

			    				<li>
			    					<a href="" class="dropdown-btn ">

			    						<span class="fs-13" >Kullanıcı</span>
			    						<i class="small ok-down-bold" ></i>
			    						</a>
			    					<ul class="dropdown-menu" id="dropdown-uye">
			    						<li><a href="<?php echo LOGIN_URL ?>?return_url=<?php	echo $RETURN_URL ?>">Giriş Yap</a></li>
			    						<li><a href="<?php echo REGISTER_URL ?>?return_url=<?php	echo $RETURN_URL ?>">Üye Ol</a></li>
			    					</ul>
			    				</li>
			    	<?php } ?>

			    				<li>
			    					<a href="" class="dropdown-btn show-cart">
			    						<i class="sepet-ico" ></i>
			    						<span class="fs-13">Sepetim (<span class="cart-total-qty">0</span>)</span>
			    					</a>
			    					<ul class="dropdown-menu panel sag-0">
			    						<li>
			    							<div class="cart-container">
			    								<div class="header-section clearfix">
			    									<div class="cart-notf">Sepet</div>
			    								</div>
			    								<div class="cart-list">
			    									<ul class="cart-list-ul">
			    									</ul>
			    								</div>
			    								<div class="bottom-section">
			    									<div class="cart-summary"><span>Toplam : <span style="display:inline" class="cart-total">0</span></span></div>
			    									<div class="cart-buttons">
			    										<a href="" class="cart-btn checkout">SİPARİŞ VER</a>
			    										<a href="" class="cart-btn gotocart">SEPETE GİT</a>
			    									</div>
			    								</div>
			    							</div>
			    						</li>
			    					</ul>
			    				</li>
			    				
			    			</ul>
			    		</div>
			    	</div>
				
				   	<!-- HEADER NAV  -->
			    	<div class="header-nav clearfix">
			    		
			    		<div class="container">
			    			<div class="logo"></div>
			    			<ul class="nav-btn-cont clearfix">    				
			    				<li>
			    					<a href="" class="dropdown-btn">
			    						<i class="dropdown-ico"></i>
			    						<span>Ürünler</span>
			    					</a>
			    					<ul class="dropdown-menu fullsize header-urunler-katalog">
			    						<li>
			    							<div class="nav-overlay clearfix">
				    							<div class="kategori-liste-cont">
				    								<div class="kategori-header">
				    									<span>Ürün Grupları</span>
				    								</div>

				    								<?php 
				    									$C_Tree->create_header_menu();
				    									echo $C_Tree->show_html();

				    								?>
				    						
				    						</div>

			    						</li>
			    					</ul>
			    				</li>
			    				<li>
			    					<a href="">
			    						<span>Blog</span>
			    					</a>
			    				</li>
			    				<li class="search-input">
			    					<div class="nav-search-cont">
			    						<div class="input-control">
											<input type="text" class="fleft" name="search_query" id="search" placeholder="Arama..." />
											<button type="button" class="fleft">ARA</button>
										</div>
										<div class="search-results">
											<ul>
												<li></li>
												<li></li>
											</ul>
										</div>
			    					</div>
			    				</li>
			    			</ul>
			    		</div>
			    	</div>
			    </div>

			   


			   

			    <div class="container">
			    <div class="content-cont clearfix">
			    	
			    <script type="text/javascript">

			    

			    </script>

			    <!-- Komple ayrı class yap  -->
			    <div class="breadcrumb">
			    	
			    	<span><?php  if( isset($BREADCRUMB)) echo $BREADCRUMB; ?></span>

			    </div>
<?php 
	// baska sayfalarda kullaniyor olabilirim
	// headerla işim bittiginde siktirliyorum o yuzden
	unset($C_Tree);
