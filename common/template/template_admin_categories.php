	<div class="row-ust-uyari-cont" id="row-ust-notf"></div>
	<div class="row">
		<div class="row-top">

			<div class="row-nav clearfix">

				<div id="row-header"><h3> <?php echo $CATEGORY_HEADER ?> </span></div>

				<div class="row-nav-col"><button onclick="DT_functions.quick_add_form( 1 )" class="btn-buyuk" >Ekle</button></div>


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
						<td class="dt-small sort" onclick="DT_functions.sort_table('id')">ID</td>
						<td class="dt-small sort" onclick="DT_functions.sort_table('order_no')">Sıra No</td>
						<td class="dt-long  sort" onclick="DT_functions.sort_table('category_name')">Kategori Adı</td>
						<td class="sort" onclick="DT_functions.sort_table('status')"><a href="" class="bonibon yesilBonibon" onclick="return false;" title="Durum"></a></td>
						<td class="dt-small">ICO</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
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

		rowNotf( "<?php echo $POST_OUTPUT; ?>",  <?php echo $OK; ?> );

		// Datatable ayarlari default 
		DT_functions.template  = "<?php echo $TEMPLATE; ?>";
		DT_functions.axreq     = "<?php echo $AJAX_REQ; ?>";
		DT_functions.category_parent = "<?php echo $CURRENT; ?>";
		DT_functions.rrp       = <?php echo $Table->get_settings("rrp"); ?>;
		DT_functions.orderby   = '<?php echo $Table->get_settings("orderby"); ?>';
		DT_functions.direction = '<?php echo $Table->get_settings("direction"); ?>';

		form_templates["<?php echo $TEMPLATE; ?>"] = '<form action="" method="post" id="category_quick_edit" onsubmit="form_submit_v3( this, false, event )"> \
				<input type="hidden" name="type" value="%%type%%" /> \
				<input type="hidden" name="parent" value="%%parent%%" /> \
				<input type="hidden" name="item_id" value="%%0%%" /> \
				<div class="input-grup"> \
					<label class="t2" for="category_name">Kategori Adı</label> \
					<div class="input-control"> \
						<input type="text" class="t2 req" name="category_name" id="category_name" value="%%1%%"/> \
					</div> \
				</div> \
				<div class="input-grup"> \
					<label class="t2" for="order_no">Sıra</label> \
					<div class="input-control"> \
						<input type="text" class="t2 req posnum" name="order_no" id="order_no" value="%%2%%" /> \
					</div> \
				</div> \
				<div class="input-grup"> \
					<label class="t2" for="tags">Etiketler</label> \
					<div class="input-control"> \
						<input type="text" class="t2" name="tags" id="tags" value="%%3%%" /> \
					</div>  \
				</div> \
				<div class="input-grup"> \
					<label class="t2" for="title">Title</label> \
					<div class="input-control"> \
						<input type="text" class="t2" name="title" id="title" value="%%4%%" /> \
					</div>  \
				</div> \
				<div class="input-grup"> \
					<label class="t2" for="details">Açıklama</label> \
					<div class="input-control"> \
						<input type="text" class="t2" name="details" id="details" value="%%5%%" /> \
					</div>  \
				</div> \
				<div class="input-grup"> \
					<input type="submit" class="btn-buyuk" value="Kaydet" /> \
				</div> \
			</form>';

		// yeni icon upload popup
		function new_icon( item_id ){
			Popup.on( ' <div style="margin-bottom:10px;"> \
				<button type="button" onclick="product_img_delete( '+item_id+', 0, \'category\' )"><i class="sq_20x20 table-sil" title="Resmi Sil" ></i>Resmi Sil</button> \
					<button type="button" onmouseenter="AHTooltip(\'img\', \'http://ahsaphobby.net/v2/res/img/static/category_img/category-'+item_id+'.png\', this, event)" onclick="AHTooltip(\'img\', \'http://ahsaphobby.net/v2/res/img/static/category_img/category-'+item_id+'.png\', this, event)"> \
						<i class="sq_20x20 table-photo" onmouseenter="AHTooltip(\'img\', \'http://ahsaphobby.net/v2/res/img/static/category_img/category-'+item_id+'.png\', this, event)" title="Resmi Görüntüle"></i>Önizleme \
					</button> \
			</div> \
			<form action="" method="post" enctype="multipart/form-data" id="upload_form" onsubmit="form_submit_v3( this, true, event )"> \
				<input type="hidden" name="type" value="upload_icon" /> \
				<input type="hidden" name="item_id" value="'+item_id+'" /> \
				<div style="padding: 0 0 10px 0;border-bottom: 1px dashed #aaa;margin-bottom: 10px;">Geçerli uzantılar: png, jpeg ve gif</div> \
				<div class="input-grup"> \
					<label class="t2" >Dosya:</label> \
					<div class="input-control"> \
						<input type="file" name="file[]" class="t2 mgtop-7" /> \
					</div> \
				</div> \
				<div class="input-grup"><input type="submit" class="btn-buyuk" value="Kaydet" /></div> \
			</form>', "Kategori Logosu Yükle" );
		}
	</script>