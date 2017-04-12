<?php

	class File {

		public static $return_text;
		public static function exists( $file ){
			return file_exists( $file );
		}

		public static function rename( $file, $name ){
		}

		public static function move( $file, $location ){
			if( self::exists( $file ) ){
				if( !rename( $file, $location ) ){
					self::$return_text = "Rename yapamadik";
					return false;
				} else {
					return true;
				}
			}
			self::$return_text = "Yok boyle bir dosya";
			return false;
		}

		public static function delete( $file ){
			if( self::exists($file) ){
				if( unlink($file) ){
					return true;
				}
			}
			return false;
		}


		public static function get_return_text(){
			return self::$return_text;
		}
	}