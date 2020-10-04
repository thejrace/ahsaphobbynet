/*
	04.02.2015 00:55 -> jquery dependency %2 ( sortable )
*/

AHReady(function(){
	// otomatik tab init
	// ne kadar efficient oldu bilmiyorum ama calisiyor
	foreach( $AHC("tab"), function( tab ){
		if( !hasClass(tab, "static") ){
			tabs[c] = new AHTab({container:tab});
			tabs[c].init();
			c++;
		}
	});

	// Notf ekran kaydiginda kayacak ( kaymiyo:S:S)
	add_event( window, "scroll", function(){
		if( Popup.is_open() ) css( $AH(Popup.popup), { top: document.body.scrollTop + 60 + "px" });			
	});



});

var AHBase = {
	version   : "2.001",
	url       : "http://ahsaphobbynet.test/",
	cur_url   : location.href,
	ajax      : "http://ahsaphobbynet.test/site/ajax/ajax_",
	display_lang 	  : "TR"
};

var AHLang = {
	dictionary: {
		'text': {
			"TR":"Yazı"
		},
		'color':{
			"TR":"Renk"
		},
		'material':{
			"TR":"Malzeme"
		},
		'thickness':{
			"TR":"Kalınlık"
		},
		'font':{
			"TR":"Font"
		},
	},
	translate: function( g, targ_lang ){
		return this.dictionary[g] ? this.dictionary[g][targ_lang] : g;
	}
};

// kendi ready fonksiyonum
function AHReady( cb ){
	// Chrome, ff, opera.. > ie8
	if( document.addEventListener ){
		document.addEventListener( "DOMContentLoaded", cb, false );
	// <= ie8	
	} else if( document.attachEvent ){
		document.attachEvent("onreadystatechange", function(){
			if( document.readyState === "complete" ){
				cb();
			}
		});
	// eksantrik browserlar icin her turlu calisacak ready
	} else {
		var old_onload = window.onload;
		window.onload = function(){
			old_onload && old_onload();
			cb();
		}
	}
}
// class selector
function $AHC( cs ){
	var el_array = [];
	// ie 8 ve altinda tum dom u tara ve className uyan
	// elementleri listeye ekle
	if( !document.querySelectorAll || ( window.attachEvent && !window.addEventListener ) ){
		var tmp = document.getElementsByTagName("*"),
			regex = new RegExp("(^|\\s)" + cs + "(\\s|$)"),
			l = tmp.length;
		for( var i = 0; i < l; i++ ){
			if( regex.test(tmp[i].className) ) el_array.push(tmp[i]);
		}
		// cache de kalmasin
		tmp = [];
	} else {
		el_array = document.querySelectorAll( "." + cs );
	}
	// // tek seçim icin
	return el_array;
}


function is_defined( vari ){
	return (typeof vari !== 'undefined');
}

function is_element( o ){
	return (
		typeof HTMLElement === "object" ? o instanceof HTMLElement :
		o && typeof o === "object" && o !== null && o.nodeType === 1 && typeof o.nodeName === "string"
	);
}

function get_object_type(obj, type){
	return Object.prototype.toString.call( obj ) === '[object '+type+']';
}
	
// id selector
function $AH(id){
	return document.getElementById(id);
}

// @parent elementinin child elementlerinde arama
function find_elem( parent, context ){
	var found = [], i,
		list = get_children( parent ), len = list.length;
	// tum elementler icin kontrol fonksiyonu calistir
	// uyanlari ekle
	for( i = 0; i < len; i++ ){
		if( match_context( list[i], context ).length > 0 ){
			found.push(list[i]);
			delete list[i];
		}
	}
	return found;
}

// @par altindaki tum children elementleri bul
function get_children( par ){
	var nodes = par.childNodes, len = nodes.length, elem_list = [], i;
	// birinci seviye childrenlar icin for baslat
	for( i = 0; i < len; i++ ){
		if( nodes[i].nodeName != "#text" && nodes[i].nodeName != "#comment" ){
			// ekle bulunanlara
			elem_list.push(nodes[i]);
			// simdi kontrol ettigimiz elementin alt elementlerine bak
			var children = get_children(nodes[i]);
			// varsa her birini bulunanlara ekle
			// recursive fonksiyon kullaniyorum
			// her element icin children var mi, varsa ekle yapiyoruz
			if( children ){
				foreach( children, function(node){
					elem_list.push(node);
				});		
			}
		}
	}
	// bosalt bunu
	nodes = [];
	return elem_list;
}

// @elem icin contextte verilen icerige uygunluk kontrolu yap
// .class, #id, [attr], li, input vs(node name kontrolu)
function match_context( elem, context ){
	var match = [];
		context = context.replace(/ /g,"");
	// class
	if( context.indexOf(".") > -1 ){
			if( hasClass( elem, context.substr(1) ) ) match.push( elem );
	// id
	} else if( context.indexOf("#") > -1 ){
		if( elem.id == context.substr(1) ) match.push( elem );
	// attr
	} else if( context.indexOf("[") > -1 ){
		// son ve ilk köşeli parantezleri temizle
		var attr_name = context.substr(1);
		attr_name = attr_name.substr( 0, attr_name.length - 1 );
		if( elem.getAttribute(attr_name) != null ) match.push( elem );
	// elem tip
	} else {
		if( elem.nodeName == context.toUpperCase() ) match.push( elem );
	}
	return match;
}


// elementin indexini bul
function get_node_index(node) {
	var index = 0;
	// bir elementin indexi o elementin oncesindeki element sayisindan bir fazla
	// ama baslangic sifir kabul ettigimiz icin direk eşit
	// onceki eleman null olana kadar yani ilk indexe gelene kadar degiskeni
	// arttiriyoruz ve indexi buluyoruz
	while ( (node = node.previousSibling) ) {
		// yalnizca DOM elementleri sayiyoruz
		if (node.nodeType != 3 || !/^\s*$/.test(node.data))	index++;
	}
	return index;
}


// ilk parenti bul	
function get_parent( elem ){
	if( elem && elem.parentNode ){
		return elem.parentNode;
	}
	return false;
}

// documente kadar parentlarini bul
function get_parents( elem ){
	var parents = [];
	// parent oldugu surece arraye ekle
	while( get_parent( elem ) ){
		parents.push( get_parent(elem) );
		elem = get_parent(elem);
	}
	return parents;
}


// direk elem yada ID si gelenin value yu döner
function get_val( elem ){
	if( is_element( elem ) ) {
		return elem.value;
	} else {
		return $AH(elem).value;
	}
	
}

// removeChild dan ziyade crossbrowser calisiyor
//https://developer.mozilla.org/en-US/docs/Web/API/Element/outerHTML
function remove_elem( elem ){
	if( elem ) elem.outerHTML = "";
}

function set_html( elem, cont ){
	if( elem ) elem.innerHTML = cont;
}

function get_html( elem ){
	if( elem ) return elem.innerHTML;
	return "";
}

function append_html( elem, content ){
	if( elem ){
		var old_content = get_html( elem );
		set_html( elem, old_content + content );
	} 
}

function prepend_html( elem, content ){
	if( elem ){
		var old_content = get_html( elem );
		set_html( content + old_content );
	}
}

// selectore gore elementlere event ekle
// birden fazla elementi foreachsiz burada handle edebiliyoruz
function add_event( selector, event, cb ){
	if( get_object_type(selector, "NodeList") || get_object_type(selector, "Array") ){
		foreach( selector, function(elem){
			add_event_to( elem, event, cb );
		});
	} else {
		add_event_to(selector, event, cb);
	}

}

// add event cross browser
// this keywordu kullanabiliyoruz
function add_event_to(elem, event, cb) {
	// addEventlistener destekleyenler icin
	function listen_handler(e) {
		// this icin
		var ret = cb.apply(this, arguments);
		if (ret === false) {
			e.stopPropagation();
			e.preventDefault();
		}
		return(ret);
	}
	// IE<9
	function attachHandler() {
		var ret = cb.call(elem, window.event);   
		if (ret === false) {
			window.event.returnValue = false;
			window.event.cancelBubble = true;
		}
		return(ret);
	}
	if( !elem ) return;
	// duruma gore eventleri bagla elemente
	if (elem.addEventListener) {
		elem.addEventListener(event, listen_handler, false);
	} else {
		elem.attachEvent("on" + event, attachHandler);
	}
}

// IE ve diger browserler icin preventDefault
function event_prevent_default( event ){
	( event.preventDefault ) ? event.preventDefault() : ( event.returnValue = false );
}
// IE ve diger browserler icin stopProp
function event_stop_propagation( event ){
	( event.stopPropagation ) ? event.stopPropagation() : ( window.event.cancelBubble = true );
}


function add_event_on( elem, find, event, cb ){
	var selector, off_target = false;
	// eger bulunacak elem  false ise direk document click, element aramiyoruz
	// off target falan mevzularinda kullanmak icin
	// diger turlu selectorun id veya class ismini al
	( !find ) ?	off_target = true : selector = ( find.substr( 1 ) );
	add_event( elem, event, function(e){
		if( !off_target ) {
			// IE8 icin e.target srcElement onun icin kontrol
			var targ = e.target;
			if( !targ ) targ = window.event.srcElement;
			// Firefox ibnesi select option larina basildiginda, target olarak
			// opiton u aliyor o yuzden onun icin kontrol. eger optionsa parenti al (select)
			if( targ.nodeName == "OPTION" ) targ = targ.parentNode;
			// class veya id tutan eleman varsa callback calistir 
			// elem i de callback e argument olarak gec ( this )
			if( hasClass( targ, selector ) || targ.id == selector ){
				cb( targ, e );
				return;
			}
			// console.log( get_parents(targ));
			// event bubble icin. en icteki elemente basildiginda parentlari da
			// kontrol et
			var parents = get_parents( targ ), len = parents.length;
			for( var i = 0; i < len; i++ ){
				if( hasClass( parents[i], selector ) || parents[i].id == selector ){
					cb( parents[i], e );
					break;
				}
			}
			
		} else {
			cb();
		}
	});
}


// select e options ekle
// @elem -> select
// @clear -> true ise varolan option varsa temizler, false ise append
// @options -> option array
function add_options( elem, clear, options, selected ){
	if( clear ) set_html(elem, "");
	foreach( options, function(option){
		var opt = document.createElement('OPTION');
		opt.value = option[0];
		opt.text  = option[1];
		// console.log(option)
		if( selected && selected == option[0] ) opt.selected  =  true;
		elem.options.add( opt );
	});
}

function show(e){
	css( e, { display:"block"} );
	// e.style.display = "block";
}

function hide(e){
	css( e, { display:"none"} );
}

function toggle_class( elem, cls ){
	if(elem){
		if(hasClass(elem, cls)){
			removeClass(elem, cls);
		} else {
			addClass(elem,cls);
		}
	}
}

function hasClass(element, cls) {
	if( element ) return (' ' + element.className + ' ').indexOf(' ' + cls + ' ') > -1;
}

function addClass(element, cls){
	if( element ) if( !hasClass(element, cls ) ) element.className += ' ' + cls;
}

function removeClass(element, cls) {
	if( element ) {
		var newClass = ' ' + element.className.replace( /[\t\r\n]/g, ' ') + ' ';
		if( hasClass(element, cls ) ){
			while( newClass.indexOf(' ' + cls + ' ' ) >= 0 ){
				newClass = newClass.replace( ' ' + cls + ' ', ' ' );
			}
			element.className = newClass.replace( /^\s+|\s+$/g, '' );
		}
	}
}

// cross-browser trimjanim preg yalarim
function trim(str){
	return str.replace(/ /g,"");
}

// array in her bir elemani icin callback
function foreach( array, cb ){
	var i, l = array.length;
	for( var i = 0; i < l; i++ ){
		cb( array[i] );
	}
}

// object extend olayi
// x objesine, y deki objeleri ekle yada overwrite
function extend(x, y) {
	var i;
	if (!x) x = {};
	for (i in y) {
		x[i] = y[i];
	}
	return x;
};

function css(elem, style) {
	// console.log(elem);
	extend(elem.style, style);
}

function debounce(func, frekans, ilkSefer) {
	// Her çağrılışta sıfırlanan bayrak ( istenen geckikmeyi algılayan )
	 var timeout;
	 return function debounced () {
		// debounce fonksiyonu ve args
		var obj = this, args = arguments;
		// Eğer ilk seferde debounce istemiyorsak direk fonksiyonu çalıştır
		// timeout'u sıfırla
		function delayed () {
			if (!ilkSefer) {
				func.apply(obj, args);
			}
			timeout = null; 
		}
		// Eğer delayden öncse basıldıysa timeout'u sıfırla
		if (timeout) {
			clearTimeout(timeout);
		}
		// Eğer delay şartı sağlanmışsa ve ilkSeferde delay istemiyorsak
		// Fonksiyonu çalıştırıyoruz.
		else if (ilkSefer) {
			func.apply(obj, args);
		}
		// Timeout' u resetledik
		timeout = setTimeout(delayed, frekans || 100); 
	};
}

var AHAJAX_V3 = {
	req: function( url, data, cb ){
		var xhr;
		DT_functions.row_loader(true);
        // modern browserlar da XMLHttpRequest kullan
        if(typeof XMLHttpRequest !== 'undefined') {
        	xhr = new XMLHttpRequest();
        } else {
            // IE 6 dayinin ibneliklerini çözüyoruz
            var versions = ["MSXML2.XmlHttp.5.0",  "MSXML2.XmlHttp.4.0", "MSXML2.XmlHttp.3.0", "MSXML2.XmlHttp.2.0", "Microsoft.XmlHttp"]        
            // uyan versiyonu kullaniyoruz tek tek kontrol edip
            for(var i = 0, len = versions.length; i < len; i++) {
            	try {
            		xhr = new ActiveXObject(versions[i]);
            		break;
            	}
            	catch(e){}
            }
        }
        // ajax requesti yaptiginda onreadystatechange e tanimlanmis
        // fonksiyon 5 defa calisacak. her calismada durum ile ilgili bilgiye
        // gore fail, complete fonksiyonlari calistirabiliyoruz
        xhr.onreadystatechange = state_check;
        // kontrol fonksiyonu
        function state_check() {
            // uninitialized, loading, loaded, interactive state lerinde
            // birsey yapma requeste devam
            if(xhr.readyState < 4) {
            	return;
            };
            // network level de bir hata olursa calisiyor. ( cross domain vs )
            xhr.onerror = function(){ console.log( "Ajax request failed" ); };
            // readyState 4 request completed
            // status 200 HTTP OK
            if( xhr.readyState === 4 && xhr.status === 200 ) {
                // IE.7 ve altinda json.parse yok
           		// crockford dayinin kutuphanesini kullanabilirim 
           		var rsp = JSON.parse(xhr.responseText);
           		if( typeof cb == 'function' ) cb( rsp );
           		DT_functions.row_loader(false);
                // console.log(xhr);
            }
        }
        // ucur bizi sıkati
        xhr.open("POST", url, true);
        
        // server dan response json
        // xhr.setRequestHeader("Content-Type", "application/json");
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        // gonder
        xhr.send(data);
    }
}

// AHTab ve oto init degiskenleri
var tabs = [], c = 0;
var AHTab = function( options ){
	this.init = function(){
		this.main_cont    =  options.container;
		this.target_divs  = $AHC( this.main_cont.getAttribute("tabdiv") );
		this.tab_btns     = find_elem( this.main_cont, "li" );
		this.state_btn    = false;
		if( hasClass(this.main_cont, "state-btn") ) this.state_btn = true;
		// ilk acilista birinci tabi goster
		// eger kalici buton ise hepsini gizle
		( !this.state_btn ) ? this.activate_tab( 0 ) : this.reset_tabs();
		var tab = this;
		// click event
		add_event( this.tab_btns, "click", function(e){
			tab.activate_tab(this);
			event_prevent_default( e );
		});
	},
	// @tab -> tab indexi veya tab buton
	// @ev -> event
	this.activate_tab = function( tab ){
		var index;
		// parametreye gore buton ve index al
		if( is_element(tab) ){
			index = get_node_index(tab);
		} else {
			index   = tab;
			tab     = this.tab_btns[index];
		}
		// kalici durum butonlar icin gizleme ozelligide oldugu icin
		// ayrica kontrol ediyoruz
		if( this.state_btn ){
			if( hasClass(tab, "aktif") ){
				this.reset_tabs();
				removeClass(tab,"aktif");
				hide( this.target_divs[index] );
			} else {
				this.reset_tabs();
				addClass(tab, "aktif");
				show( this.target_divs[index]);
			}
		// normal mutlaka bir tab acik tablar icin islem
		} else {
			this.reset_tabs();
			// yapistir gitsin
			addClass(tab, "aktif");
			show( this.target_divs[index] );
		}
	},
	this.reset_tabs = function(){
		// klasik tum btnlari pasif yap, tab contentler igizle
		foreach( this.tab_btns, function(btn){ removeClass(btn, "aktif") });
		foreach( this.target_divs, function(div){ hide( div ) });
	}
	// JS siz sayfa yenilemeli tablar icin ( vitrin )
	this.offline_mode = function( tab ){
		addClass( $AH(tab), "aktif" );
	}
}

// slider class
// .main container > .slider-liste , .slider-nav seklinde DOM tree olmali
var AHSlider = function( options ){
	this.init = function(){
		this.main_cont = $AHC( options.container )[0];
		this.cont = find_elem( this.main_cont, ".slider-liste" )[0];
		this.navs = find_elem( this.main_cont, ".slider-nav")[0];
		this.slide_count = 0;
		this.default_slide();
		// obarey
		var slider = this;
		// buton containerine on ile event ekliyoruz cunku
		// varyant secimlerinde yeni slide ekledigimizde calissin
		add_event_on( this.navs, ".slider-btn", "click", function(targ, e){
			slider.change_slide( targ, false );
			event_prevent_default( e );
		});
	},
	this.default_slide = function(){
		this.hide_all();
		show( this.get_slides()[0] );
	},
	this.get_slides = function(){
		var slides = find_elem( this.cont, "li" );
		this.slide_count = slides.length;
		return slides;
	},
	this.get_slide_count = function(){
		return this.slide_count;
	},
	this.change_slide = function( elem, index ){
		// manuel degistirme ( varyant append ) veya kullanici degistirdiyse 
		( !index ) ? this.index = elem.getAttribute("data-slider") : this.index = index;
		// ilk olarak tum slide lari gizle
		var slides = this.get_slides();
		for( var i = 0; i < this.get_slide_count(); i++ ){
			hide( slides[i] );
			if( slides[i].getAttribute("slide") == this.index ) show( slides[i] );	
		}
	},
	this.remove_appended = function(){
		foreach( find_elem( this.cont, "[appended]" ), function( elem ){
			remove_elem(elem);
		});
		foreach( find_elem( this.navs, "[appended]" ), function( elem ){
			remove_elem(elem);
		});
	},
	this.add_slider_item = function ( index, alt, title, img_url, nav_url ){
		// sonradan eklendigi icin DOM a ulasamiyorum o yuzden manuel 
		// hepsini gizleyip sonra eklediklerimi display block yapiyorum.
		this.hide_all();
		append_html( this.navs, "<a class='slider-btn' href='' data-slider='p_img_"+index+"' appended='true' ><img alt='"+alt+"' title='"+title+"' src='"+nav_url+"'  /></a>" );
		append_html( this.cont, "<li style='display:block !important;' slide='p_img_"+index+"' appended='true' ><div><span><img alt='"+alt+"' title='"+title+"' src='"+img_url+"'  /></span></div></li>" );
	},
	this.hide_all = function(){
		var slides = this.get_slides();
		for( var i = 0; i < this.get_slide_count(); i++ ){
			hide( slides[i] );
		}
	}
}


/*


// @context = class, id, ul, li vs
function find_elem( parent, context ){
	var nodes = parent.childNodes,
		len   = nodes.length, i, elem_list = [], found = [];
	// cok fazla text, comment node falan varsa ikinci
	// for loop ta gereksiz döngü yapmasin diye filtreleme
	for( i = 0; i < len; i++ ){
		if( nodes[i].nodeName != "#text" && nodes[i].nodeName != "#comment" ) elem_list.push(nodes[i]);
	}
	// bosalt 
	nodes = [];
	len = elem_list.length;
	// bosluk varsa ucur
	context = context.replace(/ /g,"");
	// tek bir for loop un icinde her seferinde indexOf vs kontrol etmektense
	// tek bir if blogu yapip 

	// console.log( elem_list );
	// class
	if( context.indexOf(".") > -1 ){
		for( i = 0; i < len; i++ ){
			if( hasClass( elem_list[i], context.substr(1) ) ) found.push( elem_list[i] );
		}
	// id
	} else if( context.indexOf("#") > -1 ){
		for( i = 0; i < len; i++ ){
			if( elem_list[i].id == context.substr(1) ) found.push( elem_list[i] );
		}
	// attr
	} else if( context.indexOf("[") > -1 ){
		// son ve ilk köşeli parantezleri temizle
		var attr_name = context.substr(1);
		attr_name = attr_name.substr( 0, attr_name.length - 1 );
		for( i = 0; i < len; i++ ){
			if( elem_list[i].getAttribute(attr_name) != null ) found.push( elem_list[i] );
		}
	// elem tip
	} else {
		for( i = 0; i < len; i++ ){
			if( elem_list[i].nodeName == context.toUpperCase() ) found.push( elem_list[i] );
		}
	}
	// listeyide ucur
	elem_list = [];
	// console.log( found );
	return found;
}


*/