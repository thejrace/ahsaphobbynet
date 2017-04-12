<?php

	class Showcase {

		private $pdo;
		protected $type, $products = array(), $category_id, $header, $table, $last_order_no;

		public function __construct( $type, $category_id ){
			$this->pdo = DB::getInstance();
			$this->type = $type;
			// kategori vitrini için eksta parametre 
			if( $category_id != "" ) $this->category_id = $category_id;
			// tablo ve headerlari type a gore belirle
			$this->set_type_variables();
		}

		// tablo ve header degiskenlerini ayrica yapiyorum
		// cunku ajax_showcase.php de save islemi yaparken gereksiz yere 
		// db den get_showcase_data ile islem olmasin
		protected function set_type_variables(){
			switch( $this->type ){
				case 'showcase_category':
					$this->table = DBT_SHOWCASE_CATEGORY;
				break;

				case 'showcase_home':
					$this->table = DBT_SHOWCASE_HOME;
					$this->header = " Anasayfa vitrini";
				break;

				case 'showcase_new':
					$this->table = DBT_SHOWCASE_NEW;
					$this->header = " Yeni ürünler vitrini";
				break;

				case 'showcase_campaign':
					$this->table = DBT_SHOWCASE_CAMPAIGN;
					$this->header = "Kampanyalı ürünler vitrini";
				break;
			}
		}

		// listeyi olusturan fonksiyon
		public function get_showcase_data( ){
			$this->products = array();

			// vitrin tablolarindan siraya gore urun idlerini al
			if( $this->type == 'showcase_category' ){
				$this->products = $this->pdo->query( "SELECT * FROM " . $this->table . " WHERE category_id = ? ORDER BY order_no", array( $this->category_id) )->results();
			} else {
			// kategori vitrini icin ayrica bir WHERE var digerlerinde yok
				$this->products = $this->pdo->query( "SELECT * FROM " . $this->table . " ORDER BY order_no")->results();
			}

			// ürünler tablosundan ürün adını alıyoruz datatable icin
			foreach( $this->products as $key => $product ){
				foreach($this->pdo->query("SELECT id, product_name FROM ". DBT_PRODUCTS . " WHERE id = ? && status = ?",array( $product["product_id"], 1 ) )->results() as $res ){
					// product[0] = array( id => x, product_name => y ) oldu
					/* $res["order_no"] = $product["order_no"]; */
					// order no yu direk dbden aldigim gibi yukarıda, arrayin kicina ekleyebilirim
					// ama direk array keyleri o sırayla geliyor o yuzden direk bandwidth save için keyden al
					$this->products[$key+1] = $res;
					// 0. key artıyor o yuzden siliyoruz
					if( $key == 0 ) unset($this->products[$key]);
				}	
			}
			// orderby yok cunku drag-drop yapiyoruz zaten
			// sacma sapan bisi oluyo
			// $this->products = Common::sort_array_key_string( $this->products, 'product_name' );
		}

		// Drag-drop sonrasi siralari kaydetme
		public function save_showcase( $data ) {
			foreach( $data as $key => $val ){
				//@val => urun id
				//@key+1 => sira ( jsden 0 key ile geliyo o yuzden arttırıyorum klasik )
				if( !$this->pdo->query( "UPDATE " . $this->table . " SET order_no = ? WHERE product_id = ? ", array( ($key+1), $val ) ) )
					return false;
			}
			return true;
		}

		// vitrindeki son sırayı bul
		public function get_last_order_no(){
			// vitrindeki son sırayı al
			// bonibonlardan vitrine urun eklerken kullanıyorum
			if( $this->category_id != "" ){
				$last_order_query = $this->pdo->query("SELECT order_no FROM " . $this->table . " WHERE category_id = ? ORDER BY order_no DESC ",array($this->category_id) )->results();
			} else {
				$last_order_query = $this->pdo->query("SELECT order_no FROM " . $this->table . " ORDER BY order_no DESC " )->results();
			}

			// Eğer vitrinde ürün yoksa 1 den başlatıyorum sırayı
			if( count( $last_order_query ) == 0 ){
				$this->last_order_no = 1; 
			} else {
			// Vitrinde önceden ürün varsa en sonuncunun arkasına yeni ürünü ekliyorum
				$this->last_order_no = ( $last_order_query[0]["order_no"] + 1 );
			}

			return $this->last_order_no;
		}


		// Template icin
		public function get_header( ){
			return $this->header;
		}

		public function get_products(){
			return $this->products;
		}
	}
