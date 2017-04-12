<?php

	class Fetch {

		// Cache yazarken her query icin prefix
		const CACHE_SQLSYN_PREFIX = "DTSQL-",
			  CACHE_SQLVAL_PREFIX = "DTSQLVAL-";

		private $pdo;
		protected $count, $results = array(),
				  $table, $sql_id;

		public function __construct( $type ){
			$this->pdo = DB::getInstance();
			// Table listesinden tablonun ismi
			$this->table          = DBTable::get( $type );
			// Kolaylik olsun diye select cumleciginin baslangici
			$this->sql_pre_syntax = "SELECT * FROM " . $this->table . " ";
			// Son query' yi cache' ye alirken basina eklenecek id
			$this->sql_id         = strtoupper($type);
		}
		// SQL cumlesini olusturan, sonuclari ve kayit sayisini alan fonksiyon
		// @type => sql cache'ye alinacak mi alinmayacak mi buna gore belirleniyor ( arama, ilk listeleme, reload )
		// @settings => klasik sql icin kullanilacak ayarlar ( DTSettings )
		// @sql => sql cumlesi ve value' lerini, cumleyi manuel olusturdugumda, query yaparken bu parametreden
		// 		   aliyorum.
		public function get_data( $type, $settings, $sql ){
			// LIMIT icin nerden nereye hesaplamasi
			($settings["page"] == 1) ? $from = 0 : $from = ( $settings["page"] * $settings["rrp"] ) - $settings["rrp"];

			// Toplam kayit sayisi ve limitle cekilen kayit sayisi icin iki ayri cumle,
			// Tekrar tekrar yazmamak icin 
			$q_settings = array( 
				" ORDER BY {$settings["orderby"]} {$settings["direction"]} ",
				" ORDER BY {$settings["orderby"]} {$settings["direction"]} LIMIT {$from}, {$settings["rrp"]} "
			);

			// Query yaparken kontrol icin basta bosaltiyoruz ( bkz. #0000 )
			// Cache de sql varsa asagida buna yaziyoruz ( bkz. #0001 )
			$query_vals = array();

			// Fetch tipine gore listele yapilacaklari
			switch( $type ){

				// Arama
				case 'search':
					// Arama da cumle ve value'leri cache' e aliyorum.
					// boylece table reload edildiginde arama yaptigim query' yi kullaniyorum.
					$this->write_cache( $sql[0], $sql[1] );

					// Kayit sayisi ve sonuclar icin cumleler
					$query_results = $this->sql_pre_syntax . $sql[0] . $q_settings[1];
					$query_count   = $this->sql_pre_syntax . $sql[0] . $q_settings[0];
					$query_vals    = $sql[1];
				break;	

				// Default sayfa ilk acildiginda products.php de calisan tip
				case 'default':
					// Cache de ki query datalarini siliyorum.
					$this->write_cache("", "");
					$query_results = $this->sql_pre_syntax . $q_settings[1];
					$query_count   = $this->sql_pre_syntax . $q_settings[0];
				break;

				// RRP, sayfa, direction degistiginde
				case 'reload':
					// Cache de query yoksa
					if( Session::get( self::CACHE_SQLSYN_PREFIX . $this->sql_id ) == "" && Session::get( self::CACHE_SQLVAL_PREFIX . $this->sql_id ) == "" ){
						// SELECT * FROM table ....
						$SQLSYNT = $this->sql_pre_syntax;
					} else {
						// SELECT * FROM table WHERE x = ? && y = ?....
						$SQLSYNT = $this->sql_pre_syntax . Session::get( self::CACHE_SQLSYN_PREFIX . $this->sql_id );	
						$query_vals = Session::get( self::CACHE_SQLVAL_PREFIX . $this->sql_id ); // #0001
					}
					// Final cumleyi olustur
					$query_results = $SQLSYNT . $q_settings[1];
					$query_count   = $SQLSYNT . $q_settings[0];
				break;
			}

			// Kosullu mu yoksa kosulsuz direk query mi yapicaz ona gore
			// db->query fonksiyonunu calistiriyoruz.
			// Final artik sql burada calitiriliyor.
			if( empty( $query_vals ) ){  // #0000
				// Kayit sayisi ve sonuclari al
				$query = $this->pdo->query( $query_count );
				$this->count = $query->count();
				$query = $this->pdo->query( $query_results );
				$this->results = $query->results();
			} else {
				// Kayit sayisi ve sonuclari al
				$query = $this->pdo->query( $query_count, $query_vals );
				$this->count = $query->count();
				$query = $this->pdo->query( $query_results, $query_vals );
				$this->results = $query->results();
			}
		}

		// Cache yazma fonksiyonu
		protected function write_cache( $syn, $val ){
			Session::set( self::CACHE_SQLSYN_PREFIX . $this->sql_id, $syn );
			Session::set( self::CACHE_SQLVAL_PREFIX . $this->sql_id, $val );
		}

		public function get_record_count(){
			return $this->count;
		}

		public function get_results(){
			return $this->results;
		}

		public function get_db_table(){
			return $this->sql_pre_syntax;
		}

	}