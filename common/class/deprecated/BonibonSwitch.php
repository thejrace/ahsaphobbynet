<?php

	class BonibonSwitch {

		private $pdo;
		protected $function, $table,
		$funcs_list = array(
			"F0" => "status",
			"F1" => "showcase_home",
			"F2" => "showcase_category",
			"F3" => "campaign",
			"F4" => "new",
			"F5" => "variant",
			"F6" => "has_form"
		);

		public function __construct( $func, $type ){
			$this->function = $this->funcs_list[$func];
			switch( $type ){
				case 'Product':
					$this->table = DBT_PRODUCTS;
				break;

				case 'Category':
					$this->table = DBT_CATEGORIES;
				break;

				case 'ProductVariants':
					$this->table = DBT_PRODUCT_VARIANTS;
				break;
			}
			$this->pdo = DB::getInstance();
		}

		public function action( $id, $state ){
			return $this->pdo->query( "UPDATE ". $this->table ." SET " . $this->function . " = ? WHERE id = ? ", array( $state, $id ) );
		}
	}