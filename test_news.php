<?php
	/**
	 * -D- Отправка заголовков;
	 */
	header('Content-Type: text/html; charset=utf-8');
	/**
	 * -D- Подключение к базе данных;
	 */
	include ('frameworks/db.php');
	$db = new db;
	$dbResult = $db->connect( 'localhost', 'root', '', 'userwork' );
	if ( !$dbResult['success'] ) {
		foreach ( $dbResult['errors'] as $errMessage ) {
			print( '<p>'.$errMessage.'</p>');
		}
		exit();
	}
	/**
	 * -D- Структура ветвления для проверки методов класса news;
	 *
	 * -V- {String} @action: переменная для выбора проверяемого метода;
	 * -D- 'add' - проверка метода <addNews>;
	 * -D- 'edit' - проверка метода <editNews>;
	 * -D- 'get' - проверка метода <getNews>;
	 * -D- 'list' - проверка метода <getNewsList>;
	 *
	 * -V- {Array} @result: возвращаемый методом результат;
	 */
	$action = 'gcp';	
	$result = array();

	include ('frameworks/news.php');
	$news = new news;
	$news->setDB( $db );

	switch( $action ) {
		case 'add':
			$result = $news->addNews( 'Заголовок новости №4','Содержание новости №4','04.04.2004','Автор №4' );
		break;
		case 'edit':
			$result = $news->editNews( 1,'Заголовок новости №11','Содержание новости №11','11.11.2011','Автор №11' );
		break;
		case 'get':
			$result = $news->getNews( 3 );
		break;
		case 'list':
			$result = $news->getNewsList();
		break;
		case 'gcp':
			$result = $news->getCountPages(1);
		break;
		default:
	}
	/**
	 * -D- Вывод полученных результатов;
	 */
	//print_r( $result );
	var_dump($result);
	/**
	 * -D- Завершение соединения с базой данных;
	 */
	$db->disconnect( $dbResult );
?>