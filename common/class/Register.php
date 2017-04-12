<?php

	/* Register 05.02.16 Obarey Inc.
	*/	
	class Register {
		private $pdo, $table, $return_text, $registered = false;

		public function __construct($input){
			$this->pdo = DB::getInstance();
			$this->table = DBT_USERS;

			if( $this->action( $input ) ){
				$this->registered = true;
				$this->return_text = "Kayıdınız gerçekleşti.";
			}
		}

		private function action( $input ){
			// salt olustur
			$salt = utf8_encode( mcrypt_create_iv( 64, MCRYPT_DEV_URANDOM ) );
			// PHP 5.1.2 ve sonrasinda var hash() fonksiyonu
			// sifre ve salti seviştir
			$hash = hash( 'sha256', $salt . $input["pass_1"] );
			$date = Common::get_current_datetime();
			if( !$this->pdo->insert( $this->table, array(
				"name" 		=> $input["name"],
				"pass" 		=> $hash,
				"salt" 		=> $salt,
				"register" 	=> $date,
				"perm_level" => 1,
				"email" 	 => $input["email"],
				"phone" 	 => ""
			)) ){
				$this->return_text = "Bir hata oluştu. Lütfen tekrar deneyin.";
				return false;
			}

			$user_id = $this->pdo->lastInsertedId();
			return true;
		}

		private function send_activation( $email ){
			
		}

		public function success(){
			return $this->registered;
		}

		public function get_return_text(){
			return $this->return_text;
		}
	}