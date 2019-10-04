<?php
	class db {
		/**
		 * -D - Локальная защищённая ссылка на объект связи с БД;
		 * -V- {link} @:db_link ссылка на линк БД;
		 */
		private $db_link = NULL;
		/**
		 * -D, Method- Установка соединения с БД, выбор рабочей кодировки клиентской стороны;
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
		public function connect ( $dbHost, $dbLogin, $dbPass, $dbName ) {
			$success = false;
			$errors = array();
			
			$this->db_link = mysqli_connect( $dbHost, $dbLogin, $dbPass, $dbName );
			if ( !$this->db_link ) {
				$errors[] = "Невозможно подключиться к базе данных. Код ошибки: ".mysqli_connect_error();
			} else {
				$success = true;
				if ( !mysqli_set_charset( $this->db_link, "utf8" )) {
					$errors[] = "Ошибка при загрузке набора символов utf8: ".mysqli_error( $this->db_link );
					exit();
				}
			}
			return array(
				'dbLink'	=> $this->db_link,
				'success'	=> $success,
				'errors'	=> $errors
			);
		}
		/**
		 * -D, Method- Завершение соединения с БД;
		 * -V- {mysqli-link} @dbLink: линк с БД;
		 */
		public function disconnect ( $dbLink ) {
			mysqli_close( $this->db_link );
		}
		/**
		 * -D, Method- Экранирование спецсимволов в строке запроса к БД;
		 * -V- {String} @str: строка запроса;
		 */
		public function realEscape ( $str ) {
			return mysqli_escape_string( $this->db_link, $str );
		}
		/**
		 * -D, Method- Запрос к БД;
		 * -V- {Array} @resultArr: массив с результатом выполнения запроса;
		 * -V- {Boolean} @success: успешность выполнения запроса;
		 * -V- {Array} @errors: массив сообщений об ошибках;
		 * -V- {Int} @id: идентификатор полученного из БД объекта;
		 * -R- Array(
		 	'success'	=> (bool),		// true - успешное выполнение, false - есть ошибки;
		 	'result'	=> (int)		// количество полученных при запросе строк;
			'id'		=> (int),		// идентификатор объекта;
			'resultArr'	=> array(),		// массив полученных данных;
			'errors'	=> array(),		// массив ошибок в строчном виде;
		 );
		 */
		public function query ( $SQL, $qType ) {
			
			$resultArr = array();
			$success = false;
			$errors = array();
			$id = NULL;
			
			switch( $qType ) {
				case 'insert':
				case 'update':
				case 'delete':
					$res = mysqli_query( $this->db_link, $SQL );
					$errno = mysqli_errno( $this->db_link );
					if ( !$errno ) {
						$success = true;
						$id = mysqli_insert_id( $this->db_link );
					} else {
						$errors[] = 'Код ошибки: '.$errno;
						$errors[] = mysqli_error( $this->db_link );
					}
				break;
				case 'select':
					$res = mysqli_query( $this->db_link, $SQL );
					$errno = mysqli_errno( $this->db_link );
					if ( !$errno ) {
						while( $row = mysqli_fetch_assoc( $res )) {
							$resultArr[] = $row;
						}
						$success = true;
					} else {
						$errors[] = 'Код ошибки: '.$errno;
						$errors[] = mysqli_error( $this->db_link );
					}
				break;
				case 'select_row':
					$res = mysqli_query( $this->db_link, $SQL );
					$errno = mysqli_errno( $this->db_link );
					if ( !$errno ) {
						$resultArr = mysqli_fetch_assoc( $res );
						$success = true;
					} else {
						$errors[] = 'Код ошибки: '.$errno;
						$errors[] = mysqli_error( $this->db_link );
					}
				break;
				default:
					$errors[] = "Неверный тип запроса";
			}
			return array(
				'success'	=> $success,
				'result'	=> mysqli_affected_rows( $this->db_link ),
				'id'		=> $id,
				'resultArr'	=> $resultArr,
				'errors'	=> $errors
			);
		}
	}
?>