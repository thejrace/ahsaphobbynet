<?php

// En altta js ve buradaki sistemlerle ilgili notlar var
class Category_Tree {
	protected $category_list = array(), $html = "", $table = DBT_CATEGORIES, $cat_check_list = array();
	private $pdo;
	public function __construct(){
		$this->pdo = DB::getInstance();
	}

	// Altkategorileri bulur
	public function create( $id, $self ){
		if( $self ) $this->category_list[] = $id;
		foreach( $this->pdo->query("SELECT id, parent FROM " . $this->table )->results() as $category ){
			if( $category["parent"] == $id ){
				$this->category_list[] = $category["id"];
				$this->create( $category["id"], false );
			}
		}
	}

	// Parentlari bulur
	public function create_up( $id, $self ){
		if( $self ) $this->category_list[] = $id;
		$query = $this->pdo->query("SELECT id, parent FROM " . $this->table . " WHERE id = ?", array( $id ) )->results();
		// Sonuç varsa
		// bu kontrol kategori sildiğim zaman kategorinin ürünlerinin
		// category sütununu 0 yaptigim zaman gerekli. Yoksa edit_product ta notice atiyor
		if( count($query) > 0 ){
			if( $query[0]["parent"] != "" ){
				$this->category_list[] = $query[0]["parent"];
				$this->create_up( $query[0]["parent"] , false );
			}
		}
	}

	// ilk dereceden sublari bul
	// isim, ikon ve id var
	public function create_level_one( $id ){
		foreach( $this->pdo->query( "SELECT id, category_name, icon FROM " . $this->table . " WHERE parent = ?", array( $id ) )->results() as $cat ){
			$this->category_list[] = $cat;
		}
	}

	public function categories_select_menu_selected( $id ){
		// listeyi bosalt onceki islemden ID ler kalmasin
		$this->category_list = array();
		// secili kategoriyi dahil etme
		$this->create_up( $id, false );
		$list = $this->get_list();
		$cont = "";
		$c = 0;		
		// Eğer ana parent ise, sub divini doldurma
		if( count($list) == 0 ) {
			return $this->categories_select_menu_default( $id );
		} else {
			foreach( $list as $cat ){
				$c++;
														  // parent, secilen_kategori, html
				$cont = $this->categories_select_menu_update( $cat, $id, $cont );
				// alt kategorili olanlarda selected olacak option listedeki bir onceki parenta esit
				//orn. ustten alta dogru kategoriler;
				// 2 - 3 - 9 - 25 olsun liste bunun tam tersi sırada gelecek
				// parentı 25 olan kategorileri listele, seçili kategoriyi (ilk fonksiyona gelen) bul ve selected yap 
				// 9 için ayni sekilde, parent ı 9 olanlari listele bu sefer listenin bir önceki elemanını selected yap ( 25 )
				// en son ana kategori listesini olusturup listedekileri, kat-list divine yazdiriyoruz
				$id = $list[$c-1];
			}
			return $this->categories_select_menu_default( $list[count($list) - 1], $cont );
		}
	}

	// Ana menüler
	// Urun ekleme de secili bir sekilde gösterirken id degiskeniyle ana kategorinin
	// option u selected yap
	public function categories_select_menu_default( $id = null, $content = null ){
			
		$options = "";
		foreach( $this->pdo->query( "SELECT id, category_name FROM ". $this->table ." WHERE parent = ? && status = ? ", array( "", 1) )->results() as $r ) {
			$selected = "";
			if( isset( $id ) && $id == $r["id"] ) $selected = ' selected ';
			$options .= '<option '.$selected.' value="'.$r["id"].'">'.$r["category_name"].'</option>';
		}

		return '<div class="input-grup">
				  <label class="t2" >Kategori</label>	
				  <div class="input-control">
					  <select name="Cat_" id="Cat_" onchange="CategoryTree(this, \'alt_\')" class="t2 nofloat">
					 	 <option value="0">Seçiniz</option>'
					  	 .$options.
					  '</select>
				  <div id="alt_" class="kat-select">'.$content.'</div></div></div>';
	}

	// Alt kategorilerin listelenmesi
	public function categories_select_menu_update( $id, $selected = null, $content = null ){
		$q = $this->pdo->query( "SELECT id, category_name FROM ". $this->table ." WHERE parent = ? && status = ? ", array( $id, 1 ) );

		$html = 
		'
		<div class="input-control">
		<select class="t2 nofloat" onchange="CategoryTree(this, \'alt_'.$id.'\')" name="Cat_'.$id.'" id="Cat_'.$id.'" >
		<option value="0">Seçiniz</option>';

		if( $q->count() > 0 ) {
			foreach( $q->results() as $r ){
				$chosen = "";
				// Seçili option için selected parametresiyle, listelenenlerin id sini karsilastir.
				// Uyani selected yap
				if( isset($selected) && $selected == $r["id"] ) $chosen = " selected ";
				$html .= '<option '.$chosen.' value="'.$r["id"].'">'.$r["category_name"].'</option>';
			}

			return $html . '</select><div id="alt_'.$id.'" class="kat-select">'.$content.'</div></div>';
		} else {

			return "";
		}		
	}

	// Standart header menu olustur
	public function create_header_menu(){
		// Mobile menu vs icin kullandiysam her ihtimale karsi bosaltiyorum.
		$this->html = "";	
		// Giris
		$this->html .= '<ul class="kategori-liste">';
		// Sol menu
		$this->create_header_menu_navs();
		$this->html .=  '</ul></div><div class="kategori-icerik-cont">'; // ekstra div kategori-liste-cont un end divi
		// Sag alt kategori vitrinleri vs
		$this->create_header_menu_content();
		$this->html .= '</div>'; // content kapanis
	}

	// Normal header menu, sol liste
	protected function create_header_menu_navs(){
		$counter = -1;
		foreach( $this->pdo->query( "SELECT * FROM  ".DBT_CATEGORIES."  WHERE status = ? && parent = ?", array(1, "") )->results() as $r ){
			$aktif = "";
			$counter++;
			if($counter == 0) $aktif = 'class="aktif"';
			$this->html .= '<li '.$aktif.'><a href="nav-tab-'.$r["id"].'" onclick="return false;">'.$r["category_name"].'</a></li>'; 
			   	
		}
	}

	// Normal header menu, sag icerikler
	protected function create_header_menu_content(){
		$counter = 0;
		foreach( $this->pdo->query( "SELECT id, category_name FROM ".DBT_CATEGORIES." WHERE parent = ? && status = ?", array("", 1) )->results() as $r){
			$aktif = "";
			$counter++;
			if( $counter == 1 ) $aktif = "secili";
				$this->html .= '

					<div class="kategori-aktif-cont '.$aktif.'" tabid="nav-tab-'.$r["id"].'">
						<div class="kategori-header">
							<a href="'.CATEGORY_URL. Common::sef_link($r["category_name"]) .'/'.$r["id"].'">'.$r["category_name"].'</a>
						</div>
						<div class="kategori-aktif">

							<div class="kategori-vitrin-cont clearfix">';

				$this->create_header_menu_showcase($r["id"]);
							

				$this->html .='	</div>
								
							<div class="kategori-link-cont">
								<label><a href="'.CATEGORY_URL. Common::sef_link($r["category_name"]) .'/'.$r["id"].'">Daha Fazla Ürün</a></label>
								<div class="kategori-link-liste clearfix">
									
								'. $this->create_header_menu_showcase_list() .'
								</div>
							</div>
						</div>
					</div>
				';

		}
		// SEO LINKLER ICIN KULLAN
		//<a href="http://hobigraf.com/'.$r["id"].'?'.Fonksiyon::sefyap(preg_replace('/\s+/', '-',$r["kategori_adi"])).'">Daha Fazla Ürün</a>
	}

	// Menu icerik ust dörtlü alt kategori
	protected function create_header_menu_showcase( $id ){
		$query = $this->pdo->query("SELECT * FROM " . DBT_CATEGORIES . " WHERE parent = ? && status = ?", array( $id, 1 ) );
		if( $query->count() > 0 ){
			$counter = 0;
			// tekrar tekrar query yapmamak icin property ye atadım
			$this->category_list = $query->results();
			foreach( $this->category_list as $r ){
				if( $counter < 4 ){
					// Üst dörtlü
					// ikon yoksa defaultu yapistir
					$cat_img_id = $r["id"];
					if( $r['icon'] == 0 ) $cat_img_id = 0;
					$this->html .= '
						<div class="vitrin-item">
							<div class="thumb">
								<a href="'.CATEGORY_URL. Common::sef_link($r["category_name"]) .'/'.$r["id"].'">
									<img src="'.RES_IMG_CATEGORY_IMG_URL . 'category-'.$cat_img_id.'.png" />
									<span class="thumb-baslik">'.$r["category_name"].'</span>
								</a>
							</div>
						</div>';
					// İçerigin altinda geri kalanlari listelerken bunlari dahil etmemek icin
					// ust dörtlüye ekledigim kategorileri siliyorum
					unset($this->category_list[$counter]);
				}
				$counter++;
			}
		}	
	}
	// alt üçlü liste
	protected function create_header_menu_showcase_list(){
		$list = "";
		// listelediklerimi tekrar listelememek icin kontrol arrayi
		// category_list ile unset yapabilinir gibi geldi ama olmadı, anlamadım sebebini..
		$added_list = array();
		if( count($this->category_list) > 0 ){
			// 3 lü sutun sayisi
			$col_count = ceil( count($this->category_list) / 3 );
			// üçten fazla olamaz o yuzden sınırlandırıyoruz
			// 9 dan sonrasi listelenmiyor
			if( $col_count > 3 ) $col_count = 3;
			// her bir sutun için div -> ul html kodu cikariyoruz
			for( $i = 0; $i < $col_count; $i++ ){
				$counter = 0;
				$list .= '<div class="link-liste-col"><ul>'; 
				foreach( $this->category_list as $r ){
					// 3 limiti ve listelenmemis ise html olustur
					if( $counter < 3 && !in_array( $r["id"], $added_list ) ){
						$list .= '<li><a href="'.CATEGORY_URL. Common::sef_link($r["category_name"]) .'/'.$r["id"].'">'.$r["category_name"].'</a></li>';
						// eklendi olarak kaydet
						$added_list[] = $r["id"];
						$counter++;
					}
				}
				$list .= '</ul></div>';
			}
		}
		return $list;
	}

	// mobile dropdownlari olusturan fonksiyon
	public function create_mobile_menu(){
		// her ihtimale karsi bosaltiyorum.
		$this->html = "";
		// Her bir ana kategori için ana listeyi oluştur
		// bunlarin içine istif yapacagiz varsa alt kategorisi
		foreach( $this->pdo->query("SELECT * FROM " . $this->table . " WHERE parent = ? && status = ? ", array("", 1))->results() as $cat ){
			// alt kategorisi olanlar için ok ve js icin class eklenmesi	
			$has_sub_class = "";
			$href = "";
			( $this->has_sub($cat["id"]) ) ? $has_sub_class = 'class="sub-dropdown-btn has-sub"' : $href = CATEGORY_URL. Common::sef_link($cat["category_name"]) .'/'.$cat["id"];
			// örnek kategori html kodu en altta documentation da var
			// class islemi sonrasi linki olustur, kategori ismini yaz vs.
			$this->html .= '<li><a href="'.$href.'" '.$has_sub_class.' >'.$cat["category_name"].'</a>';
			// burada alt kategorisi varsa, listelenecek ul olusturma
			// aciklama fonksiyonda
			$this->inner_menu( $cat["id"] );
			// final li
			$this->html .= '</li>';
		}
	}

	// alt kategorileri iç içe istifleyen recrusive fonksiyon
	protected function inner_menu( $id ){
		// alt kategorisi var mı yok mu kontrol için query yapiyoruz
		// @id parent kategorinin id'si
		$query = $this->pdo->query(" SELECT * FROM ". $this->table . " WHERE parent = ? && status = ? ", array( $id, 1 ) );
		// varsa alt kategorisi ul olusturmaya basliyoruz
		// create fonksiyonun aynisi
		if( $query->count() > 0 ){
			// ilk giris
			$this->html .= '<ul class="sub-cat">';
			// simdi her bir alt kategori icinde, alt kategorisi var mi yok mu kontrol
			// ve onlarinda alt kategorilerini listeleyecek ul lari olusturuyoruz
			foreach( $query->results() as $cat ){
				// class mantigi gene ayni
				$has_sub_class = "";
				$href = "";
				( $this->has_sub($cat["id"]) ) ? $has_sub_class = 'class="sub-dropdown-btn has-sub"' : $href = CATEGORY_URL. Common::sef_link($cat["category_name"]) .'/'.$cat["id"];
				// buton kismini olusturma
				$this->html .= '<li><a href="'.$href.'" '.$has_sub_class.'>'.$cat["category_name"].'</a>';
				// asil olay burada recrusive taktigi
				// her bir listeledigim alt kategori icin bu sefer onun alt kategorisi varsa 
				// onun da kendi ul unu olusturuyorum
				$this->inner_menu( $cat["id"] );

				$this->html .= '</li>';
			}
			$this->html .= '</ul>';
		}
	}

	// category.php de üst kısımda alt kategorileri listeleme
	public function create_sub_categories_page(){
		// header menuler olusturuluyor once o yuzden her ihtimale karsi bosalt
		$this->html = "";
		foreach( $this->category_list as $category ) {
			$img_name = "0";
			if( $category["icon"] ) $img_name = $category["id"];
			$this->html .= '
				<div class="alt-kategori-item">
					<div class="over">
						<div class="item-wrapper">
							<div class="thumb">
								<a href="'.CATEGORY_URL. Common::sef_link($category["category_name"]) .'/'.$category["id"].'">
									<img src="'.RES_IMG_CATEGORY_IMG_URL.'category-'.$img_name.'.png" />
									<div class="content">
										'.$category["category_name"].'
									</div>
								</a>
							</div>
						</div>
					</div>
				</div>';
		}
	}

	// verilen id deki kategorinin alt kategorisi var mi yok mu
	protected function has_sub( $id ){
		return $this->pdo->query("SELECT * FROM " . $this->table . " WHERE parent = ?", array($id) )->count() > 0; 
	}

	public function show_html(){
		return $this->html;
	}	

	public function get_list(){
		return $this->category_list;
	}

	public function get_list_reverse(){
		// $this->category_list[] = "";
		return array_reverse($this->category_list);
	}

}

/*
	DOCUMENTATION 

	Ornek Kategori 
	---------------
	id  |  name  |  parent  
	-----------------------
	1   | pleks  |    ""
	----|--------|---------
	3   | kontra |    1
	----|--------|---------
	6   | ozel   |    3
	----|--------|---------
	8   | ffd    |    6

	Birbirinin alt kategorisi olan 3 bagli kategori
	yukaridan asagiya dogru ;
	pleks  -->  kontra  --> ozel
	
	Kategorilerin selectlerinde sistem şöyle;
	<div class="input-control">
		<select id="Cat_[PARENT]" onchange="CategoryTree(this, 'alt_[PARENT]')">...</select>
		<div class="kat-select" id="alt_[PARENT]"></div>
	</div>

	Selectde herhangi bir degisim oldugunda, CategoryTree js fonksiyonuna parametre giden alt_[PARENT] divine
	-eger secilen kategorinin alt kategorisi varsa- onun selecti, ayni yukaridaki sekilde olusturulup yaziliyor.

	Yukaridaki ornek icin sonuna kadar acilmis html kodu ve js aciklamasi;
	
	Selectlerdeki oynamalara göre bir tane hidden inputa seçilen kategorinin ID'sini yaziyoruz. Selectlerle ugrasmiyoruz.
	<input type="hidden" name="category_id" value="0"/>

	<div class="input-control">
		<select id="Cat_" onchange="CategoryTree(this, 'alt_')">
			<option value="3">kontra</option>
		</select>
		<div class="kat-select" id="alt_">
			
			<-- İLK ALT KATEGORI  -->
			<div class="input-control">
				<select id="Cat_1" onchange="CategoryTree(this, 'alt_1')">
					<option value="6">ozel</option>
				</select>
				<div class="kat-select" id="alt_1">

					<-- İKİNCİ ALT KATEGORI  -->	
					<div class="input-control">
						<select id="Cat_" onchange="CategoryTree(this, 'alt_6')">
							<option value="8">ffd</option>
						</select>
						<div class="kat-select" id="alt_6"></div>
					</div>

				</div>
			</div>

		</div>
	</div>

	JS CategoryTree( elem, target ) fonksiyonu;
	Fonksiyon iki iş yapiyor;
		1- Ajax request yapip, seçilen alt kategorinin alt kategorisi varsa html kodunu alip, target a yaziyor
		2- Selectlerde ki degisime gore hidden inputa seçili kateogorinin ID sini yazıyor.
		Burada bir trick var tabi;
		Eğer selectlerin ilk option u olan "Seçiniz.."" seçilirse -ki value = 0 bu optionda- bir if bloğumuz var.
		Sonuç olarak her selectin parentinin ID si, kendi ID'sinde var ( Cat_[PARENT] ). Buradan ID'yi alip
		hidden inputa geciyoruz. 

		Yukarıdaki tablodan örnek verecek olursak örneğin ürünümü "özel" kategorisine eklemek istiyorsam, selectleri şöyle seçtim
		pleks -> kontra -> ozel 
		Şimdi ben "kontranın" alt kategorileri listesinden "ozel" option unu seçersem, fonksiyon otomatik olarak ozelinde alt kategorilerini
		listeleyecek. Ama o listede option "Seçiniz.." oldugu surece hidden inputta "ozel" in ID si kalacak.

		Ana kategorilerin parentları olmadıgı için yukarıdaki olay onlarda yemiyor. Bu sebeple bir if blogu daha yapip,
		eğer parentID yoksa, hidden input = 0 olsun diyoruz. Bu da server-side da error olacak, çünkü kategori id = 0 kabul etmiyoruz.


	16.12.2015 13:19 - Cradle of The Grave - 5FDP


	SITEDE MOBIL MENU ICIN

				// kategori örnek html kodu;
				/* alt kategorisi varsa;
					<li>
						<a href="" class="sub-dropdown-btn has-sub">[KATEGORI_ADI]</a>
						<ul>[ALT_KATEGORILER]</ul>
					</li>

					alt kategorisi yoksa;
					<li>
						<a href="[KATEGORI_LINKI]">[KATEGORI_ADI]</a>
					</li>
				*/

