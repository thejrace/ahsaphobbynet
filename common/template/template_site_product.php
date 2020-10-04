<!-- ÜST -->
<div class="urun-blok-top clearfix">
	<div class="urun-title">
		<span><?php echo $PRODUCT_DATA["product_name"] ?></span>
	</div>

	<div class="ust-btn-cont">
		<div class="sepet-btn-input">
			<input type="text" name="urunAdet" value="1"/>
		</div>
		<div class="sepet-btn">
			<button type="button" class="btn-buyuk add_to_cart mg-none">Sepete Ekle</button>
		</div>
	</div>
</div>


<!-- ORTA -->

<div class="urun-blok-mid clearfix">

	<div class="urun-slider-cont"> <!-- %40  -->
		<?php echo $SLIDER; ?>
	</div>

	<div class="urun-info-cont"> <!-- %60  -->
		<div class="info-col split">
			<div class="urun-derece"></div>
			<div class="mobile-sepet">
				<div class="ust-btn-cont">
					<div class="sepet-btn-input">
						<input type="text" name="urunAdet" value="1"/>
					</div>
					<div class="sepet-btn">
						<button type="button" class="btn-buyuk add_to_cart mg-none">Sepete Ekle</button>
					</div>
				</div>
			</div>

			<div class="islem-btn clearfix">
				<a href="">Alışveriş Listeme Ekle</a>
				<a href="">Fiyatı Düşünce Haber Ver</a>
				<a href="">Yorum Yap</a>
			</div>
		</div>

		<div class="info-col split">
			<div class="urun-info-fiyat">
				<div class="spec-control">
					<div class="content-left">KDV Dahil</div>
					<div class="content-right fiyat-span"><span id="price_data"><?php echo $PRODUCT_DATA["price_1"] ?></span> TL<span id="price_not"></span></div>
				<?php if( $PRODUCT_DATA["has_form"] ) { ?>
					<div class="content-right" id="variant-note">'den başlayan fiyatlarla.</div>
				<?php } ?>
				</div>
			</div>
		</div>

		<div class="info-col">
			<ul class="urun-specs-list">
				<li>
					<div class="spec-control">
						<div class="content-left">Stok Kodu</div>
						<div class="content-right"><?php echo $PRODUCT_DATA["stock_code"] ?></div>
					</div>
				</li>

				<li>
					<div class="spec-control">
						<div class="content-left">Malzeme</div>
						<div class="content-right"><?php echo $PRODUCT_DATA["material"] ?></div>
					</div>
				</li>
			</ul>
		</div>

		<div class="info-col">
			<img src="http://ahsaphobby.net/resources/img/static/spinner-row.gif" id="row-spinner" class="row-spinner"/>
			<span id="row-spinner-info"></span>
		</div>


		<?php if( $PRODUCT_DATA["has_form"] ) { ?>

			<div class="info-col">
				<div class="ah-editor-form">
					<div class="preview-cont">
						<img src="<?php echo RES_IMG_AH_EDITOR_PREV_URL . "editor_placeholder.png" ?>" id="editor-preview-img" alt="hege" />
						<div class="preview-info">Harf Sayısı : <span id="letter-count">0</span></div>
					</div>
					<div class="form-cont">
						<form action="" method="post" id="editor-form">
							<div class="input-grup">
								<input type="text" id="editor-text" class="editor-text" name="editor-text" value="<?php echo Input::get("text")?>" placeholder="AhsapHobby" />
							</div>
							
							<?php echo $VARIANTS; ?>
						</form>
					</div>
				</div>
			</div>

		<?php } else { ?>

			<div class="info-col">
				<?php echo $VARIANTS; ?>
			</div>

		<?php } ?>




	</div>

</div>


<!-- ALT -->
<div class="urun-blok-bottom">
	
	<div class="info-tabs-cont">
		<ul tabdiv="urun-info-tab" class="tab product-tabs clearfix">
			<li class="tab-btn aktif"><a href="">Ürün Açıklaması</a></li>
			<li class="tab-btn "><a href="">Yorumlar</a></li>
			<li class="tab-btn "><a href="">Taksit</a></li>
		</ul>
		<div class="urun-info-tab-cont">
			<div class="urun-info-tab"><?php echo $PRODUCT_DATA["details"] ?></div>
			<div class="urun-info-tab">Yorumlar</div>
			<div class="urun-info-tab">Taksit</div>
		</div>
	</div>

</div>


<div class="similar-products-container">
	<h3><?php echo $CATEGORY_NAME ?> Kategorisinde En Çok Satan Ürünler</h3>
	<ul>
		<li>
			<div class="item-details clearfix">
				<a href="" class="clearfix">
					<div class="bullet table-col fleft"><span>1</span></div>
					<div class="thumb table-col fleft">
						<div class="thumb-vertical">
							<img src="http://ahsaphobbynet.test/res/img/static/product_img/product-42-1-resized.png" />
						</div>
					</div>
					<div class="info table-col fleft">
						<span class="product-name">Tepsi Obarey</span>
						<span class="product-price">15TL</span>
					</div>
					<div class="stars table-col fright">*****</div>
				</a>
			</div>
		</li>
		<li>
			<div class="item-details clearfix">
				<a href="" class="clearfix">
					<div class="bullet table-col fleft"><span>2</span></div>
					<div class="thumb table-col fleft ">
						<div class="thumb-vertical">
							<img src="http://ahsaphobbynet.test/res/img/static/product_img/product-5-1-resized.png" />
						</div>
					</div>
					<div class="info table-col fleft">
						<span class="product-name">Oval Pano</span>
						<span class="product-price">20TL</span>
					</div>
					<div class="stars table-col fright">*****</div>
				</a>
			</div>
		</li>
		<li>
			<div class="item-details clearfix">
				<a href="" class="clearfix">
					<div class="bullet table-col fleft"><span>3</span></div>
					<div class="thumb table-col fleft">
						<div class="thumb-vertical">
							<img src="http://ahsaphobbynet.test/res/img/static/product_img/product-6-1-resized.png" />
						</div>
					</div>
					<div class="info table-col fleft">
						<span class="product-name">Anahtarlık</span>
						<span class="product-price">3TL</span>
					</div>
					<div class="stars table-col fright">*****</div>
				</a>
			</div>
		</li>
	</ul>
</div>


<script type="text/javascript">
	// urunun resmi yoksa error aliyoruz dogal olarak
	var Product_Slider = new AHSlider( { container:"urun-slider-cont"} );

	<?php echo $JS_EDIT_VAR_CODE;?>

	if( edit_var_code.length > 0 ){
		AHCart.start_editing( true );
		AHCart.content_id = <?php echo $CART_CONTENT_ID ?>;
	} 
	

	AHReady(function(){	
		Product_Slider.init();

		// ilk varyanti seçili hale getir ve altindaki varyantlari listele
		if( Product.has_variant() ) Variants.change( $AH('variant_1'), Product.has_editor() );


		// editor eventler
		if( Product.has_editor() ){
			AHEditor.init();
			// ilk fonta göre updated resmi al
			AHEditor.update_preview();
			// editor keyup
			add_event( AHEditor.editor_input, "keyup", debounce( function(){
				AHEditor.update_preview();
			}, 500, false ));
			add_event_on( document, false, "click", function(){
				AHEditor.Font_Select.toggle( true );
			});
			add_event( AHEditor.Font_Select.toggle_btn, "click", function(e){
				edit_var_code = [];
				AHEditor.Font_Select.toggle();
				event_prevent_default(e);
				event_stop_propagation(e);
			});

			add_event( AHEditor.Font_Select.font_option, "click", function(e){
				edit_var_code = [];
				// once varyant sec ( urun sayfasi default a donecek )
				Variants.change( this, true );
				// sonra fontu seç
				AHEditor.Font_Select.select( this );
				// en son preview
				AHEditor.update_preview();		
				event_prevent_default(e);
			});
		}
		console.log(AHCart.check_editing());
		// variant_1 change den sonra olacak
		if( Product.has_variant() ){
			if( AHCart.check_editing() ){
				// edit yapilicaksa varyantlari oto secme
				Variants.auto_select();
			}
		}

		add_event( $AHC("add_to_cart"), "click", function(e){
			AHCart.add_item(1);
		});

	});	
	var Product = {
		id: <?php echo $PRODUCT_DATA["id"] ?>,
		name: '<?php echo $PRODUCT_DATA["product_name"] ?>',
		price: <?php echo $PRODUCT_DATA["price_1"] ?>,
		seo: '<?php echo $PRODUCT_DATA["seo_keywords"] ?>',
		editor: <?php echo $PRODUCT_DATA["has_form"]?>,
		variant_count: <?php echo $VARIANT_COUNT ?>,
		slider: Product_Slider,
		page_elems: {
			price_data	 : $AH('price_data'),
			price_note 	 : $AH('price_not'),
			variant_note : $AH('variant-note')
		},
		has_variant: function(){
			return this.variant_count > 0;
		},
		has_editor: function(){
			return this.editor;
		},
		// varyantlar için slider ve fiyat guncellemesi
		update_page: function(){
			// fiyati guncelle
			set_html( this.page_elems.price_data, Variants.data.price );
			set_html( this.page_elems.price_note, "" );
			// sliderlari guncelle
			if( Variants.all_selected() && Variants.data.img != "" && Variants.data.img_resized != "" ) {
				// resim varsa slider a ekle ve eklenen resmi aktif hale getir
				this.slider.add_slider_item( this.slider.get_slide_count() + 1, this.seo, this.name, Variants.data.img, Variants.data.img_resized );
			}
		},
		// varyantlar tamamen secilmediginde normal ana urun bilgilerine
		// geri dondur sayfayi
		default_page: function(){
			// slider i basa al, eklenmis resimleri ucur
			this.slider.default_slide();
			this.slider.remove_appended();
		},
		restore_default_price: function(){
			// fiyati orjinal urun fiyatina cevir
			if( Variants.all_selected() ){
				set_html( this.page_elems.price_data, Variants.data.price );
			} else {
				set_html( this.page_elems.price_data, this.price );
			}
			set_html( this.page_elems.price_note, "" );
			set_html( this.page_elems.variant_note, "'den başlayan fiyatlarla." );
		},
		update_price: function( price ){
			set_html( this.page_elems.price_data, price );
		}
	};
	

	// variants init
	if( Product.has_variant() || Product.has_editor() ){
		var Variants = {
			chain_flag:false,
			code:[],
			code_check:null,
			data: {
				code:null,
				id:null,
				price:null,
				img:null,
				img_resized:null
			},
			all_selected: function(){
				return this.chain_flag;
			},
			auto_select: function(){
				for( var i = 1; i <= edit_var_code.length; i++ ){
					var vari = $AH("variant_"+i);
					vari.setAttribute( "value", edit_var_code[i-1]);
					this.change( vari, Product.has_editor() );
				}
			},
			change: function( elem, font ){
				var current_variant_id = parseInt( (elem.id).substr(8, 1) ),
					next_variant_id = current_variant_id + 1,
					variant_name_div_prefix = 'variant_name_',
					variant_cont_div = 'variant_'+next_variant_id,
					variant_name,
					select_value;

				// ilk varyant seçimi - font olmayanlar
				if( current_variant_id == 1 && !font ){
					for( var i = 0; i < elem.options.length; i++ ){
						if( elem.options[i].value == edit_var_code[0] ) elem.options[i].selected = true;
					}
				// font için ilk secim
				} else if( current_variant_id == 1 && font ){
					var fonts = $AHC("foption"), fcount = fonts.length;
					for( var i = 0; i < fcount; i++ ){
						if( fonts[i].getAttribute("value") == edit_var_code[0] ){
							AHEditor.Font_Select.select( fonts[i] );
							break;
						}
					}
				}
				// font div oldugu icin attr ile aliyorum variant id yi
				( font ) ? select_value = elem.getAttribute("value") : select_value = elem.value;
				// tum varyantlar secili degil
				this.chain_flag = false;
				// son varyanta gelene kadar varyant data objesi bos kalacak
				set_all_elems( this.data, true, null );
				// son varyanta gelmeden guncelleme yapmiyoruz
				Product.default_page();
				Product.restore_default_price();
				// degistirlen secenegin altindakileri disable et ve
				// kontrol listesinden cikar
				for( var i = 0  ; i < Product.variant_count; i++ ){
					// Kendi id'si ile i'yi topladıgım için en sonda 1 fazlası oluyor, onu engelle
					if( (next_variant_id + i) <= Product.variant_count ) {
						// kontrol listesinden sil bosaltilanlari
						delete this.code[next_variant_id + i];
						
						// degsitrilen varyantin sonrasindakileri bosalt
						add_options( $AH('variant_'+ (next_variant_id + i)), true, [ [0, $AH(variant_name_div_prefix +( next_variant_id + i)).value + " seçiniz..." ] ], false );
						$AH(variant_cont_div).disabled = false;
					}
				}
				// kontrol listesine ekle
				this.code[current_variant_id] = select_value;
				// eger Seciniz.. harici bir secenek secildiyse
				if( select_value != 0 ){
					// kontrol listesini serialize et
					this.code_check = assoc_array_join(this.code, "-");
					// son varyant ise obarey yapiyoruz server side da anlamak icin varyant ismini
					( $AH(variant_name_div_prefix + next_variant_id) == null ) ? variant_name = "obarey" : variant_name = $AH(variant_name_div_prefix + next_variant_id ).value;
					// item id = product id
					ajax_data = manual_serialize({ code_check:this.code_check, item_id:Product.id, type:"variant_change", variant_no:next_variant_id - 1, variant_name:variant_name });
					
					AHAJAX_V3.req( "<?php echo $AJAX_REQ ?>", ajax_data, function(r){
						// gelen html'i yazdır eger son varyant degilse
						if( $AH(variant_cont_div) != null ) {
							add_options( $AH(variant_cont_div), true, r.data, edit_var_code[current_variant_id]  );
						} else{
							// tum varyantlar secildi
							Variants.chain_flag = true;
							// son varyant ise urun bilgilerini guncelle
							extend( Variants.data, { id:r.data["id"], price:r.data["price_1"], code:r.data["variant_code"], img:r.data["url_picture_1"], img_resized:r.data["url_picture_1-resized"] } );
							Product.update_page();
							if( Product.has_editor() ) AHEditor.update_preview();
						}
					});
				}
			},
		
		};
	}

	// editor init
	if( Product.has_editor() ){
		var AHEditor = {
			text:null,
			letter_count_div:null,
			editor_input:null,
			old_img:null,
			preview:null,
			init: function(){
				this.editor_input 		= $AH('editor-text');
				this.letter_count_div 	= $AH('letter-count');
				this.preview 			= $AH('editor-preview-img');
				this.Font_Select.init();
			},
			is_empty: function(){
				return trim( get_val( this.editor_input ) ).length == 0;
			},
			request_preview: function(){
				AHAJAX_V3.req( 
					"<?php echo $AJAX_REQ?>",
					manual_serialize({
						type: 		"editor",
						item_id:    Product.id,
						var_code:   Variants.code_check,
						var_count: 	Product.variant_count,
						text:     	this.text,
						old_img:  	this.old_img,
						font:     	this.Font_Select.selected_font
					}),
					function( r ){
						AHEditor.old_img                    = r.data.old_img;
						AHEditor.preview.src                = r.data.img_src;
						set_html( AHEditor.letter_count_div, r.data.letter_count );
						// editor boşsa urun veya varyantın başlangic fiyatini gostert
						if( AHEditor.is_empty() ) Product.restore_default_price();
						if( r.data.price > 0 ) {
							Product.update_price( r.data.price );
							set_html( Product.page_elems.price_note, " Karakter başı : " + r.data.price_each + " TL");
							set_html( Product.page_elems.variant_note, "" );
						}
						
						console.log( r.oh );
					}
				);
			},
			get_form_data: function(){
				return {
					text: this.text,
					img: this.preview.src
				}
			},
			update_preview: function(){
				// editordeki yaziyi sildiginde karakter basi falan siliniyor
				this.text = get_val( this.editor_input );
				this.request_preview();
			},
			Font_Select: {
				init: function(){
					this.toggle_btn 		= $AHC("font-select")[0];
					this.selected_font_cont = $AHC("font-selected")[0];
					this.selected_font 		= this.selected_font_cont.getAttribute("value");
					this.font_list			= $AHC("font-list")[0];
					this.font_option 		= $AHC("foption");
				},
				toggle: function( hide ){
					// select harici bir yere basinda kapat selecti
					if( hide ){
						removeClass( this.font_list, "open");
						return;
					}
					// klasik toggle
					toggle_class( this.font_list, "open" );
				},
				// fontu seçme
				select: function( font ){
					// ilk aciliş seciminde AHEditor.init etmedigim icin
					// this.font_option yerine AHC kullaniyorum
					// selected divine resmi koyuyoruz
					set_html( this.selected_font_cont, get_html( font ) );
					this.selected_font = font.getAttribute("value");
					foreach( $AHC("foption"), function(fopt){ removeClass( fopt, "selected") });
					addClass(font, "selected" );
				}
			}
		};
	}

</script>

