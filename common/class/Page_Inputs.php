<?php
	class Page_Inputs {
		protected $inputs = array();

		public function __construct( $inputs ){
			$this->inputs = $inputs;
		}
		public function change_def_vals( $new ){
			foreach( $this->inputs as $input => $data ){
				if( in_array( $input, array_keys($new) ) ){
					$this->inputs[$input][1] = $new[$input];
				}
			}
		}
		public function get_all(){
			return $this->inputs;
		}
	}