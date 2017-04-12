<?php

class DBTable {
		protected $table;
		public function get( $table ){
			$list = array(
				"products"   => DBT_PRODUCTS,
				"deleted_products" => DBT_DELETED_PRODUCTS,
				"categories" => DBT_CATEGORIES,
				"variants"   => DBT_MAIN_VARIANT,
				"sub_variants" => DBT_SUB_VARIANT,
				"variant_defs" => DBT_VARIANT_DEF,
				"variant_products" => DBT_VARIANT_PRODUCTS,
				"showcase_category" => DBT_SHOWCASE_CATEGORY,
				"showcase_home" => DBT_SHOWCASE_HOME,
				"showcase_new" => DBT_SHOWCASE_NEW,
				"users" => DBT_USERS
			);

			return $list[$table];
		}

	}