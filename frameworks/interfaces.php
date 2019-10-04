<?php
interface iUsers {
	/**
	 * -D, Method- Регистрация пользователя
	 * Пароль хранится в формате md5;
	 * Пользователь добавляется в таблицу users;
	 * -V- {String} @login: Логин пользователя, от 3 до 16 символов;
	 * -V- {String} @pass: Пароль пользователя, от 3 до 16 символов;
	 * -V- {String} @firstName: Имя пользователя, от 2 до 16 символов;
	 * -V- {String} @bithday: День рождение пользователя;
	 * -R- Array(
	 	'success'	=> (bool), // true - пользователь зарегистрирован, false - есть ошибки;
		'errors'		=> array(), // массив ошибок в строчном виде
		'id'			=> (int/NULL) // ID добавленного пользователя в таблицу users
	 )
	*/
    public function register(
		string $login, 
		string $pass, 
		string $firstName, 
		string $birthday
	);
	/**
	 * -D, Method- Авторизация пользователя.
	 * Поиск производится по таблице users.
	 * Если пользователь найден, то вызывается метод setSession в который передается ID найденного пользователя;
	 * -V- {String} @login: Логин пользователя, от 3 до 16 символов;
	 * -V- {String} @pass: Пароль пользователя, от 3 до 16 символов;
	 * -R- Array(
	 	'success'	=> (bool), // true - пользователь авторизован, false - есть ошибки;
		'errors'		=> array(), // массив ошибок в строчном виде
		'id'			=> (int/NULL) // ID авторизованного пользователя найденного в таблице users
	 )
	*/
    public function auth(
		string $login,
		string $pass
	);
	/**
	 * -D, Method- Сессия пользователя.
	 * Ключ генерируется случайным набором и помещается в md5.
	 * Запись о сессии заносится в таблицу users_sessions;
	 * Ключ записывается в $_SESSION['key_session'];
	 * -V- {Integer} @id_user: ID-пользователя в таблице users;
	 * -R- Array(
	 	'success'					=> (bool), // true - пользователь авторизован, false - есть ошибки
		'errors'						=> array(), // массив ошибок в строчном виде
		'key_session'			=> (string/NULL) // Ключ сессии в строчном типе
	 )
	*/
	public function setSession(
		int $id_user
	);
	/**
	 * -D, Method- Получение сессии пользователя.
	 * Поиск производится по таблице users_sessions;
	 * -V- {String} @key_session: ключ сессии выданный методом setSession;
	 * -R- Array(
	 	'found'						=> (bool), // true - сессия найдена, false - сессия не найдена
	 	'id-user'					=> (int/NULL), // ID пользователя найденного по таблице users_sessions
		'date-created'			=> (string/NULL) // Дата создания сессии
	 )
	*/
	public function getSession(
		string $key_session
	);
		/**
	 * -D, Method- Удаление сессии пользователя.
	 * Поиск производится по таблице users_sessions;
	 * -V- {String} @key_session: ключ сессии выданный методом setSession;
	 * -R- Array(
	 	'found'						=> (bool), // true - сессия найдена, false - сессия не найдена
	 	'id-user'					=> (int/NULL), // ID пользователя найденного по таблице users_sessions
		'date-created'			=> (string/NULL) // Дата создания сессии
	 )
	*/
	public function logout(
		string $key_session
	);	
}

?>