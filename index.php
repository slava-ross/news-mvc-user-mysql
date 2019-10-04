<?php
	header('Content-Type: text/html; charset=utf-8');
	session_start();
	/**
	 * -D- Подключение к базе данных
	*/
	include ('frameworks/db.php');
	$db = new db;
	$dbResult = $db->connect(
		'localhost',
		'root',
		'',
		'userwork'
	);
	if ( !$dbResult['success'] ) {
		foreach ( $dbResult['errors'] as $errMessage ) {
			print( '<p class="message error">'.$errMessage.'</p>');
		}
		exit();
	}
	/**
	 * -D- Запуск контроллера страниц
	*/
	include ('frameworks/pages.php');
	$pages = new pages;
	$pages->setDB( $db );
	if ( !isset( $_GET['page'] )) {
	    $_GET['page'] = 'none';
	}
	$pages->router( $_GET['page'] );
?>
