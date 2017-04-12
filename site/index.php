<?php

	require "inc/init.php";
	$Page = new Page("web_index");
	$TITLE = $Page->get_title();

	// Header
	include HEADER_TEMP;

	// Table
	include $Page->show_template();

	// // Footer
	include FOOTER_TEMP;