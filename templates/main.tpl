		<main>
			<div class="main_section">
		        <div class="main_block">
					<?php
						if( count( $vars['messages'] ) === 0 ) {
							print ('<h2>Добро пожаловать на наш сайт!</h2>
								<p>Проверка работы с пользователями и базой данных.</p>'
							);
						}
						else {
							foreach ( $vars['messages'] as $msg ) {
								print('<p class="message">'.$msg.'</p>');
							}	
						}

						if( array_key_exists( 'errors', $vars ) ) {
							foreach ( $vars['errors'] as $msg ) {
								print('<p class="message error">'.$msg.'</p>');
							}	
						}
					?>
				</div>
			</div>
		</main>
