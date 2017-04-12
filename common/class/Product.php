<?php

class Product extends Product_Base implements Bonibon_Switch {

	protected $pdo;
	protected $product_variant_defined = array(), $table = DBT_PRODUCTS,
			  $product_variant_undefined = array(), $has_variant = false;

	// Urunu al id varsa
	public function __construct( $id = null ){
		$this->pdo = DB::getInstance();

		parent::$IMG_DIR = RES_IMG_PRODUCT_IMG_DIR;
		parent::$is_variant = false;
		parent::$img_prefix = "product-";
		
		if( $id != null ){
			$query = $this->pdo->query( "SELECT * FROM " . $this->table . " WHERE id = ?", array($id) )->results();
			if( count($query) == 1 ){
				$this->valid = true;
				$this->details = $query[0];
				$this->details["url"] = PRODUCT_URL . Common::sef_link( $this->details["product_name"] ) . "/" . $this->details["id"]; 
				// resimlerin url leri cikar
				$this->convert_image_url();
			}
		}
	}
	// Silinen ürünü geri alma
	public function undo_deleted($item_id){
		// deleted producttan urun bilgilerini al normal urunmus gibi details e ekle
		$query = $this->pdo->query("SELECT * FROM " . DBT_DELETED_PRODUCTS . " WHERE id = ? ", array( $item_id ) )->results();
		$this->details = $query[0];
		// urunler tablosundan son id'yi al 
		// increment bi kere kaydi o yuzden last-id + 1 degil direk increment i aliyorum
		$last_id = $this->pdo->get_auto_increment( $this->table );
		// ..../product-ID-
		$img_name_pattern = parent::$IMG_DIR . 'product-';
		for( $i = 1; $i < 5; $i++ ){
			// ..../product-ID-1
			// yeni urun ID si ile resimleri adlandır
			if( file_exists( $img_name_pattern . $item_id . '-' . $i . '-deleted.png' ) ){
				if( !(rename( $img_name_pattern . $item_id . '-' . $i . '-deleted.png', $img_name_pattern . $last_id . '-' . $i . '.png' ) &&
					  rename( $img_name_pattern . $item_id . '-' . $i . '-resized-deleted.png', $img_name_pattern . $last_id . '-' . $i . '-resized.png' ) )){
					$this->return_text = "Ürün geri alınırken bir hata oluştu. #1";
					return false;
				} 
			}
		}
		// Urunlere ekle 
		if( !$this->pdo->insert( $this->table, array(
			"category"				=>	$this->details["category"],
			"product_name"			=>	$this->details["product_name"],
			"price_1"				=>	$this->details["price_1"],
			"price_2"				=>	$this->details["price_2"],
			"price_3" 				=>	$this->details["price_3"],
			"pure_price_1"			=>	$this->details["pure_price_1"],
			"pure_price_2"			=>	$this->details["pure_price_2"],
			"pure_price_3" 			=>	$this->details["pure_price_3"],
			"kdv_included" 			=>	$this->details["kdv_included"],
			"kdv_percentage" 		=>	$this->details["kdv_percentage"],
			"sale_percentage" 		=>	$this->details["sale_percentage"],
			"stock_code" 			=>	$this->details["stock_code"],
			"stock_amount" 			=>	$this->details["stock_amount"],
			"desi" 					=>	$this->details["desi"],
			"status" 				=>	$this->details["status"],
			"showcase_home" 		=>	$this->details["showcase_home"],
			"showcase_category" 	=>	$this->details["showcase_category"],
			"new" 					=>	$this->details["new"],
			"campaign" 				=>	$this->details["campaign"],
			"has_form" 				=>	$this->details["has_form"],
			"variant" 				=>	$this->details["variant"],
			"shipment_cost" 		=>	$this->details["shipment_cost"],
			"shipment_system_cost" 	=>	$this->details["shipment_system_cost"],
			"details" 				=>	$this->details["details"],
			"picture_1" 			=>	$this->details["picture_1"],
			"picture_2" 			=>	$this->details["picture_2"],
			"picture_3" 			=>	$this->details["picture_3"],
			"picture_4" 			=>	$this->details["picture_4"],
			"similar_products" 		=>	$this->details["similar_products"],
			"seo_title" 			=>	$this->details["seo_title"],
			"seo_keywords"			=>	$this->details["seo_keywords"],
			"seo_details" 			=>	$this->details["seo_details"]
		)) ) {
			$this->return_text = "Ürün geri alınırken bir hata oluştu. #2";
			return false;
		}
		// Deleted productstan sil urunu
		if( !$this->pdo->query("DELETE FROM " . DBT_DELETED_PRODUCTS . " WHERE id = ? ", array( $item_id) ) ) {
			$this->return_text = "Ürün geri alınırken bir hata oluştu. #3";
			return false;
		}
		$this->return_text = "Ürün geri alındı.";
		return true;
	}
	// Ürünü kalıcı olarak silme
	public function delete_permanently( $item_id ){
		// isim prefixi
		$img_name_pattern = parent::$IMG_DIR . 'product-';
		for( $i = 1; $i < 5; $i++ ){
			// ..../product-ID-1
			// varsa resimlerini sil
			if( file_exists( $img_name_pattern . $item_id . '-' . $i . '-deleted.png' ) ){
				if( !(unlink( $img_name_pattern . $item_id . '-' . $i . '-deleted.png' ) &&
					  unlink( $img_name_pattern . $item_id . '-' . $i . '-resized-deleted.png' ) )){
					$this->return_text = "Ürün kalıcı olarak silinirken bir hata oluştu. #1";
					return false;
				} 
			}
		}
		// Deleted tablsundan da uçur
		if( !$this->pdo->query("DELETE FROM " . DBT_DELETED_PRODUCTS . " WHERE id = ?", array( $item_id)) ){
			$this->return_text = "Ürün kalıcı olarak silinirken bir hata oluştu. #2";
			return false;
		} 
		$this->return_text = "Ürün kalıcı olarak silindi.";
		return true;
	}
	// interface den bonibon fonksiyonu 
	public function bonibon( $func, $state ){
		$this->bonibon_function = $this->funcs_list[$func];
		$this->bonibon_state = $state;
		return $this->bonibon_action();
	}
	// bonibon tipine gore yapilacak islemi halleden fonksiyon
	public function bonibon_action(){
		switch( $this->bonibon_function ){
			// durum degistirmede baska bir aksiyon yok
			case 'status':
			break;
			// anasayfa vitrini
			case 'showcase_home':
				$this->sc_table = DBT_SHOWCASE_HOME;
				if( !$this->add_to_showcase() ) return false;
			break;
			// kategori vitrini
			case 'showcase_category':
				$this->sc_table = DBT_SHOWCASE_CATEGORY;
				if( !$this->add_to_showcase() ) return false;
			break;
			// yeni urun vitrini
			case 'new':
				$this->sc_table = DBT_SHOWCASE_NEW;
				if( !$this->add_to_showcase() ) return false;
			break;
			// kampanyali urun
			case 'campaign':
				$this->sc_table = DBT_SHOWCASE_CAMPAIGN;
				if( !$this->add_to_showcase() ) return false;
			break;
			// varyantli urun ( henuz aktif degil )
			case 'variant':
			break;
			// formlu urun ( henuz aktif degil )
			case 'has_form':
			break;
		}
		// urun tablosundan guncellemeyi yap
		if( !$this->pdo->query( "UPDATE ". $this->table ." SET " . $this->bonibon_function . " = ? WHERE id = ? ", array( $this->bonibon_state, $this->details["id"] ) ) ){
			$this->return_text = 'Urun tablosundan bonibon guncellenemedi. Product.php line 71';
			return false;
		}
		$this->return_text = "Ürün düzenlendi.";
		return true;
	}
	// vitrine ekle
	private function add_to_showcase( $is_category = null ){
		// kategori vitrininde category_id oldugu icin sql farkli 
		// o yuzden ayri yapiyorum
		// state e gore ekle veya sil
		if( $this->bonibon_state == 1 ){
			// Ekle
			if( $this->bonibon_function == 'showcase_category' ){
				// Son sırayı showcase classtan al
				// tablo isimleri ile showcase tiplerinin isimleri ayni o yuzden tablo isimlerini kullanıyorum
				$Showcase = new Showcase( $this->sc_table, $this->details["category"] );
				$last_order = $Showcase->get_last_order_no();
				if( !$this->pdo->insert( $this->sc_table, array( 'order_no' => $last_order, 'category_id' => $this->details["category"] ,'product_id' => $this->details["id"] ) ) ){
					$this->return_text = 'Kategori vitrinine urun eklenemedi. Product.php line 79';
					return false;
				}
			} else {
				// Son sırayı showcase classtan al
				$Showcase = new Showcase( $this->sc_table, "" );
				$last_order = $Showcase->get_last_order_no();
				// anasayfa, kampanyali ve yeni urun vitrini sql ayni tablolar farkli
				if( !$this->pdo->insert($this->sc_table, array( 'order_no' => $last_order, 'product_id' => $this->details["id"] ) ) ){
					$this->return_text = 'Kategori harici vitrinlerden birine urun eklenemedi. Product.php line 93';
					return false;
				}
			}
		} else {
			// Sil	
			if( !$this->pdo->query("DELETE FROM " . $this->sc_table . " WHERE product_id = ?", array( $this->details["id"]) ) ){
				$this->return_text = 'Vitrinlerden ürün silinemedi. Product.php line 112';
				return false;
			}
		}
		return true;
	}
	// Ürün ekleme
	public function add( $in ){
		// Fiyat - KDV kontrol
		// KDV dahil fiyat yazilmamissa, kdvyi ekle
		// İndirim vs olayları ürünleri listelerken Urunler class ile hesaplanıp listelencek
		$kdv_included = $this->kdv_included( $in["kdv_included"], $in["kdv_percentage"], array( $in["price_1"], $in["price_2"], $in["price_3"] ));	
		// Kargo Ücreti - Sabit Ücret kontrolü
		// class ile hesaplancak
		if( $in["shipment_system_cost"] == 1 ) $in["shipment_cost"] = 10;
		// son id yi al
		$last_id_query = $this->pdo->query("SELECT * FROM ".$this->table." ORDER BY id DESC LIMIT 1")->results();
		$last_id = ($last_id_query[0]["id"] + 1);
		if( empty( $in["stock_code"]) ){
			// Stok kodu
			$cat_query = $this->pdo->query("SELECT * FROM " . DBT_CATEGORIES . " WHERE id = ?", array( $in["category_id"]) )->results();
			// Turkce sef yap 
			$in["stock_code"] = strtoupper( substr( Common::array_key_sef($cat_query[0]["category_name"]), 0, 4 ) ). rand( 0, 90 ) . "-" .$last_id;
		}
		if( !$this->pdo->insert( $this->table, array(
			"category"				=>	$in["category_id"],
			"product_name"			=>	$in["product_name"],
			"price_1"				=>	$this->prices[0],
			"price_2"				=>	$this->prices[1],
			"price_3" 				=>	$this->prices[2],
			"pure_price_1"			=>	$in["price_1"],
			"pure_price_2"			=>	$in["price_2"],
			"pure_price_3" 			=>	$in["price_3"],
			"kdv_included" 			=>	$kdv_included,
			"kdv_percentage" 		=>	$in["kdv_percentage"],
			"sale_percentage" 		=>	$in["sale_percentage"],
			"stock_code" 			=>	$in["stock_code"],
			"stock_amount" 			=>	$in["stock_amount"],
			"desi" 					=>	$in["desi"],
			"status" 				=>	$in["status"],
			"showcase_home" 		=>	0,
			"showcase_category" 	=>	0,
			"new" 					=>	1,
			"campaign" 				=>	0,
			"has_form" 				=>	0,
			"variant" 				=>	0,
			"shipment_cost" 		=>	$in["shipment_cost"],
			"shipment_system_cost" 	=>	$in["shipment_system_cost"],
			"material" 				=>	$in["material"],
			"details" 				=>	$in["details"],
			"picture_1" 			=>	$this->picture_files["picture_1"],
			"picture_2" 			=>	$this->picture_files["picture_2"],
			"picture_3" 			=>	$this->picture_files["picture_3"],
			"picture_4" 			=>	$this->picture_files["picture_4"],
			"similar_products" 		=>	$in["similar_products"],
			"seo_title" 			=>	$in["seo_title"],
			"seo_keywords"			=>	$in["seo_keywords"],
			"seo_details" 			=>	$in["seo_details"]
		)) ) {
			$this->return_text = "Ürün eklenirken bir hata oluştu.";
			return false;
		}
		$this->return_text = "Ürün eklendi.";
		return true;
	}
	// Detayli duzenleme
	public function edit( $in ){

		// Kategorisi degisitirilmişse, önceki kategorisinde vitrindeyse kaldır ordan
		// başlangıçta kategori vitrin sütunu aynı kalacak
		$showcase_category_status = $this->details["showcase_category"];
		if( $this->details["category"] != $in["category_id"] && $this->details["showcase_category"] == 1 ){
			// kategorisi değiştirilmişse ve vitrindeyse, yeni kategorisinde vitrinde olmayacak
			$showcase_category_status = 0;
			// sil
			if( !$this->pdo->query("DELETE FROM " . DBT_SHOWCASE_CATEGORY . " WHERE product_id = ?", array( $this->details["id"] ) ) ){
				$this->return_text = "Ürün düzenlenirken bir hata oluştu.";
				return false;
			}
		}

		$kdv_included = $this->kdv_included( $in["kdv_included"], $in["kdv_percentage"], array( $in["price_1"], $in["price_2"], $in["price_3"] ));
		// Kargo Ücreti - Sabit Ücret kontrolü
		// class ile hesaplancak
		if( $in["shipment_system_cost"] == 1 ) $in["shipment_cost"] = 10;
		if( !$this->pdo->update( $this->table, 'id', $this->details["id"], array(
			"category" 				=> $in["category_id"],
			"product_name" 			=> $in["product_name"],
			"price_1" 				=> $this->prices[0],
			"price_2" 				=> $this->prices[1],
			"price_3" 				=> $this->prices[2],
			"pure_price_1" 			=> $in["price_1"],
			"pure_price_2" 			=> $in["price_2"],
			"pure_price_3" 			=> $in["price_3"],
			"kdv_included" 			=> $kdv_included,
			"kdv_percentage" 		=> $in["kdv_percentage"],
			"sale_percentage"		=> $in["sale_percentage"],
			"stock_code" 			=> $in["stock_code"],
			"stock_amount" 			=> $in["stock_amount"],
			"desi" 					=> $in["desi"],
			"status" 				=> $in["status"],
			"shipment_cost" 		=> $in["shipment_cost"],
			"shipment_system_cost" 	=> $in["shipment_system_cost"],
			"details" 				=> $in["details"],
			"material" 				=> $in["material"],
			"showcase_category"     => $showcase_category_status,
			"picture_1" 			=> $this->picture_files["picture_1"],
			"picture_2" 			=> $this->picture_files["picture_2"],
			"picture_3" 			=> $this->picture_files["picture_3"],
			"picture_4" 			=> $this->picture_files["picture_4"],
			"similar_products"		=> $in["similar_products"],
			"seo_title" 			=> $in["seo_title"],
			"seo_keywords" 			=> $in["seo_keywords"],
			"seo_details" 			=> $in["seo_details"]
		)) ) {
			$this->return_text = "Ürün düzenlenirken bir hata oluştu.";
			return false;
		}
		$this->return_text = "Ürün düzenlendi.";
		return true;
	}
	// Ürünler sayfasından ajax hızlı duzenleme
	public function quick_edit($in){
		$this->kdv_included( $this->details["kdv_included"], $this->details["kdv_percentage"], array( $in["price_1"], $in["price_2"], $in["price_3"] ));
		if( !$this->pdo->update( $this->table, "id", $this->details["id"], array(
			'product_name'    => $in["product_name"],
			'pure_price_1'    => $in["price_1"],
			'pure_price_2'    => $in["price_2"],
			'pure_price_3'    => $in["price_3"],
			'price_1'	      => $this->prices[0],
			'price_2'	      => $this->prices[1],
			'price_3'	      => $this->prices[2],
			'stock_code'      => $in["stock_code"],
			'stock_amount' 	  => $in["stock_amount"],
			'sale_percentage' => $in["sale_percentage"]
		)) ) {
			$this->return_text = "Ürün düzenlenirken bir hata oluştu.";
			return false;
		}
		$this->return_text = "Ürün düzenlendi.";
		return true;
	}
	// varyant sirasini degistir
	// Drag-drop sonrasi siralari kaydetme
	public function update_variant_order( $data ) {
		foreach( $data as $key => $val ){
			//@val => urun id
			//@key+1 => sira ( jsden 0 key ile geliyo o yuzden arttırıyorum klasik )
			if( !$this->pdo->query( "UPDATE " . DBT_VARIANT_DEF . " SET order_no = ? WHERE product_id = ? && variant_id = ?", array( ($key+1), $this->details["id"], $val ) ) ){
				$this->return_text = "Varyant sırası güncellenirken bir hata oluştu. Lütfen tekrar deneyin.";
				return false;
			}	
		}
		$this->return_text = "Varyant sırası güncellendi.";
		return true;
	}
	// ana varyant tanımlamasını kaldır
	// silmeden sonra order_no lari siraya sok
	public function remove_variant( $variant_id ){
		if( !$this->pdo->query("DELETE FROM " . DBT_VARIANT_DEF . " WHERE variant_id = ? && product_id = ?", array( $variant_id, $this->details["id"])) ){
			$this->return_text = "Varyant kaldırılırken bir hata oluştu.";
			return false;
		}
		$this->return_text = "Varyant üründen kaldırıldı.";
		return true;
	}
	// ana varyant tanımla
	public function define_variant( $variant_id ){
		// siranin sonuna ekle
		$last_order_q = $this->pdo->query("SELECT order_no FROM " . DBT_VARIANT_DEF . " WHERE product_id = ? ORDER BY order_no DESC LIMIT 1", array($this->details["id"]))->results();
		( count( $last_order_q ) ) ? $last_order = $last_order_q[0]["order_no"] + 1 : $last_order = 1;
		 

		if( !$this->pdo->query("INSERT INTO " . DBT_VARIANT_DEF . " SET variant_id = ?, product_id = ?, order_no = ?", array( $variant_id, $this->details["id"], $last_order)) ){
			$this->return_text = "Varyant ürüne tanımlanırken bir hata oluştu.";
			return false;
		}
		$this->return_text = "Varyant ürüne eklendi.";
		return true;
	}
	// ürüne tanımlı, tanımsız ana varyantları ayır
	public function variant_defs(){
		foreach( $this->pdo->query("SELECT id, variant_name FROM " . DBT_MAIN_VARIANT )->results() as $variant ){	
			$all[] = $variant;
		}
		foreach( $this->pdo->query(" SELECT variant_id, order_no FROM " . DBT_VARIANT_DEF . " WHERE product_id = ? ORDER BY order_no", array( $this->details["id"] ))->results() as $variant ){
			$defs[] = $variant["variant_id"];
			// asagida in_array kosulunu bozmamak icin sıra no lar ayri
			// bir array e aliyorum
			$defs_order[$variant["variant_id"]] = $variant["order_no"];
		}
		// Tanımlı varyant yoksa, undefined olarak tüm ana varyantları gönder
		if( !empty($defs) ){
			$this->has_variant = true;
			// @variant varyant ID si
			foreach( $all as $variant ){
				if( !in_array( $variant["id"], $defs ) ) {
					$this->product_variant_undefined[] = $variant;
				} else {
					// sıra no tanimli varyant tablosunda oldugu icin ayrica ekliyorum burada
					$variant["order_no"] = $defs_order[$variant["id"]];
					$this->product_variant_defined[] = $variant;
				}
			}
			// varyant tanim sirasina gore diz
			$this->product_variant_defined = Common::sort_array_key_string( $this->product_variant_defined, "order_no" );
		} else {
			$this->product_variant_undefined = $all;
		}
	}
	//@ type => yüzde veya fiyat azaltma
	public function calculate_sale_price( $type, $percent, $price ){
	}
	// #1 silinmis urunler tablosuna ekle
	// #2 urunler tablosundan sil
	// *product_id bilgisi eski urun ID sini gosteriyor
	// #3 urun resimlerinin ismindeki ürün idsi deleted_productsdaki id ile guncelleniyor
	// ve sonuna -deleted geliyor. ( product-33-1-deleted.png gibi )
	// *4 tum vitrinlerden siliniyor ürün
	// *5 varyant olayinda tum tanimlamalar ve urunleri siliyorum ( varyant resimleri ????? )
	public function delete(){
		// Deleted ürün listesine ekle
		$insert = $this->pdo->insert( DBT_DELETED_PRODUCTS, array(
			"product_id"            =>	$this->details["id"],
			"category"				=>	$this->details["category"],
			"product_name"			=>	$this->details["product_name"],
			"price_1"				=>	$this->details["price_1"],
			"price_2"				=>	$this->details["price_2"],
			"price_3" 				=>	$this->details["price_3"],
			"pure_price_1"			=>	$this->details["pure_price_1"],
			"pure_price_2"			=>	$this->details["pure_price_2"],
			"pure_price_3" 			=>	$this->details["pure_price_3"],
			"kdv_included" 			=>	$this->details["kdv_included"],
			"kdv_percentage" 		=>	$this->details["kdv_percentage"],
			"sale_percentage" 		=>	$this->details["sale_percentage"],
			"stock_code" 			=>	$this->details["stock_code"],
			"stock_amount" 			=>	$this->details["stock_amount"],
			"desi" 					=>	$this->details["desi"],
			"status" 				=>	$this->details["status"],
			"showcase_home" 		=>	$this->details["showcase_home"],
			"showcase_category" 	=>	$this->details["showcase_category"],
			"new" 					=>	$this->details["new"],
			"campaign" 				=>	$this->details["campaign"],
			"has_form" 				=>	$this->details["has_form"],
			"variant" 				=>	$this->details["variant"],
			"shipment_cost" 		=>	$this->details["shipment_cost"],
			"shipment_system_cost" 	=>	$this->details["shipment_system_cost"],
			"details" 				=>	$this->details["details"],
			"picture_1" 			=>	$this->details["picture_1"],
			"picture_2" 			=>	$this->details["picture_2"],
			"picture_3" 			=>	$this->details["picture_3"],
			"picture_4" 			=>	$this->details["picture_4"],
			"similar_products" 		=>	$this->details["similar_products"],
			"seo_title" 			=>	$this->details["seo_title"],
			"seo_keywords"			=>	$this->details["seo_keywords"],
			"seo_details" 			=>	$this->details["seo_details"]
		));
		// Eklenirse
		if( $insert ){
			// yeni ID
			$new_id = $this->pdo->lastInsertedId();
			// Ürün tablosundan sil
			if( !$this->pdo->query(" DELETE FROM " . $this->table . " WHERE id = ?", array( $this->details["id"] ) ) ){
				$this->return_text = "Ürün tablodan silinemedi. Product.php line 315";
				return false;
			}
			// ürünün silinecegi tablolar
			$delete_from_tables = array( DBT_SHOWCASE_CATEGORY, DBT_SHOWCASE_HOME, DBT_SHOWCASE_NEW, DBT_VARIANT_DEF );
			// varyant productlari sil bunu ayrica yapiyorum sql farkli
			foreach( $this->pdo->query("SELECT id FROM " . DBT_VARIANT_PRODUCTS . " WHERE parent = ? ", array( $this->details["id"] ) )->results() as $item ){
				if( !$this->pdo->query( "DELETE FROM ". DBT_VARIANT_PRODUCTS . " WHERE id = ?", array( $item["id"] ) ) ){
					$this->return_text = "Varyant ürünleri silinemedi. Product.php line 326";
					return false;
				}
			}
			// anasayfa, kategori ve yeni urun vitrinlerinden sil
			// varyant tanimlamalarini sil
			foreach( $delete_from_tables as $list ) {
				foreach( $this->pdo->query("SELECT id FROM " . $list . " WHERE product_id = ? ", array( $this->details["id"] ) )->results() as $item ){
					if( !$this->pdo->query( "DELETE FROM ". $list . " WHERE id = ?", array( $item["id"] ) ) ){
						$this->return_text = "Ürün vitrinden silinirken hata oldu. Vitrin tipi: ". $showcase ." Product.php line 334";
						return false;
					}	
				}
			}
			// tanimli resimleri yeni id ile adlandir
			if( !$this->rename_product_imgs( $new_id ) ){
				$this->return_text = "Resimleri yeniden adlandirma patladi.";
				return false;
			}
			$this->return_text = "Ürün silindi. ( Silinmiş ürünler kısmından sildiğiniz ürünleri geri getirebilirsiniz. )";
			return true;
		} else {
			$this->return_text = "Silinmis urunler tablosuna eklenemedi silinen urun.";
			return false;
		}	
	}
	// category.php de urunler listelenirken kullanilan html
	public function create_list_html_template(){
		if( $this->details["picture_1"] == 1 ){
			$img_src = RES_IMG_PRODUCT_IMG_URL.'product-'.$this->details["id"].'-1-resized';
		} else {
			$img_src = RES_IMG_STATIC_URL . 'photo-default';
		}
		return '
		<div class="urun-liste-item">
		<div class="hederoy">
			<div class="over">
				<div class="urun-wrapper">
					<div class="thumb">
						<a href="'.$this->details["url"].'">
							<img src="'.$img_src.'.png" />
						</a>
					</div>
					<div class="info">
						<div class="urun-isim">
							<a href="'.$this->details["url"].'">'.$this->details["product_name"].'</a>
						</div>
						<div class="urun-fiyat">
							'.$this->details["price_1"].' TL
						</div>
						<div class="buton-cont clearfix">
							<div class="sepet">
								<a href="'.$this->details["url"].'" class="sepet add_to_cart_list" product="'.$this->details["id"].'" hasvariant="'.$this->details["variant"].'" title="Sepete Ekle"></a>
							</div>
							<div class="liste">
								<a href="" class="istek" title="İstek Listeme Ekle"></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			</div>
		</div>';
	}
	// product.php slider template
	public function create_product_page_slider_template(){
		$slider_items = "";
		$slider_item_navs = "";
		for( $i = 1; $i < 5; $i++ ){
			if( $this->details["url_picture_" . $i ] != "" ){
				$slider_item_navs .= '<a class="slider-btn" href="" data-slider="p_img_'.$i.'"><img src="'.$this->details["url_picture_" . $i . "-resized"].'"/></a>';
				$slider_items .= '<li slide="p_img_'.$i.'"><div><span><img src="'.$this->details["url_picture_" . $i] . '"/></span></div></li>';
			}
		}	
		return 
			'<ul class="urun-slider-icerik slider-liste">
				'. $slider_items .'
			</ul>
			<div class="slider-nav">
				'. $slider_item_navs .'
			</div>';
	}

	// product.php varyant selectleri, ilk acilis default
	// bunun girisini bu class ta yapiyorum
	public function create_product_page_variant_template(){
		$html = "";
		// #1 ürüne tanimli varyantlari bul
		$this->variant_defs();
		$counter = 1;
		// her tanimlanmis variant icin select olusturmaya basla
		foreach( $this->get_product_variant_defined() as $v ){
			$Variant = new Variant( false, $v["id"] );
			$html .= $Variant->create_standart_select_template( $counter, $this->details["id"] );
			$counter++;
		}
		return $html;
	}

	// product.php urun bilgileri ul
	public function create_product_page_specs_template(){

	}


	public function has_defined_variant(){
		return $this->has_variant;
	}
	public function get_product_variant_defined(){
		return $this->product_variant_defined;
	}
	public function get_product_variant_undefined(){
		return $this->product_variant_undefined;
	}
	// Unutma js formdaki inputlarin sirasiyla ayni olacak burdaki sira
	public function get_cropped_details(){
		return array(
			"id" 			  => $this->details["id"],
			"product_name" 	  => $this->details["product_name"],
			"price_1" 		  => $this->details["pure_price_1"],
			"price_2" 		  => $this->details["pure_price_2"],
			"price_3" 		  => $this->details["pure_price_3"],
			"stock_amount"    => $this->details["stock_amount"],
			"stock_code" 	  => $this->details["stock_code"],
			"sale_percentage" => $this->details["sale_percentage"]
		);
	}
	public function get_return_text(){
		return $this->return_text;
	}


}