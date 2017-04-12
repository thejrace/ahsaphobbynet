<?php

	class DataTable {
		protected $table = "", $pagination, $type,
				  $settings = array( "page" => 1, "direction" => "ASC", "rrp" => 5, "orderby" => "id" );
		public function __construct( $type ){	
			$this->type = $type;
		}
		// template' e gore metodu bulan fonksiyon
		// gelen type ( template ) ile method adı aynı olcak
		public function create( $data, $recordCount ){
 			call_user_func_array( array( $this, $this->type ), array( $data, $recordCount ) );
 			// sayfalama
 			$Pagin = new Pagination;
			$this->pagination = $Pagin->create( $recordCount, $this->settings );
		}
		private function users( $data, $recordCount ){
			foreach( $data as $user ){
				$this->table .= 
					'<tr class="clearfix" >
						<td><input type="checkbox" /></td>
						<td data-th="ID">'.$user["id"].'</td>
						<td data-th="Kullanıcı Adı">'.$user["name"].'</td>
						<td data-th="Eposta">'.$user["email"].'</td>
						<td data-th="Ürün Grubu">'.$user["perm_level"].'</td>
						<td class="bonibon-panel"><a href="" class="bonibon yesilBonibon   '.$this->bonibonClass( $user["status"] ).'"            data="'.$user["id"].'" func="F0" onclick="DT_functions.bonibon_switch(this, event)" title="Durum"></a></td>	
						<td class="buton-panel"><button type="button"><i class="sq_20x20 table-h_duzenle" title="Eposta Gönder" ></i></button></td>
						<td class="buton-panel"><button type="button" onclick="DT_functions.delete_item('.$user["id"].' );"><i class="sq_20x20 table-sil" title="Sil"></i></button></td>
					</tr>';
			}
		}

		// vitrin
		private function showcase( $data, $recordCount ){
			// key sıra no için
			foreach( $data as $key => $product ){
				$this->table .= '
						<tr id="u_'.$product["id"].'">
							<td>'.$key.'</td>
							<td>'.$product["product_name"].'</td>
						</tr> ';
			}
		}
		// varyant ürünleri
		private function variant_products( $data, $recordCount ){
			foreach( $data as $urun ){
				$kdv_options = "";
				$status = array( 0 => "Hayır", 1 => "Evet" );
				foreach( $status as $key => $val ){
					$selected = "";
					if( $urun["kdv_included"] == $key ) $selected = "selected";
					$kdv_options .= '<option value="'.$key.'" '.$selected.' >'.$val.'</option>';
				}
					$this->table .= 
							'<tr>
								<td data-th="Ürün Adı"><div class="input-control"><input type="text" disabled class="intd" id="product_name_'.$urun["id"].'" value="'.$urun["product_name"].'" /></div></td>
								<td data-th="Stok Adedi"><div class="input-control"><input type="text" class="intd i-med posnum" id="stock_amount_'.$urun["id"].'" value="'.$urun["stock_amount"].'" /></div></td>
								
								<td data-th="KDV Dahil" >

								<div class="input-control">
									<select id="kdv_included_'.$urun["id"].'" class="t2">
										'.$kdv_options.'			
									</select>
								</div>	

									<input type="hidden" id="kdv_percentage_'.$urun["id"].'" value="'.$urun["kdv_percentage"].'" />
								</td>

								<td data-th="Fiyat 1"><div class="input-control"><input type="text" class="intd i-med req posnum not_zero" id="price_1_'.$urun["id"].'" value="'.$urun["pure_price_1"].'" /></div></td>
								<td data-th="Fiyat 2"><div class="input-control"><input type="text" class="intd i-med posnum" id="price_2_'.$urun["id"].'" value="'.$urun["pure_price_2"].'" /></div></td>
								<td data-th="Fiyat 3"><div class="input-control"><input type="text" class="intd i-med posnum" id="price_3_'.$urun["id"].'" value="'.$urun["pure_price_3"].'" /></div></td>
								<td data-th="Stok Kodu"><div class="input-control"><input type="text" class="intd i-med req" id="stock_code_'.$urun["id"].'" value="'.$urun["stock_code"].'" /></div></td>
								<td data-th="Desi"><div class="input-control"><input type="text" class="intd i-mini posnum" id="desi_'.$urun["id"].'" value="'.$urun["desi"].'" /></div></td>
								<td class="buton-panel"><button type="button" onclick="variant_save_item('.$urun["id"].');"><i class="sq_20x20 table-save" title="Kaydet"></i></button></td>
								<td class="buton-panel"><a href="edit_variant_product.php?pid='.$urun["id"].'" target="_blank"><i class="sq_20x20 table-duzenle" title="Detaylı Düzenle" ></i></a></td>
								<td class="bonibon-panel"><a href="" class="bonibon yesilBonibon '.$this->bonibonClass( $urun["status"] ).'" data="'.$urun["id"].'" func="F0" onclick="DT_functions.bonibon_switch(this, event)" title="Durum"></a></td>
								<td class="buton-panel"><button type="button" onclick="DT_functions.delete_item('.$urun["id"].');"><i class="sq_20x20 table-sil" title="Sil"></i></button></td>
							 </tr>';
			}
		}
		// ürüne tanımslı varyantlar ( ürün düzenleme )
		private function product_defined_variants( $data, $recordCount ){
			foreach( $data as $variant ) {
				$this->table .= 
						'<tr id="v_'.$variant["id"].'">
						<td data-th="Sıra">'.$variant["order_no"].'</td>
						<td data-th="Varyant Adı">'.$variant["variant_name"].'</td>
						<td class="buton-panel"><button type="button" onclick="variant_action( '.$variant["id"].', \'remove_variant\', event )"><i class="sq_20x20 table-sil" title="Sil"></i></button></td>
						</tr>
					';
			}
		}
		// ürüne tanımsız varyantlar ( ürün düzenleme )
		private function product_undefined_variants( $data, $recordCount ){
			foreach( $data as $variant ) {
				$this->table .= 
						'<tr>
							<td data-th="Varyant Adı">'.$variant["variant_name"].'</td>
							<td><a href="" class="bonibon yesilBonibon bonibonPasif" onclick="variant_action('.$variant["id"].', \'define_variant\', event )" title="Tanımla"></a></td>
						</tr>
					';
			}
		}
		// kategoriler
		private function categories( $data, $recordCount ){
			foreach( $data as $kategori ) {
				if( $kategori["icon"] == 0 ) {
					$ikon = '<td><button type="button" onclick="new_icon('.$kategori["id"].')"><i class="sq_20x20 table-duzenle" title="Resim Yükle" ></i></button></td>';
				} else {
					$img_url = RES_IMG_CATEGORY_IMG_URL . "category-".$kategori["id"].".png";
					$ikon = '<td>
								<button type="button" onclick="new_icon('.$kategori["id"].')"><i class="sq_20x20 table-duzenle" title="Resim Yükle" ></i></button>
								<button type="button" onclick="AHTooltip(\'img\', \''.$img_url.'\', this, event)">
									<i class="sq_20x20 table-photo" onmouseenter="AHTooltip(\'img\', \''.$img_url.'\', this, event);" title="Resmi Görüntüle"></i></button>
								</button>
							</td>';
				}
					

					$this->table .= 
						'<tr><td data-th="ID">'.$kategori["id"].'</td>
						<td data-th="Sıra No">'.$kategori["order_no"].'</td>
						<td data-th="Kategori Adı">'.$kategori["category_name"].'</td>

						<td><a href="" class="bonibon yesilBonibon '.$this->bonibonClass( $kategori["status"] ).'"" data="'.$kategori["id"].'" func="F0" onclick="DT_functions.bonibon_switch(this, event)" title="Durum"></a></td>
						
						'.$ikon.'

						<td><a href="?katid='.$kategori["id"].'">Alt Kategoriler</a></td>
						<td><a href="products.php?katid='.$kategori["id"].'" target="_blank">Ürünler</a></td>
						<td><a href="showcase.php?type=showcase_category&category_id='.$kategori["id"].'" target="_blank">Vitrin Düzenleme</a></td>
						<td><a href="">Kategori Seçenekleri</a></td>
						<td class="buton-panel"><button type="button" onclick="DT_functions.quick_edit('.$kategori["id"].', 1)"><i class="sq_20x20 table-duzenle" title="Düzenle" ></i></button></td>
						<td class="buton-panel"><a href="'.CATEGORY_URL.Common::sef_link($kategori["category_name"]).'/'.$kategori["id"].'" target="_blank"><i class="sq_20x20 table-preview" title="Sitede görüntüle"></i></a></td>	
						<td class="buton-panel"><button type="button" onclick="DT_functions.delete_item('.$kategori["id"].' )"><i class="sq_20x20 table-sil" title="Sil"></i></button></td>
						<input type="hidden" name="'.$kategori["id"].'" value="'.$kategori["status"].'" />
					</tr>
					';
			}
		}
		// ürünler
		private function products( $data, $recordCount ){
			foreach( $data as $product ){

				$Product = new Product( $product["id"] );
				$Product_Data = $Product->get_details();

				// Kategorinin ismini al
				if( $Product_Data["category"] == 0 ) {
					$category_name = "";
				} else {
					$Category = new Category( $Product_Data["category"] );
					$category_name = $Category->get_details("category_name");
				}
				
				$img_url = RES_IMG_STATIC_URL . "photo-default.png";
				if( $Product_Data["picture_1"] ) $img_url = $Product_Data["url_picture_1-resized"];
				$this->table .= 
					'<tr class="clearfix" >
						<td><input type="checkbox" /></td>
						<td data-th="ID">'.$Product_Data["id"].'</td>
						<td>
							<button type="button" onclick="AHTooltip(\'img\', \''.$img_url.'\', this, event)">
								<i class="sq_20x20 table-photo" onmouseenter="AHTooltip(\'img\', \''.$img_url.'\', this, event);" title="Resmi Görüntüle"></i></button>
							</button>
						</td>
						<td data-th="Ürün Adı">'.$Product_Data["product_name"].'</td>
						<td data-th="Kategori"><a href="?katid='.$Product_Data["category"].'">'.$category_name.'</a></td>
						<td data-th="Stok Kodu">'.$Product_Data["stock_code"].'</td>
						<td data-th="Miktar">'.$Product_Data["stock_amount"].'</td>
						<td data-th="Fiyat">'.$Product_Data["price_1"].'</td>
						<td class="bonibon-panel"><a href="" class="bonibon yesilBonibon   '.$this->bonibonClass( $Product_Data["status"] ).'"            data="'.$Product_Data["id"].'" func="F0" onclick="DT_functions.bonibon_switch(this, event)" title="Durum"></a></td>
						<td class="bonibon-panel"><a href="" class="bonibon kirmiziBonibon '.$this->bonibonClass( $Product_Data["showcase_home"] ).'"     data="'.$Product_Data["id"].'" func="F1" onclick="DT_functions.bonibon_switch(this, event)" title="Ana Sayfa Vitrini" ></a></td>
						<td class="bonibon-panel"><a href="" class="bonibon turuncuBonibon '.$this->bonibonClass( $Product_Data["showcase_category"] ).'" data="'.$Product_Data["id"].'" func="F2" onclick="DT_functions.bonibon_switch(this, event)" title="Kategori Vitrini"></a></td>
						<td class="bonibon-panel"><a href="" class="bonibon morBonibon     '.$this->bonibonClass( $Product_Data["campaign"] ).'"          data="'.$Product_Data["id"].'" func="F3" onclick="DT_functions.bonibon_switch(this, event)" title="Kampanyalı Ürün"></a></td>
						<td class="bonibon-panel"><a href="" class="bonibon maviBonibon    '.$this->bonibonClass( $Product_Data["new"] ).'"               data="'.$Product_Data["id"].'" func="F4" onclick="DT_functions.bonibon_switch(this, event)" title="Yeni Ürün"></a></td>
						<td class="bonibon-panel">

							<a href="" class="bonibon siyahBonibon   '.$this->bonibonClass( $Product_Data["variant"] ).'"  data="'.$Product_Data["id"].'" func="F5" onclick="DT_functions.bonibon_switch(this, event)" title="Seçenekli Ürün"></a>
						</td>
						<td class="bonibon-panel"><a href="" class="bonibon sariBonibon    '.$this->bonibonClass( $Product_Data["has_form"] ).'"     data="'.$Product_Data["id"].'" func="F6" onclick="DT_functions.bonibon_switch(this, event)" title="Formlu Ürün"></a></td>
						<td class="buton-panel"><button type="button" onclick="DT_functions.quick_edit('.$Product_Data["id"].', 0 )"><i class="sq_20x20 table-h_duzenle" title="Hızlı Düzenle" ></i></button></td>
						<td class="buton-panel"><a href="edit_product.php?pid='.$Product_Data["id"].'" target="_blank"><i class="sq_20x20 table-duzenle" title="Detaylı Düzenle" ></i></a></button></td>
						<td class="buton-panel"><a href="'.$Product_Data["url"].'" target="_blank"><i class="sq_20x20 table-preview" title="Sitede görüntüle"></i></a></td>	
						<td class="buton-panel"><button type="button" onclick="DT_functions.delete_item('.$Product_Data["id"].' );"><i class="sq_20x20 table-sil" title="Sil"></i></button></td>
				</tr>
			';
						
			}
		}
		// silinmiş ürünler
		private function deleted_products( $data, $recordCount ){
			foreach( $data as $product ){
				$img_url = "";
				if( $product["picture_1"] ) $img_url = RES_IMG_PRODUCT_IMG_URL . "product-".$product["id"]."-1-resized-deleted.png";
				$this->table .= 
					'<tr class="clearfix" >
						<td><input type="checkbox" /></td>
						<td data-th="ID">'.$product["id"].'</td>
						<td>
							<button type="button" onclick="AHTooltip(\'img\', \''.$img_url.'\', this, event)">
								<i class="sq_20x20 table-photo" onmouseenter="AHTooltip(\'img\', \''.$img_url.'\', this, event);" title="Resmi Görüntüle"></i></button>
							</button>
						</td>
						<td data-th="Ürün Adı">'.$product["product_name"].'</td>
						<td data-th="Kategori">'.$product["category"].'</td>
						<td data-th="Stok Kodu">'.$product["stock_code"].'</td>
						<td data-th="Miktar">'.$product["stock_amount"].'</td>
						<td data-th="Fiyat">'.$product["price_1"].'</td>
						<td class="buton-panel"><button type="button" onclick="undo_deleted_product('.$product["id"].')"><i class="sq_20x20 table-geri_al" title="Geri Al"></i></button></td>
						<td class="buton-panel"><button type="button" onclick="DT_functions.delete_item('.$product["id"].' );"><i class="sq_20x20 table-sil" title="Sil"></i></button></td>
					</tr>
			';
						
			}
		}
		// ana varyantlar
		private function variants( $data, $recordCount ){
			foreach( $data as $variant ) {
					$this->table .= 
						'<tr>
							<td data-th="ID">'.$variant["id"].'</td>
							<td data-th="Varyant Adı">'.$variant["variant_name"].'</td>
							<td><a href="sub_variants.php?varid='.$variant["id"].'" target="_blank">Alt Varyantlar</a></td>
							<td class="buton-panel"><button type="button" onclick="DT_functions.quick_edit('.$variant["id"].', 1)"><i class="sq_20x20 table-duzenle" title="Düzenle" ></i></button></td>
							<td class="buton-panel"><button type="button" onclick="DT_functions.delete_item('.$variant["id"].' )"><i class="sq_20x20 table-sil" title="Sil"></i></button></td>
						</tr>
					';
			}
		}
		// alt varyantlar
		private function sub_variants( $data, $recordCount ){
			foreach( $data as $variant ) {
					$this->table .= 
						'<tr>
							<td data-th="ID">'.$variant["id"].'</td>
							<td data-th="Varyant Adı">'.$variant["variant_name"].'</td>
							<td class="buton-panel"><button type="button" onclick="DT_functions.quick_edit('.$variant["id"].', 1)"><i class="sq_20x20 table-duzenle" title="Düzenle" ></i></button></td>
							<td class="buton-panel"><button type="button" onclick="DT_functions.delete_item('.$variant["id"].' )"><i class="sq_20x20 table-sil" title="Sil"></i></button></td>
						</tr>
					';
			}
		}
		// Kullanicinin degistirdigi ayarlari al
		// Degistirmemisse default ayarlari kullan
		public function set_settings( $in ) {
			// Tum ayarlari degismisler mi diye kontrol et
			// Ayarlar boş array' se yukarda default tanımlı ayarları al
			foreach( $this->settings as $key => $val){
				if( !empty( $in ) ){
					// isset(in[$key]) AND in solunda olacak, sikinti yaratti tersinde
					// demek ki once ilk kosula bakiyor yanlissa ikinciye bakmiyo bile dogal olarak
					if( isset($in[$key]) && $in[$key] != "") {
						$this->settings[$key] = $in[$key];
					} 	
				}	
			}
		}
		// dt ayarlarinin get 
		public function get_settings( $key = null ){
			if( $key != null && isset($this->settings[$key]) ) {
				return $this->settings[$key];
			} else {
				return $this->settings;
			}
		}
		// bonibon pasif class switch
		private function bonibonClass( $data ){
			( $data ) ? $r = '' : $r = 'bonibonPasif';
			return $r;
		}
		// table output
		public function show_table(){
			return $this->table;
		}
		// pagin output
		public function show_pagination(){
			return $this->pagination;
		}
	}