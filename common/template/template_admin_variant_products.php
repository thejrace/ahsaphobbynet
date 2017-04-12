	<div class="row-ust-uyari-cont" id="row-ust-notf"></div>
	<div class="row">

		<div class="row-nav row-top">
			<div class="row-top">
				<div id="row-header">
					<h3> "<?php echo $P_NAME; ?>" ürününe yeni seçenek ekle </h3>
				</div>

				<div class="row-nav clearfix">
					<div class="row-nav-col">
						<img src="http://ahsaphobby.net/resources/img/static/spinner-row.gif" id="row-spinner" class="row-spinner"/>
						<span id="row-spinner-info">Lütfen bekleyin...</span>
					</div>

				</div>

			</div>

		</div>

		<form action="" method="post" id="hederbede" onsubmit="form_submit_v3( this, false, event );">
			<table class="data-table varyant">
				<thead>
					<tr>
						<td><div class="input-control"><input type="text" disabled class="intd"         value="Seçenek Adı" /></div></td>
						<td><div class="input-control"><input type="text" disabled class="intd i-med"   value="Stok Adedi" /></div></td>
						<td><div class="input-control"><input type="text" disabled class="intd i-med"   value="KDV Dahil" /></div></td>
						<td><div class="input-control"><input type="text" disabled class="intd i-med"   value="Fiyat 1" /></div></td>
						<td><div class="input-control"><input type="text" disabled class="intd i-med"   value="Fiyat 2" /></div></td>
						<td><div class="input-control"><input type="text" disabled class="intd i-med"   value="Fiyat 3" /></div></td>
						<td><div class="input-control"><input type="text" disabled class="intd i-mini"  value="Desi" /></div></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				</thead>

				<tbody>
					<tr>
						<td>
							<div class="varyant-input-cont">

								<?php echo $VARIANT_SELECT; ?> 
								
							</div>
						</td>

						<td><div class="input-control"><input type="text"  class="intd i-med posnum" name="stock_amount" id="stock_amount"  value="0" /></div></td>

						<td>
							<div class="input-control">
								<select name="kdv_included" id="kdv_included" class="intd i-med">
									<option value="1">Evet</option>
									<option value="0">Hayır</option>
								</select>
							</div>
						</td>

						<td><div class="input-control"><input type="text"  class="intd i-med req posnum not_zero" name="price_1" id="price_1" value="0.000" /></div></td>
						<td><div class="input-control"><input type="text"  class="intd i-med posnum" name="price_2" id="price_2" value="0.000" /></div></td>
						<td><div class="input-control"><input type="text"  class="intd i-med posnum" name="price_3" id="price_3"  value="0.000" /></div></td>
						<td><div class="input-control"><input type="text"  class="intd i-mini posnum" name="desi" id="desi" value="0" /></div></td>
						<input type="hidden" name="parent" value="<?php echo $P_ID; ?>" />
						<input type="hidden" name="kdv_percentage" value="<?php echo $P_KDV; ?>" />
						<input type="hidden" name="type" value="AddVariant" />
						<td><input type="submit" class="btn-kucuk" value="KAYDET" /></td>
						<td><input type="reset" class="btn-kucuk temp-mavi" value="TEMİZLE" /></td>
						<td></td>
						<td></td>
					</tr>

				</tbody>
			</table>
		</form>
	</div>

	<div class="row">
		<div class="row-nav row-top">

			<div id="row-header">
				<h3> Mevcut Seçenekler </h3>
			</div>

			<div class="row-nav-col">
				<img src="http://ahsaphobby.net/resources/img/static/spinner-row.gif" id="row-spinner" class="row-spinner"/>
				<span id="row-spinner-info">Lütfen bekleyin...</span>
			</div>
		</div>

		<div class="pagination-container clearfix" id="pagin-top">
			
			<?php echo $PAGIN; ?>
		
		</div>
		
		<table class="data-table">
			<thead>
				<tr>
					<td class="sort" onclick="DT_functions.sort_table('product_name')">Seçenek Adı</td>
					<td class="sort" onclick="DT_functions.sort_table('stock_amount')">Stok Adedi</td>
					<td >KDV Dahil</td>
					<td class="sort" onclick="DT_functions.sort_table('price_1')">Fiyat 1</td>
					<td class="sort" onclick="DT_functions.sort_table('price_2')">Fiyat 2</td>
					<td class="sort" onclick="DT_functions.sort_table('price_3')">Fiyat 3</td>
					<td class="sort" onclick="DT_functions.sort_table('stock_code')">Stok Kodu</td>
					<td class="sort" onclick="DT_functions.sort_table('desi')">Desi</td>
					
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</thead>
			<tbody id="data-table-body">
				
					<?php echo $DATA_TABLE; ?>
				
			</tbody>
		</table>

		<div class="pagination-container clearfix" id="pagin-bottom">
			
			<?php echo $PAGIN; ?>
		
		</div>

	</div>

	<script type="text/javascript">

		// Datatable ayarlari default 
		DT_functions.template  = "<?php echo $TEMPLATE; ?>";
		DT_functions.axreq     = "<?php echo $AJAX_REQ; ?>";
		DT_functions.rrp       = <?php echo $PV_Table->get_settings("rrp"); ?>;
		DT_functions.orderby   = '<?php echo $PV_Table->get_settings("orderby"); ?>';
		DT_functions.direction = '<?php echo $PV_Table->get_settings("direction"); ?>';

		function variant_save_item( id ){
			var data = [],
				inputs = [ "stock_amount", "kdv_included", "stock_code", "kdv_percentage", "price_1", "price_2", "price_3", "desi" ],
				elem_id, i, l = inputs.length, error_count = 0;
			for( i = 0; i < l; i++ ){
				// inputlar sonlarinda '_ID' olacak sekildeler
				elem_id = inputs[i] +'_'+ id;
				// klasik validation yapiyoruz
				// bir sikinti yoksa data array'e ekliyoruz,
				// varsa error gosterip looptan cikiyoruz
				FormValidation.check_input( $AH( elem_id ) );
				if( FormValidation.is_valid() ){
					data[inputs[i]] = get_val( elem_id );
					FormValidation.hide_error( $AH(elem_id));
				} else {
					error_count++;
					FormValidation.show_errors();
					// continue;
				}
			}
			// varyant id sini de ekle
			data["item_id"] = id;
			// inputlarda hata ciktiysa request yapma
			// input outputtan kucuk olmamali
			// console.log( data );
			if( error_count > 0 ) return;
			// console.log( data );
			// hata yoksa datalari yolla, table reload yap
			DT_functions.request( 'VariantEdit', data, false );
							
		}
		

	</script>