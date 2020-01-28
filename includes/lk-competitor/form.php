<?php
$userdata;




// function AutoLogin()
// {
// 	if ( is_wp_error( $user_id ) ) {
// 		echo $user_id->get_error_message();
// 	}
// 	else {
// 		echo 'Юзер создан.';
// 		$user_data = array();
// 		$user_data['user_login'] = $user_login;
// 		$user_data['user_password'] = $random_password;
// 		$user_data['remember'] = true;

// 		$user = wp_signon( $user_data, false );
// 	}
// }

function SendToEmailDataNewUser()
{
	global $userdata;
	print_r($userdata['user_pass']);
	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
	$message = "Здравствуйте! Вы получили это сообщение, т.к. заполнили заявку на сайте международного конкурса Grand Musi Art" . "\r\n";
	$message .= sprintf(__('Username: %s'), $userdata['user_login']) . "\r\n";
	$message .= sprintf(__('Password: %s'), $userdata['user_pass']) . "\r\n";
	$message .= "https://music-competition.ru/login/" . "\r\n";
	wp_mail(
		$userdata['user_email'],
		sprintf(__('[%s] Your username and password'), $blogname),
		$message,
		'',
		array(WP_CONTENT_DIR . '/uploads/gma-plugin/gma-VIII.pdf')
		);
}


add_action( 'wp_enqueue_scripts', 'newMusician' );
add_action('wp_enqueue_scripts', 'test_ajax', 99);
add_shortcode('gma_application_form', 'gma_application_form_func');

function gma_application_form_func(){

	if ( is_user_logged_in() ) {
		global	$current_user;
		get_currentuserinfo();
		echo "Здравствуйте, <b>" . $current_user->display_name . "!</b>"; 
		echo "<br />";
	} 

?>


<div id="gmaApllicationFormContainer">

<form id="newCompetitorForm" enctype="multipart/form-data" method="post">
<!-- <form enctype="multipart/form-data" action="<?php echo plugins_url('gma-plugin/includes/lk-competitor/form-actions/new-competitor.php') ?>" method="post"> -->

<b>Выберите конкурс</b><br />
		<select id="competition" name="competition" size="1" class="required">
		<option value="0" id="0" disabled selected>--- Выберите конкурс ---</option>

	<?php
			$getCurrentCompetitions = getCurrentCompetitions();

			// Для дебага PHP на старнице
			// function console_log( $getCurrentCompetitions ){
			// 	echo '<script>';
			// 	echo 'console.log('. json_encode( $data ) .')';
			// 	echo '</script>';
			//   }

		 foreach ($getCurrentCompetitions as $currentCompetition):?>
		<?php 
				$id = $currentCompetition['id'];
				$header = $currentCompetition['header'];
				$description = $currentCompetition['description'];
				$name = $currentCompetition['name'];

				$fromDate = $currentCompetition['fromDate']; 
				$toDate = $currentCompetition['toDate'];
				$beforeStart = $currentCompetition['beforeStart'];
				$fromDate = date( 'd-m-Y', strtotime($fromDate) ); 
				$toDate = date( 'd-m-Y', strtotime($toDate) ); 
				$beforeStart = date( 'd-m-Y', strtotime($beforeStart) ); 
		?>

			<option value="<?php echo $id; ?>">
				<?php echo $name . " – " . $header . " (Заявки принимаются до $fromDate )"; ?>
			</option> 

	<?php endforeach; ?>		
		</select><br />
		
	<div id='containerSpecialties'>
	<b>Специальность</b><br />

		<select id="specialties" name="specialty" size="1" class="required">
		<option value="0" disabled="" selected="">Выберите вашу специальность</option>

		<?php
		
		if($getCurrentCompetitions != null){$getCurrentSpecialties = getCurrentSpecialties();}
		
		 foreach ($getCurrentSpecialties as $currentSpecialty):
			$id = $currentSpecialty['id']; 
		 	$specialty = $currentSpecialty['name'];?>
	
		<option value="<?php echo $id; ?>" name="<?php echo $specialty; ?>">
	<?php echo ($specialty); ?>
		</option>
	<?php endforeach; ?>		

		</select> <br />
	
	<div id="subSpecialtiesContainer" style="display:none">
	<b>Доп.категория специальности</b><br />

<select name="subSpecialty" id="subSpecialties" size="1" class="required">
<option value="0" disabled selected>Выберите вашу Доп.специальность</option>

<?php $getCurrentSubSpecialties = getCurrentSubSpecialties();
 foreach ($getCurrentSubSpecialties as $currentSubSpecialty):
	$id = $currentSubSpecialty['id']; 
	$parent_id = $currentSubSpecialty['parent_id']; 
	$subSpecialty = $currentSubSpecialty['name'];?>

	<option style="display:none" value="<?php echo $id; ?>" data-parent-id="<?php echo $parent_id; ?>" name="<?php echo $subSpecialty; ?>">
	<?php echo ($subSpecialty); ?>
	</option>
	<?php endforeach; ?>
	</select> <br />
	
</div>

</div>
	
	<div id="nominationContainer">
	<b>Номинация</b><br />
	
<select name="nomination" id="nomination" size="1">
		<option value="0" disabled selected>Выберите вашу номинацию</option>
		
		<?php $getCurrentNominations = getCurrentNominations();
		 foreach ($getCurrentNominations as $currentNomination):
			
			$id = $currentNomination['id']; 
		 	$nomination = $currentNomination['name'];?>
	
		<option value="<?php echo $id; ?>" style="display:none">
	<?php echo ($nomination); ?>
		</option>
	<?php endforeach; ?>	

</select><br />
	</div>
<b>Участник</b><br />
<span id='chooseMusicianContainer'>

		<select name="musician" id="musicians" size="1">
			<option value="0" disabled selected>---Выберите участника---</option>

		<?php
		$musicians_of_user = musicians_of_user();
		 foreach ($musicians_of_user as $musician):?>

<option value="<?php echo $musician['id'] ?>"><?php echo $musician['name'] ?></option> 

		<?php endforeach; ?>		

		</select> или 
		 </span>
<a href="javascript: addNewMusician()" id="newMusician">добавьте нового участника</a>
<div id="new-musician" style="display:none">
<h3>Добавьте нового участника конкурса</h3>
	<div id='soloOrNotContainer'>
		<select name="soloOrNot" id="soloOrNot" onchange="changeNomination(value)" >
			<option value="0" selected disabled>---Солист или нет?---</option>
			<option value="solo">Добавить солиста</option>	
			<option value="ensemble">Добавить ансамбль или коллектив</option>	
		</select>
	</div>
<div id="solo" style="display:none" class="newMusicianData">	
	<b>Участник</b><br />
		Фамилия <br />
		<input type="text" name="last_name" id="last_name"><br />
		Имя <br /> 
		<input type="text" name="first_name" id="first_name"><br />
		Отчество <br />
		<input type="text" name="father_name" id="father_name" ><br />
	
		<b>Дата рождения:</b><br />
	<input type="date" name="birthday" id="birthday">
</div>

<div id='ensemble' style="display:none" class="newMusicianData"> 
	<b>Название коллектива</b><br />
	<input type="text" name="ensemble_name" id="ensemble_name" placeholder="Виртуозы Москвы">
	<br /><span style="font-size:14">!Состав при желании можно указать при заполнении заявки.</span>
</div>

	Данные нового участника
</div>

<div id="ageCategory">
	<b>Возрастная категория</b><br />
	(Возрастная категория опредедяется на момент начала конкурса)<br />
		<select name="category" id="category" class="required">
			<option value="0" selected disabled>---Выберите возратную категорию---</option>
		<option value="A">
			Группа A – участники возрастом до 9 лет включительно</option>
		<option value="B">
			Группа B – участники возрастом с 10 до 12 лет включительно</option>
		<option value="C">
			Группа C – участники возрастом с 13 до 15 лет включительно</option>
		<option value="D">
			Группа D – участники возрастом с 16 до 18 лет включительно (кроме студентов Ссузов)</option>
		<option value="E">
			Группа E – учащиеся музыкальных училищ и колледжей</option>
		<option value="F">
			Группа F – учащиеся и выпускники ВУЗов</option>
		<option value="G">
			Группа G – Без возрастных ограничений</option>
		</select>
	<br />
</div>

<br />

	<label for="linkVideo"><b>Ссылка на видео. Для композиторов на выбор аудио или видео.</b></label><br />

	<input type="url" name="link" id="linkVideo" class="required"><br />

<div id="filesContainer" style="display:none" >
	<label for="compositionFile"><b>Файл для композитора</b></label><br />
	<input type="file" name="specialtyFile" id="specialtyFile">
</div>

<div id="competitorProgram">
	<b>Программа</b><br />
		<div id='compositionDiv1'>
			1. <input type="text" name="composition[]" id="composition1" >
		</div>
		
	</div>
		 <br />
	<div id='addNewComposition'><input type='button' value='Добавить произведение' id='addComposition'></div>

	<label for="timeCompositions"><b>Общее время произведений:</b></label><br />
	<input type="number" min="1" max="15" name="timeCompositions" id="timeCompositions" > <span id="minutes"> минут</span><br />

	<hr>


			
	<label for="user_email"><b>Ваш e-mail</b></label><br />
	<input type="text" name="Ваш e-mail" id="user_email" value="<?php echo wp_get_current_user()->user_email; ?>"><br />
	
	<label for="telephone"><b>Телефон</b></label><br />
	
	<input type="tel" name="telephone" id="telephone" class="required"><br /><br />

	<b>Как получить диплом?</b><br />

	<select name="getDiploma" id="getDiploma" onchange="getDiplomaFunc(value)" class="required">
			<option value="0" selected disabled>--- Как вы хотите получить диплом? ---</option>
			<option value="byEmail">Электронный вариант</option>
			<option value="inMoscow">Заберу в Москве</option>
			<option value="byPost">Почтой России(+250 руб. ко взносу)</option>
	</select> <br />
	Страна <br />
	<input type="text" name="country" id="country"><br />
	Город (обязательно): <br /> 
	<input type="text" name="city" id="city"><br />
	
<div id="adress" style="display:none">
	Индекс (обязательно): <br />
	<input type="text" name="postIndex" id="postIndex"><br />
	Улица (обязательно): <br />
	<input type="text" name="postStreet" id="postStreet"><br />
	Дом <br />
	<input type="text" name="houseNumber" id=houseNumber><br />
	Квартира <br />
	<input type="text" name="flatNumber" id="flatNumber">
</div>

	<hr>
	<span id="showTeacherForm" class="showSwitcher">Преподаватель:</span><br />
	<div id=teacher  style="display:none">

	Фамилия<br />

	<input type="text" id="teacher_lastname" name="teacher_lastname" placeholder="Необязательно"><br />

	Имя<br />

	<input type="text" id="teacher_name" name="teacher_name" placeholder="Необязательно"><br />

	Отчество<br />
	<input type="text" id="teacher_surname" name="teacher_surname" placeholder="Необязательно"><br />
</div>

<span id="showConcertmasterForm" class="showSwitcher">Концертмейстер:</span><br />
<div id='concertmaster' style="display:none">

	Фамилия<br />

	<input type="text" id="concertmaster_lastname" name="concertmaster_lastname" placeholder="Необязательно"><br />

	Имя<br />

	<input type="text" id="concertmaster_name" name="concertmaster_name" placeholder="Необязательно"><br />

	Отчество<br />

	<input type="text" id="concertmaster_surname" name="concertmaster_surname" placeholder="Необязательно"><br />
</div>
	<br />

<div>

<label for="school"><b>Учебное заведение</b></label><br />

	<input type="text" name="school" id="school" placeholder="Необязательно"><br />
</div>

<div>
	<label for="comment"><b>Комментарий</b></label><br />
	<textarea name="comment" id="comment"  rows="10"></textarea>
</div>

<div id="center"><br /><input type="submit" id="submit" value="Отправить" name="">

<div id="loader" style="width:100%; height:100%; position:relative; margin:auto; display:none; opacity:0.5">
	<img src="<?php global $plugin_url; echo $plugin_url . "/includes/lk-competitor/img/ajax-loader.gif" ?>">
</div>

</div>
</form>


<div id="oneMoreCompetitorContainer" style="display:none">
	<a href="javascript: oneMoreCompetitor();" id="oneMoreCompetitor"   target="_blank" rel="noopener noreferrer">Заполнить еще одну заявку?</a>
	<br />
	<a href="/lk-competitor"target="_blank" rel="noopener noreferrer">Перейти в личный кабинет</a>
</div>
</div>

<?php

}