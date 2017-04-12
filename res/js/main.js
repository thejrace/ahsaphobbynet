AHReady( function(){

	// mobile header ic ice menu
	add_event( $AHC("main-dropdown-btn"), "click", function(e){
		mobile_category_dropdown_toggle( this, e );
	});
	add_event( $AHC("sub-dropdown-btn"), "click", function(e){
		mobile_category_dropdown_toggle( this, e );
	});

	/*---------------------------------------------------------------------------------------------*/	

	// Header dropdown menuler */
	add_event( $AHC("dropdown-btn"), "click", function(e){
		event_stop_propagation(e);
		event_prevent_default(e);
		dropDownMenu.toggle(this);
	});

	
	add_event( $AHC("dropdown-menu"), "click", function(e){
		event_stop_propagation(e);
	});

	add_event_on( document, false, "click", function(){
		dropDownMenu.offTarget();
	});

	//Header kategori hover menu 
	add_event( find_elem( $AHC("kategori-liste")[0], "li" ), "mouseenter", function(){
		var tid = find_elem(this, "a")[0].getAttribute("href");
		foreach( find_elem( $AHC("kategori-liste")[0], "li" ), function(li_elem){ removeClass(li_elem, "aktif")});	
		addClass(this, "aktif");
		foreach( $AHC("kategori-aktif-cont"), function(kat_cont){
			removeClass(kat_cont, "secili");
			if( kat_cont.getAttribute("tabid") == tid ){
				addClass(kat_cont, "secili");
				return;
			}
		});
	});

	// sepet detaylar
	foreach( $AHC("cart-list"), function(list){
		add_event_on(list, ".item-details", "click", function(targ, e){
			var p = targ.parentNode.parentNode,
				target = find_elem( p, ".item-details-content")[0];
			toggle_class(target, "open");
			event_prevent_default(e);
		});
	});

	// header sepet
	add_event( $AHC("show-cart"), "click", function(){
		if( !hasClass(this, "loaded") ){
			AHCart.update_containers( "Yükleniyor..." );
			foreach( $AHC("show-cart"), function(c){
				addClass(c, "loaded");
			});
			AHCart.load_items();
		}
	});

	add_event_on( $AHC("cart-list-ul"), ".delete-from-cart", "click", function(targ, e){
		AHCart.delete_item( targ.getAttribute("item") );
		event_prevent_default(e);
	});

	// kategori sayfasi sepete ekle
	add_event_on( $AHC("urun-liste-container"), ".add_to_cart_list", "click", function(targ, e){
		if( targ.getAttribute("hasvariant") == 0 ){
			AHCart.add_item( { pid : targ.getAttribute("product"), qty : 1 } );
			event_prevent_default( e );	
		}
		
	});


});	
	// sepet islemleri
	AHCart = {
		content_id:null,
		editing:false,
		items:[],
		template:"",
		// sepetteki urunler ajax ile aliyoruz ilk sepeti actiginda kullanici
		load_items: function(){
			AHAJAX_V3.req( AHBase.ajax + "cart.php", manual_serialize({type:"load_items"}), function(r){
				AHCart.show_cart( r.data );
				AHCart.update_summary( r.total_price, Object.size(r.data) );
			});
		},
		// sepetteki urun sayisi ve toplam fiyat guncellemeleri
		update_summary: function( tp, item_count ){
			foreach( $AHC("cart-total"),function(tot){
				set_html ( tot, tp + "TL" );
			});
			set_html( $AHC("cart-total-qty")[0], item_count );
		},
		show_cart: function( items ){
			this.items = items;
			this.create_template();
			// html guncelleme
			this.update_containers();
		},
		// ajax ilegelen json datayi template e yazdiriyoruz
		create_template: function(){
			this.template = "";
			var temp = "", dets = "", show_details, edit_btn = "";
			foreach( this.items, function(item){
				edit_btn = "";
				dets = "";
				show_details = ' style="display:none" ';
				// eger detay bilgisi varsa listeyi olusturuyoruz
				if( Object.size( item.details ) > 0 ){
					edit_btn = '<a href="'+item.edit_url+'" target="_blank" class="edit-product"><i class="sq_20x20 table-duzenle" title="Düzenle"></i></a>';
					if( item.details.text != undefined ) dets += "<li>Yazı: "+ item.details.text +"</li>";				
					dets += "<li>Detaylar: "+ item.details.vars +"</li>";				
					dets = '<div class="item-details-content"><ul>'+dets+'</ul></div>';
					show_details = "";
				}
				temp +='<li><div class="cart-item clearfix"><div class="content-left"><div class="thumb"><img src="'+item.img_src+'" /></div></div>\<div class="content-right"><div class="item-name">'+item.name+'</div><div class="item-price">'+item.qty+' x '+item.price+'TL</div></div></div><div class="item-btns clearfix"><a href="" class="item-details" '+show_details+' title="Ürün Detayları">DETAYLAR</a><div class="fright">'+edit_btn+'<a href="" class="delete-from-cart" item="'+item.id+'"><i class="sq_20x20 table-sil" title="Sepetten Sil"></i></a></div></div>'+dets+'</li>';
			});
			this.template = temp;
		},
		show_popup: function( item ){
			this.items = [];
			this.items[0] = item;
			this.create_template();
			Popup.on( '<div id="add-to-cart-popup"><ul>'+this.template+'</ul></div><div class="bottom-section"><div class="cart-buttons"><a href="" class="cart-btn gotocart">SEPETE GİT</a><a href="" class="cart-btn keep-shopping" id="keep-shopping-btn">ALIŞVERİŞE DEVAM ET</a></div></div>', '+Ürün sepete eklendi.');
		},
		// mobile ve normal gorunum icin html guncelleme
		update_containers: function( ){
			var temp = this.template;
			foreach( $AHC("cart-list-ul"), function(list){
				set_html( list, temp );
			});
		},
		delete_item: function( item_id ){
			var c = confirm( "Ürünü sepetinizden silmek istediğinize emin misiniz?");
			if( c ){
				AHAJAX_V3.req( AHBase.ajax + "cart.php", manual_serialize({type:"delete_item", item_id:item_id}), function(r){
					AHCart.show_cart( r.data );
					AHCart.update_summary( r.total_price, Object.size(r.data) );
				});
			}
		},
		get_content_id: function(){
			return this.content_id;
		},
		start_editing: function( check ){
			this.editing = check;
		},
		check_editing: function(){
			return this.editing;
		},
		// sepete yeni urun ekleme
		// kategori sayfasından ekleme de yemez bu 
		add_item: function( input ){
			var data = { type : "item_req" };
			// urun listesinden ekleme
			if( get_object_type( input, "Object" ) ){
				extend(data, {
					pid:input.pid,
					vinit: false,
					qty : input.qty
				});
			} else {
				// ürün sayfasından ekleme
				if( Product.has_variant() ){
					if(Variants.data.id == null || Variants.data.price == null || Variants.data.code == null ){
						alert("Varyant seç");
						return false;
					} else {
						if( Product.has_editor() ){
							if( AHEditor.is_empty() ){
								alert("Editore bisiler yaz ulee");
								return false;
							} 	
						}
					}
				}
				extend(data, {
					pid:Product.id,
					vinit: Product.has_variant(),
					qty: input
				});
				if( this.check_editing() ) extend( data, {content_id:this.get_content_id()});
				if( Variants ) extend( data, {vcode:Variants.data.code}); 
				if( AHEditor ) extend( data, AHEditor.get_form_data() );
				console.log( data );
			}

			AHAJAX_V3.req( AHBase.ajax + "cart.php", manual_serialize(data), function(r){
				console.log(r);
				console.log(r.data);
				if( r.ok ){
					// tekrar resim olusturmasi icin
					if( typeof AHEditor !== 'undefined' ) AHEditor.update_preview();
					AHCart.show_popup( r.data );
					foreach( $AHC("show-cart"), function(c){
						if( hasClass(c, "loaded") ) removeClass(c, "loaded");
					});
				}
				// AHCart.update_summary( r.total_price, Object.size(r.data) );
				});
		}
	};

	// Mobil iç içe dropdown
	// butonun siblingi olan ul u goster-gizle bu kadar basit
	function mobile_category_dropdown_toggle( elem, event ){
		var target_ul, active_class = "selected";
		// acilacak dropdown ul element
		target_ul = find_elem( elem.parentNode, "ul" )[0];
		// klasik aktifse kapat, degilse ac
		if( hasClass(elem, active_class ) ){
			removeClass(elem, active_class);
			hide( target_ul );
		} else {
			addClass(elem, active_class);
			show( target_ul );
		}
		event_prevent_default( event );
	}

	// dropdown buton ve ul <li> nin veya bir parentin icinde olacak 
	// class ona ekleniyor
	var dropDownMenu = {
		hide: function(elem){
			removeClass(elem, "aktif");
			var parent = elem.parentNode;
			removeClass( parent, "open" );
			foreach( find_elem( parent, ".dropdown-menu" ), function(menu){
				hide( menu );
			});
		},
		show: function(elem){
			// tum menuleri kapat
			foreach( $AHC("dropdown-menu"), function(dropdown){
				hide(dropdown);
				removeClass( dropdown.parentNode, "open" );
			});
			addClass(elem, "aktif");
			var p = elem.parentNode;
			addClass(p, "open");
			show( find_elem( p, ".dropdown-menu")[0] );

		},
		toggle: function(elem){
			// console.log(elem.parentNode);
			var p = elem.parentNode;
			( hasClass(p, "open") ) ? this.hide(elem) : this.show(elem);	
		},
		offTarget: function(){
			foreach( $AHC("dropdown-btn"), function(dropdown){
				removeClass( dropdown, "aktif" );
				var parent = dropdown.parentNode;
				removeClass( parent, "open" );
				foreach( find_elem( parent, ".dropdown-menu" ), function(menu){
					hide( menu );
				});
			});
		}
   };