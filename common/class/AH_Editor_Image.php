<?php
	

	// http://www.craftcuts.com/viewerTool/includes/tmp_images/189a2a9e4a76dc7d762aa929cdaf4414.gif
	// 23.01.16 

	class AH_Editor_Image {

		private $pdo, $text, $img_name, $img_src, $img_url, $letter_count, $font, $ext = '.gif',
				$width, $height, $font_size, $x, $y,
				$font_folder, $src_folder,
				$return_text,
				$img_box = array();

		public function __construct( $text, $font, $prev_img ){
			$this->text = $text;
			// default, yazinin uzunluguna gore scale edicem
			$this->font_size = 30;
			$this->height    = 70;
			$this->x         = 5;
			$this->y         = 50;

			$this->src_folder  =  RES_IMG_AH_DIR . 'editor_previews/';
			$this->font = RES_FONT_FORM_DIR . $font . ".ttf";

			if( $prev_img != "default" ) $this->delete_prev_image( $prev_img );
		}

		// her keyup da bir onceki preview resmin adini js den aliyorum ve
		// siliyorum dosyayi
		private function delete_prev_image( $prev_img ){
			$img_src = $this->src_folder . $prev_img;
			if( file_exists( $img_src ) ) unlink( $img_src );
		}

		// gelen text ile harf sayisi ayni mi kontrol et
		// eger ayni degilse burada yapilan sayiyi kabul et
		private function check_length(){
			// bosluklari kaldir
			$this->letter_count = strlen( preg_replace( "/\s/", "", $this->text ) );
			if( $this->letter_count == 0 ){
				$this->text = "AhsapHobby";
			}
			return true; 
		}

		// fontlari varyantlar listesinden kontrol et
		// font form klasorunde font var mi, this->font direk dosya ismi ile ayni olmali
		private function check_font(){
			if( !file_exists( $this->font ) ){
				$this->return_text = "Font dosyasi yok.";
				return false;
			}
			return true;
		}

		// ayni isimli resim varsa tekrar isimlendirme icin
		private function check_name_collision(){
			return file_exists( $this->src_folder . $this->img_name . $this->ext );
		}
		// 32 karakterlik rastgele isim olustur
		private function create_name(){
			// ayni isimde var mi diye kontrol et her ihtimale karsi
			$this->img_name = Common::generate_random_string( 32 );
			if( !$this->check_name_collision() ){
				return true;
			} else {
				$this->create_name();
			}
		}

		private function calculate_img_size(){
			$max_width = 580;
			// eger maxwidth i gecmezsek resmin genisligi yazi kadar olacak
			$this->img_box = imagettfbbox( $this->font_size, 0, $this->font, $this->text );
			// 10 eklemeyince resim kucuk kaliyor yaziya
			$this->width = $this->img_box[2] + 10;
			// max width 580 i astigimiz durum
			if( $this->width > $max_width ){
				// resmin boyutu max genislige esit gecmeyecek
				$this->width = $max_width;
				// her bir karakterin tuttugu width kadar olacak font (deneysel ama tutuyor)
				if( $this->letter_count == 0 ) $this->letter_count = 34;
				$this->font_size = ( $max_width + 5 ) / $this->letter_count;
				// fontu size degistirdigimiz icin ortalama hesaplamak icim
				// yazinin koordinatlarini tekrar aliyoruz
				$this->img_box = imagettfbbox( $this->font_size, 0, $this->font, $this->text );
				// ortala beybe
				$this->x = ceil( ($this->width - $this->img_box[2] )/ 2 );
			}
		}

		// resmi olustur
		public function generate(){
			// harf sayisi kontrolu
			$this->check_length();
			// font kontrolü
			if( !$this->check_font() ) return false;
			// boyut hesaplamalari
			$this->calculate_img_size();

			if( !$this->create_name() ) return false; 
			// kaydedilecek hosting yolunu ve
			// resmi goruntulemek icin url olustur
			$this->img_url = RES_IMG_AH_EDITOR_PREV_URL . $this->img_name . $this->ext;
			$this->img_src = $this->src_folder . $this->img_name . $this->ext;
			// resim objesi
			$img = imagecreate($this->width, $this->height);
			// beyaz
			imagecolorallocate( $img, 255, 255, 255);
			// siyah
			$text_color = imagecolorallocate( $img, 0,0,0);
			// resme yaziyi yaz
			imagettftext( $img, $this->font_size, 0, $this->x, $this->y, $text_color, $this->font, $this->text);
			// patlat beybe
			imagegif( $img, $this->img_src );
			return true;
		}

		public function get_old_img(){
			return $this->img_name . $this->ext;
		}

		public function get_letter_count(){
			return $this->letter_count;
		}

		public function get_url(){
			return $this->img_url;
		}

		public function get_return_text(){
			return $this->return_text;
		}

	}