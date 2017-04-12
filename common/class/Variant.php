<?php

	class Variant {
		// TODO
		/*
			- Silme ve degistirme islemlerinde tanimli varyant urun varsa onlarla ilgili operasyon yapilacak
			- var defler icin parent_product, sub_var lar icin parent_var prop lari olusturmak lazim
			- variant urun icin status pasif yapildiginda bile varyant selectlerde listeleme yapiliyor
			onun onune gecmek lazim
		*/
		private $pdo;
		protected $table = DBT_MAIN_VARIANT, $valid = false, $details = array(), $is_sub_flag = false, $subs = array();

		public function __construct( $sub, $id = null ){
			$this->pdo = DB::getInstance();
			// alt varyant ayrımı
			if( $sub ) {
				$this->table = DBT_SUB_VARIANT;
				$this->is_sub_flag = true;
			}
			if( $id != null ){
				$query = $this->pdo->query("SELECT * FROM " . $this->table . " WHERE id = ?", array($id) )->results();
				if( count($query) == 1 ){
					$this->details = $query[0];
					$this->valid = true;
				}
			}
		}

		// product page varyant selecti
		// counter Product class indan geliyor kacinci tanimli varyant oldugu bilgisi
		public function create_standart_select_template( $counter, $parent_product ){
			$html = "";
			$disabled = " disabled ";
			if( $this->has_sub() ){
				// html template
				// eger ilk varyant selecti ise enable olacak
				if( $counter == 1 ) $disabled = "";
				$main_variant_name = $this->details["variant_name"];

				// font için ayri bi template oldugu icin onu olusturuyoruz
				if( $main_variant_name == 'Font' ){
					$html .= 
						'<div class="input-grup">
								<input type="hidden" id="variant_name_'.$counter.'" value="'.$main_variant_name.'" />
								<label class="t2 editor-font-label">Font</label>
								<div class="font-select clearfix" >';
					$first_font_counter = 0;
					foreach( $this->get_subs() as $sub_var ){
						if( $this->first_variant_defined_sub( $sub_var["id"], $parent_product ) ){
							if( $first_font_counter == 0 ) {
								$html .= '<a href="" class="font-selected" id="variant_'.$counter.'" value="'.$sub_var["id"].'"><img alt="'.$sub_var["variant_name"].'" src="'.RES_IMG_STATIC_URL.'font_prev/font_'.$sub_var["id"].'.png"/></a><ul class="font-list">';
							}
							$html .= '<li class="foption" value="'.$sub_var["id"].'" id="variant_'.$counter.'"><img alt="'.$sub_var["variant_name"].'" src="'.RES_IMG_STATIC_URL.'font_prev/font_'.$sub_var["id"].'.png"/></li>';		
							$first_font_counter++;
						}
					}
					$html .= '</ul></div></div>';
				} else {
				// normal template
					$html .= 
						'<div class="input-grup" ><label class="t2 varyant-label" for="variant_'.$this->details["id"].'">'.$main_variant_name.'</label>
							<input type="hidden" id="variant_name_'.$counter.'" value="'.$main_variant_name.'" />
								<div class="input-control" id="v-cont_'.$counter.'">
									<select '.$disabled.' name="variant_'.$counter.'"  id="variant_'.$counter.'" class="t2 varyant-select" onchange="Variants.change( this, false )" >
										<option value="0">'.$main_variant_name.' seçiniz...</option>';

			
					foreach( $this->get_subs() as $sub_var ){
						if( $this->first_variant_defined_sub( $sub_var["id"], $parent_product ) ){
							$html .= '<option value='.$sub_var["id"].'>'.$sub_var["variant_name"].'</option>';
						}
						
					}
					$html .= '</select></div></div>';
				}				
			}
			return $html;
		}

		// ilk listelenen varyantta, ilgili varyantin tum alt varyantlari
		// listeleniyor, sadece tanimlanmis urun olanlari listelemek icin
		// kontrol ediyoruz
		private function first_variant_defined_sub( $var_id, $parent_product ){
			foreach( $this->pdo->query("SELECT id, variant_code FROM " . DBT_VARIANT_PRODUCTS . " WHERE parent = ?",array( $parent_product ))->results() as $var ){
				$code_array = explode( "-", $var["variant_code"] );
				if( $code_array[0] == $var_id ) return true;
			}
			return false;
		}

		// varyant select degistirilince siradaki varyantlarin
		// optionlarini array olarak don. ikinci versiyon bu eskiden html donuyordum
		public function create_next_select( $data ){
			// siradaki varyantin optionlarinin arrayi
			$next_options = array();
			// varyant to örn variant_2 ( geleninin bir fazlasi )
			$next_variant_id = $data["variant_no"] + 1;
			$select = "";
			// urunun tum varyant urunlerini aliyoruz
			$all_variant_products = $this->pdo->query( " SELECT * FROM ". DBT_VARIANT_PRODUCTS ." WHERE parent = ?", array( $data["item_id"] ) );
			// son varyantta işlem yapmıyoruz.
			if( $data["variant_name"] != 'obarey' ){
				// herbir alt ürün icin;
				foreach( $all_variant_products->results() as $urun ){
					// varyant kodunu alip parcaliyoruz
					$codes = explode( '-', $urun["variant_code"] );
					$code_count = count($codes);
					$code_check = array();
					for( $i = 0; $i < $code_count; $i++ ){
						if( $i != $data["variant_no"] ){
							$code_check[] = $codes[$i];
						} else {
							break;
						}
					}
					$code_check_str = implode( '-', $code_check ); 
					if( $code_check_str == $data["code_check"]  ){
						// ayni kod sirasinda birden fazla varyant urun olabilir
						// örn 1 - 4 - 7, 1 - 4 - 10 olabilir
						// oyle bir durum varsa tekrar 4 ü eklemiyoruz option liste
						if( !in_array( $codes[ $data["variant_no"] ], $next_options )) $next_options[] = $codes[ $data["variant_no"]  ];	
					} 
				}
				$options = array( array( 0, $data["variant_name"] . " seçiniz..." ) );				
				foreach( $next_options as $option ){
					$option_name_query = $this->pdo->query( " SELECT * FROM ".DBT_SUB_VARIANT." WHERE id = ?", array( $option ) )->results();
					$options[] = array( $option, $option_name_query[0]["variant_name"] );
				}
				return $options;
			} else {
				return false;
			}
		}

		// ana varyantin alt varyantlarini al
		private function get_subs(){
			return $this->subs;
		}
		// alt varyanti var mi kontrol
		private function has_sub(){
			foreach( $this->pdo->query("SELECT * FROM " . DBT_SUB_VARIANT . " WHERE parent = ?", array( $this->details["id"] ) )->results() as $var ){
				$this->subs[] = $var;
			}
			return count($this->subs) > 0;
		}

		// alt ve ana varyantlar için tek fonksiyon
		public function add( $in ){
			if( $this->is_sub() ){
				return $this->pdo->query("INSERT INTO ". $this->table. " SET variant_name = ?, parent = ?", array( $in["variant_name"], $in["parent"] ) );
			} else {
				return $this->pdo->query("INSERT INTO ". $this->table. " SET variant_name = ?", array( $in["variant_name"] ) );
			}
		}

		public function edit( $in ){
			return $this->pdo->query("UPDATE ". $this->table. " SET variant_name = ? WHERE id = ?", array( $in["variant_name"], $this->details["id"] ) );
		}

		public function delete(){
			return $this->pdo->query("DELETE FROM ". $this->table . " WHERE id = ?", array( $this->details["id"]) );
		}

		public function exists(){
			return $this->valid;
		}

		public function get_details(){
			return $this->details;
		}

		public function return_all(){
			return $this->pdo->query("SELECT * FROM " . $this->table )->results();
		}

		public function is_sub(){
			return $this->is_sub_flag;
		}

		public function get_cropped_details(){
			return array(
				"id" => $this->details["id"],
				"variant_name" => $this->details["variant_name"]
			);
		}
		
		// Varyant mevcut seçenekleri düzenleme sayfasında listeledigim select
		// @def_list = listelenmis urune tanimli varyantlar
		public function list_all_as_select( $def_list ){
			$html = "";
			$defined_var_ids = array();
			foreach( $def_list as $def_var ){
				// Ana varyantın alt varyantı var mı kontrol et
				// Yoksa selecte ekleme
				$sub_query = $this->pdo->query( " SELECT * FROM ".DBT_SUB_VARIANT." WHERE parent = ? ", array( $def_var["id"] ) );
				if( $sub_query->count() > 0 ){
					$html .= '
						<div class="input-control">
							<select name="variant[]" id="varyant_'.$def_var["id"].'" class="intd" >
								<option value="0">'.$def_var["variant_name"].' seçiniz...</option>';
					// Altvaryant optionlarını olustur
					foreach( $sub_query->results() as $r ){
						$html .= '<option value="'.$r["id"].'">'.$r["variant_name"].'</option>';
					}
				}
				$html .= '</select></div>';
				
			}
			return $html;
		}
	}


	/*
	DEPRECATED

		// varyant select degistirilince siradaki varyantlarin
		// selectlerini olusturma ajax_products.php de kullaniyorum
		public function create_next_select( $data ){
			// siradaki varyantin optionlarinin arrayi
			$next_options = array();
			// varyant to örn variant_2 ( geleninin bir fazlasi )
			$next_variant_id = $data["variant_no"] + 1;
			$select = "";
			// urunun tum varyant urunlerini aliyoruz
			$all_variant_products = $this->pdo->query( " SELECT * FROM ". DBT_VARIANT_PRODUCTS ." WHERE parent = ?", array( $data["item_id"] ) );
			// son varyantta işlem yapmıyoruz.
			if( $data["variant_name"] != 'obarey' ){
				// herbir alt ürün icin;
				foreach( $all_variant_products->results() as $urun ){
					// varyant kodunu alip parcaliyoruz
					$codes = explode( '-', $urun["variant_code"] );
					$code_count = count($codes);
					$code_check = array();
					for( $i = 0; $i < $code_count; $i++ ){
						if( $i != $data["variant_no"] ){
							$code_check[] = $codes[$i];
						} else {
							break;
						}
					}
					$code_check_str = implode( '-', $code_check ); 
					if( $code_check_str == $data["code_check"]  ){
						// ayni kod sirasinda birden fazla varyant urun olabilir
						// örn 1 - 4 - 7, 1 - 4 - 10 olabilir
						// oyle bir durum varsa tekrar 4 ü eklemiyoruz option liste
						if( !in_array( $codes[ $data["variant_no"] ], $next_options )) $next_options[] = $codes[ $data["variant_no"]  ];	
					} 
				}
				// selectin basini olusturduk
				$select = '<select name="varyant_'.$next_variant_id.'"  id="varyant_'.$next_variant_id.'" class="t2 varyant-select" onchange="Variants.change( this, false )" >
							<option value="0">'.$data["variant_name"].' seçiniz...</option>';
				// yeni her option un ismini al ve selectin option larini olustur					
				foreach( $next_options as $option ){
					$option_name_query = $this->pdo->query( " SELECT * FROM ".DBT_SUB_VARIANT." WHERE id = ?", array( $option ) )->results();
					$select .= '<option value="'.$option.'">'.$option_name_query[0]["variant_name"].'</option>';
				}
				$select .= '</select>';
				return $select;
			} else {
				return false;
			}
		}




	*/