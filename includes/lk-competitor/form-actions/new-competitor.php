<?php

// Если email нет среди юеров, то нужно создать нового юзера и получить user_id.  Сейчас не отправляется форма для unlogin users, т.к. не передается user_id
// Возможно нужно получать всегда user_id по email в форме заявки, т.к. человек может исправить на новый email.
// Для незалогиненного юзера нужно добавить getId по введному email
// Также нужно добавить подсказку в форме рядом с email, которая будет обхяснять,
// что этот email будет использовать для логина и просмотра информации о заявке. 

/*
$type_content = 0 - URL
$type_content = 1 - файл

*/

// header('Content-type: application/json');
// define('SHORTINIT', true);
// require_once('../../../../../../wp-config.php'); // подключить, иначе не будет работать Запрос Insert



add_action('wp_ajax_testAjax', 'testAjax');
add_action('wp_ajax_nopriv_testAjax', 'testAjax');

function createNewUser($user_email, $user_pass)
{
	$userdata;
	$user_email = $user_email;
	$user_login = $user_email;


	$userdata = array(
		'user_pass'       => $user_pass, // обязательно
		'user_login'      => $user_email, // обязательно
		'user_email'      => $user_email,
		'display_name'    => '',
		'rich_editing'    => 'false', // false - выключить визуальный редактор
		'role'            => 'competitor', // (строка) роль пользователя
	);
	
	$user_id = wp_insert_user( $userdata );

	//Проверка создания пользователя
	if ( is_wp_error( $user_id ) ) {

		echo $user_id->get_error_message();
	}
	else {
		// return $userdata;
		echo 'Юзер создан.';
	}
}

function testAjax()
{
	
	global $wpdb;
	global	$current_user; // пото нужно ID залогиненого юзера.

	// ПОка веременно по email нахожу Id.
	$user = get_user_by( 'email', $_POST['user_email'] );

	$user_email = $_POST['user_email'];
	createNewUser("guitarall@yandex.ru", "aksdjhaksjdahs");
	

	// $userId = $user->ID;

	$userId = get_current_user_id();


	// $_POST = array_map('stripslashes_deep', $_POST);
	
	$birthday = $_POST['birthday'];
	
	if ( $_POST['name'] != null ) {
		$name_musician = $_POST['name'];
		$name_musician = stripslashes($name_musician);
		
		
		$wpdb->insert(
			"wp_gma_musicians_of_user",
			[
				"name" => "$name_musician", 
				"user_id" => $userId,
				"birthday" => "$birthday", 
				"removed" => 0
			],
			
			['%s', '%d', '%s', '%d']
		);
		
		$musician_id = $wpdb->insert_id;
		
	}  else {
		$musician_id = $_POST['musiciaId'];
	}
	
	
	$age_category = $_POST['ageCategory'];
	
	$competition_id = $_POST['competitionId'] ;
	
	$country = $_POST['country'];
	$city = $_POST['city'];
	$telephone = $_POST['telephone'];
	
	$postStreet = $_POST['postStreet'];
	$houseNumber = $_POST['houseNumber'];
	$flatNumber = $_POST['flatNumber'];


	$compositions =  ($_POST["compositions"]);
	$compositions = stripslashes($compositions);

	if ($_POST['getDiploma'] == "byPost") {
		$address = "$postStreet,  $houseNumber,  $flatNumber,  $city,  $country";
	} else {
		$address = NULL;
		$postStreet = NULL;
		$houseNumber = NULL;
		$flatNumber = NULL;
	}

	$teacher = $_POST['teacher'];
	$concertmaster = $_POST['concertmaster'];
	$school = $_POST['school'];
	$school = stripslashes($school);
	$timeCompositions = $_POST['timeCompositions'];

	
	$wpdb->insert(
		"wp_gma_competitor",
		[
			"user_id" => $userId,
			"musician_id" => $musician_id,
			"compositions" => $compositions,
			"telephone" => $telephone,
			"postIndex" => $_POST['postIndex'],   
			"city" => $city,  
			"country" => $country,  
			"address" => $address,  
			"getDiploma" => $_POST['getDiploma'],  
			"competition_id" => $competition_id, 
			"age_category" => "$age_category", 
			"isConfirm" => 0,
			"teacher" => $teacher,
			"concertmaster" => $concertmaster,
			"school" => $school,
			"timeCompositions" => $timeCompositions
		],
	
	[
		'%d', 
		'%d', 
		'%s', 
		'%s', 
		'%s', 
		'%s', 
		'%s', 
		'%s',
		'%s', 
		'%d',
		'%s', 
		'%d',
		'%s',
		'%s',
		'%s',
		'%d'
	  ]
);

	$competitor_id =  $wpdb->insert_id;
	$specialty_id = $_POST['specialty'];

	$wpdb->insert(
		"wp_gma_specialty_for_competitor",
		[
			"competitor_id" => $competitor_id,
			"specialty_id" => $specialty_id
		],
		
		['%d', '%d']
	);

	$nomination_id = $_POST['nomination'];

	$wpdb->insert(
		"wp_gma_nomination_for_competitor",
		[
			"competitor_id" => $competitor_id,
			"nomination_id" => $nomination_id
		],
		
		['%d', '%d']
	);
	

		if ( isset($_POST['source']) ) {
		$type_content = 0; //Тип контента url
		$source = htmlspecialchars( $_POST['source'] );
		
		$wpdb->insert(
			"wp_gma_competitor_content",
			[
			"source" => $source, 
			"type" => $type_content,
			"competitor_id" => $competitor_id
		],
		
			['%s', '%d', '%d']
			);
	}


	
	if($_FILES['specialtyFile']['name'] && $_FILES['specialtyFile']['error'] == 0){ // Проверяем, загрузил ли пользователь файл
		
		$upload_dir = wp_get_upload_dir();

		$dir = $upload_dir['basedir'] . "/gma-plugin/specialty-files/gma_id_$competition_id/specialty_id_$specialty_id/";

		if(!is_dir($dir)) wp_mkdir_p($dir);
	
		$fileInfo = new SplFileInfo($_FILES['specialtyFile']['name']);
		$new_file_name = $competitor_id . '.' . $fileInfo->getExtension();
		$destiation_dir = $dir . $new_file_name; // Директория для размещения файла
		move_uploaded_file($_FILES['specialtyFile']['tmp_name'], $destiation_dir ); // Перемещаем файл в желаемую директорию
		echo 'File Uploaded'; // Оповещаем пользователя об успешной загрузке файла

		$type_content = 1; //Тип контента путь
		
		$source = $upload_dir['baseurl'] . "/gma-plugin/specialty-files/gma_id_$competition_id/specialty_id_$specialty_id/" . $new_file_name;
	
		// $find_h = '#^http(s)?://#';
		// $find_w = '/^www\./';
		// $replace = '';
		// $source = preg_replace( $find_h, $replace, $source );
		// $source = preg_replace( $find_w, $replace, $source );


		// echo $source;
		$wpdb->insert(
			"wp_gma_competitor_content",
			[
			"source" => $source, //надо решить вопрос с сохранением ссылки.
			"type" => $type_content,
			"competitor_id" => $competitor_id
			],
		
			['%s', '%d', '%d']
			);

	}
		else{
		// echo 'No File Uploaded'; // Оповещаем пользователя о том, что файл не был загружен
		}

		// $compositionsArray = json_decode($_POST['compositions']);


		

		// print_r($_POST);
		// echo('<br>');
		// echo ($address);
		// var_dump($_POST);
		// echo($userId);

	exit;
}

