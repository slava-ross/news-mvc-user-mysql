<?php
	/**
	*	-D- Класс @news - работа с новостями;
	*/
	class news {
		/**
		 * -D - Локальный защищённый экземпляр объекта БД;
		 * -V- {db} @db: БД;
		 */
		private $db = NULL;
		/**
		 * -D, Method- Метод выполняющий валидацию ввода полей новости и добавляющий новость в Б/Д;
		 * -V- {String} @dbHost: IP-адрес или доменное имя сервера БД;
		 * -V- {String} @dbLogin: Логин пользователя БД;
		 * -V- {String} @dbPass: Пароль пользователя БД;
		 * -V- {String} @dbName: Имя БД;
		 * -R- Array(
		 	'dbLink'	=> (mysqli-link)//
			'success'	=> (bool),		// true - успешное подключение, false - есть ошибки;
			'errors'	=> array(),		// массив ошибок в строчном виде;
		 );
		 */
		public function addNews ( $title, $content, $dateCreated, $author ) {
			$success = false;
			$id = NULL;
			$errors = array();

			$title = trim( $title );
			$dateCreated = trim( $dateCreated );
			$author = trim( $author );

			if( empty( $title )) {
				$errors[] = "Укажите заголовок!";
			} elseif ( mb_strlen( $title, 'utf-8' ) > 50 ) {
				$errors[] = "Заголовок должен быть не более 50 символов!";
			}
			
			if( empty( $dateCreated )) {
				$errors[] = "Укажите дату создания новости!";
			} elseif ( !preg_match('/^[0-9]{2}\.[0-9]{2}\.[0-9]{4}$/ui', $dateCreated )) {
				$errors[] = "Дата создания новости должна быть в формате: 00.00.0000";
			} else {
				$dateCreated = date('Y-m-d H:i:s', strtotime( $dateCreated ));
			}

			if( empty( $author )) {
				$errors[] = "Укажите имя автора!";
			} elseif ( mb_strlen( $author, 'utf-8' ) > 50 ) {
				$errors[] = "Имя автора должно быть не более 50 символов!";
			}

			if( count( $errors ) == 0 ) {
				$query = '
					INSERT INTO
						news (
							`title`,
							`content`,
							`date-created`,
							`author`
						) 
					VALUES (
						"'.$this->db->realEscape( $title ).'",
						"'.$this->db->realEscape( $content ).'",
						"'.$dateCreated.'",
						"'.$this->db->realEscape( $author ).'");
				';
				$res = $this->db->query($query, 'insert');
				if ( $res['success'] ) {
					$success = true;
					$id = $res['id'];
				} else {
					$errors = $res['errors'];
				}
			}
			return array(
				'success'	=> $success,
				'errors'	=> $errors,
				'id'		=> $id
			);
		}
		/**
		 * -D, Method- Изменение новости в Б/Д;
		 * -V- {String} @newsId: идентификатор записи;
		 * -V- {String} @title: заголовок новости;
		 * -V- {String} @content: содержимое новости;
		 * -V- {String} @dateCreated: дата создания новости;
		 * -V- {String} @author: имя автора;
		 * -R- Array(
			'success'	=> (bool),		// true - успешное выполнение, false - есть ошибки;
			'errors'	=> array(),		// массив ошибок в строчном виде;
		 );
		 */
		public function editNews ( $newsId, $title, $content, $dateCreated, $author ) {
			$success = false;
			$errors = array();

			$title = trim( $title );
			$dateCreated = trim( $dateCreated );
			$author = trim( $author );

			if( empty( $title )) {
				$errors[] = "Укажите заголовок!";
			} elseif ( mb_strlen( $title, 'utf-8' ) > 50 ) {
				$errors[] = "Заголовок должен быть не более 50 символов!";
			}
			
			if( empty( $author )) {
				$errors[] = "Укажите имя автора!";
			} elseif ( mb_strlen( $author, 'utf-8' ) > 50 ) {
				$errors[] = "Имя автора должно быть не более 50 символов!";
			}

			if( empty( $dateCreated )) {
				$errors[] = "Укажите дату создания новости!";
			} elseif ( !preg_match('/^[0-9]{2}\.[0-9]{2}\.[0-9]{4}$/ui', $dateCreated )) {
				$errors[] = "Дата создания новости должна быть в формате: 00.00.0000";
			} else {
				$dateCreated = date('Y-m-d H:i:s', strtotime( $dateCreated ));
			}
			if( count( $errors ) == 0 ) {
				$query = '
					UPDATE
						`news`
					SET
						`title`= "'.$this->db->realEscape( $title ).'",
						`content`="'.$this->db->realEscape( $content ).'",
						`date-created`="'.$dateCreated.'",
						`author`="'.$this->db->realEscape( $author ).'"
					WHERE
						`id` = '.$newsId.';
				';
				
				$res = $this->db->query($query, 'update');
				if ( $res['success'] ) {
					$success = true;
				} else {
					$errors = $res['errors'];
				}
			}
			return array(
				'success'	=> $success,
				'errors'	=> $errors
			);
		}
 		/**
		*	-D- @getNews - Метод чтения новости из Б/Д;
		*/
		public function getNews ( $newsId ) {
			$success = false;

			$query = '
				SELECT
					`title`,
					`content`,
					`date-created`,
					`author`
				FROM
					`news`
				WHERE
					`id` = '.$newsId.';
			';
			$res = $this->db->query( $query, 'select_row' );
			if ( $res['success'] ) {
				if ( count ( $res['resultArr'] )) {
					return array(
						'title'			=> $res['title'],
						'content'		=> $res['content'],
						'dateCreated'	=> $res['date-created'],
						'author'		=> $res['author']
					);
				}
			} else {
				return false;
			}
		}
 		/**
		*	-D- @getNews - Метод чтения списка новостей из Б/Д;
		*
		*/
		public function getNewsList ( $pnum, $limit ) {
			$success = false;
			$newsArr = NULL;
			$errors = array();

			$pStart = ($pnum - 1) * $limit;

			$query = '
				SELECT
					`title`,
					`content`,
					`date-created`,
					`author`
				FROM
					`news`
				LIMIT '.$pStart.','.$limit.';
			';
			$res = $this->db->query( $query, 'select' );
			if ( $res['success'] ) {
				$success = true;
				$newsArr = $res['resultArr'];
			} else {
				$errors = $res['errors'];
			}
			return array(
				'success'	=> $success,
				'newsArr'	=> $newsArr,
				'errors'	=> $errors
			);
		}
		/**
		 * -D, Method- Экземпляр объекта БД;
		 * -V- {db} @db: БД;
		 */
		public function setDB( $db ) {
			$this->db = $db;
		}
		/**
		 * -D, Method- Получение количества страниц с новостями;
		 * -V- {int} @limit: количество новостей отображаемых на одной странице;
		 * -R- {int} @limit: Количество страниц;
		 * -R- {boolean} @limit = false: ошибки при выполнении;
		 */
		public function getCountPages( $limit ) {
			$query = '
				SELECT
					COUNT(*) as pcount
				FROM
					`news`;
			';
			$res = $this->db->query( $query, 'select_row' );
			if ( $res['success'] ) {
				$countPages = intval( $res['resultArr']['pcount'] );
					return intval( ceil( $countPages / $limit ));
			} else {
				return false;
			}
		}	
	}
?>