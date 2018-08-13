<?php
require_once "app/DB.php";
require_once "lib/curl_query.php";
require_once "lib/simple_html_dom.php";
class Parser
{
	public function array_formation(){
		$pages = array();
		$pages[] = 'http://filmix.cc/persons';
		for ($i=2; $i <= 9999; $i++) { 
			$pages[] = 'http://filmix.cc/persons/page/'.$i.'/';
		}
		$this->start_parse($pages);
	return;
	}

	public function start_parse (array $urls){
		$items = array();
		foreach($urls as $url){
			$html = curl_get($url);
			$dom = str_get_html($html);
			$elements = $dom->find("a[itemprop=url]");
			foreach($elements as $element){
				$check_url = DB::check_url($element->href);
				if($check_url == 1){
					$items[] = $element->href;
				}
				if(count($items) == 100){
					break 2;
				}
			}
		}
		$result = $this->Parse($items);
		return;
	}

	public function Parse(array $pages){
		foreach($pages as $page){
			$item = curl_get($page);
			$dom = str_get_html($item);
			$name = $dom->find('.name', 0);
			$original_name = $dom->find('.origin-name', 0);
			$data_birthday = $dom->find('.personebirth', 0);
			$foto_link = $dom->find('a.poster-box', 0);
			$place = $dom->find('span[itemprop="address"]', 0);
			$biography = $dom->find('.about', 0);
			$data = array('name' => iconv('windows-1251', 'utf-8', $name->plaintext),'link_page' => $page, 'original_name' => iconv('windows-1251', 'utf-8', $original_name->plaintext), 'data_birthday' => iconv('windows-1251', 'utf-8', $data_birthday->plaintext), 'foto_link' => $foto_link->href, 'place' => iconv('windows-1251', 'utf-8', $place->plaintext), 'biography' => iconv('windows-1251', 'utf-8', $biography->plaintext));
			DB::set_info($data);
		}
		return 1;
	}

	/*
	public function check_date(){
		$db = self::getInstance();
		$result = $db->query('SELECT * FROM date_parsers ORDER BY id DESC LIMIT 1');
		$date = mysqli_fetch_all($result, MYSQLI_ASSOC);
		$today_date = date("Y-m-d");
		$difference = date('d', strtotime($today_date)) - date('d', strtotime($date[0]['date_pars']));
		if($difference > 0){
			$this->array_formation();
		}
	}
	*/	
}
