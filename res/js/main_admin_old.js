$(function(){

/* NAV MENU TOGGLE
 * ---------------
 * 
 */	
	$('.sol-icerik-toggle').click(function(){

		if($(window).width() < 1000){
			$('.nav-menu').toggleClass("hidden");
		} else{
			$('.sol-icerik').toggleClass("sol-icerik-collapsed");
			$('.sag-icerik').toggleClass("sag-icerik-collapsed");
		}

		return false;
	});



	
	
/* NAV MENU PLUGIN UYGULA
 * ----------------------
 * 
 */
	$('.tree-menu').navMenu();

	$(document).find("[tabdiv]").each(function(){
		$(this).jwTab({
			tabCont: $( $(this).attr("tabdiv") ),
			efekt: 'normal'
		});
	});

	// Notf ekran kaydiginda kayacak
	$(document).on( 'scroll', function(){
	    if( Popup.is_open() ) $AH(Popup.popup).style.top = ( document.body.scrollTop + 60 ) + "px";
	    
	});

	$(document).on("click", ".notf-overlay", function(){
		AHNotf.hideAll();
	});


	 $('.refresh_buton').click(function(){
        $('#captcha_img').attr("src", "http://hobigraf.com/admin/inc/captcha.php?"+(new Date()).getTime());
    });
	 
	$(document).find("form").each(function(){FormValidation.keyup(this);});

	// console.log( DT_functions );


}); //ana_function_end
		
/* V2 FONKSIYONLAR */
	var numInput = [];

	var DT_functions = {
		"template"  : "",
		"rrp" 		: "",
		"direction" : "",
		"page" 		: 1,
		"orderby"   : "",
		"container" : "data-table-body",
		"category_parent":"",
		"axreq"     : "",
		reload_table: function(){
			this.request( 'Reload', "", false );
		},
		request: function(rq, d, cb){
			this.row_loader(true);
			var rt =  "type="+rq+"&";
			// datalari manuel yazdiysam yani form serialize yoksa
			// gelen objecti serialize hale getir
			if( d instanceof Object ) d = manual_serialize(d);

			console.log( rt + d +"&"+this.dt_display_settings()+this.get_template() );

			AHAJAX( this.axreq, "post", "json", rt + d +"&"+this.dt_display_settings()+this.get_template(), function(r){
				if(r.ok) {	
					console.log(r.oh);
					$AH("pagin-bottom").innerHTML = r.pagin;
					$AH("pagin-top").innerHTML = r.pagin;
					$AH(DT_functions.container).innerHTML = r.data;
				}
			}, false ); 

		},
		get_category_parent: function(){
			return this.category_parent;
		},
		get_template: function(){
			return "&template="+this.template;
		},
		change_page: function( p ){
			this.page = p;
			this.reload_table();
			event.preventDefault();
		},
		change_rrp: function( r ){
			this.page = 1;
			this.rrp  = r;
			this.reload_table();
		},
		// Urunler icin bonibon switchler
		bonibon_switch: function( elem ){
			var t = elem.getAttribute("func"),
				d = elem.getAttribute("data"), s;
			// Durum aktifse 0 yolla ( acikken kapatma )
			// Durum pasifse 1 yolla
			( !hasClass(elem, "bonibonPasif") ) ? s = 0 : s = 1;
			this.request( "BonibonSwitch", { "func":t, "data":d, "state":s }, false );
		},
		sort_table: function( d ){
			this.orderby = d;
			( this.direction == "ASC" ) ? this.direction = "DESC" : this.direction = "ASC";
			this.reload_table();
		},
		// type => urun, kategori vs...
		search: function( type, form ){
			// FORM  VALIDATON YAP
			if( FormValidation.check( form ) ) {
				this.page = 1;
				this.request( type, serialize(form), false );
			}
			
			event.preventDefault();
		},
		// Table display ayarlini hidden inputlardan alan fonksiyon
		// Hidden inputlar pagination in en ustundeler
		dt_display_settings: function() {
			return manual_serialize( {"rrp":this.rrp, "page":this.page, "orderby":this.orderby, "direction":this.direction } );
			// return "&rrp="+this.rrp+"&page="+this.page+"&orderby="+this.orderby+"&direction="+this.direction;
		},
		row_loader: function(st){
			var s = $AH("row-spinner");
			st ? s.style.display = "block" : s.style.display = "none";
		},
		quick_add_form: function( t ){
			Popup.on( form_parse_fill( this.template, [] ) );
		},
		quick_edit: function( p, t ){
			AHAJAX( this.axreq, "post", "json", "type=QuickEdit&action=request_form&item_id="+p, function(r){
				console.log(r.oh);
				Popup.on( form_parse_fill( DT_functions.template, r.data ) );

			}, false);
		},
		delete_item: function( i ){
			switch( this.template ){
				case "Products":
					t = "ürünü";
				break;

				case "Categories":
					t = "kategoriyi";
				break;
			}
			if( confirm( "Bu "+t+" silmek istediğinize emin misiniz?") ){
				this.request( "DeleteItem", { "item_id" : i } , false );
			}
		}

	}

	// Template formlari dolduran fonksiyon
	// Template deki degerler ile array deki valuesi ayni sirada olmali
	// Template => %%numerik_sira%% 
	// Data     => key:val seklinde olacak
	function form_parse_fill( form, data ){
		var f = form_templates[form], r, i, c = 0, v = "",
			// Requestten almadigim default form degiskenleri
			exc = { "type":"", "parent":"" };
		if( data.length == 0 ){
			// replace edilecek input sayisini bul
			// urunlerde hizli ekleme yok ama belki lazim olur bu sekilde kalsin 29.11.15
			switch( form ) {
				case 'Categories': ec = 8; break;
				case 'Products' : ec = 9; break;
			}
			// Form type input
			exc["type"] = "QuickAdd";
			// Kategori icin parent kategori idsi ( default null )
			exc["parent"] = DT_functions.get_category_parent();
			// Numerik parse ve replace
			for( i = 0; i < ec; i++ ){				
				r = f.replace( '%%'+c+'%%', v );
				// her replace icin, işlenmis stringi kullan
				f = r;
				c++;
			}
		} else {
			// Form type input
			exc["type"] = "QuickEdit";
			// datadaki her array icin don
			for( i in data ){
				// Her loopta value bosalt 
				v = "";
				// Value varsa arrayden al 
				if( data[i] != undefined ) v = data[i];
				// console.log( data[i]);
				r = f.replace( '%%'+c+'%%', v );
				// her replace icin, işlenmis stringi kullan
				f = r;
				c++;
			}
		}
		// Ajaxla gelen data arrayi harici (exc array) parse ve replace
		for( i in exc ){
			r = f.replace( "%%"+i+"%%", exc[i] );
			f = r;
		}
		return f;
	}

	// Kategori-> ekle, duzenle
	// Ürün-> ekle, duzenle
	// form_parse_fill() de kullaniyorum bu templatleri
	var form_templates = {
		'Categories':
			'<form action="" method="post" id="category_quick_edit" onsubmit="form_submit_v3( this, false )"> \
				<input type="hidden" name="type" value="%%type%%" /> \
				<input type="hidden" name="parent" value="%%parent%%" /> \
				<input type="hidden" name="item_id" value="%%0%%" /> \
				<div class="input-grup"> \
					<label class="t2" for="category_name">Kategori Adı</label> \
					<div class="input-control"> \
						<input type="text" class="t2 req" name="category_name" id="category_name" value="%%1%%"/> \
					</div> \
				</div> \
				<div class="input-grup"> \
					<label class="t2" for="order_no">Sıra</label> \
					<div class="input-control"> \
						<input type="text" class="t2 req posnum" name="order_no" id="order_no" value="%%2%%" /> \
					</div> \
				</div> \
				<div class="input-grup"> \
					<label class="t2" for="tags">Etiketler</label> \
					<div class="input-control"> \
						<input type="text" class="t2" name="tags" id="tags" value="%%3%%" /> \
					</div>  \
				</div> \
				<div class="input-grup"> \
					<label class="t2" for="title">Title</label> \
					<div class="input-control"> \
						<input type="text" class="t2" name="title" id="title" value="%%4%%" /> \
					</div>  \
				</div> \
				<div class="input-grup"> \
					<label class="t2" for="details">Açıklama</label> \
					<div class="input-control"> \
						<input type="text" class="t2" name="details" id="details" value="%%5%%" /> \
					</div>  \
				</div> \
				<div class="input-grup"> \
					<input type="submit" class="btn-buyuk" value="Kaydet" /> \
				</div> \
			</form>',

		'Products':

			'<form action="" method="post" id="product_quick_edit" onsubmit="form_submit_v3( this, false )"> \
				<input type="hidden" name="type" value="%%type%%" /> \
				<input type="hidden" name="item_id" value="%%0%%" /> \
				<div class="input-grup"> \
					<label class="t2" for="product_name">Ürün Adı</label> \
					<div class="input-control"> \
						<input type="text" class="t2 req" name="product_name" id="product_name" value="%%1%%"/> \
					</div>  \
				</div> \
				<div class="input-grup"> \
					<label class="t2" for="price_1">Fiyat 1</label> \
					<div class="input-control"> \
						<input type="text" class="t2 req posnum" name="price_1" id="price_1" value="%%2%%" /> \
					</div>  \
				</div> \
				<div class="input-grup"> \
					<label class="t2" for="price_2">Fiyat 2</label> \
					<div class="input-control"> \
						<input type="text" class="t2 posnum" name="price_2" id="price_2" value="%%3%%" /> \
					</div>  \
				</div> \
				<div class="input-grup"> \
					<label class="t2" for="price_3">Fiyat 3</label> \
					<div class="input-control"> \
						<input type="text" class="t2 posnum" name="price_3" id="price_3" value="%%4%%" /> \
					</div>  \
				</div> \
				<div class="input-grup"> \
					<label class="t2" for="stock_amount">Stok Adedi</label> \
					<div class="input-control"> \
						<input type="text" class="t2 posnum" name="stock_amount" id="stock_amount" value="%%5%%" /> \
					</div>  \
				</div> \
				<div class="input-grup"> \
					<label class="t2" for="stock_code">Stok Kodu</label> \
					<div class="input-control"> \
						<input type="text" class="t2" name="stock_code" id="stock_code" value="%%6%%" /> \
					</div>  \
				</div> \
				<div class="input-grup"> \
					<label class="t2" for="sale_percent">İndirim ( % )</label> \
					<div class="input-control"> \
						<input type="text" class="t2 posnum" name="sale_percent" id="sale_percent" value="%%7%%" /> \
					</div>  \
				</div> \
				<div class="input-grup"> \
					<input type="submit" class="btn-buyuk" value="Kaydet" /> \
				</div> \
			</form>'
	};
	

	function manual_serialize( j ){
		var i, s = [], c, str = "";
		for( i in j ){
			s.push( i + "=" + j[i] );
		}
		str = s.join("&");
		return str;
	}

	function form_submit_v3( elem, return_type ){
		var enctype = elem.getAttribute("enctype"),
			id      = elem.getAttribute("id"),
			e       = [];

		// form kontrolü
		if( FormValidation.check(elem) ){
			if( enctype == null && !return_type ){
				// console.log(serialize(elem));
				AHAJAX( DT_functions.axreq, "post", "json", serialize(elem)+"&"+DT_functions.dt_display_settings()+DT_functions.get_template(), function(r){	
					// server-side kontrol için manuel error yazdiriyorum
					if(!r.ok){	
						// console.log(r.inputret);
						// e arrayini bosaltmama ragmen devamli ekliyor
						// sebebini bulamadim
						for( var i = 0; i < FormValidation.get_input_list().length; i++ ){
							if( r.inputret[FormValidation.get_input_list()[i].id] != null )
								e.push( [ FormValidation.get_input_list()[i], r.inputret[FormValidation.get_input_list()[i].id] ] );
						}
						// console.log(r.oh);
						FormValidation.show_serverside_errors( e );
					} else { // r.ok	
						// console.log( "doeroy" );
						if( Popup.is_open() )Popup.off();
						if( r.text != undefined ) rowNotf(r.text, 1);
						if( r.table_reload ) DT_functions.reload_table();

					}
					console.log(r.oh);
				}, false);
				// Ajax return
				// console.log("prevent default");
			 	event.preventDefault();
			}
		} else {
			// Normal submit
	    	// Form kontrolleri patlarsa
	    	console.log( "form patladi ");
	 		event.preventDefault();
		}	

	}

	var FormValidation = {
		errors: [],
		list: [],
		check: function( f ){
			// Listeyi her kontrol öncesi bosalt
			this.list = [];
			var form = f, i;
			// Formdaki inputlari listele
			for( i = 0; i <= form.elements.length; i++ ){
				if( form.elements[i] != undefined ) {
					if( form.elements[i].type == "text" ||
						form.elements[i].type == "textarea" ||
						form.elements[i].type == "password" ||
						form.elements[i].type == "email" ||
						form.elements[i].type == "select-one" ||
						form.elements[i].type == "select-multiple" ||
						form.elements[i].type == "checkbox"
						) this.list.push( form.elements[i] );
					// Radio secildiyse
					if( form.elements[i].type == "radio" ){
						if( form.elements[i].checked ) this.list.push( form.elements[i] );
					}
				}
			}

			this.check_input( this.list );
			if( this.is_valid() ) {
				return true;
			} else {
				this.show_errors();
			}
		},
		get_input_list: function(){
			return this.list;
		},
		check_input: function(input){
			// Toplu kontrol

			var elem;
			if( input instanceof Array ){
				var input_count = input.length;
				for( var i = 0; i < input_count; i++ ){
					elem = input[i];
					if( hasClass( elem, "posnum" ) ){
						if( !this.posnum( elem.value ) ) this.errors.push( [ elem, "Numerik ve sıfırdan büyük olmalıdır."] );
					}

					if( hasClass( elem, "req") ){
						if( !this.req( elem.value ) ) this.errors.push( [ elem, "Boş bırakılamaz."] );
					}

					if( elem.type == "email" ) {
						if( !this.email( elem.value ) ) this.errors.push( [ elem, "Lütfen geçerli bir email adresi girin."] );
					}
				}
			// Tekli
			} else {

			}
		},
		is_valid: function(){
			return ( this.errors.length == 0 );
		},
		show_serverside_errors: function( errors ){
			this.errors = errors;
			this.show_errors();
		},
		show_errors: function(){
			var co = this.errors.length;
			for( var i = 0; i < co; i++ ){
				// input'un kontrol parent'ina error notf divini ekle
				var elem = this.errors[i][0];
					parent = elem.parentNode,
					error_div  = document.createElement('div'),
					error_span = document.createElement('span');
				// Hata zaten varsa yeni error divleri yapma
				if( !hasClass(elem, "redborder") ){
					error_div.className = "input-error";
					error_div.style.left = ( parent.offsetWidth + 5 ) + "px";
					addClass(elem, "redborder");
					error_span.innerHTML = this.errors[i][1];
					error_div.appendChild(error_span);
					parent.appendChild(error_div);
				}
			}
			// Hatalari gosterdikten sonra bosalt
			// Bir önce kontrol edilen formun hatalarindan kurtulmak
			this.errors = [];
		},
		hide_error: function( e ){
			var p = e.parentNode, pc = p.childNodes, i;
			removeClass(e, "redborder" );
			// input-error divini bul ve sil
			for( i = 0; i < pc.length; i++ ){
				if( pc[i] != undefined ) 
					if( hasClass(pc[i], "input-error") ) {
						p.removeChild(p.childNodes[i]);
					}
			}
		},
		posnum: function( val ){
			// Bos birakilmissa true don, onu kontrol icin req() fonksiyonu var
			if(val.trim() == "") return true;
			return (val - 0) == val && (''+val).trim().length > 0 && !( val < 0 );
		},

		req: function( val ){
			return !( val.trim() == "" || val == undefined );
		},

		email: function( val ){
			var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
   			return re.test(val);
		},
		// jquerynin gozunu seviyim mod on burada
		keyup: function (form){
			var _form = $(form);
				_form.find("select,input[type=radio], input[type=checkbox], input[type=text].req, input[type=text].posnum, input[type=email].req, input[type=password].req, textarea.req").each(function(){
					// Sonradan eklendiği için inputlar parenta event ekliyorum
					_form.on("keyup", "input, textarea, select", function(){
						if( hasClass(this, "redborder") ){
				 			// hata spanlarını gizle
				 			FormValidation.hide_error(this);
				 		}
					});
				});
		}
	};

	var Popup = {
		"overlay": "popup-overlay",
		"popup"  : "popup",
		"open"   : false,

		on: function( data ){
			show( $AH(this.overlay) )

			var	i = $AH(this.popup);
			show(i);

			// Once datalari yazdir
			i.innerHTML = "<div id='popup-buton' onclick='Popup.off()'>X</div>" + data;

			// Ölç - ortala
			i.style.left = "50%";
			i.style.marginLeft = "-" + ( i.offsetWidth / 2 ) + "px";
			i.style.top = ( document.body.scrollTop + 60 ) + "px";

			this.open = true;
		},

		off: function(){
			hide($AH(this.overlay));
	        $AH(this.popup).innerHTML = "";
			hide($AH(this.popup));
			this.open = false;
		},
		is_open: function(){
			return this.open;
		}

	}

	// Kategori selectlerde alt kategorisi olan kategorilerin
	// alt kategorilerini select olarak listeleyen fonksiyon
	function CategoryTree(elem, target){	
		var t = $AH(target), val;	
		t.innerHTML = "";	
		// Eğer seçiniz option'u seçilirse bir üst kategornin ID'sini al
		( elem.value == 0 ) ? val = $AH("Cat_" + t.parentNode.id.substr(4) ).value  : val = elem.value;
		document.getElementById("category_id").value = val;

		AHAJAX( "ajax/ajax_category_tree.php", "post", "json", "data=" + elem.value, function(r){
			t.innerHTML = r.data;
		}, false);
	}

	function rowNotf(notf, type){

		var c = $AH("row-ust-notf"), i;
		type == 1 ? i = "notf1" : i = "notf0";
		c.innerHTML = '<div class="icerik '+i+' clearfix"><span>' + notf + '</span><div class="btn" onclick="rowNotfKapat();">[x]</div><div>';
		document.body.scrollTop = 0;

	}	

	function rowNotfKapat(){
		$AH("row-ust-notf").innerHTML = "";
	}


	function serialize(form) {
		if (!form || form.nodeName !== "FORM") {
			return;
		}
		var i, j, q = [];
		for (i = form.elements.length - 1; i >= 0; i = i - 1) {
			if (form.elements[i].name === "") {
				continue;
			}
			switch (form.elements[i].nodeName) {
			case 'INPUT':
				switch (form.elements[i].type) {
				case 'text':
				case 'hidden':
				case 'password':
				case 'button':
				case 'reset':
				case 'submit':
					q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
					break;
				case 'checkbox':
				case 'radio':
					if (form.elements[i].checked) {
						q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
					}						
					break;
				case 'file':
					break;
				}
				break;			 
			case 'TEXTAREA':
				q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
				break;
			case 'SELECT':
				switch (form.elements[i].type) {
				case 'select-one':
					q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
					break;
				case 'select-multiple':
					for (j = form.elements[i].options.length - 1; j >= 0; j = j - 1) {
						if (form.elements[i].options[j].selected) {
							q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].options[j].value));
						}
					}
					break;
				}
				break;
			case 'BUTTON':
				switch (form.elements[i].type) {
				case 'reset':
				case 'submit':
				case 'button':
					q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
					break;
				}
				break;
			}
		}
		return q.join("&");
	}

/* V2 FONKSIYONLAR*/

		function show(e){
			e.style.display = "block";
		}

		function hide(e){
			e.style.display = "none";
		}

		function $AH(id){
			return document.getElementById(id);
		}

		function kategoriSelect(elem, target){	
			var t = $AH(target), val;	
			t.innerHTML = "";	
			// Eğer seçiniz option'u seçilirse bir üst kategornin ID'sini al
			( elem.value == 0 ) ? val = $AH("Kategori_" + t.parentNode.id.substr(4) ).value  : val = elem.value;
			document.getElementById("category").value = val;

			AHAJAX("", "post", "json", "type=kategoriSelect&data=" + elem.value, function(r){
				t.innerHTML = r.data;
			}, false);
		}

		function IsNumeric(input)
		{
		    return (input - 0) == input && (''+input).trim().length > 0;
		}

		// Numeric input kontrolü
		// FormSub' da kullanıyorum
		function inputNumKontrol(num){
			// console.log(num);
			if( num.length != 0 ) {
				var Hata = [];
				// Hatalı girişleri array'e ekle
				for( var i = 0; i < num.length; i++ ){
					// console.log(num);
					if( $AH( num[i] ) != null ){
						// console.log(num[i]);
						if( !IsNumeric( $AH( num[i] ).value ) ) Hata.push(num[i]);
					} 
					
				}

				// Eğer hatalı giriş yoksa true dön
				if( Hata.length == 0 ){
					return true;
				} else {
					// Hatalı giriş olan inputlara error yazdır
					for( var i = 0; i < Object.size(Hata); i++ ){
						AHINPUTERROR.showError( "Lütfen yalnızca rakam kullanın!", Hata[i]);
					}
					return false;
				}
			} else {
				// Eğer numerik input yoksa true dön
				return true;
			}
		}

		function formSub(elem, ev, returnType ){

			var enctype = elem.getAttribute("enctype"),
				id      = elem.id;

			
				AHForm.kontrol("#"+id, "Lütfen boş bırakmayın veya kritere uygun bir giriş yapın.");

				// Eğer numInput yani numerik input yoksa tek tek yazmamak için kontrolü burda yap
				if ( typeof numInput == 'undefined' ) numInput = [];
				if( AHForm.ok && inputNumKontrol(numInput) ){

					if( enctype == null && !returnType ){
			 			AHAJAX("", "post", "json", AHForm.serialize() , function(r){	
			 				if(!r.ok){
			 					for(var i = 0; i < Object.size(r); i++){

			 						if(r[AHINPUTERROR.formInput(AHForm.formid)[i]] != null){
			 							AHINPUTERROR.showError(r[AHINPUTERROR.formInput(AHForm.formid)[i]], AHINPUTERROR.formInput(AHForm.formid)[i]);
			 						}

								}
								
			 				} else {

				 			}
				 		
		 				}, false);
			 			// Ajax return
		 				ev.preventDefault();
		 			}
		 			// Normal submit

		 		} else {
		 			// Form kontrolleri patlarsa
		 			ev.preventDefault();
		 		
		 		}
		}

		// elem, event, ajax true ( ajax yok ) - false ( ajax var )
		function dataTableSubmit(elem, ev, returnType ){

			var enctype = elem.getAttribute("enctype"),
				id      = elem.getAttribute("id")
			
				AHForm.kontrol("#"+id, "Lütfen boş bırakmayın veya kritere uygun bir giriş yapın.");

				// Eğer numInput yani numerik input yoksa tek tek yazmamak için kontrolü burda yap
				if ( typeof numInput == 'undefined' ) numInput = [];
				if( AHForm.ok && inputNumKontrol(numInput) ){
					if( enctype == null && !returnType ){
			 			AHAJAX("", "post", "json", AHForm.serialize() + AHDataTable.tableSettingsData(), function(r){	
			 				if(!r.ok){	
			 					console.log( r.text );
			 					for(var i = 0; i < Object.size(r); i++){
			 						if(r[AHINPUTERROR.formInput(AHForm.formid)[i]] != null){
			 							AHINPUTERROR.showError(r[AHINPUTERROR.formInput(AHForm.formid)[i]], AHINPUTERROR.formInput(AHForm.formid)[i]);
			 						}
								}
								if( r.text != undefined ) rowNotf(r.text, 0);	
			 				} else {
			 					if( r.text != undefined ) rowNotf(r.text, 1);

								// AHNotf.insert(AHNotf.sekme, "<span>"+r.text+"</span>");
				 				AHNotf.hideAll();
				 				AHDataTable.tableUpdate(r.data);
				 				$('.pagination-container').empty().append(r.sayfalama);
				 			}

				 		
		 				}, false);
			 			// Ajax return
		 				ev.preventDefault();
		 			}

		 			// Normal submit

		 		} else {
		 			// Form kontrolleri patlarsa
		 			ev.preventDefault();
		 		
		 		}
		}

		function AHTooltip(type, data, elem, e){
			var t = $AH("ah-tooltip");
			if( type == "img" ) data = '<img src="'+data+'" />';
			t.innerHTML = data;
			t.style.left = ( e.pageX + 20 ) + "px";
			t.style.top  = ( e.pageY + 20 ) + "px";
			t.style.display  = "block";

			elem.onmouseout = function(){
				
				t.style.display  = "none";
				t.style.left = 0 + "px";
				t.style.top  = 0 + "px";
				t.innerHTML = "";
			}

			e.stopPropagation();
		}

		// Arama dropdown
		function DTAramaToggle(){
			var c = $AH("dt-arama-cont");
			( c.style.display == 'block' ) ? c.style.display = "none" : c.style.display = "block"; 
		}

		function hasClass(element, cls) {
			return (' ' + element.className + ' ').indexOf(' ' + cls + ' ') > -1;
		}

		function addClass(element, cls){
			if( !hasClass(element, cls ) ) element.className += ' ' + cls;
		}

		function removeClass(element, cls) {
			var newClass = ' ' + element.className.replace( /[\t\r\n]/g, ' ') + ' ';
			// console.log( newClass );
			if( hasClass(element, cls ) ){
				while( newClass.indexOf(' ' + cls + ' ' ) >= 0 ){
					newClass = newClass.replace( ' ' + cls + ' ', ' ' );
				}
				element.className = newClass.replace( /^\s+|\s+$/g, '' );
			}
		}

		
		var AHINPUTERROR = {

			valErrors: [],
				// Input boş mu kontrol et
				// @params input Element veya Array : Kontrol edilecek input array veya tek element.
				valKontrol: function(input){
					// Her kontrolde hata array'ini temizle
					this.valErrors = [];
					if(input instanceof Array){
						
						for(var i=0; i < input.length; i++){
							if($.trim( $("#" +input[i]).val() ) == '' && $("#" +input[i]).hasClass("req") ){
								this.valErrors.push(input[i]);

							} else {

								// Eğer input epostaysa regex kontrolü yap
								if($("#" +input[i]).attr("name") == "eposta"){

									
									if(! this.emailKontrol( $("#" + input[i]).val() ) ){
										this.valErrors.push(input[i]);
									} 

								} 

								this.errorFalse(input[i]);		
							}
						}
					} else {
						if($.trim( $("#" +input).val() ) == '' && $("#" +input).hasClass("req")){
							this.valErrors.push(input);
						} else {

							// Eğer input epostaysa regex kontrolü yap
							if($("#" +input).attr("name") == "eposta"){

								if(! this.emailKontrol( $("#" + input).val() ) ){
									this.valErrors.push(input);
								} 

							}

							// Eğer doluysa inputun error attr false yap
							this.errorFalse(input);		
							
						}
					}
					
				},

				emailKontrol: function(input){
					var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
   					
   					return re.test(input); //true - false
				},
				
				// Error varsa false, yoksa true döndürür
				valKontrolToken: function(mesaj){
					if((this.valErrors).length == 0){
						return true;
					} else {
						this.showError(mesaj, this.valErrors);
						//return false;
					}
				},

				// @param elem Element
				errorFalse: function(elem){
					$("#" +elem).attr('error', 'false');
				},

				// @param elem Element
				errorTrue: function(elem){
					$("#" +elem).attr('error', 'true');
				},

				// @param input Element
				hideError: function(input){
					$("#" + input).css({'borderColor': ''});

					var parent = $("#" + input).parent();
					// Eğer hata span varsa onu kaldır
					if(parent.find(".input-error") != null){
						parent.find(".input-error").remove();
					}

					this.errorFalse(input);
				},

				// Inputlara uyarı yazdır
				// @param mesaj String
				// @param input Element
				showError: function(mesaj, input){

					// Gelen inputlar array mi ona bak
					// Ona göre işlem yap
					if(input instanceof Array){

						for(var i = 0; i < input.length; i++){
							
							// Parentı tanımla
							var parent = $("#" +input[i]).parent();
							
							// Border'ı kırmızı yap
							$("#" +input[i]).css({'borderColor' : 'red'});


							// ALOOO BUTUN FORM ELEMENTLERİNİ INPUT GRUP İÇİNE AL!!!
							// INPUT ERROR' U INPUTA GÖRE DEGİL PARENT A GORE CIKAR
							//var w =$("#" +input[i]).outerWidth();
							var w = parent.outerWidth();

							// Eger error attr false ise atraksiyon yap
							// true ise zaten yapılmış olacak aşağıdakiler
							if( !$(input[i]).attr('error') ){
								
								// Eğer uyarı eklenmemişse
								// Uyarıyı parentın en altına ekle
								if(parent.find(".input-error").length == 0 ){
									parent.append('<div class="input-error" style="left:'+w+'px"><span>'+mesaj+'</span></div>');
								}
								// Input'un error attr true yap
								this.errorTrue(input[i]);
							}
						}
					
					} else {

						// Border'ı kırmızı yap
						$("#" +input).css({'borderColor' : 'red'});

						
						// Parentı tanımla
						var parent = $("#" +input).parent();

						//var w = $("#" +input).outerWidth();
						var w = parent.outerWidth();

						// Yukarıdaki işlemlerin aynısı
						if( !$(input).attr('error') ){
							
							// Eğer uyarı eklenmemişse
							// Uyarıyı parentın en altına ekle
						if(parent.find(".input-error").length == 0 ){		
							parent.append('<div class="input-error" style="left:'+w+'px"><span>'+mesaj+'</span></div>');
						}	
							this.errorTrue(input);	
						} 
						
					}
				},

				// Inputları tek tek idlerini girmektense bunla form id içindeki tüm input lara ulaş.
				formInput: function(formid){
					var form = $(formid);
						inputArray = [];

					form.find("input[type=text], input[type=password], textarea").each(function(){
						inputArray.push($(this).attr("id"));
					});

					return inputArray;
				},

				keyup: function(form){

					var _form = $(form);
						inputArray = [];

					_form.find("input[type=text].req, input[type=password].req, textarea.req").each(function(){
						
							// Sonradan eklendiği için inputlar parenta event ekliyorum
							_form.on("keyup", "input, textarea", function(){
								if( $(this).attr("error") == "true" ){
			 						// hata spanlarını gizle
			 						AHINPUTERROR.hideError(this.id);
			 						//console.log("abo");
		 						}
							});
						
						

					});
		
				}


 		};

 		var AHForm = {

			formData: {},
			formid:"",
			ok: true,

			kontrol: function(formid, mesaj){

				this.ok = true;
				this.formid = formid;

				// $(this.formid).css({"opacity":0.5});

				AHINPUTERROR.valKontrol(AHINPUTERROR.formInput(formid));

				if(!AHINPUTERROR.valKontrolToken(mesaj)){	
					this.ok = false;
					// $(this.formid).css({"opacity":1});
				} 
			},

			serialize: function(){
				this.formData = $(this.formid).serialize();
				return this.formData;
			},

			// Yalnızca numeric input fonksiyonu
			numeric: function(e){
				 if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
		             // Ctrl+A
		            (e.keyCode == 65 && e.ctrlKey === true) || 
		             // home, end, sol, sag ok
		            (e.keyCode >= 35 && e.keyCode <= 39)) {
		                 // no problema
		                 return;
		        }
		        // Yukardıkiler harici ve say degilse yazdırma
		        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
		            e.preventDefault();
		        }
			},
			// Kartno container 4 karakterden sonra yandaki inputa geçir
			// Yalnızca rakam kabul et
			kartno: function(elem){
	
				$("#" + elem).on("keydown", function(e){
					AHForm.numeric(e);
					
					var id = parseInt( ($(this).attr("id")).substring(6,7) );

					if( ($(this).val()).length == 4 && id != 4){
						$('#kartno' +(id+1)).focus();

					}

				});
			}
		};
 		

		var AHNotf = {
			bg    : ".notf-overlay",
			sekme : ".notf-cont",

			show: function(notf){
				$(this.bg).fadeIn(300);
				$(notf).fadeIn(300);
				
			},

			showLoader: function(){
				$(this.loader).fadeIn(100);
			},

			hideLoader: function(){
				$(this.loader).fadeOut(100);
			},

			hide: function(notf){
				$(this.bg).fadeOut(100);
				$(notf).empty().fadeOut(100);
			},

			hideAll: function(){
				$(this.bg).fadeOut(100);
				$(this.sekme).empty().fadeOut(100);
			},

			insert: function(sekme, data){
				$(sekme).empty().append("" + data + "").css({
					"top"       : ( $(document).scrollTop()+100 ) + "px",
					"marginLeft": ( "-" + $(".notf-cont").width() * 0.5 )+ "px"
					});
			},

			alert: function(mesaj){
				this.insert(this.sekme, mesaj);
				this.show(this.sekme);
			
			
			},

			formContent: function(type, data){

				this.insert(AHNotf.sekme, '<img src="http://ahsaphobby.net/resources/img/static/ico/spinner-row.gif" />');
				this.show(AHNotf.sekme);

				function bebe(r){
					var code = '$(document).find("form").each(function(){AHINPUTERROR.keyup(this);});';
					AHNotf.insert(AHNotf.sekme, r.form + "<script type='text/javascript'>" + code + '</script>');

					// console.log(r);
					//AHNotf.show(AHNotf.sekme);
				}

				// console.log(  "data="+data+"&type="+ type + AHDataTable.tableSettingsData() );
				AHAJAX("", "post", "json", "data="+data+"&type="+ type + AHDataTable.tableSettingsData(), bebe, false);
				

			}

		};

		function AHAJAX(url, type, dataType, data, callback, spinner){

			if(spinner){
				AHNotf.showLoader();
			}
			DT_functions.row_loader(true);
			console.log(data);

			$.ajax({
				url: url,
				type: type,
				dataType: dataType,
				data: data,
				success: function(r){
					if(typeof callback == 'function'){
						callback(r);
					}

					if(spinner){
						AHNotf.hideLoader();
					}
					DT_functions.row_loader(false);

				},
				error: function(){
					console.log("fail");
					window.location.reload();
				}

			});
		}

		var AHDataTable = {

			"btn"       : "[func]",
			"btnDuzenle": "[func=duzenle]",
			"btnStats"  : "[func=stats]",
			"btnSil"    : "[func=sil]",
			"btnKey"    : "[switch=key]",
			"btnKaydet" : "[func=btnKaydet]",
			"spinner"   : ".row-spinner",
			"notfSpan"  : ".row-ajax-rsp",
			"tableData" : ".data-table-cont",

			switchbtn: function(elem){
				var t = $(elem).attr("func"),
					d = $(elem).attr("data"),
					s;

				if( !$(elem).hasClass("bonibonPasif") ){
					// Durum aktifse 0 yolla
					s = 0;
				} else {
					// Durum pasifse 1 yolla
					s = 1;
				}

				// console.log( AHDataTable.tableSettingsData() );
				AHAJAX("", "post", "json", "type=switchBtn&func="+t+"&data="+d+"&state="+ s + AHDataTable.tableSettingsData(), function(r){
					AHDataTable.tableUpdate(r.data);	
					// console.log(r);
				},false);
	
			},

			delete: function(elem, alert) {
				var t = $(elem).attr("func"),
					d = $(elem).attr("data"),
					c = confirm(alert);

				if(c){
					AHAJAX("", "post", "json", "type="+t+"&data="+d + AHDataTable.tableSettingsData(), function(r){
					
						rowNotf(r.text, r.ok);
						AHDataTable.tableUpdate(r.data);
						$('.pagination-container').empty().append(r.sayfalama);
						console.log(r.ks);	
					},false);
				}
			},

			// Düzenle, sil, istatistik butonları
			func: function(elem){
				var t, d;

				// Dataları al
				// Input mu, button mu kontrol ediyorum. Ona göre data ve name 'i alıyorum.
				if( $(elem).is("select") || $(elem).is("input") || $(elem).is("textarea") ) {
					t = $(elem).attr("name");
					d = $(elem).val();
				} else {
					t = $(elem).attr("func");
					d = $(elem).attr("data");
				}
					
				// Formu göster
				AHNotf.formContent(t, d);
				
			},

			kaydetBtnSwitch: function(s){ 
				if(s) { $(this.btnKaydet).attr("disabled", false); }
				else { $(this.btnKaydet).attr("disabled", true); }
				
			},

			// KULLANMIYORUM! BONIBONLAR VAR ARTIK
			// Değişiklikte kaydetmek için kullanılan buton
			kaydet: function(){
				AHDataTable.spinnerShow();
				// console.log($("#data-table-form").serialize() + );
				AHAJAX("", "post", "json", $("#data-table-form").serialize() + AHDataTable.tableSettingsData(), function(r){
					if(r.ok){
						AHDataTable.rspNotf(r.text);
						AHDataTable.tableUpdate(r.data);
						setTimeout(function(){ $(AHDataTable.notfSpan).empty(); }, 1000);	
					}
				},false);	
			},

			spinnerShow: function(){
				$(this.spinner).show();
			},

			spinnerHide: function(){
				$(this.spinner).hide();
			},

			rspNotf: function(data){
				$(this.notfSpan).empty().append(data);
				$(this.spinner).hide();
				this.kaydetBtnSwitch(0);
			},

			tableSettingsData: function() {
				return "&rrp="+$AH("dt_rrp").value+"&sayfa="+$AH("dt_sayfa").value + "&orderby=" + $AH("dt_orderby").value + "&ascOrDsc=" + $AH("dt_ascOrDsc").value;
			},

			// V2 YI KULLANIYORUM ARTIK
			tableSettings: function(elem, data){
				AHDataTable.spinnerShow();

				var	upd = '&hesap=true',
					btn = '&buton=false',
					tData;

				// Başka bir fonksiyon oluşturmamak için böyle kısa yol yapıyorum. Tembellik zor
				if( elem == "reload" ) {
					// Table reload

					tData = "&rrp="+data[0]+"&sayfa="+data[1];
					upd = '&hesap=false';
				} else {
					// Butonlarla vs. işlem
					tData = AHDataTable.tableSettingsData();

					if( $(elem).is("a") ){
						btn = '&buton='+$(elem).attr("f");
					} 

					if( $(elem).attr("name") == 'sayfa' ) {
						upd = '&hesap=false';
					}
				}		
				// console.log( tData + " btn -> " +  btn );

				AHAJAX("", "post", "json", "type=tableSet"+ tData + upd+btn, function(r){
					if(r.ok){
						console.log(" | | kayits " + r.ks );
						AHDataTable.tableUpdate(r.data);
						$('.pagination-container').empty().append(r.sayfalama);
						$(AHDataTable.spinner).hide();
					}
				},false);

			},


			// Sayfalama butonları ve selectlerinde,
			// Icon upload sonrası reload için kullanıyorum.
			tableSettingsV2: function( type, data ){
				AHDataTable.spinnerShow();
				
				var tData = "";

				switch( type ){
					// Reload ( Upload sonrası sayfa yenilendiği için ajax'sız versiyon )
					// 	data[0] => "rrp"
					//  data[1] => "sayfa" o - a 
					case 0:

						tData = "&rrp="+data[0]+"&sayfa="+data[1]+"&orderby="+data[2]+"&ascOrDsc="+data[3]+'&hesap=false';

					break;

					// RRP, Sayfa değiştirme
					//  data = elem
					case 1:

						var	upd   = '&hesap=true',
							btn   = '&buton=false';

						// Butonlarla değiştirme yapıldıysa, hangi butona basıldı al
						if( data.getAttribute("f") != null ) btn = '&buton='+data.getAttribute("f");

						// Buton click varsa hesaplama yap,
						// Direk select'ler ile değiştirme varsa yapma
						if( data.getAttribute("name") == 'sayfa' ) upd = '&hesap=false';

						tData += upd + btn + AHDataTable.tableSettingsData();;

					break;

					// Orderby
					case 2:

						$AH("dt_orderby").value = data.trim();
						( $AH("dt_ascOrDsc").value == "ASC" ) ? $AH("dt_ascOrDsc").value = "DESC" : $AH("dt_ascOrDsc").value = "ASC";

						tData += AHDataTable.tableSettingsData();

					break;

				}

				// console.log( tData );

				AHAJAX("", "post", "json", "type=tableSet"+ tData /*+ upd+btn*/, function(r){
					if(r.ok){
						// console.log(" | | kayits " + r.ks );
						AHDataTable.tableUpdate(r.data);
						$('.pagination-container').empty().append(r.sayfalama);
						$(AHDataTable.spinner).hide();
					}
				},false);

			},

			tableUpdate: function(data){	
				$(this.tableData).empty().append(data);	
			}

		};

		Object.size = function(obj){
			var size = 0, key;
			for(key in obj){
				if(obj.hasOwnProperty(key)) size++;
			}
			return size;
		};

		

/* SOL NAV MENU PLUGIN
 * -------------------
 * 
 * Alt kategorisi olan menuler için. Parametre almıyor.
 * 
 * Kullanım:
 * $(".menu).navMenu();
 * 
 * Sayfa açıldığında açık kalmasını istediğin menü ye .aktif classını ekle ve
 * document.ready' de çağır.
 */

(function($) {
  
	$.fn.navMenu = function(){

		return this.each(function(){
			var buton = $(this).children("a").first(); 
			var menu = $(this).children('.sub-tree').first(); // ul li ul
			var isActive = $(this).hasClass('aktif');

			//Açık kalması istenen menü varsa, aktif classını manuel ekle
			if(isActive) {
				menu.show();
				// Oku sagdan, asagiya cevir
				buton.find('i').removeClass("nav-ok-sag").addClass("nav-ok-asagi");
			}

			buton.click(function(e){
				e.preventDefault(); //# olmasın (return false)
				if(isActive){
					//Menuyu kapat
					menu.stop().slideUp();
					isActive = false;
					buton.find('i').removeClass("nav-ok-asagi").addClass("nav-ok-sag");
					buton.parent('li').removeClass("aktif");
				} else {
					//Menuyu ac
					menu.stop().slideDown();
					isActive = true;
					buton.find('i').removeClass("nav-ok-sag").addClass("nav-ok-asagi");
					buton.parent('li').addClass("aktif");
				}
			});
		});

	}; 
}(jQuery));

/** #SOL NAV MENU PLUGIN /

/* TAB PLUGIN
 * -------------------
 * Tab plugini
 * Uygulanan element <ul> olmalı, tabCont: tabların içeriği
 * Mutlaka belirtilmesi lazim. 
 * "aktif" class'ı manuel eklenmiş tab ilk açılışta gözükür.
 * 
 * Kullanım:
 * $("ul#mesaj).jwTab({
 * 	  tabCont: $('.mesajTab');	
 * });
 * 
*/

(function($) {
	
	$.fn.jwTab = function(options){

		var settings = $.extend({
			tabCont: '.tab-icerik',
			efekt: 'fade',
			ajaxData: null
		}, options);

		return this.each(function(){

			var tabButon = $(this).find("li.tab-btn"),
				tabCont = $(settings.tabCont),
				isActive = $(this).find("li.tab-btn").hasClass("aktif"),
				aktifIndex = $(this).find("li.tab-btn aktif").index(),
				ajax = false;

			// Hepsini gizle css' e gerek kalmadan
			tabCont.hide();
			

			if(settings.ajaxData != null){
				ajax = true;
			}

			// tekrar aktif olanı goster
			if(isActive) {

				if(ajax){

					tabCont.eq(aktifIndex + 1).empty().append(settings.ajaxData).show();
				} else {
					tabCont.eq(aktifIndex + 1).show();
				}
				
			}

		
			tabButon.click(function(e){

				e.preventDefault();

				switch (settings.efekt) {
					case 'fade':

						if(ajax) {

							tabCont.empty().stop().fadeOut(50);
							tabButon.removeClass("aktif");

							$(this).addClass("aktif");
							tabCont.eq($(this).index()).empty().append(settings.ajaxData).stop().fadeIn();

						} else {
							tabCont.stop().fadeOut(50);
							tabButon.removeClass("aktif");

							$(this).addClass("aktif");
							tabCont.eq($(this).index()).stop().fadeIn();
						}

						
					break;
					case 'normal':

						if(ajax){
							tabCont.empty().stop().hide();
							tabButon.removeClass("aktif");

							$(this).addClass("aktif");
							tabCont.eq($(this).index()).empty().append(settings.ajaxData).stop().show();
						} else {
							tabCont.stop().hide();
							tabButon.removeClass("aktif");

							$(this).addClass("aktif");
							tabCont.eq($(this).index()).stop().show();
						}	
					break;
						
				}

					
			});
		});
	};
}(jQuery));

/** #TAB PLUGIN **/


// function rowLoader( st ){
	// 	var s = $AH("row-spinner");
	// 	console.log(s);
	// 	st ? s.style.display = "block" : s.style.display = "none";
	// }

	// Table update ler icin yeni request fonksiyonu
	// function DTReq( rq, d, cb ){
	// 	rowLoader(true);
	// 	var rt =  "type="+rq+"&";
	// 	console.log( rt + d + DT_functions.dt_display_settings() );
	// 	$.ajax({
	// 		url: AXREQ,
	// 		type: "post",
	// 		dataType: "json",
	// 		data: rt + d +"&"+DT_functions.dt_display_settings(),
	// 		success: function(r){
	// 			if(typeof cb == 'function'){
	// 				cb(r);
	// 			}
	// 			rowLoader(false);
	// 			console.log(r.oh);
	// 			$AH("data-table-cont").innerHTML = r.data;
	// 		},
	// 		error: function(){
	// 			console.log("fail");
	// 			window.location.reload();
	// 		}
	// 	});
	// }

	// Sayfa degisikligi
	// function DataTableReload_P( p ){
	// 	// Table display ayarlarini inputlardan aldigim icin (dt_display_settings)
	// 	// butonlara basildiginda inputun degerini manuel degistiriyorum.
	// 	DT_functions.page = p;
	// 	DTReq( 'DTReload_P', "page="+p, false );
	// 	event.preventDefault();
	// }

	// function DataTableRRP( r ){
	// 	DTReq( 'DataTableRRP', "rrp="+r , false );
	// }

	// function DataTableSearch( event ){
	// 	DTReq( 'ProdSearch', serialize($AH("product_search")), false );
	// 	event.preventDefault();
	// }