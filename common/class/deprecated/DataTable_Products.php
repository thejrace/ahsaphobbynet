<?php
	

	// DEPRECATED

	class Product_DataTable {

		protected $table = "", $pagination;

		public function create( $data, $recordCount, $settings ){

			$Pagin = new Pagination;

			$this->pagination = $Pagin->create( $recordCount, $settings );
		
			foreach( $data as $product ){
				$this->table .= 
					'<tr class="clearfix" >
						<td><input type="checkbox" /></td>
						<td data-th="ID">'.$product["id"].'</td>
						<td>ICO</td>
						<td data-th="Ürün Adı">'.$product["urun_adi"].'</td>
						<td data-th="Kategori">'.$product["kategori"].'</td>
						<td data-th="Stok Kodu">'.$product["stok_kodu"].'</td>
						<td data-th="Miktar">'.$product["stok_adedi"].'</td>
						<td data-th="Fiyat">'.$product["fiyat1"].'</td>
						<td class="bonibon-panel"><a href="" class="bonibon yesilBonibon   '.$this->bonibonClass( $product["durum"] ).'"           data="'.$product["id"].'" func="F0" onclick="DT_functions.bonibon_switch(this); return false;" title="Durum"></a></td>
						<td class="bonibon-panel"><a href="" class="bonibon kirmiziBonibon '.$this->bonibonClass( $product["vitrin_anasayfa"] ).'" data="'.$product["id"].'" func="F1" onclick="DT_functions.bonibon_switch(this); return false;" title="Ana Sayfa Vitrini" ></a></td>
						<td class="bonibon-panel"><a href="" class="bonibon turuncuBonibon '.$this->bonibonClass( $product["vitrin_kategori"] ).'" data="'.$product["id"].'" func="F2" onclick="DT_functions.bonibon_switch(this); return false;" title="Kategori Vitrini"></a></td>
						<td class="bonibon-panel"><a href="" class="bonibon morBonibon     '.$this->bonibonClass( $product["kampanyali_urun"] ).'" data="'.$product["id"].'" func="F3" onclick="DT_functions.bonibon_switch(this); return false;" title="Kampanyalı Ürün"></a></td>
						<td class="bonibon-panel"><a href="" class="bonibon maviBonibon    '.$this->bonibonClass( $product["yeni_urun"] ).'"       data="'.$product["id"].'" func="F4" onclick="DT_functions.bonibon_switch(this); return false;" title="Yeni Ürün"></a></td>
						<td class="bonibon-panel">

							<a href="" class="bonibon siyahBonibon   '.$this->bonibonClass( $product["secenekli_urun"] ).'"  data="'.$product["id"].'" func="F5" onclick="DT_functions.bonibon_switch(this); return false;" title="Seçenekli Ürün"></a>
						</td>
						<td class="bonibon-panel"><a href="" class="bonibon sariBonibon    '.$this->bonibonClass( $product["formlu_urun"] ).'"     data="'.$product["id"].'" func="F6" onclick="DT_functions.bonibon_switch(this); return false;" title="Formlu Ürün"></a></td>



						<td class="buton-panel"><button type="button" onclick="DT_functions.quick_edit('.$product["id"].')"><i class="table-duzenle" title="Hızlı Düzenle" ></i></button></td>
						<td class="buton-panel"><button type="button"><a href="urun-duzenle.php?uid='.$product["id"].'" target="_blank"><i class="table-duzenle" title="Detaylı Düzenle" ></i></a></button></td>
						<td class="buton-panel"><button type="button" data="'.$product["id"].'" func="stats" onclick="AHDataTable.func(this)"><i class="table-istatistik" title="İstatistikler"></i></button></td>	
						<td class="buton-panel"><button type="button" data="'.$product["id"].'" func="sil" onclick="AHDataTable.delete(this,\'Ürünü silmek istediğinizden emin misiniz? \')"><i class="table-sil" title="Sil"></i></button></td>
						<input type="hidden" name="'.$product["id"].'" value="'.$product["durum"].'" />
				</tr>
			';
						
			}

		}

		// Bonibonlar
		protected function bonibonClass( $data ){
			( $data ) ? $r = '' : $r = 'bonibonPasif';
			return $r;
		}

		public function show_table(){
			return $this->table;
		}

		public function show_pagination(){
			return $this->pagination;
		}

	}