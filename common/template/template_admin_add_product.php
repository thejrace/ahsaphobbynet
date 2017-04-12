<div class="row-ust-uyari-cont" id="row-ust-notf"></div>

	<form method="post" action="" onsubmit="form_submit_v3( this, false, event );" id="add_product_form" enctype="multipart/form-data">
			
		<div class="row">
			<ul tabdiv="tab-icerik" class="tab">
				<li class="tab-btn aktif"><a href="">Ürün Bilgileri</a></li>
				<li class="tab-btn "><a href="">Fiyat Bilgileri</a></li>
				<li class="tab-btn "><a href="">Ürün Detayları</a></li>
				<li class="tab-btn "><a href="">Diğer</a></li>
				<li class="tab-btn "><a href="">SEO Bilgileri</a></li>
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

				<input type="hidden" id="category_id" name="category_id" value="<?php echo $CURRENT; ?>" />
				<input type="hidden" id="type" name="type" value="add_product" />

				<?php echo $SEARCH_CATEGORIES; ?>
				
<!-- 				<div class="input-grup">
					<label class="t2" >Kategori</label>	
				  	<div class="input-control">
						<select name="Cat_" id="Cat_" onchange="CategoryTree(this, 'alt_')" class="t2 nofloat">
					 		<option value="0">Seçiniz</option>
					 		<option value="1">Ayna Pleksler</option>
					 	</select>
				  		<div id="alt_" class="kat-select"></div>
					</div>
				</div>	 -->
					  
					


				<div class="input-grup">
					<label class="t2" for="product_name" >Ürün Adı</label>
					<div class="input-control">
						<input type="text" name="product_name" id="product_name" class="t2 req" value="<?php echo $INPUTS["product_name"][1] ?>" />
					</div>
				</div>

				<div class="input-grup">
					<label class="t2" for="price_1" >Fiyat 1</label>
					<div class="input-control">
						<input type="text" name="price_1" id="price_1" class="t2 req posnum not_zero" value="<?php echo $INPUTS["price_1"][1] ?>" />
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
							<option value="0"  >Pasif</option>
							<option value="1" selected >Aktif</option>
						</select>
					</div>
				</div>

				<div class="input-grup">
					<label class="t2" for="kdv_included" >KDV Dahil</label>
					<div class="input-control">
						<select name="kdv_included" class="t2">
							<option value="0" selected >Hayır</option>
							<option value="1" >Evet</option>
						</select>
					</div>
					<div class="input-not">Hayır seçeneği işaretli ise sistem bir sonraki sayfadaki KDV oranına göre fiyata KDV' yi ekleyecektir.</div>
				</div>

				<div class="input-grup">
					<label class="t2" for="material" >Malzeme</label>
					<div class="input-control">
						<select name="material" class="t2">
							<option value="0" selected >Seçiniz..</option>
							<option value="MDF" >MDF</option>
							<option value="Kontra" >Kontra</option>
							<option value="Pleks" >Pleks</option>
							<option value="Others" >Diğer</option>
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
						<input type="text" name="price_2" id="price_2" class="t2 posnum" value="<?php echo $INPUTS["price_2"][1] ?>" />
					</div>
				</div>

				<div class="input-grup">
					<label class="t2" for="price_3" >Fiyat 3</label>
					<div class="input-control">
						<input type="text" name="price_3" id="price_3" class="t2 posnum" value="<?php echo $INPUTS["price_3"][1] ?>" />
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
						<select name="shipment_system_cost" class="t2">
							<option value="0" selected >Hayır</option>
							<option value="1" >Evet</option>
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
				 		<textarea value="" name="details" id="details" class="t2" value="<?php echo $INPUTS["details"][1] ?>"></textarea>
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
				 	<div class="input-not"></div>
				</div>

				<div class="input-grup">
					<label class="t2" for="picture_2" >Resim 2</label>
				 	<div class="input-control">
				 		<input type="file" name="picture_2" id="picture_2" class="t2" />
				 	</div>
				 	<div class="input-not"></div>
				</div>

				<div class="input-grup">
					<label class="t2" for="picture_3" >Resim 3</label>
				 	<div class="input-control">
				 		<input type="file" name="picture_3" id="picture_3" class="t2" />
				 	</div>
				 	<div class="input-not"></div>
				</div>

				<div class="input-grup">
					<label class="t2" for="picture_4" >Resim 4</label>
				 	<div class="input-control">
				 		<input type="file" name="picture_4" id="picture_4" class="t2" />
				 	</div>
				 	<div class="input-not"></div>
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
				 		<textarea value="" name="seo_keywords" id="seo_keywords" class="t2" value="<?php echo $INPUTS["seo_keywords"][1] ?>"></textarea>
				 	</div>
				 	<div class="input-not">Kelimeler arasında virgül kullanın.</div>
				</div>

				<div class="input-grup">
					<label class="t2" for="seo_details" >Açıklamalar</label>
				 	<div class="input-control">
				 		<textarea value="" name="seo_details" id="seo_details" class="t2" value="<?php echo $INPUTS["seo_details"][1] ?>"></textarea>
				 	</div>
				</div>

				<div class="input-grup"><input type="submit" value="Kaydet" class="btn-buyuk"/></div>

			</div>

			<div class="tab-icerik">		
				<div id="row-header">
					<h3>
						Ürünü ekledikten sonra, düzenleme sayfasından varyant işlemlerini yapabilirsiniz.
					</h3>
				</div>

			</div>

		</div>

	</form>



	<script type="text/javascript">
		rowNotf( "<?php echo $POST_OUTPUT; ?>",  <?php echo $OK; ?> );

	</script>