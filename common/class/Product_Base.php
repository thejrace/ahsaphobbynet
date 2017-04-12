<?php

	class Product_Base {
		protected $return_text = "", $prices = array(), $details = array(), $valid = false, $picture_files = array();
		protected static $is_variant = false, $img_prefix = "product-", $IMG_DIR;
		protected $bonibon_function, $bonibon_state, $sc_table, $funcs_list = array(  // Bonibon interface degiskenleri
					"F0" => "status", 
					"F1" => "showcase_home",
					"F2" => "showcase_category",
					"F3" => "campaign",
					"F4" => "new",
					"F5" => "variant",
					"F6" => "has_form" );

		// Kullanicidan gelen array ile guncelleme
		public function part_edit( $data ){
			if( !$this->pdo->update( $this->table, 'id', $this->details["id"], $data ) ){
				$this->return_text = 'Güncelleme esnasında bir hata oluştu.';
				return false;
			}
			return true;
		}

		protected function kdv_included( $included, $percentage, $prices ){
			// Eğer işaretli değilse, fiyatları tek tek kontrol edip
			// değişiklik olanları ham ve vergili olarak db ye ekle
			if( $included == 0 ) {
				for( $i = 0; $i < 3; $i++ ){
					$this->prices[$i] = Common::add_kdv( $percentage, $prices[$i] );
				}
			// KDV Dahil seçeneği işaretliyse
			} else {
				$this->prices = $prices;	
			}
			return $included;
		}

		// Edit yaparken bos gelenleri degistirmemek icin
		protected function update_picture_files(){
			foreach( $this->picture_files as $key => $val ){
				// Ürün eklerken details olmadigi icin kontrol ediyoruz
				if( isset($this->details[$key]))
					if( $val == 0 ) $this->picture_files[$key] = $this->details[$key];
			}
		}

		// düzenleme yapılırken @id parametresi, resimleri isimlendirirken kullanılacak
		// @is_variant => varyant ürünü ve normal ürün eklerken last_id alirken kullaniliyor
		public function handle_pictures( $files, $id = null ){

			// Resmin id'si
			// eger id gelmisse anla ki edit yapiliyor
			// null ise son id yi db den alip yapistiyoruz
			if( $id == null ){
				// Resim adlandırması için son ID'yi almam lazım
				if( self::$is_variant ) {
					$query = $this->pdo->query("SELECT * FROM ".DBT_PRODUCT_VARIANTS." ORDER BY id DESC LIMIT 1")->results();
				} else {
					$query = $this->pdo->query("SELECT * FROM ".DBT_PRODUCTS." ORDER BY id DESC LIMIT 1")->results();
				}
				$LAST_ID = ($query[0]["id"] + 1);
			} else {
				$LAST_ID = $id;
			}			

			// Resimleri al
			foreach( $files as $key => $file ){
				if( $file["name"] != "" && $file["type"] != "" && $file["tmp_name"] != "" ){
					// picture_1 ---> 1 oldu counter 
					$counter = substr( $key, 8 );
					// Ürün listelemede yeniden boyutlandırma kullandığım için iki farklı upload yapıyorum
					// 	1-> orj boyut
					//  2-> y=150px, x orantılı olarak otomatik
					for( $i = 0; $i < 2; $i++){
						$resize = "";
						$upload = new Upload( $file );
						$upload->image_resize = true;
						// resized upload için gereken komutları 2. döngüde al
						if( $i == 0 ){
							$upload->image_y = 150;
							$upload->image_ratio_x = true;
							$resize = '-resized';
						} else {
							$upload->image_y = 600;
							$upload->image_ratio_x = true;
						}
						$upload->file_max_size = '5242880';
						$upload->allowed = array( 'image/*' );
						$upload->file_overwrite = true;
						if($upload->uploaded) {	
							$upload->image_convert = 'png';
							$upload->file_new_name_body = self::$img_prefix .$LAST_ID.'-'.$counter. $resize ;
							$upload->Process(RES_IMG_PRODUCT_IMG_DIR);
							if($upload->processed) {
								// DB için verileri listeye al
								// _resized eki ürün listelenirken koyulacak
								$this->picture_files[$key] = 1;
							} else {
								// PATLADI
								$this->return_text = "Resimler yüklenirken bir hata oluştu. Lütfen tekrar deneyin. ( Geçerli uzantılar: png, jpeg, gif. )";
								return false;
							}
						} else {
							// URUN PATLADI
							$this->return_text = "Geçerli uzantılar: png, jpeg, gif.";
							return false;
						}
					} // for
				} else {
					$this->picture_files[$key] = 0;

				}// file if
			} // foreach
	
			// edit icin
			// zaten eklenmis resimler degistirilmediyse dokunmamak icin			
			$this->update_picture_files();
			return true;
		}

		// Resimler DBde 1, 0 olarak kayitli
		// product_edit ve goruntuleme icin URL formatına ceviriyorum details de
		// template te url_picture_1 seklinde ulasiyorum linklere
		protected function convert_image_url(){
			$limit = 5;
			if( self::$is_variant ) $limit = 2;
			for( $i = 1; $i < $limit; $i++ ){
				// Eğer resim varsa
				if( $this->details["picture_" . $i] != 0 ) {
					$this->details["url_picture_" . $i] = RES_IMG_PRODUCT_IMG_URL . self::$img_prefix .$this->details["id"]. "-" . $i . ".png";
					$this->details["url_picture_" . $i . "-resized"] = RES_IMG_PRODUCT_IMG_URL . self::$img_prefix .$this->details["id"]. "-" . $i . "-resized.png";
				} else {
				// Yoksa null
					$this->details["url_picture_" . $i] = "";
					$this->details["url_picture_" . $i . "-resized"] = "";
				}
			}
		}

		// Ürün silindiginde tanımlı resimlerini deleted_products daki yeni ID si ile guncelle
		protected function rename_product_imgs( $new_id ){
			for( $i = 1; $i < 5; $i++ ){
				$name_body = RES_IMG_PRODUCT_IMG_DIR . "product-";
				// Eger resim varsa
				if( file_exists( $name_body . $this->details["id"] . "-" . $i . ".png" ) ) {
					// orjinal resmi ve resized olan kucuk olani yeni id ile guncelle
					if( 
						!( rename( $name_body . $this->details["id"] . "-" . $i . ".png", $name_body . $new_id . "-" . $i . "-deleted.png" ) &&
						   rename( $name_body . $this->details["id"] . "-" . $i . "-resized.png", $name_body . $new_id . "-" . $i . "-resized-deleted.png" ) )
					) return false;
				}
			}
			return true;
		}

		// urun duzenlemede resim silme,
		// varyant duzenlemede resim silme
		public function delete_picture_file( $order ){
			if( file_exists( RES_IMG_PRODUCT_IMG_DIR . self::$img_prefix . $this->details["id"] . "-" . $order . ".png" ) ){
				// orjinal ve resizedi yala yut
				if( !(unlink( RES_IMG_PRODUCT_IMG_DIR . self::$img_prefix . $this->details["id"] . "-" . $order . ".png" ) && 
					  unlink( RES_IMG_PRODUCT_IMG_DIR . self::$img_prefix . $this->details["id"] . "-" . $order . "-resized.png" )
					)
				) {
					$this->return_text = "Resim silinirken bir hata oluştu. #1";
					return false;
				} 

				// DB den resim sutununu 0 yap
				if( !$this->pdo->update( $this->table, 'id', $this->details["id"], array( 'picture_'.$order => 0 )) ) {
					$this->return_text = "Resim silinirken bir hata oluştu. #2";
					return false;
				}
				
			}
			$this->return_text = "Resim silindi.";
			return true;
		}

		public function get_details( $key = null ){

			if( $key != null ){
				return $this->details[$key];
			} else {
				return $this->details;
			}	
		}

		public function exists(){
			return $this->valid;
		}

	}