<?php
	/* Login - 07.02.2016  Obarey Inc.*/	

	class Login {
		private $pdo, $table, $return_text, $logged_in = false, $user_id;

		public function __construct( $input ){
			// formu gostermiyorum ama, formsuz curl vs ile post yaparsa bile
			// throttle varsa isleme almiyoruz
			$LT = new Login_Throttle;
			$LT->check();
			if( !$LT->is_activated() ){
				$this->pdo = DB::getInstance();
				$this->table = DBT_USERS;
				// formla giris
				if( !empty( $input )) {
					if( $this->action( $input ) ){
						$this->logged_in = true;
						$this->return_text = "Giriş yapıldı.";
					}
				} else {
					// remember me ile giris
					if( $this->remember() ){
						$this->logged_in = true;
						$this->return_text = "Giriş yapıldı.";
					}
				}
			}
		}

		private function action($input){

			// eposta kontrolu
			$email_query = $this->pdo->query("SELECT * FROM " . $this->table . " WHERE email = ?", array( $input["email"] ) );
			if( $email_query->count() == 1 ){
				$user_data = $email_query->results();
				$user_salt = $user_data[0]["salt"];
				$user_pass = $user_data[0]["pass"];
				$user_id   = $user_data[0]["id"];
			} else {
				$this->record_failed_login( $input["email"] );
				$this->return_text = "Eposta veya şifre yanlış. Lütfen tekrar kontrol ediniz.";
				return false;
			}

			// sifre kontrolu
			$input_pass = hash( 'sha256', $user_salt . $input["pass"] );
			if( $input_pass != $user_pass ){
				$this->record_failed_login( $input["email"] );
				$this->return_text = "Eposta veya şifre yanlış. Lütfen tekrar kontrol ediniz. hashler uyusmadı";
				return false;
			}

			// remember me kontrolu
			if( isset( $input["remember_me"] ) ){
				$updated_vals = $this->update_remember_me_token($user_id);
				if( !$updated_vals ){
					$this->return_text = "Bir hata oluştu. Lütfen tekrar deneyin.";
					return false;
				}
			}

			// giris yapilmamisken sepete urun attiysa onu transfer et
			$Cart = new Cart( true );
			$Cart->guest_data_transfer( $user_id );
			unset( $Cart );

			// guest session, cookie ve db den verileri sil
			$Guest = new Guest;
			$Guest->delete_data();
			unset( $Guest );

			// dbyi guncelle
			if( !$this->pdo->update( $this->table, "id", $user_id, array(
				'last_login' => Common::get_current_datetime()
			))) {
				$this->return_text = "Bir hata oluştu. Lütfen tekrar deneyin.";
				return false;
			}
			$this->user_id = $user_id;
			return true;
		}

		private function remember(){
			if( !Cookie::exists("ahrmetoken") ) return false;
			$cookie = Cookie::get("ahrmetoken");
			$selector  = substr($cookie, 0, 12 );
			$validator = substr($cookie, 12 );
			// selector kontrolu
			$selector_query = $this->pdo->query("SELECT * FROM " . DBT_AUTH_TOKENS . " WHERE selector = ? ", array($selector) )->results();
			if( count($selector_query) != 1 ) {
				$this->return_text = "Selector DB de yok yalanji var gene.";
				return false;
			}
			// cookie deki validator den token olustur, dbki ile karsilastir
			$cookie_token = hash( 'sha256', $validator );
			$auth_token = $selector_query[0]["token"];
			$user_id = $selector_query[0]["user_id"];

			if( !Common::hash_equals( $auth_token, $cookie_token) ){
				$this->return_text = "Tokenlar uyusmuyor. Yalanji var.";
				return false;
			}
			if( !$this->update_remember_me_token( $selector_query[0]["user_id"] ) ) return false;
			$this->user_id = $user_id;
			return true;
		}

		private function record_failed_login( $email ){
			$this->pdo->insert(DBT_FAILED_LOGINS, array(
				'email' 	=> $email,
				'ip'    	=> Common::get_ip_int(),
				'attempted'	=> Common::get_current_datetime()
			));

			// throttle kontrol
			// ilk once tum yanlis girisler icin kontrol yap eger throttle
			// gerekmiyorsa, yalnızca IP ve email icin ayni kontrol yap
			$LT = new Login_Throttle;
			if( $LT->check_limit_for_all() ) return;
			$LT->check_limit_for_ip( $email );
		}

		// selector ( 12 )
		// validator -> hash -> token (DB)
		// cookie de utf8_encode karakterler tutulmuyor o yuzden base64
		private function update_remember_me_token( $user_id ){
			$selector  = substr( base64_encode( mcrypt_create_iv( 12, MCRYPT_DEV_URANDOM ) ), 0, 12 );
			$validator = base64_encode( mcrypt_create_iv( 32, MCRYPT_DEV_URANDOM ) );
			$token     = hash( 'sha256', $validator );

			// yeni mi ekleyecegiz yoksa update mi kontrol
			$exists_query = $this->pdo->query("SELECT * FROM " . DBT_AUTH_TOKENS . " WHERE user_id = ?", array($user_id))->results();
			if( count($exists_query) == 1 ){
				if( !$this->pdo->update(DBT_AUTH_TOKENS, "user_id", $user_id, array(
					"token" => $token,
					"selector" => $selector
				))){ 
					$this->return_text = "Yeni token update edilemedi.";
					return false;
				}
			} else {	
				if( !$this->pdo->insert(DBT_AUTH_TOKENS, array(
					"user_id"  => $user_id,
					"selector" => $selector,
					"token"    => $token
				))){ 
					$this->return_text = "Yeni token kayit edilemedi";
					return false;
				}
			}
			Cookie::setwithtime("ahrmetoken", $selector.$validator, time()+86400*365 );
			return true;
		}

		public function success(){
			return $this->logged_in;
		}

		public function get_return_text(){
			return $this->return_text;
		}

		public function get_user_id(){
			return $this->user_id;
		}

	}