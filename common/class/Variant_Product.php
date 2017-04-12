<?php

	class Variant_Product extends Product_Base implements Bonibon_Switch {

		protected $pdo;
		protected $error, $parent, $table = DBT_VARIANT_PRODUCTS;

		public function __construct( $id = null ){
			$this->pdo = DB::getInstance();

			// product_base degiskenleri
			parent::$is_variant = true;
			parent::$img_prefix = "variant-product-";
			parent::$IMG_DIR = RES_IMG_STATIC_DIR . "product_img/";

			if( $id != null ){
				// direk id geldiyse db den id ye gore al
				if( !is_array( $id ) ){
					$check_query = $this->pdo->query(" SELECT * FROM " . $this->table . " WHERE id = ?", array( $id ) );
				} else {
					// varyant kodu geldiyse array olarak ona gore cek
					// [ varyant_code, parent_product ]
					// urun sayfasinda varyant seçiminde boyle aliyorum urun bilgilerini
					$check_query = $this->pdo->query(" SELECT * FROM " . $this->table . " WHERE variant_code = ? && parent = ?", array( $id[0], $id[1] ) );
				}
				if( $check_query->count() != 0 ){
					foreach( $check_query->results() as $res ){
						$this->details = $res;
					}
					$this->valid = true;
					// resimlerin url leri cikar
					$this->convert_image_url();
				}
			}
		}

		// interface den bonibon fonksiyonu 
		public function bonibon( $func, $state ){
			$this->bonibon_function = $this->funcs_list[$func];
			$this->bonibon_state = $state;
			return $this->bonibon_action();
		}

		// bonibon tipine gore yapilacak islemi halleden fonksiyon
		// variant product oldugu icin sadece durum bonibonu var
		public function bonibon_action(){
			// urun tablosundan guncellemeyi yap
			if( !$this->pdo->query( "UPDATE ". $this->table ." SET " . $this->bonibon_function . " = ? WHERE id = ? ", array( $this->bonibon_state, $this->details["id"] ) ) ){
				$this->return_text = 'Urun tablosundan bonibon guncellenemedi. Product.php line 71';
				return false;
			}
			$this->return_text = 'Değişiklikler kaydedildi.';
			return true;
		}

		// Ürüne varyant seçeneği ekleme
		public function add( $data ){
			// Degiskenlerim
			$v_code = "";
			$v_name  = "";
			$var_count = count( $data["variant"] );
			// Varyantları post ederken array'e ters ekliyor formda nedense
			// o yuzden ters ceviriyorum
			$data["variant"] = array_reverse( $data["variant"] );

			// Boş gelen varyant var mı kontrol
			$counter = 0;
			foreach( $data["variant"] as $v ){
				$counter++;
				if( empty($v) ) {
					// Varsa error yazdir
					$this->return_text = "Eksik seçenek tespit edildi ( ".$counter.". sıra ). 'Seçenek Adı' altındaki tüm seçeneklerin seçili olduğudan emin olun. ";
					return false;
				}
			}
					
	 		// Gelenlere göre isim ve kod oluştur
			for( $i = 0; $i < $var_count; $i++ ){
				$name_query = $this->pdo->query(" SELECT variant_name FROM ". DBT_SUB_VARIANT ." WHERE id = ?", array( $data["variant"][$i] ) )->results();
				$variant_name = $name_query[0]["variant_name"];
				// ilk varyantin basina tire ekleme
				if( $i > 0 ) {
					// isim - isim - isim
					$v_name  .= ' - ' . $variant_name;
					// id - id - id 
					$v_code  .= '-' . $data["variant"][$i];
				} else {
					$v_name  .= $variant_name;
					$v_code  .= $data["variant"][$i];
				}
			}
	 		// Aynı varyanttan yoksa kaydet, yoksa error
			if( !$this->pdo->query(" SELECT * FROM ".$this->table." WHERE variant_code = ? && parent = ?", array($v_code, $data["parent"] ) )->count() == 0 ){
				$this->return_text = "Bu seçenek zaten mevcut. Lütfen farklı bir giriş yapın.";
				return false;
			}
			// Fiyat - KDV kontrol
			// KDV dahil fiyat yazilmamissa, kdvyi ekle
			// İndirim vs olayları ürünleri listelerken Urunler class ile hesaplanıp listelencek
			$kdv_included = $this->kdv_included( $data["kdv_included"], $data["kdv_percentage"], array( $data["price_1"], $data["price_2"], $data["price_3"] ));

			// // Kargo Ücreti - Sabit Ücret kontrolü
			// // class ile hesaplancak
			// if( $data["shipment_system_cost"] == 1 ) $data["shipment_cost"] = 10;

			// son id yi al
			$last_id_query = $this->pdo->query("SELECT * FROM ".$this->table." ORDER BY id DESC LIMIT 1")->results();
				// tablo bossa id = 1 olsun
			( !isset($last_id_query[0]) ) ? $last_id = 1 : $last_id = ($last_id_query[0]["id"] + 1);
			// Stok kodu
			$cat_query = $this->pdo->query("SELECT * FROM " . DBT_PRODUCTS . " WHERE id = ?", array( $data["parent"]) )->results();
			// Turkce sef yap 
			$stock_code = strtoupper( substr( Common::array_key_sef($cat_query[0]["product_name"]), 0, 4 ) ). rand( 0, 90 ) . "-" .$last_id;

			if( !$this->pdo->insert( $this->table, array(
				"parent" 		=> $data["parent"],
				"product_name" 	=> $v_name,
				"variant_code" 	=> $v_code,
				"price_1" 		=> $this->prices[0],
				"price_2" 		=> $this->prices[1],
				"price_3" 		=> $this->prices[2],
				"pure_price_1" 	=> $data["price_1"],
				"pure_price_2" 	=> $data["price_2"],
				"pure_price_3" 	=> $data["price_3"],
				"desi" 			=> $data["desi"],
				"stock_code" 	=> $stock_code,
				"stock_amount"	=> $data["stock_amount"],
				"kdv_included"	=> $kdv_included,
				"shipment_cost" => 0,
				"details"		=> "",
				"picture_1" 	=> ""
			)) ) {
				$this->return_text = "Varyant eklenirken bir hata oluştu. Lütfen tekrar deneyin.";
				return false;
			}
			$this->return_text = "Varyant eklendi.";
			return true;
		}

		public function edit( $in ){

			$kdv_included = $this->kdv_included( $in["kdv_included"], $in["kdv_percentage"], array( $in["price_1"], $in["price_2"], $in["price_3"] ));
			
			// Kargo Ücreti - Sabit Ücret kontrolü
			// class ile hesaplancak
			if( $in["shipment_system_cost"] == 1 ) $in["shipment_cost"] = 10;

			if( !$this->pdo->update( $this->table, "id", $this->details["id"], array(	
				"price_1" 				=> $this->prices[0],
				"price_2" 				=> $this->prices[1],
				"price_3" 				=> $this->prices[2],
				"pure_price_1" 			=> $in["price_1"],
				"pure_price_2" 			=> $in["price_2"],
				"pure_price_3" 			=> $in["price_3"],
				"kdv_included"			=> $kdv_included,
				"kdv_percentage" 		=> $in["kdv_percentage"],
				"sale_percentage" 		=> $in["sale_percentage"],
				"stock_code" 			=> $in["stock_code"],
				"stock_amount"			=> $in["stock_amount"],
				"desi" 					=> $in["desi"],
				"status"				=> $in["status"],	
				"shipment_cost" 		=> $in["shipment_cost"],
				"shipment_system_cost" 	=> $in["shipment_system_cost"],
				"details"				=> $in["details"],
				"picture_1" 			=> $this->picture_files["picture_1"]
			)) ){
				$this->return_text = "Varyant düzenlenirken bir hata oluştu. Lütfen tekrar deneyin.";
				return false;
			}
			$this->return_text = "Değişiklikler kaydedildi.";
			return true;
		}

		public function quick_edit( $data ){
			// Fiyat - KDV kontrol
			// KDV dahil fiyat yazilmamissa, kdvyi ekle
			// İndirim vs olayları ürünleri listelerken Urunler class ile hesaplanıp listelencek
			$kdv_included = $this->kdv_included( $data["kdv_included"], $data["kdv_percentage"], array( $data["price_1"], $data["price_2"], $data["price_3"] ));
			if( !$this->pdo->update( $this->table, "id", $this->details["id"], array(	
				"price_1" 				=> $this->prices[0],
				"price_2" 				=> $this->prices[1],
				"price_3" 				=> $this->prices[2],
				"pure_price_1" 			=> $data["price_1"],
				"pure_price_2" 			=> $data["price_2"],
				"pure_price_3" 			=> $data["price_3"],
				"kdv_included"			=> $kdv_included,
				"stock_code" 			=> $data["stock_code"],
				"stock_amount"			=> $data["stock_amount"],
				"desi" 					=> $data["desi"]
			)) ){
				$this->return_text = "Varyant düzenlenirken bir hata oluştu. Lütfen tekrar deneyin.";
				return false;
			}
			$this->return_text = "Değişiklikler kaydedildi.";
			return true;
		}

		// varyantin resmi varsa bul ve sil
		public function delete(){
			// resim zaten 1 tane oldugu icin parent fonksiyonu kullan
			if( !$this->delete_picture_file(1) ) {
				$this->return_text = "Varyant silinirken bir hata oluştu. Lütfen tekrar deneyin. #1";
				return false;
			}
			if( !$this->pdo->query(" DELETE FROM " . $this->table . " WHERE id = ?", array( $this->details["id"] ) ) ){
				$this->return_text = "Varyant silinirken bir hata oluştu. Lütfen tekrar deneyin. #2";
				return false;
			}
			$this->return_text = "Varyant silindi.";
			return true;
		}

		public function get_return_text(){
			return $this->return_text;
		}

		public function exists(){
			return $this->valid;
		}

	}