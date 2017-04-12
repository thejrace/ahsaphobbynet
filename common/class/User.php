<?php

	/* User - 07.02.2016 Obarey Inc. */
	class User {
		private $pdo, $table = DBT_USERS, $valid = false, $id;
		public function __construct( $id = null ){
			$this->pdo = DB::getInstance();
			if( isset($id) ) {
				$this->id = $id;
				if( $this->isset_sessions() ){
					$this->valid = true;
				} else {
					$query = $this->pdo->query("SELECT * FROM ". $this->table . " WHERE id = ?", array($this->id) )->results();
					if( count($query) == 1 ){
						$this->valid = true;
						Session::set( "user_id", $query[0]["id"] );
						Session::set( "user_name", $query[0]["name"] );
					}
				}
			}
		}

		public function change_password(){

		}

		public function edit_account(){

		}

		public function isset_sessions(){
			return Session::exists("user_id") && Session::exists("user_name");
		}

		public function get_id(){
			return Session::get("user_id");
		}

	}