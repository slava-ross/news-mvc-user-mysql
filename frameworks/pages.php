<?php
	/**
	*	-D- @pages - Класс "сборщика страниц" (Page Controller);
	*
	*/
	class pages {
		/**
		 * -D - Локальный защищённый экземпляр объекта БД;
		 * -V- {db} @db: БД;
		 */
		private $db = NULL;
		/**
		 * -D, Method- Экземпляр объекта БД;
		 * -V- {db} @db: БД;
		 */
		public function setDB( $db ) {
			$this->db = $db;
		}
		/**
		*	-D- @getTemplate - Метод подключения шаблона с передачей ему необходимых для отображения страницы параметров;
		*
		*/
		public function getTemplate( $file, $vars=array() ) {
			include( $file );
		}
		/**
		*	-D- @router - Основной метод задающий "маршрут" приложения для формирования соответствующей страницы;
		*
		*/
		public function router( $page ) {

			include( 'frameworks/users.php' );
			$users = new users;
			$users->setDB( $this->db );
			
			$authorized = $users->isAuth();
			$mainMessage = array();
			$messages = array();
			/**
			*	-D- Выбор страницы;
			*/
			switch( $page ) {
				/**
				*	-D- Формирование и отображение страницы "Регистрация";
				*
				*/
				case 'reg':
					if( isset( $_POST['submit'] )) {
						$result = $users->register( $_POST['login'], $_POST['password'], $_POST['firstname'], $_POST['birthday'] );
					
						if ( $result['success'] ) {

							$messages[] = "Спасибо за регистрацию!";

							$this->getTemplate(
								'templates/header.tpl',
								array(
									'title'=>'New Site - Home page',
									'styles'=>'css/main.css',
									'auth'=>$authorized
								)
							);
							$this->getTemplate(
								'templates/main.tpl',
								array(
									'messages' => $messages
								)
							);
						}
						else {
							$this->getTemplate( 
								'templates/header.tpl',
								array(
									'title'=>'Регистрация',
									'styles'=>'css/reg.css',
									'auth'=>$authorized
								)
							);
							$this->getTemplate(
								'templates/reg.tpl',
								array(
									'errors' => $result['errors']
								)
							);
						}
					} else {
						$this->getTemplate( 
							'templates/header.tpl',
							array(
								'title'=>'Регистрация',
								'styles'=>'css/reg.css',
								'auth'=>$authorized
							)
						);
						$this->getTemplate(	'templates/reg.tpl' );
					}

					$this->getTemplate( 'templates/footer.tpl' );
				break;
				/**
				*	-D- Формирование и отображение страницы "Вход" (Авторизация);
				*
				*/
				case 'auth':
					if( isset( $_POST['submit'] )) {
						$result = $users->auth( $_POST['login'], $_POST['password'] );
						if ( $result['success'] ) {

							$authorized = $users->isAuth();
							$messages[] = "Добро пожаловать!";

							$this->getTemplate(
								'templates/header.tpl',
								array(
									'title'=>'New Site - Home page',
									'styles'=>'css/main.css',
									'auth'=>$authorized
								)
							);
							$this->getTemplate(
								'templates/main.tpl',
								array(
									'messages' => $messages
								)
							);
						}
						else {
							$this->getTemplate(
								'templates/header.tpl',
								array(
									'title'=>'Вход',
									'styles'=>'css/auth.css',
									'auth'=>$authorized
								)
							);
							$this->getTemplate(
								'templates/auth.tpl',
								array(
									'errors' => $result['errors']
								)
							);
						}
					} else {
						$this->getTemplate(
							'templates/header.tpl',
							array(
								'title'=>'Вход',
								'styles'=>'css/auth.css',
								'auth'=>$authorized
							)
						);
						$this->getTemplate(	'templates/auth.tpl' );
					}

					$this->getTemplate( 'templates/footer.tpl' );
				break;
				/**
				*	-D- Формирование и отображение страницы "Добавить новость";
				*
				*/
				case 'add_news':
					include( 'frameworks/news.php' );
					$news = new news;
					$news->setDB( $this->db );

					if( isset( $_POST['submit'] )) {
						$result = $news->addNews( $_POST['title'], $_POST['content'], $_POST['news_date'], $_POST['author'] );
						
						if ( $result['success'] ) {

							$messages[] = "Новость создана.";

							$this->getTemplate(
								'templates/header.tpl',
								array(
									'title'=>'New Site - Home page',
									'styles'=>'css/main.css',
									'auth'=>$authorized
								)
							);
							$this->getTemplate(
								'templates/main.tpl',
								array(
									'messages' => $messages
								)
							);
						}
						else {
							$this->getTemplate(
								'templates/header.tpl',
								array(
									'title'=>'Добавление новости',
									'styles'=>'css/news.css',
									'auth'=>$authorized
								)
							);
							$this->getTemplate(
								'templates/add_news.tpl',
								array(
									'errors' => $result['errors']
								)
							);
						}
					} else {
						$this->getTemplate(
							'templates/header.tpl',
							array(
								'title'=>'Добавление новости',
								'styles'=>'css/news.css',
								'auth'=>$authorized
							)
						);
						$this->getTemplate(	'templates/add_news.tpl' );
					}

					$this->getTemplate( 'templates/footer.tpl' );
				break;
				/**
				*	-D- Формирование и отображение страницы "Новости";
				*
				*/
				case 'news':
					include ('frameworks/news.php');
					/**
					*	-V- {news} @newsSource: экземпляр объекта новостей;
					*	-V- {array} @result: массив с результатами работы методов объекта, содержащий как рабочую информацию, так и сообщения об ошибках;
					*/
					$news = new news;
					$news->setDB( $this->db );
					$result = array();
					$limit = 3;
					if ( isset( $_GET['pnumber'] )) {
						$pnum = $_GET['pnumber'];
					}
					else {
						$pnum = 1;
					}
					$result = $news->getNewsList( intval( $pnum ), $limit );
					$pageCount = $news->getCountPages( $limit );
					$this->getTemplate(
						'templates/header.tpl',
						array(
							'title'	=>'Новости',
							'styles'=>'css/news.css',
							'auth'	=>$authorized
						)
					);
					$this->getTemplate(
						'templates/news.tpl',
						array(
							'newsArray' => $result['newsArr'],
							'pageCount' => $pageCount,
							'errorMessages' => $result['errors']
						)
					);
					$this->getTemplate( 'templates/footer.tpl' );
				break;
				/**
				*	-D- Выход пользователя из авторизованного состояния (пункт меню "Выход");
				*
				*/
				case 'exit':
					$result = $users->logout( $_SESSION['key_session'] );
					if ( $result['deleted'] ) {
						$mainMessage[] = 'Goodbye!';	
					}
					else {
						$mainMessage = $result['errors'];
					}
					$authorized = $users->isAuth();
				/**
				*	-D- Формирование и отображение главной страницы сайта и страница "по-умолчанию";
				*
				*/
				case 'main':
				default:
					$this->getTemplate(
						'templates/header.tpl',
						array(
							'title'=>'New Site - Home page',
							'styles'=>'css/main.css',
							'auth'=>$authorized
						)
					);
					$this->getTemplate(
						'templates/main.tpl',
						array(
							'messages'=>$mainMessage
						)
					);
					$this->getTemplate( 'templates/footer.tpl' );
			}
		}
	}
?>