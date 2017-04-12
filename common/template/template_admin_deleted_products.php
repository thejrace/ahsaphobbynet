	<div class="row-ust-uyari-cont" id="row-ust-notf"></div>
	<div class="row">
		<div class="row-top">
			
			<div class="arama">

				<div class="dt-arama-toggle">
					<span><button type="button" class="btn-buyuk temp-mavi" onclick="DTAramaToggle()">Arama</button>
				</div>
				<div id="dt-arama-cont">
					<form action="" method="post" id="product_search"  onsubmit="DT_functions.search( 'ProdSearch', this, event );" >
						<input type="hidden" id="category_id" name="category_id" value="0"/>
									
						<?php echo $SEARCH_CATEGORIES; ?>			

						<div class="input-grup">
							<label class="t2" for="product_name" >Ürün Adı</label>
							<div class="input-control">
								<input type="text" value="" name="product_name" id="product_name" class="t2" />
							</div>
						</div>
						<div class="input-grup">

							<div class="input-control">
								<label class="t2" for="price_from">Fiyat</label>
								<input type="text" class="t1 mini posnum" id="price_from" name="price_from" />
								<div class="input-tire">-</div>
								<input type="text" class="t1 mini posnum" id="price_to" name="price_to"/>
							</div>

						</div>

						<div class="input-grup">
							<label class="t2" for="product_id" >ID</label>
							<div class="input-control">
								<input type="text" value="" name="product_id" id="product_id" class="t2"/>
							</div>
						</div>
						<div class="input-grup">
							<label class="t2" for="stock_code" >Stok Kodu</label>
							<div class="input-control">
								<input type="text" value="" name="stock_code" id="stock_code" class="t2"/>
							</div>
						</div>						
						<div class="input-grup">
							<div class="input-control">
								<label class="t2" for="stock_amount">Stok Adedi</label>
								<input type="text" class="t2 posnum" id="stock_amount" name="stock_amount" />
								<div class="input-tire">-</div>
								<select name="stock_amount_sym" id="stock_amount_sym" class="t2">
									<option value="0">=</option>
									<option value="1"><=</option>
									<option value="2">>=</option>
								</select>
							</div>
						</div>
						<div class="input-grup">
							<label class="t2" for="specs" >Özellikleri</label>
							<div class="input-control">
								<select name="specs" id="specs" class="t2">
									<option value="0">Seçiniz..</option>
									<option value="1">İndirimli</option>
									<option value="2">Seçenekli</option>
									<option value="3">Ana Sayfa Vitrini</option>
									<option value="4">Resmi Olmayan Ürünler</option>
									<option value="5">Açıklaması Olmayan Ürünler</option>
								</select>
							</div>
						</div>


						<div class="input-grup"><input type="submit" value="Ara" class="btn-buyuk"/></div>

					</form>
	
				</div>

			</div>

			<div class="row-nav clearfix">

				<div class="row-nav-col">
					<img src="http://ahsaphobby.net/resources/img/static/spinner-row.gif" id="row-spinner" class="row-spinner"/>
					<span id="row-spinner-info">Lütfen bekleyin...</span>
				</div>

			</div>

		</div>
		<div class="data-table-cont" id="data-table-cont">
			<div class="pagination-container clearfix" id="pagin-top">
				<?php echo $PAGIN; ?>
			</div>
		
			<table class="data-table">
				<thead>
					<tr>
						<td class="dt-small"><input type="checkbox" /></td>
						<td class="dt-small sort" onclick="DT_functions.sort_table('id')">ID</td>
						<td class="dt-small"></td>
						<td class="dt-long sort"   onclick="DT_functions.sort_table('product_name')">Ürün Adı</td>
						<td class="dt-small sort"   onclick="DT_functions.sort_table('category')">Kategori</td>
						<td class="dt-mid sort" onclick="DT_functions.sort_table('stock_code')">Stok Kodu</td>
						<td class="dt-small sort" onclick="DT_functions.sort_table('stock_amount')">Stok Adedi</td>
						<td class="dt-small sort" onclick="DT_functions.sort_table('price_1')">Fiyat</td>
						<td></td>
						<td></td>

					</tr>
				</thead>
				<tbody id="data-table-body">
						<?php echo $DATA_TABLE;  ?>
				</tbody>

			</table>
		
			<div class="pagination-container clearfix" id="pagin-bottom">
				<?php echo $PAGIN; ?>
			</div>

		</div> <!-- data table cont -->
		

	</div> <!-- row -->

		<script type="text/javascript"> 

		// Datatable ayarlari default 
		DT_functions.template  = "<?php echo $TEMPLATE; ?>";
		DT_functions.axreq     = "<?php echo $AJAX_REQ; ?>";
		DT_functions.rrp       = <?php echo $Product_Table->get_settings("rrp"); ?>;
		DT_functions.orderby   = '<?php echo $Product_Table->get_settings("orderby"); ?>';
		DT_functions.direction = '<?php echo $Product_Table->get_settings("direction"); ?>';

		// Ürünü geri alma
		function undo_deleted_product( pid ){
			DT_functions.request('undo_deleted_product', { item_id : pid }, false );
		}

		</script>