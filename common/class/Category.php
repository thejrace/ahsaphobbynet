<?php

	class Category implements Bonibon_Switch {

		private $pdo;
		protected $details = array(), $valid = false, $table = DBT_CATEGORIES, $return_text = "";
		protected $bonibon_function, $bonibon_state, $sc_table, $funcs_list = array( "F0" => "status" ); // Bonibon interface degiskenleri

		public function __construct( $id = null ){
			$this->pdo = DB::getInstance();
			// Varolan kategori icin islemler
			if( $id != null ){
				$query = $this->pdo->query( "SELECT * FROM " . $this->table . " WHERE id = ? ", array( $id ) )->results();
				if( count($query) == 1 ){
					$this->details = $query[0];
					$this->valid = true;
					$this->details["url"] = CATEGORY_URL . Common::sef_link( $this->details["category_name"] ) .'/'. $this->details["id"];
				}
			// Yeni eklerken vs.
			} else{

			}
		}

		// kategori logosu silme
		// @order product_img_delete.php de direk product ve variant_product
		// img delete ile uyumlu olsun diye var
		// kullanmiyorum fonksiyonda
		public function delete_picture_file( $order=null ){
			$prefix = 'category-';
			if( file_exists( RES_IMG_CATEGORY_IMG_DIR . $prefix . $this->details["id"] . ".png" ) ){
				// orjinal ve resizedi yala yut
				if( !unlink( RES_IMG_CATEGORY_IMG_DIR . $prefix . $this->details["id"] . ".png" ) 
				) {
					$this->return_text = "Resim silinirken bir hata oluştu. #1";
					return false;
				} 

				// DB den resim sutununu 0 yap
				if( !$this->part_edit(array( 'icon' => 0 )) ) {
					$this->return_text = "Resim silinirken bir hata oluştu. #2";
					return false;
				}
				
			}
			$this->return_text = "Resim silindi.";
			return true;
		}

		// Kullanicidan gelen array ile guncelleme
		public function part_edit( $data ){
			if( !$this->pdo->update( $this->table, 'id', $this->details["id"], $data ) ){
				$this->return_text = 'Güncelleme esnasında bir hata oluştu.';
				return false;
			}
			$this->return_text = 'Değişiklikler kaydedildi.';
			return true;
		}

		// interface den bonibon fonksiyonu 
		public function bonibon( $func, $state ){
			$this->bonibon_function = $this->funcs_list[$func];
			$this->bonibon_state = $state;
			return $this->bonibon_action();
		}

		// bonibon tipine gore yapilacak islemi halleden fonksiyon
		// sadece durum bonibonu var
		public function bonibon_action(){
			// urun tablosundan guncellemeyi yap
			if( !$this->pdo->query( "UPDATE ". $this->table ." SET " . $this->bonibon_function . " = ? WHERE id = ? ", array( $this->bonibon_state, $this->details["id"] ) ) ){
				$this->return_text = 'Bonibon hatası oluştu. Lütfen tekrar deneyin. #1';
				return false;
			}
			$this->return_text = 'Değişiklikler kaydedildi.';
			return true;
		}

		// Query de ORDER BY kelimesi olabilecegi icin order_no yaptim order yoksa calismiyo query
		public function edit( $in ){
			if( !$this->pdo->update( $this->table, 'id', $this->details["id"], array(
				"category_name" => $in["category_name"],
				"details" 		=> $in["details"],
				"order_no" 		=> $in["order_no"],
				"tags" 			=> $in["tags"],
				"title" 		=> $in["title"]
			)) ) {
				$this->return_text = "Kategori düzenlenirken bir hata oluştu. Lütfen tekrar deneyin.";
				return false;
			}
			$this->return_text = "Değişiklikler kaydedildi.";
			return true;
		}

		public function add( $in ){
			if( !$this->pdo->insert( $this->table, array(
				"category_name" => $in["category_name"],
				"parent"		=> $in["parent"],
				"details" 		=> $in["details"],
				"order_no" 		=> $in["order_no"],
				"tags" 			=> $in["tags"],
				"title" 		=> $in["title"]
			)) ){
				$this->return_text = "Kategori eklenirken bir hata oluştu. Lütfen tekrar deneyin.";
				return false;
			}
			$this->return_text = "Kategori eklendi.";
			return true;
		}

		// #1 Kategoriyi ve alt kategorilerini sil
		// #2 Kategoriye ve alt kategorilerine dahil olan ürünlerin;
		//		- category, status ve showcase_category sütunlarini 0 yap
		// #3 Kategori ve alt kategorilerinin kategori vitrini tanimlamalarini sil
		public function delete(){
			$C_Tree = new Category_Tree;
			// alt kategorileri cikar
			$C_Tree->create( $this->details["id"], true );
			// Herbirini sil
			foreach( $C_Tree->get_list() as $category ){
				// kategoriyi sil
				if( !$this->pdo->query("DELETE FROM " . $this->table . " WHERE id = ?", array($category) ) ){
					$this->return_text = "Kategori silinirken bir hata oluştu. Lütfen tekrar deneyin. #1";
					return false;
				}
				// kategorinin ürünlerinin category sütununu 0 yap
				// durumlarını pasife çevir
				foreach( $this->pdo->query("SELECT id FROM " . DBT_PRODUCTS . " WHERE category = ?", array( $category ))->results() as $product ){
					if( !$this->pdo->update( DBT_PRODUCTS, 'id', $product["id"], array( "category" => 0, "status" => 0, "showcase_category" => 0 ) ) ){
						$this->return_text = "Kategori silinirken bir hata oluştu. Lütfen tekrar deneyin. #2";
						return false;
					}
				}
				// kategori vitrin tanimlamalarini kaldir
				foreach( $this->pdo->query( "SELECT id FROM " . DBT_SHOWCASE_CATEGORY . " WHERE category_id = ?", array( $category ) )->results() as $showcase_def ){
					if( !$this->pdo->query( "DELETE FROM " . DBT_SHOWCASE_CATEGORY . " WHERE id = ?", array( $showcase_def["id"] ) ) ){
						$this->return_text = "Kategori silinirken bir hata oluştu. Lütfen tekrar deneyin. #3";
						return false;
					} 
				}
			}
			$this->return_text = "Kategori silindi.";
			return true;
		}

		public function exists(){
			return $this->valid;
		}

		// Quick edit icin tum bilgileri gondermemek icin kırpıyorum. Bandwidth lan bu amcik az kullan
		// Formdaki inputlarin sirasiyla ayni olacak unutmaaa
		public function get_cropped_details(){
			return array(	
				"id" 			=> $this->details["id"],
				"category_name" => $this->details["category_name"],
				"order_no" 		=> $this->details["order_no"],
				"tags" 			=> $this->details["tags"],
				"title" 		=> $this->details["title"],
				"details" 		=> $this->details["details"]
			);
		}

		public function get_details( $key = null ){
			if( isset( $key ) ){
				return $this->details[$key];
			} else {
				return $this->details;
			}	
		}

		public function get_return_text(){
			return $this->return_text;
		}
		
	}