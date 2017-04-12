<div class="row-ust-uyari-cont" id="row-ust-notf"></div>
	<div class="row">
		<div class="row-top">

			<div class="row-nav clearfix">

				<div id="row-header"><h3> <?php echo $VARIANT_HEADER ?> </span></div>

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
						<td class="sort" onclick="DT_functions.sort_table('variant_name')">Varyant Adı</td>
						<td></td>
						<td class="dt-small"></td>
						<td class="dt-small"></td>
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
		DT_functions.rrp       = <?php echo $V_Table->get_settings("rrp"); ?>;
		DT_functions.orderby   = '<?php echo $V_Table->get_settings("orderby"); ?>';
		DT_functions.direction = '<?php echo $V_Table->get_settings("direction"); ?>';

		form_templates["variants"] = 
			'<form action="" method="post" id="add_variant" onsubmit="form_submit_v3( this, false, event )"> \
				<input type="hidden" name="type" value="%%type%%" /> \
				<input type="hidden" name="item_id" value="%%0%%" /> \
				<div class="input-grup"> \
					<label class="t2" for="variant_name">Varyant Adı</label> \
					<div class="input-control"> \
						<input type="text" class="t2 req" name="variant_name" id="variant_name" value="%%1%%"/> \
					</div>  \
				</div> \
				<div class="input-grup"> \
					<input type="submit" class="btn-buyuk" value="Kaydet" /> \
				</div> \
			</form>';

	</script>