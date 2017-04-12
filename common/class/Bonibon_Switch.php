<?php
	
	interface Bonibon_Switch {

		// class degiskenleri alan fonksiyon
		// action bunun icinde calistiriliyor
		public function bonibon( $func, $state );

		// fonksiyon ayrimi yapar
		// urun tablosundan state i gunceller
		// ekstra islem yapilacaksa ( vitrin vs. ) yapar 
		public function bonibon_action();

	}