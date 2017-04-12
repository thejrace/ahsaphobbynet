<?php

	require "inc/init.php";

	$PAGE     = new Page("start");
	$TITLE    = $PAGE->get_title();
	$SUBTITLE = $PAGE->get_subtitle();

	require "inc/header.php";
?>

				<div class="row">
   					<h2 class="row-baslik">Son Hareketler</h2>
   					<a href="">
	   					<span class="hareket-sayi">25</span>
	   					<span class="hareket-baslik">Okunmamış Mesaj</span>
   					</a>
   					<a href="">
	   					<span class="hareket-sayi">33</span>
	   					<span class="hareket-baslik">Onay Bekleyen Sipariş</span>
   					</a>
   					<a href="">
	   					<span class="hareket-sayi">11</span>
	   					<span class="hareket-baslik">Yeni Üye</span>
   					</a>
   				</div>



<?php
	include "inc/footer.php";