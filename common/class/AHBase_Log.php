<?php

	class AHBase_Log {

		public static function add( $log, $error = null ){
			$type = "LOG";
			if(isset($error)) $type = "HATA";
			Session::set("ahbase_errors", Session::get("ahbase_errors") . "<br> <b>".$type." :</b>" . $log . " | <b> ( Timestamp:" .  date("Y-m-d") . " " . date("H:i:s") . " ) </b>" );
		}

		public static function get( ){
			return Session::get("ahbase_errors");
		}

	}