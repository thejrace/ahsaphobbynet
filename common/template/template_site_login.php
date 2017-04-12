<?php
		
	// Login throttle bildirim
	if( $LT->is_activated() ){
?>
<div class="notf error">
	<span>Lütfen <?php echo $LT->get_delay() ?> saniye sonra tekrar deneyin...</span>
	<div>
	<div>Bu mesajı neden alıyorum?</div>
	<ul>
		<li>Güvenlik sebebiyle siteye girişler aralıklı yapılmaktadır.</li>
	</ul>
	</div>
</div>
<?php
	} else {
?>
<div class="log-reg-container">
	<div class="log-reg-header">
		<span>Giriş Yap</span>
		<div class="form-notf"></div>
	</div>
	<div class="log-reg-form">
		<form action="" method="post" id="login">
			<div class="input-grup">
				<label class="t2" for="email">E-Posta</label>
				<div class="input-control">
					<input type="text" class="t2 req email" name="email" id="email" />
				</div>
			</div>

			<div class="input-grup">
				<label class="t2" for="pass">Şifre</label>
				<div class="input-control">
					<input type="password" class="t2 req" name="pass" id="pass" />
				</div>
			</div>

			<div class="input-grup">
				<label class="t2" for="remember_me">Beni Hatırla</label>
				<div class="input-control mgtop-8">
					<input type="checkbox" class="t2" name="remember_me" id="remember_me" />
				</div>
			</div>

			<input type="hidden" name="return_url" value="<?php echo Input::get("return_url") ?>" />

			<div class="input-grup">
				<input type="submit" class="btn-buyuk" oldval="Giriş" value="Giriş" />
			</div>

		</form>
	</div>
	<div class="log-reg-footer">
		<div class="log-reg-options">
			<a href="register.php">Kayıt Ol</a>
			<a href="">Şifremi Unuttum</a>
		</div>
		
	</div>
</div>

<script type="text/javascript">
	
	AHReady(function(){
		add_event( $AH("login"), "submit", function(e){
			var options = {
				form: this,
				url : "<?php echo $AJAX_REQ ?>",
				return_type : false,
				notf_cont: $AHC("form-notf")[0]
			};
			form_submit( options, function(r){
				set_html( $AHC("log-reg-container")[0], "<div class='notf success'>"+r.text+" Geri yönlendiriliyorsunuz...</div>");
				// console.log(r.redirect);
				setTimeout( function(){ window.location = r.redirect }, 2000 );
			}, e);
		});

	});
</script>

<?php
	}  // formu goster throttle yok

?>