<?php
	class File_Upload {

		private $pdo;
		protected $return_text, $files = array();
		protected $settings = array(
			'overwrite'  => true,
			'allowed'    => 'image/*',
			'resize'     => false,
			'width'      => '',
			'height'     => '',
			'ratio_x'    => true,
			'ratio_y'    => true,
			'extension'  => 'png',
			'max_size'   => '5242880',
			'file_name'  => '',
			'target_dir' => ''
		);

		// ayarlar
		public function set_settings( $new_settings ){
			// Eğer dosya ismi ve yüklencek klasör yoksa direk kes
			if( $new_settings['file_name'] == '' || $new_settings['target_dir'] == '' ){
				$this->return_text = 'Dosya ismi veya yükleneceği klasör belirtilmemiş.';
				return false;
			}
			// gelen ayarlari kullan
			foreach( $this->settings as $setting => $val ){
				if( in_array( $setting, array_keys( $new_settings ) ) ){
					$this->settings[$setting] = $new_settings[$setting];
				}
			}
			return true;
		}
		// upload yapan fonksiyon
		public function handle_upload( $_files ){
			// $_FILES arrayini duzenle
			$this->rearray_multiple_files( $_files );
			// Dosyalari sirayla upload et
			foreach( $this->files as $key => $file ){
				if( $file["name"] != "" && $file["type"] != "" && $file["tmp_name"] != "" ){
					// class init
					$Upload = new Upload( $file );
					// yeniden boyutlandirma yapilacaksa
					if( $this->settings['resize'] ){
						$Upload->image_resize = true;
						if( $this->settings['width']  != '' ) $Upload->image_x = $this->settings['width'];
						if( $this->settings['height'] != '' ) $Upload->image_y = $this->settings['height'];
						if( $this->settings['ratio_x'] ) $Upload->image_ratio_x = true;
						if( $this->settings['ratio_y'] ) $Upload->image_ratio_y = true;
					}
					// max boyut
					$Upload->file_max_size = $this->settings['max_size'];
					// kabul edilecek formatlar
					$Upload->allowed = array( $this->settings['allowed'] );
					// varsa aynisi uzerine yazma
					$Upload->file_overwrite = $this->settings['overwrite'];
					// ayarlar okeyse
					if($Upload->uploaded) {	
						// dosya formati
						$Upload->image_convert = $this->settings['extension'];
						// yeni isim
						$Upload->file_new_name_body = $this->settings['file_name'];
						// klasore tasi dosyayi
						$Upload->Process($this->settings['target_dir']);
						// eger tasima islemi yapildiysa
						if( !$Upload->processed ) {
							// tasima isleminde bir problem var
							$this->return_text = "Resimler yüklenirken bir hata oluştu. Lütfen tekrar deneyin. ( Geçerli uzantılar: png, jpeg, gif. )";
							return false;
						} 
					} else {
						// ilk upload ayarlari ile ilgili bir problem var ( uzanti, max_size vs.)
						$this->return_text = "Geçerli uzantılar: png, jpeg, gif.";
						return false;
					}
				} else {
					// eğer hic upload classi kullanilmamissa file inputlarin
					// hepsi bos gelmis demek, false dön
					if( !isset($Upload) ){
						$this->return_text = "Dosya yok. Lütfen seçiniz.";
						return false;
					}
					
					// dosya yok
				}// file if
			} // foreach

			return true;
		}

		// coklu upload da $_FILES sacma sapan bir sekilde geliyor
		// onu her bir file icin duzgun array haline getiriyorum
		// handle_upload da kullanabilecek şekilde
		protected function rearray_multiple_files( $_files ){
			$counter;
			foreach( $_files as $files => $file_array ){
				$counter = count($file_array['name']);
				for( $i = 0; $i < $counter; $i++ ){
					foreach( array_keys($file_array) as $prop ) {
						$this->files[ $i ][ $prop ] = $file_array[$prop][$i];
					}	
				}
			}
		}

		public function get_files(){
			return $this->files;
		}

		public function get_return_text(){
			return $this->return_text;
		}

		public function get_settings(){
			return $this->settings;
		}

	}