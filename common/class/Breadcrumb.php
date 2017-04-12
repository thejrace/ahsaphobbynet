<?php
	
	/*
	* Breadcrumb class v1 10.01.2016 Obarey Inc.
	* 	header.php nin altinda breadcrumb yazdiriyoruz
	* 	her breadcrumb bulunacak sayfada $BREADCRUMB degiskenine ana string i
	* 	yazdiriyoruz.
	*	#category, product sayfalarinda ozel islemler oldugu icin kendi
	*	private methodlari var
	*/
	class Breadcrumb {
		protected $bread_vars = array(), $breadcrumb;
		// @type breadcrumb icin ozel bir islem yapilacaksa kullaniliyor
		// type olmadiginda set() methoduyla gelen array ile olusturulacak		  
		public function __construct( $type = null ){
			$this->breadcrumb = '<a href="'.SITE_URL.'">Anasayfa</a>';
			if( isset($type) && is_array($type) ){
				// tum gelenleri array olarak iceri al
				$this->bread_vars = $type;
				call_user_func_array( array( $this, $this->bread_vars['type'] ), array() );
			}
		}

		// @array her bir crumb icin iki indexli array iceren ana array
		// orn. array( array( giris, giris.php ), array( cikis, cikis.php ) )
		// 0. index isim, 1. index url
		// soldan saga dogru ekleyerek gider
		public function set( $array ){
			foreach( $array as $crumb ){
				$this->add_new_drop( $crumb[0], $crumb[1] );
			}
		}

		// kategori breadcrumb için ozel fonksiyon
		// product.php ve category.php de kullanilir
		// __construct tan cagiriyoruz
		private function category(){
			// mantik categroy selectleri gibi aktif kategorinin
			// ust kategorilerini bulup sirayla crumb şeklinde
			// string concat 
			$C_Tree = new Category_Tree;
			// kendisinide dahil ediyoruz itemin
			$C_Tree->create_up( $this->bread_vars['item_id'], true );
			// listeyi tersten aliyorum cunku Category_Tree array( kendisi, 1. parent, 2.parent )
			// seklinde olusturuyor listeyi basamak basamak
			foreach( $C_Tree->get_list_reverse() as $cat ){
				// kategori ismini aldik
				$Category = new Category($cat);
				$this->add_new_drop( $Category->get_details('category_name'), $Category->get_details("url") );
				unset($Category);
			}
		}

		// ilk olarak kategori agacini olustur
		// en sona urunun adini ekle
		private function product(){
			$this->category();
			$this->add_new_drop( $this->bread_vars['item_name'], "" );
		}

		// ana string e yeni crumb ekleme
		private function add_new_drop( $name, $url ){
			$this->breadcrumb .= '<a href="'.$url.'">'.$name.'</a>';
		}

		public function get(){
			return $this->breadcrumb;
		}

	}