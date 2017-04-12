<?php

	class AH_Editor_Product {

		private $price;

		public function calculate_price( $price_each, $letter_count ){

			$this->price = $price_each * $letter_count;

		}

		public function get_price(){
			return $this->price;
		}

	}