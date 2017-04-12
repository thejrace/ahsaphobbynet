	<div class="row-ust-uyari-cont" id="row-ust-notf"></div>
	<div class="row">

		<ul class="tab static">
			<li class="tab-btn" id="showcase_home"><a href="?type=showcase_home">Anasayfa Vitrini</a></li>
			<li class="tab-btn" id="showcase_new"><a href="?type=showcase_new">Yeni Ürünler Vitrini</a></li>
			<li class="tab-btn" id="showcase_category"><a href="?type=showcase_category">Kategori Vitrini</a></li>
			<li class="tab-btn" id="showcase_campaign"><a href="?type=showcase_campaign">Kampanyali Ürünler Vitrini</a></li>
		</ul>

		<div class="row-top">

			<div class="row-nav clearfix">

				<div id="row-header"><h3 id="row-header-content"> <?php echo $SHOWCASE_HEADER ?></h3></div>

				<div class="clearfix">
					<div class="row-nav-col">
						<?php 
							echo $SEARCH_CATEGORIES;
						 ?>
						<input type="hidden" name="category_id" id="category_id" value="" />
						<input type="hidden" name="showcase_type" id="showcase_type" value="<?php echo $SHOWCASE_TYPE ?>" />
					</div>
					<?php 
						// Kategori seç butonu
						if( $SHOWCASE_TYPE == "showcase_category" ) { ?>
							<div class="row-nav-col"><button type="button" class="btn-buyuk mgtop-7 temp-kirmizi" onclick="showcase_category_get()" >Seç</button></div>
						<?php } ?>
				</div>
				<div class="row-nav-col"><button type="button" class="btn-buyuk" onclick="showcase_save()" >Kaydet</button></div>

				<div class="row-nav-col">
					<img src="http://ahsaphobby.net/resources/img/static/spinner-row.gif" id="row-spinner" class="row-spinner"/>
					<span id="row-spinner-info">Lütfen bekleyin...</span>
				</div>

			</div>



		</div>
		<div class="data-table-cont" id="data-table-cont">
			
			<div class="pagination-container blocked clearfix" id="pagin-top">
				<?php echo $PAGIN; ?>
			</div>

			<table class="data-table">
				<thead>
					<tr>
						<td class="dt-small sort" onclick="DT_functions.sort_table('order_no')">Sıra</td>
						<td class="dt-long  sort" onclick="DT_functions.sort_table('product_name')">Ürün Adı</td>
					</tr>
				</thead>
				<tbody id="data-table-body" >

						<?php echo $DATA_TABLE;  ?>
				</tbody>

			</table>
		
			<div class="pagination-container blocked clearfix" id="pagin-bottom">
				<?php echo $PAGIN; ?>
			</div>

		</div> <!-- data table cont -->
		

	</div> <!-- row -->

	<script src="<?php echo RES_JS_URL ?>jquery.js"></script>
	<script src="<?php echo RES_JS_URL ?>jquery-ui.js"></script>

	<script type="text/javascript"> 

		// Her ajax requestte showcase_type ve category_id gidecek

		// Sırayı kaydetme ( herhangi bir değişiklik yapilmadiginda sorted boş kaldıgı icin, reload oluyor. )
		function showcase_save(){
			DT_functions.req_extra_data = { showcase_type:get_val('showcase_type'), category_id:get_val('category_id') };
			// sorted hareketi yapilmazsa ajax fail oluyor
			DT_functions.request( 'save_showcase', sorted, false );
			// yeni sira listesini de boşaltıyoruz ki, baska kategori duzenlenirken
			// eski liste hafizada kalmasin
			sorted = "";
		}

		// Vitrini getirilecek kategoriyi seçme
		function showcase_category_get(){
			// Burda kategori ID yi selecte bagli inputtan aliyorum
			DT_functions.request( 'category_select', { showcase_type:get_val('showcase_type'), category_id:get_val('category_id') }, false );
		}

		// Liste sirasinin yazilacagi var
		var sorted = "";
		$( "#data-table-body" ).sortable({
			update: function(){
				sorted = $( "#data-table-body" ).sortable( "serialize");
			}
		});	

		// 3 farklı vitrin tipine gore hangi tab secilecek
		var Static_Tab = new AHTab({});
		Static_Tab.offline_mode("<?php echo $SHOWCASE_TYPE ?>")

		// Datatable ayarlari default 
		DT_functions.template  = "<?php echo $TEMPLATE; ?>";
		DT_functions.axreq     = "<?php echo $AJAX_REQ; ?>";
		DT_functions.rrp       = <?php echo $Table->get_settings("rrp"); ?>;
		DT_functions.orderby   = '<?php echo $Table->get_settings("orderby"); ?>';
		DT_functions.direction = '<?php echo $Table->get_settings("direction"); ?>';

	</script>