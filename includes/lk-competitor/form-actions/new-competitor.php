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

$userdata;
$result = array();

// Отправлять подтверждение регистрации завки иреквизиты для оплаты. Вписать до какой даты нужно оплатить.
// Отправлять письмо всем пользователям. Если зарегитрированный, то не вписывать логин и пароль. Можно только email. 
// Для новых юзеров в письме: логин, пароль и реквизиты с подтверждением. Для уже созданных только реквизиты и ссылку на страницу логина.
function SendToEmailDataNewUser()
{
	global $userdata;
	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
	$message = "Здравствуйте! Вы получили это сообщение, т.к. заполнили заявку на сайте международного конкурса Grand Music Art" . "\r\n";
	$message .= sprintf(__('Username: %s'), $userdata['user_login']) . "\r\n";
	$message .= sprintf(__('Пароль: %s'), $userdata['user_pass']) . "\r\n";
	$message .= "https://music-competition.ru/login/" . "\r\n";

	// $attachments = array(WP_CONTENT_DIR . '/uploads/attach.zip');
	$headers = 'From: Организационный комитет Grand music art <gma@music-competition.ru>' . "\r\n";

	wp_mail(
		$userdata['user_email'],
		sprintf(__('[%s] Ваш логин и пароль'), $blogname),
		$message,
		$headers,
		// array(WP_CONTENT_DIR . '/uploads/gma-plugin/gma-VIII.pdf')
		);
}

function SendToEmailBill($link, $competitorName, $ageCategory, $address, $city, $telephone, $specialty, $nomination, $program, $timeCompositions, $school, $getDiploma, $teacher, $concertmaster)
{
	global $userdata;
	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
	$message = "Спасибо! Рады вашему участию." . "\r\n\r\n";
	$message .= "$link\r\n";
	$message .= "$competitorName\r\n";
	$message .= "$ageCategory\r\n";
	if($address != null) {$message .= "$address\r\n";}
	$message .= "$city\r\n";
	$message .= "$telephone\r\n";
	$message .= "$specialty\r\n";
	$message .= "$nomination\r\n";
	$message .= "$program\r\n";
	$message .= "$timeCompositions\r\n";
	if($school != null){$message .= "$school\r\n";}
	$message .= "$getDiploma\r\n";
	if($teacher != null){$message .= "$teacher\r\n";}
	if($concertmaster != null){$message .= "$concertmaster\r\n";}
	$message .="
	Просим оплатить вступительный взнос (2000 рублей) до 17.02.
	
	Варианты оплаты:
		1. Перевод на карту: 5536 9137 8477 6219
	
		 2. Оплата картой по ссылке: https://money.yandex.ru/to/410013426471715
	После оплаты нужно прислать подтверждение в виде чека или скриншота экрана, чтобы мы видели дату и время платежа.
	В письме с оплатой напишите пожалуйста фамилию участника, за которого она произведена.
	
	​​​​​​​ 	
	P.S. Если эти способы оплаты для вас не удобны, напишите нам.\r\n\r\n";
	$message .= "Личный кабинет: https://music-competition.ru/login/" . "\r\n";

	// $attachments = array(WP_CONTENT_DIR . '/uploads/attach.zip');
	$headers = 'From: Организационный комитет Grand music art <gma@music-competition.ru>' . "\r\n";

	wp_mail(
		$userdata['user_email'],
		sprintf(__('[%s] Ваш логин и пароль'), $blogname),
		$message,
		$headers,
		// array(WP_CONTENT_DIR . '/uploads/gma-plugin/gma-VIII.pdf')
		);
}

function createNewUser($user_email, $user_pass)
{
	global $result;

	global $userdata;
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
		$result['userCreated'] = 0; //Юзер уже создан. Заново не создаётся  И не логинется  

	}
	else if( !is_wp_error( $user_id ) &&  !is_user_logged_in() ) {
		
		$user_data = array();
		$user_data['user_login'] = $user_email;
		$user_data['user_password'] = $user_pass;
		$user_data['remember'] = true;

		$userLogin = wp_signon($user_data, false);
		
		$result['userCreated'] = 1; //Юзер создан. 

		SendToEmailDataNewUser();

		// echo 'Юзер создан.';
	}
}

function testAjax()
{
	global $result;
	global $wpdb;	
	global $current_user; // пото нужно ID залогиненого юзера.

	// ПОка веременно по email нахожу Id.
	$name_musician;	
	$user_email = $_POST['user_email'];
	createNewUser($user_email, "pass");
	
	$user = get_user_by( 'email', $_POST['user_email'] );
	$userId = $user->ID;
	$result['userName'] = $user->display_name; 

	
	// $userId = get_current_user_id();
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
		
		$name_musician = $_POST['savedName'];
		$name_musician = stripslashes($name_musician);
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
			"isConfirm" => 1,
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
		$type_content = 0; //Тип контента url Если 1, то это файл. Для композиторов.
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
		// echo 'File Uploaded'; // Оповещаем пользователя об успешной загрузке файла

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

		
		// SendToEmailBill($source,$name_musician,$age_category,$address,$city,$telephone,$specialty_id,$nomination_id,$compositions,$timeCompositions,$school,$getDiploma,$teacher,$concertmaster);
		// AddCompetitorToBitrix($user_email,$link,$name_musician,$age_category,$address,$city,$telephone,$specialty_id,$nomination_id,$program,$timeCompositions,$school,$getDiploma,$teacher,$concertmaster);

		// print_r($_POST);
		// echo('<br>');
		// echo ($address);
		// var_dump($_POST);
		// echo($userId);
		// print_r($result);
		echo json_encode($result);
		wp_die();
	exit;
}

// Потом заменить со всеми параметрами.

function AddCompetitorToBitrix($user_email,$link, $competitorName, $ageCategory, $address, $city, $telephone, $specialty, $nomination, $program, $timeCompositions, $school, $getDiploma, $teacher, $concertmaster)
{

	//подключение к серверу CRM
	define('CRM_HOST', 'gma.bitrix24.ru'); // Ваш домен CRM системы
	define('CRM_PORT', '443'); // Порт сервера CRM. Установлен по умолчанию
	define('CRM_PATH', '/crm/configs/import/lead.php'); // Путь к компоненту lead.rest
	//авторизация в CRM
	define('CRM_LOGIN', 'frozzesav@gmail.com'); // Логин пользователя Вашей CRM по управлению лидами
	define('CRM_PASSWORD', 'a1b2c4d3'); // Пароль пользователя Вашей CRM по управлению лидами
	//перехват данных из Contact Form 7
	$title = "Новый участник";

	//далее мы перехватывает введенные данные в Contact Form 7
	$firstName = $competitorName ; //перехватываем поле [your-name]
	$email = $user_email; //перехватываем поле [your-message]
	// $birthday = ;
	// $specialty = ;
	// $nomination = ;
	$numberCompetition ="VIII" ;
	// $vocal_type = ;
	// $category = ;
	// $members = ;
	// $program = ;

	// $getDiploma = ;
	
	$coutry = $country;
	$city = $city;
	// $index = ;
	// $street = ;
	// $dom = ;
	// $appartement= ;

	$link = $link ;
	$school = $school;
	// $surname_t = ;
	// $your_name_t = ;
	// $middle_name_t = ;
	$phone = $telephone;
	$concertmaster =$concertmaster ;
	// $comments = ;
		
	//сопостановление полей Bitrix24 с полученными данными из Contact Form 7
	$postData = array(
		'UF_CRM_1543516558' => $title,
		'TITLE' => $firstName, // Установить значение свое значение
		'NAME' => $firstName,
		// 'UF_CRM_1541452314' => $numberCompetition,
		// 'UF_CRM_1541512410' => $category,
		// 'UF_CRM_1541512870' => $birthday,
		// 'UF_CRM_1541452397' => $link,
		// 'UF_CRM_1541433862957' => $specialty, 
		// 'UF_CRM_1541509433' => $nomination,
		'EMAIL_WORK' => $email,
		// 'PHONE_MOBILE' => $phone,

			// 'UF_CRM_1541511456' => $getDiploma,
			// // Адрес
			// 'ADDRESS_COUNTRY' => $coutry,
			// 'ADDRESS_CITY' => $city,
			// 'ADDRESS_POSTAL_CODE' => $index,
			// 'ADDRESS' => $street .', ' . $dom,
			// 'ADDRESS_2' => $appartement,
			// //Преподаватель
			// 'UF_CRM_1541509757' => $surname_t,
			// 'UF_CRM_1541509772' => $your_name_t,
			// 'UF_CRM_1541509789' => $middle_name_t,
			// //Концертмейстер
			// 'UF_CRM_1541509857' => $concertmaster,
			// //Учебное заведение
			// 'UF_CRM_1541509883' => $school,
	);
	//передача данных из Contact Form 7 в Bitrix24
	if (defined('CRM_AUTH')) {
		$postData['AUTH'] = CRM_AUTH;
		} else {
		$postData['LOGIN'] = CRM_LOGIN;
		$postData['PASSWORD'] = CRM_PASSWORD;
		}
	$fp = fsockopen("ssl://".CRM_HOST, CRM_PORT, $errno, $errstr, 30);
	if ($fp) {
		$strPostData = '';
	foreach ($postData as $key => $value)
	$strPostData .= ($strPostData == '' ? '' : '&').$key.'='.urlencode($value);
	$str = "POST ".CRM_PATH." HTTP/1.0\r\n";
	$str .= "Host: ".CRM_HOST."\r\n";
	$str .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$str .= "Content-Length: ".strlen($strPostData)."\r\n";
	$str .= "Connection: close\r\n\r\n";
	$str .= $strPostData;
	fwrite($fp, $str);
	$result = '';
	while (!feof($fp))
	{
		$result .= fgets($fp, 128);
	}
	fclose($fp);
	$response = explode("\r\n\r\n", $result);
	$output = '<pre>'.print_r($response[1], 1).'</pre>';
	} else {
	echo 'Connection Failed! '.$errstr.' ('.$errno.')';
	}
}


