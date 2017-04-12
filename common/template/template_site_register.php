<div class="log-reg-container">
	<div class="log-reg-header">
		<span>Kayıt Ol</span>
		<div class="form-notf"></div>
	</div>
	<div class="log-reg-form">
		<form action="" method="post" id="register">
			<div class="input-grup">
				<label class="t2" for="email">E-Posta</label>
				<div class="input-control">
					<input type="text" class="t2 email req" name="email" id="email" />
				</div>
			</div>

			<div class="input-grup">
				<label class="t2" for="email">İsim</label>
				<div class="input-control">
					<input type="text" class="t2 req" name="name" id="name" />
				</div>
			</div>

			<div class="input-grup">
				<label class="t2" for="pass">Şifre</label>
				<div class="input-control">
					<input type="password" class="t2 req" name="pass_1" id="pass_1" />
				</div>
			</div>

			<div class="input-grup">
				<label class="t2" for="pass">Şifre Tekrar</label>
				<div class="input-control">
					<input type="password" class="t2 req" name="pass_2" id="pass_2" />
				</div>
			</div>

			<div class="input-grup">
				<label class="t2" for="policy_agree"><a href="">Kullanım Koşullarını</a> Okudum ve Kabul Ediyorum</label>
				<div class="input-control mgtop-8">
					<input type="checkbox" class="t2" name="policy_agree" id="policy_agree" />
				</div>
			</div>

			<input type="hidden" name="return_url" value="<?php echo Input::get("return_url") ?>" />

			<div class="input-grup">
				<input type="submit" class="btn-buyuk" value="Kayıt" />
			</div>

		</form>
	</div>
	<div class="log-reg-footer">
		<div class="log-reg-options">
			<a href="login.php">Hesabınız var mı? Giriş yapın.</a>
		</div>
		
	</div>
</div>

<script type="text/javascript">
	
	AHReady(function(){
		add_event( $AH("register"), "submit", function(e){
			var options = {
				form: this,
				url : "<?php echo $AJAX_REQ ?>",
				return_type : false,
				notf_cont: $AHC("form-notf")[0]
			};
			form_submit( options, function(r){
				set_html( $AHC("log-reg-container")[0], "<div class='notf success'>"+r.text+"</div>");
				// console.log(r.redirect);
				setTimeout( function(){ window.location = r.redirect }, 2000 );
			}, e);
		});
	});
</script>