<?php 

// define('GMA_COMPETITION', $wpdb->prefix . "gma_competition"); // Переменные таблиц попробовать объявить константами

function gma_install (){	
   global $wpdb;
	   $sql = [];

   	$wp_users = $wpdb->prefix . "users";
   	
   	$gma_competition = $wpdb->prefix . "gma_competition";
   	$gma_competition_specialty = $wpdb->prefix . "gma_competition_specialty";
	$gma_competitor = $wpdb->prefix . "gma_competitor";
	$gma_musicians_of_user = $wpdb->prefix . "gma_musicians_of_user";
	$gma_jury = $wpdb->prefix . "gma_jury";
	$gma_jury_specialty = $wpdb->prefix . "gma_jury_specialty";
	$gma_person = $wpdb->prefix . "gma_person";
	$gma_scores = $wpdb->prefix . "gma_scores";
	$gma_specialty = $wpdb->prefix . "gma_specialty";
	$gma_nomination = $wpdb->prefix . "gma_nomination";
	$gma_nomination_for_specialty = $wpdb->prefix . "gma_nomination_for_specialty";
	$gma_specialty_for_competitor = $wpdb->prefix . "gma_specialty_for_competitor";
	$gma_competitor_content = $wpdb->prefix . "gma_competitor_content";
	

	   if($wpdb->get_var("SHOW TABLES LIKE '$gma_competition'") != $gma_competition) {

	       $sql[] = "CREATE TABLE " . $gma_competition . " (
			id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
			name varchar(128) NOT NULL,
			description varchar(512) NULL,
			header varchar(256) NOT NULL,
			fromDate date NOT NULL,
			toDate date NOT NULL,
			beforeStart date NOT NULL,
			prizes varchar(1024) NOT NULL,
			pageUrl varchar(256) NULL,
			enabled tinyint(1) NOT NULL,
			PRIMARY KEY id (id)
		)";
		}

		if($wpdb->get_var("SHOW TABLES LIKE '$gma_specialty'") != $gma_specialty) {

	       $sql[] = "CREATE TABLE " . $gma_specialty . " (
			id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
			parent_id int(10) UNSIGNED NULL,
			name varchar(128) NOT NULL,
			removed tinyint(1) NOT NULL DEFAULT 0,
			PRIMARY KEY id (id),
	        FOREIGN KEY (parent_id) REFERENCES ". $gma_specialty ."(ID)
		)";
		}

		if($wpdb->get_var("SHOW TABLES LIKE '$gma_nomination'") != $gma_nomination) {

	       $sql[] = "CREATE TABLE " . $gma_nomination . " (
			id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
			name varchar(128) NOT NULL,
			PRIMARY KEY id (id)
		)";
		}

		if($wpdb->get_var("SHOW TABLES LIKE '$gma_nomination_for_specialty'") != $gma_nomination_for_specialty) {

	       $sql[] = "CREATE TABLE " . $gma_nomination_for_specialty . " (
			nomination_id int(10) UNSIGNED NOT NULL,
			specialty_id int(10) UNSIGNED NOT NULL,
	        FOREIGN KEY (nomination_id) REFERENCES ". $gma_nomination ."(id),
	        FOREIGN KEY (specialty_id) REFERENCES ". $gma_specialty ."(id)
		)";
		}

	   if($wpdb->get_var("SHOW TABLES LIKE '$gma_musicians_of_user'") != $gma_musicians_of_user) {

	       $sql[] = "CREATE TABLE " . $gma_musicians_of_user . " (
			id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
			name varchar(128) NOT NULL,
			user_id bigint(20) UNSIGNED NOT NULL,
			birthday date NULL,
			removed tinyint (1) NOT NULL DEFAULT 0,
			PRIMARY KEY id (id),
	        FOREIGN KEY (user_id) REFERENCES ". $wp_users ."(ID)
		)";
		}

	   if($wpdb->get_var("SHOW TABLES LIKE '$gma_competitor'") != $gma_competitor) {

	       $sql[] = "CREATE TABLE " . $gma_competitor . " (
			id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
			musician_id int(10) UNSIGNED NOT NULL,
			compositions varchar(128) NOT NULL,
			timeCompositions tinyint(10),
			teacher varchar(128) NULL,
			concertmaster varchar(128) NULL,
			getDiploma varchar(128) NOT NULL,
			telephone varchar(10) NOT NULL,
			city varchar(256) NOT NULL,
			user_id bigint(20) UNSIGNED NOT NULL,
			competition_id int(10) UNSIGNED NOT NULL,
			age_category varchar(2) NOT NULL,
			isConfirm tinyint(1) NOT NULL,
			PRIMARY KEY id (id),
	        FOREIGN KEY (user_id) REFERENCES ". $wp_users ."(ID),
	        FOREIGN KEY (musician_id) REFERENCES ". $gma_musicians_of_user ."(id),
	        FOREIGN KEY (competition_id) REFERENCES ". $gma_competition ."(id)
		)";
		}

	   if($wpdb->get_var("SHOW TABLES LIKE '$gma_competitor_content'") != $gma_competitor_content) {

	       $sql[] = "CREATE TABLE " . $gma_competitor_content . " (
			id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
			source varchar(256) NOT NULL,
			type tinyint(1) NOT NULL,
			competitor_id int(10) UNSIGNED NOT NULL,
			PRIMARY KEY id (id),
			FOREIGN KEY (competitor_id) REFERENCES ". $gma_competitor ."(id)
		)";
		}

		if($wpdb->get_var("SHOW TABLES LIKE '$gma_specialty_for_competitor'") != $gma_specialty_for_competitor) {

	       $sql[] = "CREATE TABLE " . $gma_specialty_for_competitor . " (
			competitor_id int(10) UNSIGNED NOT NULL,
			specialty_id int(10) UNSIGNED NOT NULL,
	        FOREIGN KEY (competitor_id) REFERENCES ". $gma_competitor ."(id),
	        FOREIGN KEY (specialty_id) REFERENCES ". $gma_specialty ."(id)

		)";
		}

	   if($wpdb->get_var("SHOW TABLES LIKE '$gma_person") != $gma_person) {

	       $sql[] = "CREATE TABLE " . $gma_person . " (
			id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
			fio varchar(128) NOT NULL,
			user_id bigint(20) UNSIGNED NOT NULL,
			PRIMARY KEY id (id),
	        FOREIGN KEY (user_id) REFERENCES ". $wp_users ."(ID)
		)";
		}

	   if($wpdb->get_var("SHOW TABLES LIKE '$gma_jury") != $gma_jury) {

	       $sql[] = "CREATE TABLE " . $gma_jury . " (
			id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
			fio varchar(128) NOT NULL,
			user_id bigint(20) UNSIGNED NOT NULL,
			competition_id int(10) UNSIGNED NOT NULL,
			specialty_id int(10) UNSIGNED NOT NULL,
			PRIMARY KEY id (id),
	        FOREIGN KEY (user_id) REFERENCES ". $wp_users ."(ID),
	        FOREIGN KEY (competition_id) REFERENCES ". $gma_competition ."(ID),
	        FOREIGN KEY (specialty_id) REFERENCES ". $gma_specialty ."(ID)
		)";
		}

		if($wpdb->get_var("SHOW TABLES LIKE '$gma_jury_specialty'") != $gma_jury_specialty) {

	        $sql[] = "CREATE TABLE " . $gma_jury_specialty . " (
			jury_id int(10) UNSIGNED NOT NULL,
			specialty_id int(10) UNSIGNED NOT NULL,
			competition_id int(10) UNSIGNED NOT NULL,
	        FOREIGN KEY (competition_id) REFERENCES ". $gma_competition ."(ID),
	        FOREIGN KEY (jury_id) REFERENCES ". $gma_jury ."(ID),
	        FOREIGN KEY (specialty_id) REFERENCES ". $gma_specialty ."(ID)
		)";
		}

	   if($wpdb->get_var("SHOW TABLES LIKE '$gma_scores'") != $gma_scores) {

	       $sql[] = "CREATE TABLE " . $gma_scores . " (
			competitor_id int(10) UNSIGNED NOT NULL,
			competition_id int(10) UNSIGNED NOT NULL,
			jury_id int(10) UNSIGNED NOT NULL,
			score float(10) UNSIGNED NULL,
			comments varchar(1024) NULL,
	        FOREIGN KEY (competitor_id) REFERENCES ". $gma_competitor ."(id),
	        FOREIGN KEY (competition_id) REFERENCES ". $gma_competition ."(id),
	        FOREIGN KEY (jury_id) REFERENCES ". $gma_jury ."(id)
		)";
		}

	   if($wpdb->get_var("SHOW TABLES LIKE '$gma_competition_specialty'") != $gma_competition_specialty) {

	        $sql[] = "CREATE TABLE " . $gma_competition_specialty . " (
			competition_id int(10) UNSIGNED NOT NULL,
			specialty_id int(10) UNSIGNED NOT NULL,
	        FOREIGN KEY (competition_id) REFERENCES ". $gma_competition ."(ID),
	        FOREIGN KEY (specialty_id) REFERENCES ". $gma_specialty ."(ID)
		)";
		}

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);

}

// Запись данных в таблицы
function gma_install_data() {
	global $wpdb;
   	$gma_specialty = $wpdb->prefix . "gma_specialty";
	
	$spec = array(
	1 => 'Фортепиано', 
	2 => 'Народные инструменты', 
	3 => 'Струнно-смычковые инструменты', 
	4 => 'Духовые и ударные инструменты', 
	5 => 'Вокал', 
	6 => 'Классическая гитара', 
	7 => 'Электрогитара', 
	8 => 'Композиторы', 
	9 => 'Музыковедение');
	
   	foreach ($spec as $key => $specialty) {
	$wpdb->insert($gma_specialty, [ 'id' => $key, 'name' => $specialty ], ['%d', '%s'] );
	}
	
	$sub_specialty = array(
		'Академический',
		'Эстрадный',
		'Народный');
	$key_vocal = array_search('Вокал', $spec);
	foreach ($sub_specialty as $key => $sub_spec) {
	$wpdb->insert($gma_specialty, [ 'parent_id' => $key_vocal, 'name' => $sub_spec ], ['%d', '%s'] );
		}

		$sub_specialty = array(
			'Рок-гитара',
			'Джазовая гитара');
		$key_vocal = array_search('Электрогитара', $spec);
		foreach ($sub_specialty as $key => $sub_spec) {
		$wpdb->insert($gma_specialty, [ 'parent_id' => $key_vocal, 'name' => $sub_spec ], ['%d', '%s'] );
			}

		$sub_specialty = array(
			'Собственное сочинений',
			'Аранжировка');
		$key_compositor = array_search('Композиторы', $spec);
		foreach ($sub_specialty as $key => $sub_spec) {
		$wpdb->insert($gma_specialty, [ 'parent_id' => $key_compositor, 'name' => $sub_spec ], ['%d', '%s'] );
			}
	

	$gma_nomination = $wpdb->prefix . "gma_nomination";
	
	$nominations = array(
		'1' => 'Солисты',
		'2' => 'Ансамбли',
		'3' => 'Оркестры', 
		'4' => 'Хоры' 
	);
	
	foreach ($nominations as $key => $nomination) {
		$wpdb->insert($gma_nomination, [ 'id' => $key, 'name' => $nomination ], ['%d', '%s'] );
			}
}



//Функция удаления. Времененно удаляет таблицы плагина после деактивации.
function gma_uninstall()
{
		global $wpdb;
		$gma_tables = [
	   	$wpdb->prefix . "gma_competition_specialty",
		$wpdb->prefix . "gma_scores",
		$wpdb->prefix . "gma_jury_specialty",
		$wpdb->prefix . "gma_jury",
		$wpdb->prefix . "gma_person",
		$wpdb->prefix . "gma_specialty_for_competitor",
		$wpdb->prefix . "gma_competitor_content",
		$wpdb->prefix . "gma_competitor",
		$wpdb->prefix . "gma_nomination_for_specialty",
		$wpdb->prefix . "gma_nomination",
		$wpdb->prefix . "gma_specialty",
		$wpdb->prefix . "gma_musicians_of_user",
		$wpdb->prefix . "gma_competition"
		];	
			
			foreach ($gma_tables as $gma_table) {
					$wpdb->query("DROP TABLE IF EXISTS $gma_table");
				
				}
}