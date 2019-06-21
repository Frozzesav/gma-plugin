<?php 
add_action('wp_ajax_setDataJury', 'set_data_jury_func');

function set_data_jury_func()
{ 
  
 global $wpdb;
 global	$current_user;
 $current_user_id = $current_user->id;

 $competitor_id =  $_POST['competitorId'];
 $competition_id =  $_POST['competitionId'];

$current_jury_id = $wpdb->get_var( "SELECT id FROM `wp_gma_jury` WHERE user_id = $current_user_id" );
$score = $_POST['score'];

$wpdb->insert(
	'wp_gma_scores',
  array( 
    'competitor_id' => $competitor_id,  
    'competition_id' => $competition_id,
    'jury_id' => $current_jury_id,
    'score' => $score
  ),
	array( '%d', '%d', '%d','%f' )
 );

print_r($_POST);
// echo $current_jury_id;
// echo '<br>';
// echo $current_user_id;


  exit;
}




