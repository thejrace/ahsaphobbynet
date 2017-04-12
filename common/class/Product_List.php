<?php 	
	class Product_List extends Product_Filter {

		private $pdo;
		protected $html = "", $pagin = "",
				  $category_id,
				  $all = array(),
				  $category_products = array(),
				  $showcase_products = array(),
				  $settings = array( "page" => 1, "direction" => "ASC", "rrp" => 30, "orderby" => "id" );
		public function __construct( $category_id ){
			$this->pdo = DB::getInstance();
			$this->category_id = $category_id;
		}

		// vitrindeki ürünleri al
		protected function list_showcase_products(){
			foreach( $this->pdo->query("SELECT ".DBT_PRODUCTS.".id, ".DBT_PRODUCTS.".product_name, ".DBT_PRODUCTS.".price_1, ".DBT_PRODUCTS.".material FROM ". DBT_PRODUCTS." INNER JOIN ".DBT_SHOWCASE_CATEGORY." ON ".DBT_SHOWCASE_CATEGORY.".product_id = ".DBT_PRODUCTS.".id WHERE category = ? && status = ? ORDER BY order_no", array( $this->category_id, 1 ) )->results() as $product) {
				$this->showcase_products[] = $product;
			}
		}

		// vitrin harici kategorinin urunleri
		// ilk acilista vitrinde olmayanlari al
		// @showcase_flag false ise koşulsuz tüm ürünleri al
		// kullanici urun goruntuleme ayarlarini degistirdiyse false olacak flag
		// showcase_true ise ilk acilis
		protected function list_self_products( $showcase_flag ){
			// kategori listesini çıkar ( kendisi dahil )
			$C_Tree = new Category_Tree;
			$C_Tree->create( $this->category_id, true );
			$cat_list = $C_Tree->get_list();
			// category icin sql syntax i olustur
			$sql = Common::array_to_sql( count($cat_list), 'category', 'OR' );
			// status u sondan listeye ekle
			$cat_list[] = 1;
			foreach( $this->pdo->query("SELECT id, product_name, price_1, showcase_category, material FROM " . DBT_PRODUCTS . " WHERE ( ".$sql." ) && status = ?", $cat_list )->results() as $product ){
				if( $showcase_flag ){
					if( $product['showcase_category'] == 0 ) $this->category_products[] = $product;
				} else {
					$this->category_products[] = $product;
				}
			}
		}
		// listeyi olusturan main metod
		// islem sirasi :
		// #1 - urun listesini vitrin durumuna bakarak al
		// #2 - listeyi filtrele ( kullanici filtre secmediyse hepsi doner problem yok )
		// #3 - settingsden orderby ve directionlara gore listedeki urunleri siraya diz
		// #4 - sayfalam ve urunleri listeleme
		public function create( ){
			// #1
			// vitrin sıralaması ilk acilista yapiliyor onun flag i
			$showcase_init = true;
			// eğer listeleme ayari degistirildiyse vitrin olayi yok( ilk acilis degil )
			if( $this->settings['orderby'] != 'id' ) $showcase_init = false;
			// eğer vitrin sıralaması yapilicaksa listele vitrindekileri
			if( $showcase_init ) $this->list_showcase_products();	
			// kategori urunlerini al ( vitrin harici veya direk hepsi )
			$this->list_self_products( $showcase_init );
			// tum urunleri tek bir array de birlestir
			$this->all = array_merge( $this->showcase_products, $this->category_products );
			// isimiz bittigi icin birlestirme sonrasi kucuk arrayleri bosalt
			$this->showcase_products = array();
			$this->category_products = array();

			// #2
			// filtreleme yaptigim zaman uymayan urunleri
			// listeden cikar
			$counter = 0;
			foreach( $this->all as $product ) {
				// fiyat filtreleme
				if( !$this->price_filter( $product['price_1'] ) ) unset( $this->all[$counter]);
				// kampanya ozellikleri filtreleme
				if( !$this->filter_campaign( $product['id'] ) ) unset( $this->all[$counter]);
				// materyal filtreleme
				if( !$this->filter_material( $product['material'] ) ) unset( $this->all[$counter]);
				$counter++;
			}

			// #3
			// bu if blogu gene listeleme ayarlari degistirildiyse calisiyor
			// orderby ve direction ayarina gore all array'ini duzenliyoruz
			if( !$showcase_init ){
				// orderby a gore keylerden sirala array i
				// direction a gore arrayi keylerine gore düzenle
				if( $this->settings['direction'] == 'ASC' ){
					$direction = SORT_ASC;
				} else {
					$direction = SORT_DESC;
				}
				$this->all = Common::array_sort_by_column( $this->all, $this->settings["orderby"], $direction );
			}

			// #4
			// sayfalama htmli olusturma ve sayfalam parametrelerini olusturma ( to, from )
			$Pagin = new Pagination;
			$this->pagin = $Pagin->create_site_template( count($this->all), $this->settings );

			// eğer urun varsa listele
			if( count($this->all) != 0 ){
				// urun siralamasi icin sayac
				$counter = 0;
				// her urun icin html i guncelle
				foreach( $this->all as $product ) {
					// if( !$this->price_filter( $product['price_1'] ) ) continue;

					$counter++;
					// listeleme ayarlarina gore kac tane siralanacaksa o kadar urun listele
					if( $counter > $Pagin->get_from() && $counter <= $Pagin->get_to() ) {	
						// urun objesini olustur
						$Product = new Product( $product["id"] );
						// liste htmlini olustur
						$this->html .= $Product->create_list_html_template();
						// yeni urun icin destroy et degiskeni
						unset($Product);
					}	
				}
			// ürün yoksa
			} else {
				$this->html = "<div><div class='notf error'>Bu kategoriye henüz ürün eklenmemiş.</div></div>";
			}
		}

		// Kullanicinin degistirdigi ayarlari al
		// Degistirmemisse default ayarlari kullan
		public function set_settings( $in ) {
			// bu iki if blogunun sebebi urun listeleme ayar barinda
			// direction ve orderby i tek bir selectten aliyorum
			// örn 'product_name_DESC' olarak geliyor
			// o yuzden ayarlari karsilastirmadan once parcaliyorum gelen input degerini
			if( $in['orderby'] == 'product_name_DESC'){
				$in['orderby'] = 'product_name';
				$in['direction'] =  'DESC';
			} else if( $in['orderby'] == 'product_name_ASC' ){
				$in['orderby'] = 'product_name';
				$in['direction'] =  'ASC';
			} else if ( $in['orderby'] == 'price_1_DESC' ){
				$in['orderby'] = 'price_1';
				$in['direction'] = 'DESC';
			} else if( $in['orderby'] == 'price_1_ASC' )	{
				$in['orderby'] = 'price_1';
				$in['direction'] = 'ASC';
			}	

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

		public function show(){
			return $this->html;
		}

		public function get_pagination(){
			return $this->pagin;
		}

		public function get_list(){
			return $this->all;
		}

	}



	/*

						// array keylere gore siraladigim icin ayni fiyattan birden fazla urun olabilir
					// eger varsa array key in sonuna counter ekliyorum ki ayni fiyatlilar overwrite olmasin
					// if( isset( $this->all[Common::array_key_sef( $this->all[$counter][$this->settings['orderby']])] ) ){
					// 	$this->all[ Common::array_key_sef( $this->all[$counter][$this->settings['orderby']] . $counter )] = $this->all[$counter];
					// } else {	
					// 	$this->all[Common::array_key_sef( $this->all[$counter][$this->settings['orderby']] )] = $this->all[$counter];
					// }
					// kopyaladiktan sonra sil listeden ürünü
					// unset( $this->all[$counter] );
					// $counter++;
	
	*/