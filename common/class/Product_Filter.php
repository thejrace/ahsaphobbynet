<?php

	class Product_Filter {
		protected $filter_headers = array( 'filter_material', 'filter_price', 'filter_campaign' );
		protected $filters = array();

		// her liste refreshte filtre ayarlarini alan fonksiyon
		public function set_filters( $in ){
			// uygulanacak filtreleri filters array'ine geç
			foreach( $this->filter_headers as $header ){
				if( isset( $in[$header] ) ) $this->filters[substr($header,7)] = $in[$header];
			}
		}

		// malzeme filtresi
		protected function filter_material( $material ){
			// ilk acilis
			if( !isset($this->filters['material']) ) return true;
			// kriter uyumlulugunu bulmak icin default false
			// tanimlanan return degiskenleri
			$rule_1 = false;
			$rule_2 = false;
			$rule_3 = false;
			$rule_4 = false;
			foreach( $this->filters['material'] as $material_rule ){
				// kontra
				if( $material_rule == 'Kontra' ){
					$rule_1 = $material == $material_rule;
				// mdf
				} else if( $material_rule == 'MDF' ){
					$rule_2 = $material == $material_rule;
				// pleks
				} else if( $material_rule == 'Pleks' ){
					$rule_3 = $material == $material_rule;
				// diğer
				} else if( $material_rule == 'Others' ){
					$rule_4 = $material == $material_rule;
				}
			}
			// uygulanan filtrelerden en az birine uyuyorsa true dön
			return $rule_1 || $rule_2 || $rule_3 || $rule_4;
		}

		// kampanya filtresi
		protected function filter_campaign( $product_id ){
			// ilk acilis
			if( !isset($this->filters['campaign']) ) return true;
			// kriter uyumlulugunu bulmak icin default false
			// tanimlanan return degiskenleri
			$rule_1 = false;
			$rule_2 = false;
			$rule_3 = false;
			// ozellikleri kontrol icin urun bilgilerini al
			$Product = new Product( $product_id );
			foreach( $this->filters['campaign'] as $campaign_rule ){
				// yeni ürün
				// urun tablsounda new sütununu kontrol et
				if( $campaign_rule == 'new' ){
					$rule_1 = $Product->get_details('new') != 0;
				// sinirli stok
				// urun tablosundan stock_amount 25 ten küçükse
				} else if( $campaign_rule == 'limited_stock' ){
					$rule_2 = $Product->get_details('stock_amount') < 25;
				// indirim
				// urun tablosundan sale_percentage 0 dan buyukse
				} else if( $campaign_rule == 'sale' ){
					$rule_3 = $Product->get_details('sale_percentage') > 0;
				}
			}
			unset($Product);
			// uygulanan filtrelerden en az birine uyuyorsa true dön
			return $rule_1 || $rule_2 || $rule_3;
		}
		// fiyat filtresi
		// TODO: fiyat araliklari dinamik olabilir
		// @price ürünün fiyatı
		// uygulanan filtrelere göre ürün fiyati kriterlere
		// uyuyorsa true doner
		protected function price_filter( $price ){
			// ilk acilista filtreleme olmadigi icin direk true donuyoruz
			if( !isset($this->filters['price']) ) return true;
			// kriter uyumlulugunu bulmak icin default false
			// tanimlanan return degiskenleri
			$rule_1 = false;
			$rule_2 = false;
			$rule_3 = false;
			// herbir fiyat filtresi için ürünün fiyatini kontrol et
			// her loopta tek bir aralık kontrol
			// üst limitler küçük eşittir
			foreach( $this->filters['price'] as $price_rule ){
				// 0 - 25
				if( $price_rule == 1 ){
					$rule_1 = $price >= 0 && $price <= 25;
				// 25 - 50
				} else if( $price_rule == 2 ){
					$rule_2 = $price > 25 && $price <= 50;
				// 50 - 100
				} else if( $price_rule == 3 ){
					$rule_3 = $price > 50 && $price <= 100;
				}
			}
			// uygulanan filtrelerden en az birine uyuyorsa true dön
			return $rule_1 || $rule_2 || $rule_3;
		}

		public function get_filters(){
			return $this->filters;
		}

	}