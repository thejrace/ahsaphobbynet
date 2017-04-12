<div class="left-25-col">
		<div class="col-title">
			<span>Sonuçları Filtrele</span>
			<button type="button" class="hide_toggle open" target="filter-cont">[ GİZLE ]</button>
		</div>
		<div id="filter-cont">
			<form id="filter-form" action="" method="post">
				<div class="filter-row txt-center">
					<button type="button" class="filter-main-btn filter-apply">SEÇİMİ UYGULA</button>
					<button type="reset" class="filter-main-btn filter-reset">RESET</button>
				</div>
				<div class="filter-row">
					<div class="filter-row-header">Fiyat</div>
					<ul>
						<li>
							<div class="filter-cb clearfix">
								<span>0 - 25TL</span>
								<div class="man-cb"><div class="inner"></div></div>
								<input type="checkbox" class="filter-input"  name="filter_price[]" value="1" />
							</div>
						</li>
						<li>
							<div class="filter-cb clearfix">
								<span>25 - 50TL</span>
								<div class="man-cb"><div class="inner"></div></div>
								<input type="checkbox" class="filter-input"  name="filter_price[]" value="2" />
							</div>
						</li>
						<li>
							<div class="filter-cb clearfix">
								<span>50 - 100TL</span>
								<div class="man-cb"><div class="inner"></div></div>
								<input type="checkbox" class="filter-input"  name="filter_price[]" value="3" />
							</div>
						</li>
					</ul>

				</div>

				<div class="filter-row">
					<div class="filter-row-header">Malzeme</div>
					<ul>
						<li>
							<div class="filter-cb clearfix">
								<span>MDF</span>
								<div class="man-cb"><div class="inner"></div></div>
								<input type="checkbox" class="filter-input"  name="filter_material[]" value="MDF" />
							</div>
						</li>
						<li>
							<div class="filter-cb clearfix">
								<span>Kontra</span>
								<div class="man-cb"><div class="inner"></div></div>
								<input type="checkbox" class="filter-input"  name="filter_material[]" value="Kontra" />
							</div>
						</li>
						<li>
							<div class="filter-cb clearfix">
								<span>Pleks</span>
								<div class="man-cb"><div class="inner"></div></div>
								<input type="checkbox" class="filter-input"  name="filter_material[]" value="Pleks" />
							</div>
						</li>
						<li>
							<div class="filter-cb clearfix">
								<span>Diğer</span>
								<div class="man-cb"><div class="inner"></div></div>
								<input type="checkbox" class="filter-input"  name="filter_material[]" value="Others" />
							</div>
						</li>
					</ul>

				</div>

				<div class="filter-row">
					<div class="filter-row-header">Fırsat Ürünleri</div>
					<ul>
						<li>
							<div class="filter-cb clearfix">
								<span>İndirimli Ürünler</span>
								<div class="man-cb"><div class="inner"></div></div>
								<input type="checkbox" class="filter-input" name="filter_campaign[]" value="sale" />
							</div>
						</li>
						<li>
							<div class="filter-cb clearfix" >
								<span>Yeni Ürünler</span>
								<div class="man-cb"><div class="inner"></div></div>
								<input type="checkbox" class="filter-input"  name="filter_campaign[]" value="new" />
							</div>
						</li>
						<li>
							<div class="filter-cb clearfix">
								<span>Sınırlı Stok</span>
								<div class="man-cb"><div class="inner"></div></div>
								<input type="checkbox" class="filter-input"  name="filter_campaign[]" value="limited_stock" />
							</div>
						</li>
					</ul>

				</div>
				<div class="filter-row txt-center">
					<button type="button" class="filter-main-btn filter-apply">SEÇİMİ UYGULA</button>
					<button type="reset" class="filter-main-btn filter-reset">RESET</button>
				</div>
			</form>
		</div>
	</div>
	

	<div class="col-split">&nbsp</div>
	

	<div class="right-75-col">
		<div class="col-title">
			<span><?php echo $CATEGORY_NAME ?></span>
			<div class="sixpack-top clearfix"><button type="button" class="hide_toggle open" target="alt-kategori-cont">[ ALT KATEGORİLERİ GİZLE ]</button></div>
		</div>
	

		<div class="alt-kategori-cont clearfix" id="alt-kategori-cont">
			<div class="sixpack clearfix">
			<?php echo $SUB_CATEGORY_SHOWCASE; ?>
			</div>
		</div>

		<div class="listeleme-ayar-bar clearfix">
			<div class="listele-sol fleft">
				<button type="button" class="btn-liste"></button>
				<button type="button" class="btn-katalog"></button>
				<img src="http://ahsaphobby.net/resources/img/static/spinner-row.gif" id="row-spinner" class="row-spinner"/>
				<div id="row-spinner-info" class="fleft"></div>
			</div>
			<div class="listele-sag fright">
				<span> Sırala </span>
				<select name="orderby" onchange="DT_functions.sort_table(this.value)">
					<option value="id" selected>Seçiniz</option>
					<option value="price_1_ASC" >Fiyat ( Artan )</option>
					<option value="price_1_DESC" >Fiyat ( Azalan )</option>
					<option value="product_name_ASC">İsme Göre ( A - Z )</option>
					<option value="product_name_DESC">İsme Göre ( Z - A)</option>
				</select>
				<span> Göster </span>
				<select name="rrp" onchange="DT_functions.change_rrp(this.value)">
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4" selected>4</option>
					<option value="10">10</option>
				</select>
			</div>
		</div>

		<div class="urun-liste-sayfalama clearfix" id="pagin-top">
			<?php echo $PAGIN ?>
		</div>

		<div class="urun-liste-container clearfix" id="urun-liste-container">
			<?php echo $LIST ?>
		</div>
		
		<div class="urun-liste-sayfalama clearfix" id="pagin-bottom">
			<?php echo $PAGIN ?>			
		</div>


	</div>




	<script type="text/javascript">



		// Datatable ayarlari default 
		extend( DT_functions,{
			template:"<?php echo $TEMPLATE; ?>",
			axreq:"<?php echo $AJAX_REQ; ?>?katid=<?php echo Input::get('katid')?>",
			rrp:<?php echo $Product_List->get_settings("rrp"); ?>,
			orderby:"<?php echo $Product_List->get_settings("orderby"); ?>",
			direction:"<?php echo $Product_List->get_settings("direction"); ?>",
			container:"urun-liste-container",
			filter_init:true,
			filter_form:"filter-form"
		});

		AHReady( function(){
			// filter butonlari
			add_event( $AHC("filter-cb"), "click", function(){
				filter_buton_check( this, false );
			});
			
			// filtre uygulama butonu
			add_event( $AHC("filter-apply"), "click", function(){
				DT_functions.filter_apply();
			});

			// reset butonu
			add_event( $AHC("filter-reset"), "click", function(){
				foreach( $AHC("filter-cb"), function(cb){ filter_buton_check( cb, true ) });
				DT_functions.filter_apply();
			});

			add_event( $AHC("hide_toggle"), "click", function(){
				toggle_sub_menu( this );
			});

		});

		// filtre butonlarin callback i
		// @elem buton
		// @reset reset butonuna basildiysa
		function filter_buton_check( elem, reset ){
			var cb = find_elem( elem, ".man-cb" )[0],
				s = "selected", inp = find_elem(elem, ".filter-input" )[0];
			if( reset ){
				if( hasClass(elem,s) ){
					inp.checked = false;
					removeClass(elem,s);
				}
			} else {
				if( hasClass(elem,s) ){
					inp.checked = false;
					removeClass(elem,s);
				} else {
					inp.checked = true;
					addClass(elem,s);
				}
			}
		}

		function toggle_sub_menu(elem){
			var tar = $AH(elem.getAttribute("target")),
				o = "open";
			if( hasClass(elem, o) ){
				removeClass(elem,o);
				set_html(elem, "[GÖSTER]" );
				hide(tar);
			} else {
				addClass(elem, o);
				set_html(elem, "[GİZLE]");
				show(tar);
			}
		}

	</script>