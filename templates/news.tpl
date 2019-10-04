<main>
	<div class="news">
		<h2>Новости</h2>
		<?php
			if ( count( $vars['errorMessages'] ) == 0 ) {
				if( is_array( $vars['newsArray'] )) {
					foreach ( $vars['newsArray'] as $newsItem ) {
						print ('<div class="news_block">');
						print ('<h3>'.$newsItem['title'].'</h3>');
						print ('<p>'.$newsItem['content'].'</p>'.$newsItem['date-created'].'<br><b>'.$newsItem['author'].'</b>');
						print ('</div>');
					}
					print ( '<ul class="paginator">' );
					for ( $i = 1; $i <= $vars['pageCount']; $i++ ) {
						print ( '<li><a href="index.php?page=news&pnumber='.$i.'">'.$i.'</a></li>' );
					}
					print( '</ul>' );
				}
			}
			else {
				foreach ( $vars['errorMessages'] as $errorMsg ) {
					print('<p class="message error">'.$errorMsg.'</p>'); 
				}
			}
		?>	
	</div>
</main>

