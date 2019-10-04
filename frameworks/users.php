<?php 
	// include( 'frameworks/interfaces.php' );
	// implements iUsers 
	class users {
		/**
		 * -D - Локальный защищённый экземпляр объекта БД;
		 * -V- {db} @db: БД;
		 */
		private $db = NULL;
		/**
		 * -D, Method- Авторизация пользователя;
		 * Поиск производится по таблице users;
		 * Если пользователь найден, то вызывается метод setSession в который передается ID найденного пользователя;
		 * -V- {String} @login: Логин пользователя, от 3 до 16 символов;
		 * -V- {String} @pass: Пароль пользователя, от 3 до 16 символов;
		 * -R- Array(
			'success'	=> (bool),		// true - пользователь авторизован, false - есть ошибки;
			'errors'	=> array(),		// массив ошибок в строчном виде;
			'id'		=> (int/NULL)	// ID авторизованного пользователя найденного в таблице users;
		 );
		 */
		public function auth( $login, $pass ) {
			$success = false;
			$id = NULL;
			$errors = array();
			
			$login = trim( $login );
			$pass = trim( $pass );
			
			if( empty( $login )) {
				$errors[] = "Укажите Ваш логин!";
			} elseif ( mb_strlen( $login, 'utf-8' ) < 3 || mb_strlen( $login, 'utf-8' ) > 16 ) {
				$errors[] = "Неверный логин!";
			}

			if( empty( $pass )) {
				$errors[] = "Укажите Ваш пароль!";
			} elseif ( mb_strlen( $pass, 'utf-8' ) < 3 || mb_strlen( $pass, 'utf-8' ) > 16 ) {
				$errors[] = "Неверный пароль!";
			}

			if( count( $errors ) == 0 ) {
				$query = '
					SELECT 
						id 
					FROM 
						users 
					WHERE 
						login = "'.$this->db->realEscape( $login ).'" AND 
						pass = "'.$this->db->realEscape( md5( $pass ) ).'"
					LIMIT 1	
					;
				';
				$res = $this->db->query($query, 'select_row');
				if ( $res['success'] ) {
					if ( count ( $res['resultArr'] )) {
						$row = $res['resultArr'];
						$success = true;
						$id = $row['id'];
						$this->setSession( $id );
					}
					else {
						$errors[] = "Неверные логин/пароль!";
					}
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
		 * -D, Method- Регистрация пользователя;
		 * Пароль хранится в формате md5;
		 * Пользователь добавляется в таблицу users;
		 * -V- {String} @login: Логин пользователя, от 3 до 16 символов;
		 * -V- {String} @pass: Пароль пользователя, от 3 до 16 символов;
		 * -V- {String} @firstName: Имя пользователя, от 2 до 16 символов;
		 * -V- {String} @bithday: День рождения пользователя;
		 * -R- Array(
			'success'	=> (bool),		// true - пользователь зарегистрирован, false - есть ошибки;
			'errors'	=> array(),		// массив ошибок в строчном виде;
			'id'		=> (int/NULL)	// ID добавленного пользователя в таблицу users;
		 );
		 */
		public function register( $login, $pass, $firstName, $birthday ) {
			$success = false;
			$id = NULL;
			$errors = array();

			$login = trim( $login );
			$pass = trim( $pass );
			$firstName = trim( $firstName );
			$birthday = trim( $birthday );

			if( empty( $login )) {
				$errors[] = "Укажите Ваш логин!";
			} elseif ( mb_strlen( $login, 'utf-8' ) < 3 || mb_strlen( $login, 'utf-8' ) > 16 ) {
				$errors[] = "Логин должен быть от 3 до 16 символов!";
			}

			if( empty( $pass )) {
				$errors[] = "Укажите Ваш пароль!";
			} elseif ( mb_strlen( $pass, 'utf-8' ) < 3 || mb_strlen( $pass, 'utf-8' ) > 16 ) {
				$errors[] = "Пароль должен быть от 3 до 16 символов!";
			}
			
			if( empty( $firstName )) {
				$errors[] = "Укажите Ваше имя!";
			} elseif ( mb_strlen( $firstName, 'utf-8' ) > 50 ) {
				$errors[] = "Имя должно быть не более 50 символов!"; //date('Y-m-d', strtotime($_POST['date']));
			}

			if( empty( $birthday )) {
				$errors[] = "Укажите дату Вашего Дня Рождения!";
			} elseif ( !preg_match('/^[0-9]{2}\.[0-9]{2}\.[0-9]{4}$/ui', $birthday) ) {
				$errors[] = $birthday;
				$errors[] = "День рождения должен быть в формате: 00.00.0000";
			} else {
				$birthday = date('Y-m-d H:i:s', strtotime( $birthday ));
			}
			
			if( count( $errors ) === 0 ) {
				$query = '
					SELECT
						COUNT(*) as login_num
					FROM
						users
					WHERE
						login = "'.$this->db->realEscape( $login ).'"
					;
				';
				$res = $this->db->query( $query, 'select_row' );
				if ( $res['success'] ) {
					$loginCount = intval( $res['resultArr']['login_num'] );
				} else {
					$errors = $res['errors'];
				}
				if ( $loginCount ) {
					$errors[] = "Логин уже существует!";
				}
				else {
					$query = '
						INSERT INTO 
							users (
								first_name, 
								login, 
								pass, 
								birthday
							) 
						VALUES (
							"'.$this->db->realEscape( $firstName ).'",
							"'.$this->db->realEscape( $login ).'",
							"'.md5( $pass ).'",
							"'.$birthday.'"
						);
					';
					$res = $this->db->query($query, 'insert');
					if ( $res['success'] ) {
						$success = true;
						$id = $res['id'];
					} else {
						$errors = $res['errors'];
					}
				}
			}
			return array(
				'success'	=> $success,
				'errors'	=> $errors,
				'id'		=> $id
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
		 * -D, Method- Сессия пользователя;
		 * Ключ генерируется случайным набором и помещается в md5;
		 * Запись о сессии заносится в таблицу users_sessions;
		 * Ключ записывается в $_SESSION['key_session'];
		 * -V- {Integer} @id_user: ID-пользователя в таблице users;
		 * -R- Array(
			'success'		=> (bool),			// true - пользователь авторизован, false - есть ошибки;
			'errors'		=> array(),			// массив ошибок в строчном виде;
			'key_session'	=> (string/NULL)	// Ключ сессии в строчном типе;
		 );
		 */
		public function setSession( $id_user ) {
			$success = false;
			$errors = array();
			$key_session = NULL;

			$key = md5 ( uniqid() );
			$query = '
				INSERT INTO
					users_sessions (
						`id-user`,
						`key-session`,
						`date-created`
					)
				VALUES (
					'.$id_user.',
					"'.$key.'",
					CURRENT_TIMESTAMP()
				);
			';
			$res = $this->db->query($query, 'insert');
				if ( $res['success'] ) {
					$success = true;
					$key_session = $key;
					$_SESSION['key_session'] = $key_session;
				} else {
					$errors[] = "Ошибка создания сессии!";
				}
			return array(
				'success'		=> $success,
				'errors'		=> $errors,
				'key_session'	=> $key_session
			);
		}
		/**
		 * -D, Method- Получение сессии пользователя;
		 * Поиск производится по таблице users_sessions;
		 * -V- {String} @key_session: ключ сессии выданный методом setSession;
		 * -R- Array(
			'found'			=> (bool),			// true - сессия найдена, false - сессия не найдена;
			'id-user'		=> (int/NULL),		// ID пользователя найденного по таблице users_sessions;
			'date-created'	=> (string/NULL)	// Дата создания сессии;
		 );
		 */
		public function getSession( $key_session ) {
			$found = false;
			$id_user = NULL;
			$date_created = NULL;

			$query = '
				SELECT
					`id-user`,
					`date-created`
				FROM users_sessions
				WHERE
					`key-session` = "'.$this->db->realEscape( $key_session ).'";
			';
			$res = $this->db->query($query, 'select_row');
			if ( $res['success'] ) {
				if ( count ( $res['resultArr'] )) {
					$row = $res['resultArr'];
					$found = true;
					$id_user = $row['id-user'];
					$date_created = $row['date-created'];
				}
			} else {
				$errors = $res['errors'];
			}
			return array(
				'found'			=> $found,
				'id-user'		=> $id_user,
				'date-created'	=> $date_created
			);
		}
		/**
		*	-D, Method- Метод выполняющий проверку авторизации пользователя по сессионной переменной;
		*
		*/
		public function isAuth() {
			return ( !empty( $_SESSION['key_session'] ));
		}
		/**
		*	-D, Method- Метод выполняющий завершение сессии пользователя;
		*
		*/
		public function logout( $key_session ) {
			$deleted = false;
			$errors = array();

			$query = '
				DELETE FROM
					users_sessions
				WHERE
					`key-session` = "'.$this->db->realEscape( $key_session ).'";
			';
			$res = $this->db->query( $query, 'delete' );
			if ( $res['success'] ) {
				unset( $_SESSION['key_session'] );
				$deleted = true;
			} else {
				$errors[] = "Ошибка завершения сессии!";
			}
			return array(
				'deleted'		=> $deleted,
				'errors'		=> $errors
			);
		}
	}  
?>