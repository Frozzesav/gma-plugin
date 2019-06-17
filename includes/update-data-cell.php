<?php 

header('Content-type: application/json');

define('SHORTINIT', true);

// require_once($_SERVER['DOCUMENT_ROOT'.'/wp-load.php'])

require_once('../../../../wp-config.php');



function updateTest($id,$score) {

	global $wpdb;

	$data = array ('score'=>$score);

	$where = array ('id'=>$id);		  	

	return $wpdb->update('wp_cf7_test',$data,$where);

}



$score = -1;

if (isset($_POST['score'])) {

	$score = $_POST['score'];

}



$id = -1;



if (isset($_POST['id'])) {

	$id = $_POST['id'];

}

if ($id != -1 && $score != -1) {
	if ($score > 0 && $score <= 25 ) {
		$result = updateTest($id, $score);
		if ($result > 0) {
			$data = [ 'Данные сохранены' ];
		} else {
			$data = [ 'Не удалось сохранить' ];
		}
	} else {
		$data = [ 'Оценка должна быть от 0 до 25' ];
	}
} else {
	$data = [ 'Нет данных для обновления' ];
}

echo json_encode( $data );



