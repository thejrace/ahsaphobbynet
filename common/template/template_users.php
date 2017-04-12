	<div class="row-ust-uyari-cont" id="row-ust-notf"></div>
	<div class="row">	

		<div class="row-top">
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
						<td class="dt-long sort"   onclick="DT_functions.sort_table('name')">Kullanıcı Adı</td>
						<td class="dt-small sort"   onclick="DT_functions.sort_table('email')">Eposta</td>
						<td class="dt-mid">Ürün Grubu</td>
						<td class="dt-small sort" onclick="DT_functions.sort_table('status')">Durum</td>
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

	</script>