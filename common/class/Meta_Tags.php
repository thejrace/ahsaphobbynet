<?php

	class Meta_Tags {

		private $pdo, $table = DBT_META_TAGS,
				$og = array(), $meta = array();
		public function __construct(){
			$this->pdo = DB::getInstance();
		}

		// tagleri db den alip, hangi sayfadaysak sayfa bilgilerini de
		// kicina ekleyen method
		public function create_tags( $item_info ){
			// taglari og ve meta arraylerini al
			foreach( $this->pdo->query( ' SELECT * FROM ' . $this->table )->results() as $tag ) {
				$this->$tag['meta_type'] = $tag;
			}
			// dbdeki tag lara aktif sayfanini verilerini ekle
			// normal sitenin taglarinin sonunda {item_title} vb. seklinde
			// bulunan kismi ornegin urunun title i ile degistir
			// tag boylece hem sitenin hemde incelenen urunun taglarini icerecek
			$replaces = array( 'title', 'keywords', 'description' );
			foreach( $replaces as $replace ){
				// meta ve og icin replace islemlerini yap
				$this->og[$replace] = str_replace( '{item_'.$replace.'}', $item_info[$replace], $this->og[$replace] );
				$this->meta[$replace] = str_replace( '{item_'.$replace.'}', $item_info[$replace], $this->meta[$replace] );
			}
		}
		// getter lar
		public function get_meta(){
			return $this->meta;
		}
		public function get_og(){
			return $this->og;
		}

	}