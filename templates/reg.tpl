<main>
	<div class="forms">
		<form method="post">
			<p><label><b>Ваше имя:</b><br>
				<input type="text" name="firstname" placeholder="Имя" value="<?php if( isset( $_POST['firstname'] )) print $_POST['firstname'] ?>">
			</label></p>
			<p><label><b>Дата рождения:</b><br>
				<input type="text" name="birthday" placeholder="Дата рождения" value="<?php if( isset( $_POST['birthday'] )) print $_POST['birthday'] ?>">
			</label></p>
			<p><label><b>Логин:</b><br>
				<input type="text" name="login" placeholder="Логин" value="<?php if( isset( $_POST['login'] )) print $_POST['login'] ?>">
			</label></p>
			<p><label><b>Пароль:</b><br>
				<input type="password" name="password" placeholder="********">
			</label></p>
			<p><input type="submit" name="submit" value="Регистрация"></p>
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