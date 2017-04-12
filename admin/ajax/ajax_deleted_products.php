<?php 

	require "../inc/init.php";
	
	// Arama icin ayri class, fonksiyonda olabilir
	// bunu global hale getirecegim arama yaparken query olusturmak icin
	class ProductSearch {

		public function action( $inputs ){
			// Tablodaki sütunlar
			$SQL_COLS = [
				"SQL_ID"     => "",
				"SQL_FT_TO"  => "",
				"SQL_FT_FR"  => "",
				"SQL_STA"    => "",
				"SQL_STK"    => "",
				"SQL_KAT"    => ""
			];

			// Koşulları default hali
			// Koşul belirtilmezse tüm datayı çekmek için iki array'in default değerleri boş
			$SQL_VALS = [
				"SQL_ID_BIND"	  => "",
				"SQL_FT_TO_BIND"  => "",
				"SQL_FT_FR_BIND"  => "",
				"SQL_STA_BIND"    => "",
				"SQL_STK_BIND"    => "",
				"SQL_KAT_BIND"    => ""
			];

			$SQL_URUN_ADI   = "";
			$SQL_URUN_ADIKW = [];

			if( !empty( $inputs["product_name"] ) ){

				// Gelen kelimeleri ayır
				$SQL_URUN_ADIKW  = explode( " ", trim( $inputs["product_name"] ) );

				$count = count( $SQL_URUN_ADIKW );
				foreach( $SQL_URUN_ADIKW as $str => $val ){

					// Boş olmayanları 3 arama syntax ı olarak düzenle
					if( $val != "" ) {
						$SQL_URUN_ADIKW[] = '%'.$val.'%';
						$SQL_URUN_ADIKW[] = $val.'%';
						$SQL_URUN_ADIKW[] = '%'.$val;
					}
					// SQL syntax eklenmemiş ve boşları sil
					unset( $SQL_URUN_ADIKW[$str] );	
				}

				// LIKE syntaxını ekle
				$counter = 0;
				foreach( $SQL_URUN_ADIKW as $r ){
					$counter++;

					if( $counter == 1  ) {
						$SQL_URUN_ADI .= " product_name LIKE ?  ";
					} else {
						$SQL_URUN_ADI .= " || product_name LIKE ?  ";
					}
				}

			}

			// Gelen inputlara göre kontrolleri yap
			if( !empty( $inputs["category_id"])  ){

				// Arama yapılan kategorinin alt kategorisi seçilmemiş, yani ' Seçiniz ' option'u seçiliyken aranırsa
				// Kendisinin ve alt kategorilerinde ki ürünleri listele
				// NOT: SQL_KAT' lar VALS ve COLS array'lerinde sonuncu eleman olmalı.
				// 	    Kaç kategori gelecegini bilmedigimiz için en sonda her zaman doğru sıralama.
				$C_Tree = new Category_Tree;
				$C_Tree->create( $inputs["category_id"], true );

				if( count( $C_Tree->get_list() ) > 1 ) {
					$katCounter = 0;
					foreach( $C_Tree->get_list() as $kat ){
						$katCounter++;
						if( $katCounter == 1 ) {
							$SQL_COLS["SQL_KAT"] .= ' category = ? ';
							$SQL_VALS["SQL_KAT_BIND"] = $kat;
						} else {

							$SQL_COLS["SQL_KAT"] .= ' || category = ? ';
							$SQL_VALS["SQL_KAT_BIND" . $katCounter ] = $kat;
						}
					}
				} else {
					$SQL_COLS["SQL_KAT"] = "category = ? ";
					$SQL_VALS["SQL_KAT_BIND"] = $inputs["category_id"]; 
				}
			}

			if( !empty( $inputs["product_id"]) ){
				$SQL_COLS["SQL_ID"] = "id = ? "; $SQL_VALS["SQL_ID_BIND"] = $inputs["product_id"];  }

				// fiyata gore siralamayi sadece fiyat1 e göre yapiyorum
				// fiyat2, 3 kullanıldıgında sıkıntı yaratır
				if( !empty( $inputs[ "price_from"] ) ){
					$SQL_COLS["SQL_FT_FR"] = "price_1 >= ? "; $SQL_VALS["SQL_FT_FR_BIND"] = $inputs["price_from"];  }

					if( !empty( $inputs[ "price_to"] ) ){
						$SQL_COLS["SQL_FT_TO"] = "price_1 <= ? "; $SQL_VALS["SQL_FT_TO_BIND"] = $inputs["price_to"]; }

						if( !empty( $inputs[ "stock_amount"] ) ){
							$Semboller = [ "=", "<=", ">=" ];
							$S = $Semboller[ $inputs['stock_amount_sym'] ];
							$SQL_COLS["SQL_STA"] = "stock_amount " . $S . " ? "; $SQL_VALS["SQL_STA_BIND"] = $inputs["stock_amount"]; }
							
							if( !empty( $inputs[ "stock_code"] ) ){
								$SQL_COLS["SQL_STK"] = "stock_code = ? "; $SQL_VALS["SQL_STK_BIND"] = $inputs["stock_code"]; }


								
			// Boş value ' ler için kontrol
			// Eğer girilmemiş koşullar varsa listeden çıkar,
			// Bunu sql sorgusu yaparken boş değer gidip hata vermesin diye yapıyorum.
			foreach( $SQL_VALS as $key => $val ){
				if( $val == "" ) unset( $SQL_VALS[$key] );
			}
			foreach( $SQL_COLS as $key => $val ){
				if( $val == "" ) unset( $SQL_COLS[$key] );
			}

			// Koşul var mı kontrol et. Yoksa tüm datayı çekmek için sql'i düzenle
			$sqlKosullar =  "";

			// Ürün adı dahil
			if ( count($SQL_COLS) > 0 && count($SQL_VALS) > 0 && $SQL_URUN_ADI != "" ) {

				$sqlKosullar =  " WHERE " . ' ( ' . $SQL_URUN_ADI . ' ) && ';

			// Ürün adı sadece	
			} else if ( count($SQL_COLS) == 0 && count($SQL_VALS) == 0 && $SQL_URUN_ADI != "" ) {

				$sqlKosullar =  " WHERE " . ' ( ' . $SQL_URUN_ADI . ' ) ';

			} else if( count($SQL_COLS) > 0 && count($SQL_VALS) > 0 && $SQL_URUN_ADI == "" ) {
				$sqlKosullar =  " WHERE ";
			}

			// Girilen koşulları sql' syntax a göre kıç kıça && ile ekle		
			$counter = 0;
			foreach( $SQL_COLS as $key => $val ){
				$counter++;
				if( $counter < count($SQL_COLS) ) $SQL_COLS[$key] = $SQL_COLS[$key] . " && ";
				$sqlKosullar .= $SQL_COLS[$key];
			}

						// Ürün adı keyword aramayı ayrıca dahil ediyorum
			$SQL_VALS = array_merge( $SQL_URUN_ADIKW, $SQL_VALS );

			return array( $sqlKosullar, $SQL_VALS );
		}
	}

	if( $_POST ){
		// Form kontrolu
		$Validation = new Validation( new InputErrorHandler );
		$TEMPLATE_KEY = "template";
		$TEMPLATE = "";

		// DB den datayi al
		if( Input::get($TEMPLATE_KEY) != "" ){
			$TEMPLATE = Input::get($TEMPLATE_KEY);
			$Fetch = new Fetch( $TEMPLATE );
			$Product_Table = new DataTable( $TEMPLATE );
			$Product_Table->set_settings( Input::escape( $_POST ) );
		} 

		// Input error larini tutan array
		$input_output = array();

		// Table guncellenecek mi kontrol icin
		// Default true
		$ReloadTable = true;

		// Ajax return degiskenleri
		$DATA  = "";
		$PAGIN = "";
		$OK    = true;
		$TEXT  = "";

		// Fetch icin gerekli parametreler default ayar bu
		// Sadece search icin degisiyorlar
		$FetchType = "reload";
		$FetchSQL  = false;

		switch( Input::get("type") ){
			// Arama 
			case 'ProdSearch':
				$FetchType = "search";
				$FetchSQL  = ProductSearch::action( Input::escape($_POST) );
			break;

			case 'DeleteItem':
				$Product = new Product;
				if( !$Product->delete_permanently( Input::get("item_id") ) ){
					$OK = false;
				}
			break;

			// Deleted productstan ürünü geri alma
			case 'undo_deleted_product':
				$Product = new Product;
				if( !$Product->undo_deleted( Input::get("item_id") ) ){
					$OK = false;
				} 
			break;

		}

		if( $ReloadTable ) {
			if( isset($Product) ) $TEXT = $Product->get_return_text();
			// Al datalari
			$Fetch->get_data( $FetchType, $Product_Table->get_settings(), $FetchSQL );
			// Datatable init					
			$Product_Table->create( $Fetch->get_results(), $Fetch->get_record_count() );
			$DATA  = $Product_Table->show_table();
			$PAGIN = $Product_Table->show_pagination();
		}
	
		$output = json_encode(array(
			"data"         => $DATA, 		 // datatable
			"pagin"        => $PAGIN,   	 // table sayfalam
			"ok"           => $OK,	    	 // istek tamam mi
			"text" 		   => $TEXT,    	 // bildirim
			"table_reload" => $ReloadTable,  // table update edilecek mi
			"inputret"     => $input_output, // form input errorlari
			"oh"           => $_POST
		));

		echo $output;
		die;

	}