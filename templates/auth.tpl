<main>
	<div class="forms">
		<form method="post">
			<p><label><b>Логин:</b><br>
				<input type="text" name="login" value="<?php if( isset($_POST['login']) ) print $_POST['login']?>">
			</label></p>
			<p><label><b>Пароль:</b><br>
				<input type="password" name="password">
			</label></p>
			<p><input type="submit" name="submit" value="Вход"></p>
			<?php
				if ( array_key_exists( 'errors', $vars )) {
					foreach( $vars['errors'] as $errorMsg ) {
						print('<p class="message error">'.$errorMsg.'</p>'); 
					}
				}
			?>
		</form>
	</div>
</main>