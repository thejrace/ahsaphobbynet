// AH.v2 - 2015
var AH = {};
/*** <DT>  ***/

	/*  IE ICIN EVENTLERI UYUMLU HALE GETIR     */
	var DT_functions = {
		template : "",
		rrp	: "",
		direction : "",
		page 		: 1,
		orderby  : "",
		container : "data-table-body",
		category_parent:"",
		axreq    : "",
		req_extra_data:[],
		filter_init: false,  // urunlerde filtre
		filter_form:"",      // urunler filtre formu
		reload_table: function(){
			this.request( 'Reload', "", false );
		},
		// cb su an kullanmiyorum ama belki lazim olur
		request: function(rq, d, cb){

			var rt =  "type="+rq+"&",
				ext_data = "",
				filter_data = "",
				e  = []; // server-side error icin
			// datalari manuel yazdiysam yani form serialize yoksa
			// gelen objecti serialize hale getir
			if( d instanceof Object ) d = manual_serialize(d);
			if( Object.size(this.req_extra_data ) > 0 ) ext_data = "&"+manual_serialize(this.req_extra_data);

			// urun listelemede filtre ayarlari
			// display settings ile ayni mantik fakat bu sefer
			// direk formdan aliyorum filtre ayarlarini
			if( this.filter_init ) filter_data = '&'+serialize($AH(this.filter_form));

			AHAJAX_V3.req( this.axreq, rt + d +"&"+this.dt_display_settings()+this.get_template()+filter_data+ext_data, function(r){
				console.log(r.oh);
				
				if( r.text != "" ) rowNotf(r.text, r.ok);
				if(r.ok) {	
					
					// IE8 ve altindaysa
					// if( !document.addEventListener ) location.reload();

					if( r.pagin != undefined ){
						$AH("pagin-bottom").innerHTML = r.pagin;
						$AH("pagin-top").innerHTML = r.pagin;
					}

					if( r.data != undefined ){
						set_html( $AH(DT_functions.container), r.data );
					}
	
					if(typeof cb == 'function'){
						cb(r);
					}

				} else {
					var i = 0;
					// Server dan gelen errolar icin
					for( i in r.inputret ){
						// Form olmadigi icin inputlisti manuel olusturuyoruz
						e.push( [ $AH(i), r.inputret[i] ] );
					}
					// console.log(e);
					FormValidation.show_serverside_errors( e );
				}
			}, false ); 

			// ekstra data objesini boşalt
			this.req_extra_data = {};

		},
		get_category_parent: function(){
			return this.category_parent;
		},
		get_template: function(){
			return "&template="+this.template;
		},
		filter_apply: function(){
			DT_functions.page = 1;
			this.reload_table();
		},
		change_page: function( p ){
			this.page = p;
			this.reload_table();
			// event.preventDefault();
		},
		change_rrp: function( r ){
			this.page = 1;
			this.rrp  = r;
			this.reload_table();
		},
		// Urunler icin bonibon switchler
		bonibon_switch: function( elem, event ){
			var t = elem.getAttribute("func"),
				d = elem.getAttribute("data"), s;
			// Durum aktifse 0 yolla ( acikken kapatma )
			// Durum pasifse 1 yolla
			( !hasClass(elem, "bonibonPasif") ) ? s = 0 : s = 1;
			this.request( "BonibonSwitch", { func:t, data:d, state:s }, false );
			event_prevent_default( event );
		},
		sort_table: function( d ){
			this.orderby = d;
			( this.direction == "ASC" ) ? this.direction = "DESC" : this.direction = "ASC";
			this.reload_table();
		},
		// type => urun, kategori vs...
		search: function( type, form, event ){
			// FORM  VALIDATON YAP
			if( FormValidation.check( form ) ) {
				this.page = 1;
				this.request( type, serialize(form), false );
			}			
			event.preventDefault();
		},
		form_submit: function( type, form, event ){
			if( FormValidation.check( form ) ) {
				this.page = 1;
				this.request( type, serialize(form), false );
			}	
			event.preventDefault();
		},
		// Table display ayarlini hidden inputlardan alan fonksiyon
		// Hidden inputlar pagination in en ustundeler
		dt_display_settings: function() {
			return manual_serialize( {rrp:this.rrp, page:this.page, orderby:this.orderby, direction:this.direction } );
			// return "&rrp="+this.rrp+"&page="+this.page+"&orderby="+this.orderby+"&direction="+this.direction;
		},
		row_loader: function(st){
			var s = $AH("row-spinner"), i = $AH("row-spinner-info");
			if( s != undefined && i != undefined ){
				if(st){
					css( i, {display:"inline-block" } );
					css( s, {display:"inline-block" } );
				} else {
					hide( i );
					hide( s );
				}
			}
		},
		quick_add_form: function( t ){
			Popup.on( form_parse_fill( this.template, [] ), "+ Ekle" );
		},
		quick_edit: function( p, t ){
			// template bilgisini de burda gönderiyorum
			// ( varyantları sub ve main olarak ayırt ederken form için lazım )		
			// console.log( this.axreq );
			AHAJAX_V3.req( this.axreq, manual_serialize({ type:"QuickEdit", action:"request_form", item_id:p, template:this.template }), function(r){
				console.log(r.oh);

				Popup.on( form_parse_fill( DT_functions.template, r.data ), "Düzenle" );
			}, false);
		},
		delete_item: function( i ){
			var t = "varyantı";
			switch( this.template ){
				
				case "products":
					t = "ürünü";
				break;

				case "categories":
					t = "kategoriyi";
				break;

				case "deleted_products":
					t = "ürünü kalıcı olarak"
				break;
			}
			if( confirm( "Bu "+t+" silmek istediğinize emin misiniz?") ){
				this.request( "DeleteItem", { "item_id" : i } , false );
			}
		}
	}
/*** </DT>  ***/



/*** <FORM TEMPLATE - PARSE>  ***/
	var form_templates = {};
	// Template formlari dolduran fonksiyon
	// Template deki degerler ile array deki valuesi ayni sirada olmali
	// Template => %%numerik_sira%% 
	// Data     => key:val seklinde olacak
	function form_parse_fill( form, data ){
		console.log("buraya giriyom");

		var f = form_templates[form], r, i, c = 0, v = "",
			// Requestten almadigim default form degiskenleri
			exc = { "type":"", "parent":"" };
		// Bos form
		if( data.length == 0 ){
			// replace edilecek input sayisini bul
			// urunlerde hizli ekleme yok ama belki lazim olur bu sekilde kalsin 29.11.15
			switch( form ) {
				case 'categories': ec = 8; break;
				case 'products' : ec = 9; break;
				case 'variants' : ec = 2; break;
				case 'sub_variants' : ec = 2; break;
			}
			// Form type input
			exc["type"] = "QuickAdd";
			// Kategori icin parent kategori idsi ( default null )
			// Varyant eklerkende bunu kullanıyorum
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


/*** </FORM TEMPLATE - PARSE>  ***/

/*** <AHNOTF - ROWNOTF>  ***/

	var Popup = {
		overlay: "popup-overlay",
		popup  : "popup",
		open   : false,
		on: function( data, header ){
			show( $AH(this.overlay) )
			var	i = $AH(this.popup);
			show(i);
			// Once datalari yazdir
			set_html( i,  "<div id='popup-buton' onclick='Popup.off()'>X</div><div id='popup-header'>"+header+"</div>" + data );
			// Ölç - ortala
			css( i, {
				left: "50%",
				marginLeft:  "-" + ( i.offsetWidth / 2 ) + "px",
				top: ( document.body.scrollTop + 60 ) + "px"
			});
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

	function rowNotf(notf, type){
		// yazi gelmezse calisma
		if( notf != "" ){
			var c = $AH("row-ust-notf"), i;
			type == 1 ? i = "notf1" : i = "notf0";
			set_html( c, '<div class="icerik '+i+' clearfix"><span>' + notf + '</span><div class="btn" onclick="rowNotfKapat();">[x]</div><div>' );
			document.body.scrollTop = 0;
		}

	}	

	function rowNotfKapat(){
		set_html( $AH("row-ust-notf"), "" );
	}
/*** </AHNOTF - ROWNOTF>  ***/


/*** <FORM CONTROL>   ***/

	function manual_serialize( j ){
		var i, s = [], c, str = "";
		if( Object.size(j) > 0 ){
			for( i in j ){
				s.push( i + "=" + j[i] );
			}
			str = s.join("&");
		}
		return str;
	}


	// datatable icin 
	function form_submit( options, cb, event ){
		var enctype = options.form.getAttribute("enctype"),
			id      = options.form.getAttribute("id"),
			e       = [],
			success   = false;
			console.log( options.return_type );
			console.log( enctype );
		// form kontrolü
		if( FormValidation.check(options.form) ){	
			css( options.form, { opacity: 0.3 } );
			FormValidation.disable_submit_btn( true );
			if( ( enctype == null && !options.return_type ) || ( enctype == "application/x-www-form-urlencoded" && !options.return_type ) ){
				console.log("ajax_basla");
				AHAJAX_V3.req( options.url, serialize(options.form), function(r){	
					if(!r.ok){
						var i = 0;
						for( i in r.inputret ){
							e.push( [ $AH(i), r.inputret[i] ] );
						}
						FormValidation.show_serverside_errors( e );
						FormValidation.disable_submit_btn( false );
						set_html( options.notf_cont, r.text );
					} else { // r.ok	
						if( Popup.is_open() )Popup.off();
						cb( r );
						success = true;
					}
					css( options.form, { opacity:1 } );
					console.log(r.oh);
				}, false);
				FormValidation.disable_submit_btn( false );
			 	event_prevent_default( event );
			} else {
				console.log( "no ajax");
				event_prevent_default( event );
			}
		} else {
	    	console.log( "form patladi ");
	    	FormValidation.disable_submit_btn( false );
	 		event_prevent_default( event );
		}
		return success;
	}


	// datatable icin 
	function form_submit_v3( elem, return_type, event ){
		var enctype = elem.getAttribute("enctype"),
			id      = elem.getAttribute("id"),
			e       = [],
			success   = false;

		// form kontrolü
		if( FormValidation.check(elem) ){	
			css( elem, { opacity: 0.3 } );
			FormValidation.disable_submit_btn( true );
			// IE8 de enctype farkli geliyor sagdaki if o
			if( ( enctype == null && !return_type ) || ( enctype == "application/x-www-form-urlencoded" && !return_type ) ){
				// console.log(serialize(elem));
				AHAJAX_V3.req( DT_functions.axreq, serialize(elem)+"&"+DT_functions.dt_display_settings()+DT_functions.get_template(), function(r){	
					// server-side kontrol için manuel error yazdiriyorum
					if( r.text != "" ) rowNotf(r.text, r.ok);
					if(!r.ok){
						// console.log(r.inputret);
						// e arrayini bosaltmama ragmen devamli ekliyor
						// sebebini bulamadim
						var i = 0;
						for( i in r.inputret ){
							// Form olmadigi icin inputlisti manuel olusturuyoruz
							e.push( [ $AH(i), r.inputret[i] ] );
						}

						// console.log(e);
						FormValidation.show_serverside_errors( e );
						FormValidation.disable_submit_btn( false );
					} else { // r.ok	
						// console.log( "doeroy" );
						if( Popup.is_open() )Popup.off();
						if( r.table_reload ) DT_functions.reload_table();
						success = true;
					}
					css( elem, { opacity:1 } );
					console.log(r.oh);
				}, false);
				// Ajax return
				// console.log("prevent default");
				FormValidation.disable_submit_btn( false );
			 	event_prevent_default( event );

			}
		} else {
			// elem.style.opacity = 1;
			// Normal submit
	    	// Form kontrolleri patlarsa
	    	console.log( "form patladi ");
	    	FormValidation.disable_submit_btn( false );
	 		event_prevent_default( event );
		}
		return success;
	}

	var FormValidation = {
		errors: [],
		list: [],
		error_messages: {
			posnum: "Numerik ve sıfırdan büyük olmalıdır.",
			req: "Boş bırakılamaz.",
			not_zero: "Sıfırdan büyük olmalıdır.",
			email: "Lütfen geçerli bir email adresi girin."
		},
		submit_btns:[],
		find_inputs: function (f){
			// Listeyi her kontrol öncesi bosalt
			this.list = [];
			var form = f, i;
			// ilk versiyonda tum inputlari listeliyordum
			//artik kontrol class i olanlari aliyoruz sadee
			for( i = 0; i <= form.elements.length; i++ ){
				if( form.elements[i] != undefined ) {
					if( 
						hasClass( form.elements[i], "posnum" ) ||
						hasClass( form.elements[i], "req" )  ||
						hasClass( form.elements[i], "not_zero" )  ||
						hasClass( form.elements[i], "email" ) 
					) {
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
					// submit btn
					if( form.elements[i].type == "submit" ) this.submit_btns.push( form.elements[i] );
				}
			}
			this.keyup( f );
		},
		check: function(f){
			this.find_inputs(f);
			this.check_input( this.list );
			if( this.is_valid() ) {
				return true;
			} else {
				this.show_errors();
			}
		},
		// form submit esnasinda tum submit butonlari disabled yap
		// birden fazla olabilir submit o yuzden array
		disable_submit_btn: function( flag ){
			var status_text, i, prevtext = "Kaydet";
			if( this.submit_btns[0].getAttribute("oldval") != undefined ) prevtext = this.submit_btns[0].getAttribute("oldval");
			for( i = 0; i < this.submit_btns.length; i++ ){

				status_text = "Lütfen bekleyin..."
				if( !flag ) status_text = prevtext;
				this.submit_btns[i].disabled = flag;
				this.submit_btns[i].value = status_text;
			}
		},
		get_input_list: function(){
			return this.list;
		},
		check_input: function(input){
			// Toplu kontrol
			var elem, i, x;
				input_count = input.length;
			// gelen inputlarin sayisini inputlar array halinde geldiginde aliyoruz
			// eger tek bir input gelirse length = undefined oluyor. buradan tek input geldigini anlayip
			// loop icin son limiti 1 yapiyoruz yani bir kere loop yapiyor.
			if( input_count == undefined ) input_count = 1;
			for( i = 0; i < input_count; i++ ){
				// burada da input tekse direk onu loop ta isleme aliyoruz
				// eger liste halinde geldiyse, listenin elemanlarini tek tek isliyoruz
				( input instanceof Array ) ? elem = input[i] : elem = input;
				for( x in this.error_messages ){
					if( hasClass( elem, x ) ){
						// ornek -> this.posnum( val )
						if( !this[x]( elem.value ) ) this.errors.push( [ elem, this.error_messages[x] ] );
					}
				}
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
					parentt = elem.parentNode, // parent IE'de window objesinin native elementi, o yuzden iki t li
					error_div  = document.createElement('div'),
					error_span = document.createElement('span'); // hatanin yazildigi
					close_btn = document.createElement('div'); // hatayi kapatan x butonu
				// Hata zaten varsa yeni error divleri yapma
				if( !hasClass(elem, "redborder") ){
					
					addClass(elem, "redborder");
					close_btn.className = "close_error_dialogue";
					close_btn.setAttribute( "onclick", "FormValidation.hide_error( $AH( '"+elem.id+"' ) )" ); // onclick fonksiyonu burada ekliyorum
					close_btn.innerHTML = "X"; // basılacak carpi ahah
					error_span.innerHTML = this.errors[i][1];
					error_div.appendChild(error_span);
					error_div.appendChild(close_btn);

					console.log( parentt );
					parentt.appendChild(error_div);

					error_div.className = "input-error";
					css( error_div, { left:parentt.offsetWidth + 5 + "px" } );
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
		close_error_dialogue: function( error_div ){
			console.log( error_div );
		},
		posnum: function( val ){
			// console.log( val );
			// Bos birakilmissa true don, onu kontrol icin req() fonksiyonu var
			if( trim(val) == "") return true;
			return (val - 0) == val && trim( (''+val) ).length > 0 && !( val < 0 );
		},
		not_zero: function( val ){
			return !( val <= 0 );
		},
		req: function( val ){
			return !( trim( val ) == "" || val == undefined );
		},

		email: function( val ){
			var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
   			return re.test(val);
		},
		// keyuta error gizleme
		keyup: function (form){
			add_event_on( form, ".req", "keyup", function(targ, e){ 	 if( hasClass(targ, "redborder") ) FormValidation.hide_error( targ ) });
			add_event_on( form, ".posnum", "keyup", function(targ, e){ 	 if( hasClass(targ, "redborder") ) FormValidation.hide_error( targ ) });
			add_event_on( form, ".email", "keyup", function(targ, e){ 	 if( hasClass(targ, "redborder") ) FormValidation.hide_error( targ ) });
			add_event_on( form, ".not_zero", "keyup", function(targ, e){ if( hasClass(targ, "redborder") ) FormValidation.hide_error( targ ) });
		}
	};

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
/*** </FORM CONTROL>  ***/

/*** <MUHTELIF>  ***/
	// Kategori selectlerde alt kategorisi olan kategorilerin
	// alt kategorilerini select olarak listeleyen fonksiyon
	function CategoryTree(elem, target){	
		var t = $AH(target), val;
		t.innerHTML = "";	
		// Eğer seçiniz option'u seçilirse bir üst kategornin ID'sini al
		// detayli aciklama CategoryTree classında var
		if( elem.value == 0 ) {
			// Ana kategorilerin idlerinin sonunda parentlarının ID leri yok ( Cat_ )
			// O yuzden onlar seçiniz yapildiginda cat_id' yi 0 yap
			( elem.id.substr(4) == "" ) ? val = 0 : val = elem.id.substr(4);
		} else {
			// Seçiniz harici secimler
			val = elem.value;
		}
		document.getElementById("category_id").value = val;

		AHAJAX_V3.req( "ajax/ajax_category_tree.php","data=" + elem.value, function(r){
			t.innerHTML = r.data;
		}, false);
	}

	// Urun - varyant -kategori resim silme
	// @template -> product - variant - category
	// @pid -> item id
	// @pindex -> kacinci resim
	function product_img_delete( pid, pindex, template ){
		AHAJAX_V3.req( "ajax/ajax_product_img_delete.php", manual_serialize({item_id:pid, picture_index:pindex, template:template}), function(r) {
			// row notf bildirim yap
			rowNotf( r.text, r.ok );
			// kategori ikon silmede popupu kapat
			if( Popup.is_open() ) Popup.off();
			// table reload gerekiyorsa
			if( r.table_reload ) DT_functions.reload_table();
			// varyant ve urun editte bu var
			if( template != 'category' ) $AH('img_holder_' + pindex).innerHTML = "";
			
		}, false);

		event.preventDefault();
	}

	// assoc arrayleri join ile normal array gibi
	// stringe ceviren fonksiyon
	// normal numeric array e ceviriyorum assoc array i
	// sonra join
	function assoc_array_join( array, seperator ){
		var i, n = [], str;
		if( array.length == 0 ) return "";
		for( i in array ){
			n.push( array[i] );
		}
		return n.join(seperator);
	}

	// array temizleme
	// @arrray temizlenecek array
	// @is_assoc numerik mi assoc mu array ona gore for yapcaz
	function array_delete_all_elems( array, is_assoc ){
		var i, c;
		if( is_assoc ){
			// assoc temizleme
			for( i in array ){
				delete array[i];
			}
		} else {
			// numerik temizleme
			c = array.length;
			for( i = 0; i < c; i++ ){
				delete array[i];
			}
		}
	}

	// array temizleme
	// @arrray temizlenecek array
	// @is_assoc numerik mi assoc mu array ona gore for yapcaz
	// @default_val elementler neye esitlenecek
	function set_all_elems ( array, is_assoc, default_val ){
		var i, c;
		if( is_assoc ){
			// assoc temizleme
			for( i in array ){
				array[i] = default_val;
			}
		} else {
			// numerik temizleme
			c = array.length;
			for( i = 0; i < c; i++ ){
				array[i] = default_val;
			}
		}
	}


	function AHTooltip(type, data, elem, e){
		var t = $AH("ah-tooltip");
		if( type == "img" ) data = '<img src="'+data+'" />';
		set_html( t, data );
		css( t, {
			left: ( e.pageX + 20 ) + "px",
			top:  ( e.pageY + 20 ) + "px",
			display: "block"
		});
		elem.onmouseout = function(){
			css( t, {
				left: 0 + "px",
				top:  0 + "px",
				display: "none"
			});
			set_html(t, "");
		}
		event_stop_propagation( e );
	}

	// Arama dropdown
	function DTAramaToggle(){
		var c = $AH("dt-arama-cont");
		( c.style.display == 'block' ) ? c.style.display = "none" : c.style.display = "block"; 
	}

	Object.size = function(obj){
		var size = 0, key;
		for(key in obj){
			if(obj.hasOwnProperty(key)) size++;
		}
		return size;
	};
/*** </MUHTELIF>  ***/


	var Admin_Tree_Menu = {
		init: function(){
			foreach( $AHC("tree-menu"), function(li){
				var menu = find_elem(li, ".sub-tree")[0],
					btn = find_elem(li, ".menu-item" )[0],
					ico = find_elem(btn, "i")[0];
				// mobile de ekranin yarisini kapliyor o yuzden
				// default kapali menuler
				if( document.body.offsetWidth > 750 ) {
					if( hasClass(li, "aktif") ){
						addClass(ico, "nav-ok-asagi");
						show( menu );
					}
				}
				add_event(btn, "click", function(e){
					Admin_Tree_Menu.activate(li, menu, ico);
					event_prevent_default(e);
				});
			});
		},
		activate: function(elem, menu, ico){
			if( hasClass(elem, "aktif") ){
				removeClass(elem, "aktif");
				removeClass(ico, "nav-ok-asagi");
				hide(menu);
			} else {
				addClass(elem, "aktif");
				addClass(ico, "nav-ok-asagi");
				show(menu);
			}
		}
	};


/*** <READY>  ***/
	AHReady(function(){

		// mobilde navmenuyu gizleyip ekrani buyuttugunde navmenu de
		// hidden class i kalmasin diye kontrol edip varsa kaldiriyoruz
		add_event( window, "resize", debounce( function(){
			if( document.body.offsetWidth > 1000 ){
				var nav_menu = $AHC("nav-menu")[0];
				if( hasClass(nav_menu, "hidden") ) removeClass(nav_menu, "hidden");
			}
		}), 500, false );

		add_event( $AHC('sol-icerik-toggle'), "click", function(e){
			var nav_menu = $AHC("nav-menu")[0],
				right = $AHC("sag-icerik")[0],
				left = $AHC("sol-icerik")[0];

			if( document.body.offsetWidth < 950 ){
				toggle_class(nav_menu, "hidden");
			} else {
				if( hasClass(nav_menu, "hidden") ) removeClass(nav_menu, "hidden");
				toggle_class(right, "sag-icerik-collapsed");
				toggle_class(left, "sol-icerik-collapsed");
			}
			event_prevent_default(e);
		});


		
		/* NAV MENU PLUGIN UYGULA
		 * ----------------------
		 * 
		 */
		// $('.tree-menu').navMenu();

		Admin_Tree_Menu.init();


	}); //ana_function_end
/*** </READY>  ***/