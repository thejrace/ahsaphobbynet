<div class="row-ust-uyari-cont" id="row-ust-notf"></div>

	<form method="post" action="" onsubmit="form_submit_v3( this, false, event );" id="add_product_form" enctype="multipart/form-data">
			
		<div class="row">
			<ul tabdiv="tab-icerik" class="tab">
				
				<li class="tab-btn aktif"><a href="">Ürün Bilgileri</a></li>
				<li class="tab-btn"><a href="">Fiyat Bilgileri</a></li>
				<li class="tab-btn"><a href="">Ürün Detayları</a></li>
				<li class="tab-btn"><a href="">Diğer</a></li>
				<li class="tab-btn"><a href="">SEO Bilgileri</a></li>
				<li class="tab-btn"><a href="">Varyantlar</a></li>
			</ul>

			<div class="row-top">

				<div class="row-nav clearfix">
					<div class="row-nav-col">
						<img src="http://ahsaphobby.net/resources/img/static/spinner-row.gif" id="row-spinner" class="row-spinner"/>
						<span id="row-spinner-info">Lütfen bekleyin...</span>
					</div>

				</div>

			</div>

			<div class="tab-icerik">

				<input type="hidden" id="category_id" name="category_id" value="<?php echo $P_INFO["category"]; ?>" />
				<input type="hidden" id="type" name="type" value="edit_product" />
			

				<?php echo $SEARCH_CATEGORIES; ?>
				
				<div class="input-grup">
					<label class="t2" for="product_name" >Ürün Adı</label>
					<div class="input-control">
						<input type="text" name="product_name" id="product_name" class="t2 req" value="<?php echo $INPUTS["product_name"][1] ?>" />
					</div>
				</div>

				<div class="input-grup">
					<label class="t2" for="price_1" >Fiyat 1</label>
					<div class="input-control">
						<input type="text" name="price_1" id="price_1" class="t2 req posnum" value="<?php echo $INPUTS["pure_price_1"][1] ?>" />
					</div>
				</div>

				<!-- RASTGELE BI DEGER ATA -->
				<div class="input-grup">
					<label class="t2" for="stock_code" >Stok Kodu</label>
					<div class="input-control">
						<input type="text" name="stock_code" id="stock_code" class="t2" value="<?php echo $INPUTS["stock_code"][1] ?>" />
					</div>
					<div class="input-not">Boş bırakılırsa sistem otomatik olarak ekleyecektir.</div>
				</div>

				<div class="input-grup">
					<label class="t2" for="stock_amount" >Stok Adedi</label>
					<div class="input-control">
						<input type="text" name="stock_amount" id="stock_amount" class="t2 posnum" value="<?php echo $INPUTS["stock_amount"][1] ?>" />
					</div>
				</div>

				<div class="input-grup">
					<label class="t2" for="desi" >Desi</label>
					<div class="input-control">
						<input type="text" name="desi" id="desi" class="t2 posnum" value="<?php echo $INPUTS["desi"][1] ?>" />
					</div>
					<div class="input-not">desi-kg  |  <a href="" onclick="desiHesaplayici(); return false;"><b>Desi Hesaplayıcı</b></a> </div>
				</div>

				<div class="input-grup">
					<label class="t2" for="status" >Durum</label>
					<div class="input-control">
						<select name="status" class="t2">
							<?php 
								$options = "";
								$status = array( 0 => "Pasif", 1 => "Aktif" );
								foreach( $status as $key => $val ){
									$selected = "";
									if( $INPUTS["status"][1] == $key ) $selected = "selected";
									$options .= '<option value="'.$key.'" '.$selected.' >'.$val.'</option>';
								}
								echo $options;
							?>			
						</select>
					</div>
				</div>

				<div class="input-grup">
					<label class="t2" for="kdv_included" >KDV Dahil</label>
					<div class="input-control">
						<select name="kdv_included" id="kdv_included" class="t2">
							<?php 
								$options = "";
								$status = array( 0 => "Hayır", 1 => "Evet" );
								foreach( $status as $key => $val ){
									$selected = "";
									if( $INPUTS["kdv_included"][1] == $key ) $selected = "selected";
									$options .= '<option value="'.$key.'" '.$selected.' >'.$val.'</option>';
								}
								echo $options;
							?>			
						</select>
					</div>
					<div class="input-not">Hayır seçeneği işaretli ise sistem bir sonraki sayfadaki KDV oranına göre fiyata KDV' yi ekleyecektir.</div>
				</div>

				<div class="input-grup">
					<label class="t2" for="material" >Malzeme</label>
					<div class="input-control">
						<select name="material" id="material" class="t2">
							<?php 
								$options = "";
								$status = array( 0 => "Seçiniz..", "MDF" => "MDF", "Kontra" => "Kontra", "Pleks" => "Pleks", "Others" => "Diğer" );
								foreach( $status as $key => $val ){
									$selected = "";
									if( $INPUTS["material"][1] == $key ) $selected = "selected";
									$options .= '<option value="'.$key.'" '.$selected.' >'.$val.'</option>';
								}
								echo $options;
							?>			
						</select>
					</div>
					<div class="input-not">Boş bırakılabilir.</div>
				</div>

				<div class="input-grup"><input type="submit" value="Kaydet" class="btn-buyuk"/></div>

			</div>

			<div class="tab-icerik">

				<div class="input-grup">
					<label class="t2" for="price_2" >Fiyat 2</label>
					<div class="input-control">
						<input type="text" name="price_2" id="price_2" class="t2 posnum" value="<?php echo $INPUTS["pure_price_2"][1] ?>" />
					</div>
				</div>

				<div class="input-grup">
					<label class="t2" for="price_3" >Fiyat 3</label>
					<div class="input-control">
						<input type="text" name="price_3" id="price_3" class="t2 posnum" value="<?php echo $INPUTS["pure_price_3"][1] ?>" />
					</div>
				</div>

				<div class="input-grup">
					<label class="t2" for="kdv_percentage" >KDV</label>
					<div class="input-control">
						<input type="text" name="kdv_percentage" id="kdv_percentage" class="t2 posnum" value="<?php echo $INPUTS["kdv_percentage"][1] ?>" />
					</div>
					<div class="input-not">%</div>
				</div>

				<div class="input-grup">
					<label class="t2" for="sale_percentage" >İndirim</label>
					<div class="input-control">
						<input type="text" name="sale_percentage" id="sale_percentage" class="t2 posnum" value="<?php echo $INPUTS["sale_percentage"][1] ?>" />
					</div>
					<div class="input-not">%</div>
				</div>

				<div class="input-grup">
					<label class="t2" for="shipment_cost" >Kargo Ücreti</label>
					<div class="input-control">
						<input type="text" name="shipment_cost" id="shipment_cost" class="t2 posnum" value="<?php echo $INPUTS["shipment_cost"][1] ?>" />
					</div>
					<div class="input-not">Sabit kargo ücreti için Kargo Sistem seçeneği işaretli olmamalıdır.</div>
				</div>


				<div class="input-grup">
					<label class="t2" for="shipment_system_cost" >Kargo Sistem</label>
					<div class="input-control">
						<select name="shipment_system_cost" id="shipment_system_cost" class="t2">
							<?php 
								$options = "";
								$status = array( 0 => "Hayır", 1 => "Evet" );
								foreach( $status as $key => $val ){
									$selected = "";
									if( $INPUTS["shipment_system_cost"][1] == $key ) $selected = "selected";
									$options .= '<option value="'.$key.'" '.$selected.' >'.$val.'</option>';
								}
								echo $options;
							?>			
						</select>
					</div>
					<div class="input-not">Desiye göre otomatik hesaplanır.</div>
				</div>

				<div class="input-grup"><input type="submit" value="Kaydet" class="btn-buyuk"/></div>

			</div>


			<div class="tab-icerik">

				<div class="input-grup">
					<label class="t2" for="details" >Ürün Detayları</label>
				 	<div class="input-control">
				 		<textarea value="" name="details" id="details" class="t2"><?php echo $INPUTS["details"][1] ?></textarea>
				 	</div>
				 	<div class="input-not">Ürün inceleme sayfasında görüntülenecektir.</div>
				</div>
			
				 <div class="input-grup"><input type="submit" value="Kaydet" class="btn-buyuk"/></div>

			</div>


			<div class="tab-icerik">

				<div class="input-grup">
					<label class="t2" for="picture_1" >Resim 1</label>
				 	<div class="input-control">
				 		<input type="file" name="picture_1" id="picture_1" class="t2" />
				 	</div>
				 	<div class="input-not" id="img_holder_1">
				 		<?php if( $P_INFO["picture_1"] == 1 ){ ?>
					 		<button type="button" onmouseenter="AHTooltip( 'img', '<?php echo $P_INFO["url_picture_1"] ?>' , this, event);" >[ Görüntüle ]</button>
					 		<button type="button" onclick="product_img_delete( <?php echo $P_INFO["id"] ?> , 1, 'product' )">[ Sil ]</button>
				 		<?php }  ?>
				 	</div>
				</div>

				<div class="input-grup">
					<label class="t2" for="picture_2" >Resim 2</label>
				 	<div class="input-control">
				 		<input type="file" name="picture_2" id="picture_2" class="t2" />
				 	</div>
				 	<div class="input-not" id="img_holder_2">
				 		<?php if( $P_INFO["picture_2"] == 1 ){ ?>
					 		<button type="button" onmouseenter="AHTooltip( 'img', '<?php echo $P_INFO["url_picture_2"] ?>' , this, event);" >[ Görüntüle ]</button>
					 		<button type="button" onclick="product_img_delete( <?php echo $P_INFO["id"] ?> , 2, 'product' )">[ Sil ]</button>
				 		<?php }  ?>
				 	</div>
				</div>

				<div class="input-grup">
					<label class="t2" for="picture_3" >Resim 3</label>
				 	<div class="input-control">
				 		<input type="file" name="picture_3" id="picture_3" class="t2" />
				 	</div>
				 	<div class="input-not" id="img_holder_3">
				 		<?php if( $P_INFO["picture_3"] == 1 ){ ?>
					 		<button type="button" onmouseenter="AHTooltip( 'img', '<?php echo $P_INFO["url_picture_3"] ?>' , this, event);" >[ Görüntüle ]</button>
					 		<button type="button" onclick="product_img_delete( <?php echo $P_INFO["id"] ?> , 3, 'product' )">[ Sil ]</button>
				 		<?php } ?>
				 	</div>
				</div>

				<div class="input-grup">
					<label class="t2" for="picture_4" >Resim 4</label>
				 	<div class="input-control">
				 		<input type="file" name="picture_4" id="picture_4" class="t2" />
				 	</div>
				 	<div class="input-not" id="img_holder_4">
				 		<?php if( $P_INFO["picture_4"] == 1 ){ ?>
					 		<button type="button" onmouseenter="AHTooltip( 'img', '<?php echo $P_INFO["url_picture_4"] ?>' , this, event);" >[ Görüntüle ]</button>
					 		<button type="button" onclick="product_img_delete( <?php echo $P_INFO["id"] ?> , 4, 'product' )">[ Sil ]</button>
				 		<?php }  ?>
				 	</div>
				</div>

				<div class="input-grup">
					<label class="t2" for="similar_products" >Tavsiye Ürünler</label>
					<div class="input-control">
						<input type="text" name="similar_products" id="similar_products" class="t2" value="<?php echo $INPUTS["similar_products"][1] ?>" />
					</div>
					<div class="input-not"><a href="" style="font-size:14px">[ + ]</a> ( Ürün ID'lerini aralarına virgül koyarak yazın. )</div>
				</div>
			
				<div class="input-grup"><input type="submit" value="Kaydet" class="btn-buyuk"/></div>

			</div>


			<div class="tab-icerik">
				
				<div class="input-grup">
					<label class="t2" for="seo_title" >Sayfa Başlık</label>
					<div class="input-control">
						<input type="text" name="seo_title" id="seo_title" class="t2" value="<?php echo $INPUTS["seo_title"][1] ?>"/>
					</div>
				</div>

				<div class="input-grup">
					<label class="t2" for="seo_keywords" >Anahtar Kelimeler</label>
				 	<div class="input-control">
				 		<textarea value="" name="seo_keywords" id="seo_keywords" class="t2"><?php echo $INPUTS["seo_keywords"][1] ?></textarea>
				 	</div>
				 	<div class="input-not">Kelimeler arasında virgül kullanın.</div>
				</div>

				<div class="input-grup">
					<label class="t2" for="seo_details" >Açıklamalar</label>
				 	<div class="input-control">
				 		<textarea value="" name="seo_details" id="seo_details" class="t2"><?php echo $INPUTS["seo_details"][1] ?></textarea>
				 	</div>
				</div>

				<div class="input-grup"><input type="submit" value="Kaydet" class="btn-buyuk"/></div>

			</div>

			<div class="tab-icerik">

				<div class="row inner">
					<ul tabdiv="in-tab-icerik" class="tab">
						<li class="tab-btn aktif"><a href="">Tanımlı Varyantlar</a></li>
						<li class="tab-btn"><a href="">+ Yeni Ekle</a></li>	
					</ul>
					<div class="in-tab-icerik">
						
						<?php 
							$Def_Table = new DataTable("product_defined_variants");
							$Def_Table->set_settings( array() );
							$Def_Table->create( $DEFINED_VARIANTS, count($DEFINED_VARIANTS) );		
						?>
						<div class="row-nav" style="margin-bottom:20px">
							<div class="row-nav-col"><button type="button" class="btn-buyuk" onclick="save_variant_order()" >Kaydet</button></div>

							<div class="row-nav-col"><a href="variant_products.php?pid=<?php echo Input::get("pid"); // ürün idsini getten al ?>" target="_blank"><button type="button" class="btn-buyuk temp-mavi">Mevcut Seçenekleri Düzenle</button></a></div>
						</div>

						<div class="row-nav" style="margin-bottom:20px">
							<div>Varyantların sırasını değiştirmek için, sırasını değiştirmek istediğiniz varyanta tıklayıp istediğiniz yere taşıyın ve yukarıdaki
							Kaydet butonuna basın.</div>
							<div>Not: Eğer varolan sırada kayıtlı alt ürünler varsa bu işlemden sonra kullanılamayacaktır.</div>
						</div>
						
						<div class="data-table-cont" id="data-table-cont">
							<table class="data-table">
								<thead>
									<tr>
										<td class="dt-small sort">Sıra</td>
										<td class="dt-long  sort">Varyant Adı</td>
										<td class="dt-small sort"></td>
									</tr>
								</thead>
								<tbody id="def-data-table-body">

										<?php echo $Def_Table->show_table();  ?>
								</tbody>
							</table>
						
						</div> <!-- data table cont -->

					
					</div>
					<div class="in-tab-icerik">
						<?php 
							$Undef_Table = new DataTable("product_undefined_variants");
							$Undef_Table->set_settings( array() );
							$Undef_Table->create( $UNDEFINED_VARIANTS, count($UNDEFINED_VARIANTS) );
								
						?>
						<div class="data-table-cont" id="data-table-cont">
							<table class="data-table">
								<thead>
									<tr>
										<td class="dt-long  sort">Varyant Adı</td>
										<td class="dt-small sort" >Tanımla</td>
									</tr>
								</thead>
								<tbody id="undef-data-table-body">

										<?php echo $Undef_Table->show_table();  ?>
								</tbody>
							</table>
						
						</div> <!-- data table cont -->
					</div>
					
				</div>
			</div>

			

		</div>

	</form>


	<script src="<?php echo RES_JS_URL ?>jquery.js"></script>
	<script src="<?php echo RES_JS_URL ?>jquery-ui.js"></script>
	<script type="text/javascript">

		// Varyant sirasi degistirilindiginde onceden kaydedilmis varyant urunleri patliyor
		function save_variant_order(){
			var c = confirm( "Eğer varolan sırada kayıtlı alt ürünler varsa bu işlemden sonra kullanılamayacaktır." );
			if( c ) {
				if( sorted != "" ){
					AHAJAX_V3.req( "edit_product.php?pid=<?php echo Input::get("pid")?>", "ajax_req=true&type=update_variant_order&" + sorted , function(r) {
						rowNotf( r.text, r.ok );
						set_html( $AH('def-data-table-body'), r.def_table );
					}, false);
					// yeni sira listesini de boşaltıyoruz ki, baska kategori duzenlenirken
					// eski liste hafizada kalmasin
					sorted = "";
				}
			}
		}

		// Liste sirasinin yazilacagi var
		var sorted = "";
		$( "#def-data-table-body" ).sortable({
			update: function(){
				sorted = $( "#def-data-table-body" ).sortable( "serialize");
			}
		});	

		
		rowNotf( "<?php echo $POST_OUTPUT; ?>",  <?php echo $OK; ?> );

		function variant_action( p, action_type, event ){

			AHAJAX_V3.req( "edit_product.php?pid=<?php echo Input::get("pid")?>", manual_serialize({ ajax_req:true, type:action_type, item_id:p }), function(r) {
				rowNotf( r.text, r.ok );
				set_html( $AH('undef-data-table-body'), r.undef_table );
				set_html( $AH('def-data-table-body'), r.def_table );
			}, false);

			event_prevent_default(event);
		}

		



	</script>