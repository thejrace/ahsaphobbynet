<?php

	class Cart {

		private $pdo, $items, $cart_id, $total_price = 0,
				$guest_id = "", $user_id = "",
				$return_text, $item_details = array(),
				$table = DBT_USER_CARTS, $content_table = DBT_CART_CONTENTS;

		public function __construct( $is_guest ){
			$this->pdo = DB::getInstance();
			if( $is_guest ){
				$this->guest_id = Guest::get_id();
			} else {
				$this->user_id = User::get_id();
			}
			// sepeti yoksa olustur
			$this->create();
		}

		public function update_item( $input ){

		}

		public function get_items(){
			$total_price = array();
			$fetch = $this->pdo->query("SELECT * FROM ". $this->content_table . " WHERE cart_id = ?", array( $this->cart_id ))->results();
			if( count($fetch) > 0 ){
				$items = array();
				foreach( $fetch as $item ){
					$URL_TEXT = "";
					$URL_VARCODE = "";
					$details = array();
					$total_price[] = $item["total_price"];
					
					$Product = new Product( $item["product_id"] );
					$IMG_SRC = $Product->get_details("url_picture_1-resized");
					// varyant kontrolu
					if( $item["variant_id"] ){
						$VProduct = new Variant_Product( $item["variant_id"] );
						$details["vars"] = $VProduct->get_details("product_name");
						$URL_VARCODE = "varcode=".$VProduct->get_details("variant_code");
					}
					// resim kontrolu
					if( $item["editor_text"] != Null ){
						$IMG_SRC = $item["img"];
						$details["text"] = $item["editor_text"];
						$URL_TEXT = "&text=".$item["editor_text"];
						// adet * tekli fiyat seklinde gosterdigimiz icin total price almiyorum
						$PRICE = $item["item_price"] * $item["editor_letter_count"];
					} else {
						$PRICE = $item["item_price"];
					}				

					$data = array(
						"cart_id"  => $item["cart_id"],
						"id"   	   => $item["id"],
						"name"     => $Product->get_details("product_name"),
						"price"    => $PRICE,
						"qty"      => $item["quantity"],
						"img_src"  => $IMG_SRC,
						"edit_url" => PRODUCT_URL . Common::sef_link($Product->get_details("product_name")) . "/".$item["product_id"]."?content_id=".$item["id"]."&".$URL_VARCODE.$URL_TEXT 
					);
					$data["details"] = $details;
					$items[] = $data;
				}
				$this->total_price = array_sum( $total_price );
				return $items;
			} else {
				return array();
			}
		}	

		public function get_total_price(){
			return $this->total_price;
		}

		public function add_item( $input ){
			// carpimi etkilemesin yoksa o yuzden 1
			$letter_count = 1;
			$db_params = array(
				'cart_id'	 => $this->cart_id,
				'product_id' => $input["pid"],
				'quantity' 	 => $input["qty"],
				'added' 	 => Common::get_current_datetime()
			);

			$P = new Product( $input["pid"] );
			if( !$P->exists() ){
				$this->return_text = "Böyle bir ürün yok.";
				return false;
			}

			// editor 
			// resimi yeniden adlandirma 
			if( isset($input["img"]) && isset($input["text"] ) ){
				$letter_count = strlen( preg_replace( "/\s/", "", $input["text"] ) );
				if( $letter_count <= 0 ){
					$this->return_text = "Editöre giriş yapılmamış.";
					return false;
				}
					
				// eklemede editor onizlemesinin cart klasorune tasima
				$img_name = substr( $input["img"], 60 );
				if( !isset($input["content_id"])){		
					if( !File::move( RES_IMG_AH_EDITOR_PREV_DIR . $img_name, RES_IMG_AH_EDITOR_PREV_CART_DIR . substr( $img_name, 0, -4 ) . "_" . $this->cart_id . ".gif") ){
						$this->return_text = File::get_return_text();
						return false;
					}
				}

				$db_params["img"]  = RES_IMG_AH_EDITOR_PREV_CART_URL . substr( $img_name, 0, -4 ) . "_" . $this->cart_id . ".gif";
				$IMG_SRC = $db_params["img"];
				$DETAILS_TEXT = $input["text"];
				$db_params["editor_text"] = $input["text"];
				$db_params["editor_letter_count"] = $letter_count;
			}

			// varyant - urun kontrol
			if( $input["vinit"] == "true"){
				$VP = new Variant_Product( array( $input["vcode"], $input["pid"] ) );
				if( !$VP->exists() ){
					$this->return_text = "Ürünün böyle bir varyantı yok.";
					return false;
				}
				// editor yoksa varyant resmini al
				if( !isset($db_params["img"]) ) $IMG_SRC = $P->get_details("url_picture_1-resized");
				$this->item_details = array(
					"id"   		=> $VP->get_details("id"),
					"name" 		=> $P->get_details("product_name"),
					"img_src"	=> $IMG_SRC,
					"details" 	=> array( "vars" => $VP->get_details("product_name") ),
					"price" 	=> $VP->get_details("price_1"),
					"qty"  		=> $input["qty"]
				);
				// 1 - editor varsa detaylara texti ekle
				// 2 - fiyat olarak varyant * harf sayisi al
				if( isset($DETAILS_TEXT) ) {
					$this->item_details["details"]["text"] = $DETAILS_TEXT;
					$this->item_details["price"] = $letter_count * $VP->get_details("price_1");
				}

				// update item kontrolu
				// ekleme ile ayni kontrollu yaptigim icin sadece content id varsa
				// final işlemi ekleme degilde guncelleme yapiyorum
				if( isset($input["content_id"])){
					$update_query = $this->pdo->query("SELECT * FROM ". $this->content_table . " WHERE id = ?",array($input["content_id"]))->results();
					if( count($update_query > 0 ) ){
						// zaten cart klasorunde resim var, onu overwrite yapiyorum resmi guncellemek icin
						if( $update_query[0]["editor_text"] != Null ){
							$old_img_name = substr( $update_query[0]["img"], 65 );
							if( !File::move( RES_IMG_AH_EDITOR_PREV_DIR . $img_name,  RES_IMG_AH_EDITOR_PREV_CART_DIR . $old_img_name ) ){
								$this->return_text = File::get_return_text();
								return false;
							}
							// popup icin resim url
							$this->item_details["img_src"] = RES_IMG_AH_EDITOR_PREV_CART_URL . $old_img_name;
							$TOTAL_PRICE = $input["qty"] * $VP->get_details("price_1") * $letter_count;
						} else {
							$this->item_details["img_src"] = $P->get_details("url_picture_1-resized");
							$DETAILS_TEXT = NULL;
							$letter_count = NULL;
							$TOTAL_PRICE = $input["qty"] * $VP->get_details("price_1");
						}


						$this->pdo->update($this->content_table, "id", $input["content_id"],array(
							"variant_id" 		  => $VP->get_details("id"),
							"total_price"		  => $TOTAL_PRICE,
							"item_price" 		  => $VP->get_details("price_1"),
							"quantity" 			  => $input["qty"],
							"editor_text" 		  => $DETAILS_TEXT,
							"editor_letter_count" => $letter_count
						));
					} else {
						return false;
					}
					return true;
				}

				// yalnizca editor texti bos olan( editörsüz ) varyantlar icin update yapiyoruz
				$exists_query = $this->pdo->query("SELECT * FROM " . $this->content_table ." WHERE cart_id = ? && product_id = ? && editor_text IS NULL && variant_id = ?", array( $this->cart_id, $input["pid"], $VP->get_details("id") ))->results();
				if( count($exists_query) == 1 ){
					$new_qty = $exists_query[0]["quantity"] + 1;
					$this->pdo->update( $this->content_table, "id", $exists_query[0]["id"], array( 
						"quantity"    => $new_qty,
						"total_price" => $VP->get_details("price_1") * $new_qty
					));
					// burada bitir asagiyla isimiz yok update yaptigimiz icin
					return true;
				}
				$db_params["variant_id"] = $VP->get_details("id");
				$db_params["item_price"] = $VP->get_details("price_1");
			} else {
				$this->item_details = array(
					"id"   		=> $P->get_details("id"),
					"name" 		=> $P->get_details("product_name"),
					"price" 	=> $P->get_details("price_1"),
					"img_src" 	=> $P->get_details("url_picture_1-resized"),
					"qty"  		=> $input["qty"]
				);
				// urunden sepette varsa adeti arttir, toplam fiyati guncelle
				$exists_query = $this->pdo->query("SELECT * FROM " . $this->content_table ." WHERE cart_id = ? && product_id = ? && editor_text IS NULL && variant_id IS NULL", array( $this->cart_id, $input["pid"]))->results();
				if( count($exists_query) == 1 ){
					$new_qty = $exists_query[0]["quantity"] + 1;
					$this->pdo->update( $this->content_table, "id", $exists_query[0]["id"], array( 
						"quantity"    => $new_qty,
						"total_price" => $P->get_details("price_1") * $new_qty
					));
					// burada bitir asagiyla isimiz yok update yaptigimiz icin
					return true;
				}
				$db_params["item_price"] = $P->get_details("price_1");
			}
			$db_params["total_price"] = $input["qty"] * $db_params["item_price"] * $letter_count;
			if( !$this->pdo->insert( $this->content_table, $db_params ) ){
				$this->return_text = "DB ye ekleyemedik.";
				return false;
			}
			return true;
		}

		public function has_cart(){
			$query = $this->pdo->query("SELECT * FROM " . $this->table . " WHERE user_id = ? && guest_id = ?", array( User::get_id(), "" ) )->results();
			if( count( $query ) == 1 ){
				$this->cart_id = $query[0]["id"];
				return true;
			} else {
				$query = $this->pdo->query("SELECT * FROM " . $this->table . " WHERE user_id = ? && guest_id = ?", array( "", Guest::get_id() ) )->results();
				if( count( $query ) == 1 ){
					$this->cart_id = $query[0]["id"];
					return true;
				}
			}
			return false;
		}

		public function create(){
			if( !$this->has_cart() ){
				$this->pdo->insert( $this->table, array(
					"user_id" => $this->user_id,
					"guest_id" => $this->guest_id,
					"created" => Common::get_current_datetime()
				));
				$this->cart_id = $this->pdo->lastInsertedId();
				Session::set("hederoy", "Cart var id => " . $this->cart_id . "  Guest id = " . $this->guest_id);
			} else {
				Session::set("hederoy", "Cart yok");
			}
		}

		// giristen sonra uye hesabina sepet bilgilerini transfer et
		public function guest_data_transfer( $id ){
			if( $this->has_cart() ){
				// eger kullanicinin sepeti yoksa
				// - uyeye yeni sepet olustur ( guest_id NULL, user_id yeni_id olacak sekilde guncelle kayıdı )
				$user_cart_check = $this->pdo->query("SELECT * FROM " . $this->table ." WHERE user_id = ?",array($id))->results();
				if( count($user_cart_check) == 0 ){
					$this->pdo->query("UPDATE " . $this->table . " SET guest_id = ?, user_id = ? WHERE guest_id = ?",array(
						"",
						$id,
						$this->guest_id
					));

				} else {
					// eger kullanicinin sepeti varsa
					// - misafirin tum sepet iceriklerini kullanicinin sepetine bagla
					// - misafirin sepetini sil
					$user_cart_id = $user_cart_check[0]["id"];
					$guest_contents = $this->pdo->query("SELECT * FROM ". $this->content_table . " WHERE cart_id = ?",array( $this->cart_id ) );
					foreach( $guest_contents->results() as $content ){
						$this->pdo->update( $this->content_table, "id", $content["id"], array(
							"cart_id" => $user_cart_id
						));
					}
					// sepeti varsa kullanicinin misafirin sepetini sil iceriklerini transfer
					$this->pdo->query("DELETE FROM " . $this->table . " WHERE guest_id = ?", array( $this->guest_id ) );
				}
			}
		}

		public function delete_item( $id ){
			// editorse resimi de cart klasorunden siliyoruz
			$img_check = $this->pdo->query("SELECT * FROM " . $this->content_table . " WHERE id = ?",array($id))->results();
			if( $img_check[0]["img"] != Null ){
				File::delete( RES_IMG_AH_EDITOR_PREV_CART_DIR . substr( $img_check[0]["img"], 65 ) );
			}
			return $this->pdo->query("DELETE FROM " . $this->content_table . " WHERE id = ?", array($id));
		}


		public function get_item_count(){

		}

		public function get_return_text(){
			return $this->return_text;
		}

		public function get_item_details(){
			return $this->item_details;
		}
		
	}