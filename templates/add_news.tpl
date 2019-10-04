<main>
	<div class="forms">
		<h2>Добавление новости</h2>
		<form method="post">
			<p><label>Заголовок:<br>
				<input type="text" name="title" value="<?php if( isset( $_POST['title'] )) print $_POST['title'] ?>">
			</label></p>
			<p><label>Содержание:<br>
				<textarea name="content"><?php if( isset( $_POST['content'] )) print $_POST['content'] ?></textarea>
			</label></p>
			<p><label>Дата:<br>
				<input type="text" name="news_date" value="<?php if( isset( $_POST['news_date'] )) print $_POST['news_date'] ?>">
			</label></p>
			<p><label>Автор:<br>
				<input type="text" name="author" value="<?php if( isset( $_POST['author'] )) print $_POST['author'] ?>">
			</label></p>
			<p><input type="submit" name="submit" value="Создать"></p>
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