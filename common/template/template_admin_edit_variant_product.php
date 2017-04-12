<div class="row-ust-uyari-cont" id="row-ust-notf"></div>

	<form method="post" action="" onsubmit="form_submit_v3( this, false, event );" id="add_product_form" enctype="multipart/form-data">
			
		<div class="row">
			<ul tabdiv="tab-icerik" class="tab">
				<li class="tab-btn aktif"><a href="">Ürün Bilgileri</a></li>
				<li class="tab-btn "><a href="">Fiyat Bilgileri</a></li>
				<li class="tab-btn "><a href="">Ürün Detayları</a></li>
				<li class="tab-btn "><a href="">Diğer</a></li>
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

				<input type="hidden" id="type" name="type" value="add_product" />

				<?php echo $SEARCH_CATEGORIES; ?>

				<div class="input-grup">
					<label class="t2" for="product_name" >Ürün Adı</label>
					<div class="input-control">
						<input type="text" disabled name="product_name" id="product_name" class="t2 req" value="<?php echo $INPUTS["product_name"][1] ?>" />
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
				 	<div class="input-not" id="img_holder_1">
				 		<?php if( $INPUTS["picture_1"][1] == 1 ){ ?>
					 		<button type="button" onmouseenter="AHTooltip( 'img', '<?php echo $P_INFO["url_picture_1"] ?>' , this, event);" >[ Görüntüle ]</button>
					 		<button type="button" onclick="product_img_delete( <?php echo $P_INFO["id"] ?> , 1, 'variant' )">[ Sil ]</button>
				 		<?php }  ?>
				 	</div>

				</div>
			
				<div class="input-grup"><input type="submit" value="Kaydet" class="btn-buyuk"/></div>

			</div>

		</div>

	</form>



	<script type="text/javascript">
		rowNotf( "<?php echo $POST_OUTPUT; ?>",  <?php echo $OK; ?> );

	</script>