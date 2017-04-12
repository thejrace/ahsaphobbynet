<?php	

	/*	
		Login Throttle - 07.02.2016 Obarey Inc.

		- Login sayfasinda eski kayit kontrolu yapiyoruz. Suresi gecmis throttlelari
			pasif hale getiriyoruz.
		- Formu gostermeden once ip bazli veya genel throttle aktive edilmis mi kontrol ediyoruz 
		
		- Her failed login de tum site icin ve IP icin limitin uzerinde failed login varsa
			limit aşımına göre throttle aktive ediyoruz.
		- Bu aşama once tum site icin kontrolle başlıyor, eğer tüm sitede throttle varsa
			IP bazlı throttle kontrolü yapmıyoruz. 
			Tüm sitede limit aşımı yoksa, IP için limit kontrolü yapıyoruz eger varsa limit
			aşımı yalnızca o IP ve mail e throttle uyguluyoruz.

		|FAILED L| DURUM ( TÜM ) |	| FAILED L | DURUM( IP )  |
		|------------------------|	|-------------------------|
		|   <10  | Normal 		 |  |      3   |    L2        |
		|------------------------|  |-------------------------|
		|  11-30 | L1 - 10sn     |  |     > 4  |    L3        |
		|------------------------|  |-------------------------|
		|  31-50 | L2 - 15sn     |  
		|------------------------|	
		|  31-50 | L3 - 25sn     |
		|------------------------|

	*/
	class Login_Throttle {
		private $pdo, $table = DBT_LOG_THROTTLE, $activated = false, $limits = array(), $delay;
		public function __construct(){
			$this->pdo = DB::getInstance();
			$this->limits = array(
				"L1" => 10,
				"L2" => 15,
				"L3" => 25
			);
		}
		// Login form gosterilmeden once ve Login constructta calisiyor
		public function check(){
			// ilk tum site icin throttlelara bak
			$all_query = $this->pdo->query("SELECT * FROM ". $this->table ." WHERE type = ? && active = ? && until > ?", array( "all", 1, time() ) )->results();
			if( count($all_query) == 1 ){
				$this->activated = true;
				$this->delay = $all_query[0]["delay"];
				return;
			}
			// ip icin bak
			$ip_query = $this->pdo->query("SELECT * FROM ". $this->table . " WHERE user_ip = ? && active = ? && until > ?", array( Common::get_ip_int(), 1, time() ))->results();
			if( count($ip_query) == 1 ){
				$this->activated = true;
				$this->delay = $ip_query[0]["delay"];
			}
		}

		// eski kayitlari pasif hale getir
		// her login sayfasında calistiriliyor
		public function check_for_old_records(){
			$old_records = $this->pdo->query("SELECT * FROM " . $this->table . " WHERE type = ? && active = ? && until < ?", array( "all", 1, time() ))->results();
			foreach( $old_records as $record ){
				$this->pdo->update( $this->table, "id", $record["id"], array( "active" => 0 ) );
			}
			$old_records = $this->pdo->query("SELECT * FROM " . $this->table . " WHERE type = ? && active = ? && until < ?", array( "ip", 1, time() ))->results();
			foreach( $old_records as $record ){
				$this->pdo->update( $this->table, "id", $record["id"], array( "active" => 0 ) );
			}
		}

		// Her failed login de yapilacak
		// IP icin son 15dk daki failed login sayisini bulup
		// gerekliyse throttle uyguluyoruz IP ye
		public function check_limit_for_ip( $user_email ){
			$check = $this->pdo->query("SELECT COUNT(1) AS fcount FROM ".DBT_FAILED_LOGINS." WHERE ( email = ? || ip = ? ) && attempted > DATE_SUB(NOW(), INTERVAL 15 minute )", array($user_email, Common::get_ip_int()))->results();
			$fcount = $check[0]["fcount"];
			// limitin altindaysa birsey yapiyoruz
			if( $fcount < 3 ) return;
			// limite gelmiş
			if( $fcount == 3 ){
				$this->delay = $this->limits["L2"];
			} else if( $fcount > 3 ){
				$this->delay = $this->limits["L3"];
			}
			$this->set_new( "ip", $user_email, Common::get_ip_int() );
			return true;
		}

		// Her failed login de yapilacak
		public function check_limit_for_all(){
			$check = $this->pdo->query("SELECT COUNT(1) AS fcount FROM ".DBT_FAILED_LOGINS." WHERE attempted > DATE_SUB(NOW(), INTERVAL 15 minute )")->results();
			$fcount = $check[0]["fcount"];
			// limitin altindaysa birsey yapiyoruz
			if( $fcount < 10 ) return;
			// limite gelmiş
			if( $fcount >= 10 && $fcount <= 30 ){
				$this->delay = $this->limits["L1"];
			} else if( $fcount > 30 && $fcount <= 50 ){
				$this->delay = $this->limits["L2"];
			} else {
				$this->delay = $this->limits["L3"];
			}
			$this->set_new("all");
			return true;
		}
		// throttle uygula
		public function set_new( $type, $email = "", $ip = 0 ){
			if( !$this->pdo->insert( $this->table, array(
				"type" => $type,
				"user_email" => $email,
				"user_ip" => $ip,
				"activated" => Common::get_current_datetime(),
				"until" => time() + $this->delay,
				"delay" => $this->delay,
				"active" => 1
			))) { return false; }
			return true;
		}
		public function get_delay(){
			return $this->delay;
		}
		public function is_activated(){
			return $this->activated;
		}
	}