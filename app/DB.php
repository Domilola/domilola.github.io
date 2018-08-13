<?php

class DB
{
	public $count_new = 0;
	private static $_instance = null;
	private static $_connectParams = array(
		'_host' => 'localhost',
		'_user' => 'root',
		'_db' => 'parser',
		'password' => ''
	);


	private function __construct(){}
	private function __clone(){}
	private function __wakeup(){}

	static public function getInstance(){
		if(is_null(self::$_instance)){
			self::$_instance = new \mysqli();
			self::$_instance->connect(self::$_connectParams['_host'], self::$_connectParams['_user'], self::$_connectParams['password'], self::$_connectParams['_db']);
		}
		return self::$_instance;
	}

	public function check_url($link_page){
		$db = self::getInstance();
		$link_page = mysqli_real_escape_string($db, $link_page);
		$result = $db->query('SELECT * FROM info_stars WHERE link_page = "'.$link_page.'"')->fetch_all();
		if(empty($result)) return 1;
	}

	public function set_info(array $data){
		$db = self::getInstance();
		$name_original = mysqli_real_escape_string($db, $data['original_name']);
		$link_page = mysqli_real_escape_string($db, $data['link_page']);
		$name = mysqli_real_escape_string($db, $data['name']);		
		$data_birthday = mysqli_real_escape_string($db, $data['data_birthday']);
		$place = mysqli_real_escape_string($db, $data['place']);
		$biography = mysqli_real_escape_string($db, $data['biography']);
		$db->query('INSERT INTO info_stars (link_page, name_rus, name_original, foto_link, birthday, birth_place, biography) VALUES ("'.$link_page.'", "'.$name.'", "'.$name_original.'", "'.$data['foto_link'].'", "'.$data_birthday.'", "'.$place.'", "'.$biography.'")');
		return;
	}	
}
