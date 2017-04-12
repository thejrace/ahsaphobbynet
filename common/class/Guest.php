<?php

	class Guest {

		private $pdo, $table = DBT_GUESTS, $guest_id, $cart_id;

		public function __construct(){
			$this->pdo = DB::getInstance();
		}

		// init te calistirdigim fonksiyon
		public function create_temp_id(){
			// cookie yoksa yeni id olustur
			if( !Cookie::exists("ahguest") ){
				$this->create_new();
			// varsa session kontrolu ve session yoksa dbden kontrol et tokeni
			} else {
				if( !Session::exists("guest_id") ) {
					if( $this->token_is_valid() ){
						Session::set("guest_id", Cookie::get("ahguest") );
					} else {
						Cookie::destroy( "ahguest" );
						Session::destroy("ahguest");
						$this->create_new();
					}
					
				}
			}
		}

		private function token_is_valid(){
			$check = $this->pdo->query("SELECT * FROM " . $this->table . " WHERE code = ?", array( Cookie::get("ahguest") ) )->results();
			return count( $check ) == 1;
		}

		// ip olayi ayni networkte cakisma yapiyo o yuzden iptal
		private function create_new(){
			//$ip_check = $this->pdo->query("SELECT * FROM " . $this->table . " WHERE ip = ?", array( Common::get_ip_int() ) )->results();
			//if( count( $ip_check ) == 1 ){
			//	$this->guest_id = $ip_check[0]["code"];
			//} else {
			// benzersiz id olustur
				$this->guest_id = Common::generate_unique_random_string( $this->table, "code", 30 );
				$this->pdo->insert( $this->table, array(
					"code" 	  => $this->guest_id,
					"ip"   	  => Common::get_ip_int(),
					"created" => Common::get_current_datetime()
				));
		//	}
			Cookie::setwithtime( "ahguest", $this->guest_id, time()+86400*365 );
			Session::set( "guest_id", $this->guest_id );
		}

		public function delete_data(){
			$this->pdo->query("DELETE FROM " . $this->table . " WHERE code = ?", array( Session::get("guest_id")));
			Cookie::destroy( "ahguest" );
			Session::destroy( "guest_id" );
		}

		public function get_id(){
			return Session::get("guest_id");
		}


	}