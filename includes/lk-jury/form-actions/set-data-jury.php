<?php 
add_action('wp_ajax_setDataJury', 'set_data_jury_func');
add_action('wp_ajax_getDataJury', 'get_data_jury_func');

function set_data_jury_func()
{ 

  // $_GET       = array_map('stripslashes_deep', $_GET);
  $_POST      = array_map('stripslashes_deep', $_POST);
  // $_COOKIE    = array_map('stripslashes_deep', $_COOKIE);
  // $_SERVER    = array_map('stripslashes_deep', $_SERVER);
  // $_REQUEST   = array_map('stripslashes_deep', $_REQUEST);

  global $wpdb;
  global	$current_user;
  $current_user_id = $current_user->id;
 
  $competitor_id =  $_POST['competitorId'];
  $competition_id =  $_POST['competitionId'];
  

$current_jury_id = $wpdb->get_var( "SELECT id FROM `wp_gma_jury` WHERE user_id = $current_user_id" );

$oldData = $wpdb->get_var( "SELECT COUNT(*) FROM wp_gma_scores WHERE competitor_id = $competitor_id AND jury_id = $current_jury_id;" );


$score = $_POST['score'];
$comment = $_POST['comment'];


$competitor_results = $wpdb->get_row(
  "SELECT 
  * 
  FROM wp_gma_scores
    WHERE 
      competitor_id = $competitor_id 
  AND competition_id = $competition_id
  AND jury_id = $current_jury_id", ARRAY_A);
  
  $oldScore = $competitor_results['score'];
  $oldComment = serialize($competitor_results['comment']);

  // if ($oldScore != $score) {
  //   $score;
  // } else {
  //   $score = $oldScore;
  // }
  
  // if ($oldComment != $comment) {
  //   $comment;
  // } else {
  //   $comment = $oldComment;
  // }

  //КАк сделать проверку если не стоит еще балл или коммент??? Нужно сделать запрос, где ID jury и ID competitor нет в таблице
 
if( $oldData != 0 )
  {
    $wpdb->update(
      'wp_gma_scores',
      array( 
        'score' => $score,
        'comments' => $comment
      ),
      array(
        'competitor_id' => $competitor_id,  
        'competition_id' => $competition_id,
        'jury_id' => $current_jury_id
      ),
      array( 
        '%f',
        '%s' 
      ),
      array(
        '%d', 
        '%d', 
        '%d'
      )
    );
    
    echo "Оценка обновлена"; 
  }

  else 
  {
    $wpdb->insert(
      'wp_gma_scores',
      array( 
        'competitor_id' => $competitor_id,  
        'competition_id' => $competition_id,
        'jury_id' => $current_jury_id,
        'score' => $score,
        'comments' => $comment
      ),
      array( '%d', '%d', '%d','%f', '%s' )
     );
    echo "Оценка поставлена";
  }


var_dump($oldData);
  exit;
}


// function get_data_jury_func()
// {
//   global $wpdb;
//   global	$current_user;
//   $current_user_id = $current_user->id;
 
//   $competitor_id =  $_POST['competitorId'];
//   $competition_id =  $_POST['competitionId'];
//   $current_jury_id = $wpdb->get_var( "SELECT id FROM `wp_gma_jury` WHERE user_id = $current_user_id" );


//   $competitor_results = $wpdb->get_row(
//     "SELECT 
//     * 
//     FROM wp_gma_scores
//       WHERE 
//         competitor_id = $competitor_id 
//     AND competition_id = $competition_id
//     AND jury_id = $current_jury_id", ARRAY_A);
    
//     $oldScore = $competitor_results['score'];
//     $oldComment =  $competitor_results['comment'];

//     print_r ($_POST);
    
//   exit;

// }