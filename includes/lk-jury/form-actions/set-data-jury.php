<?php 
add_action('wp_ajax_setDataJury', 'set_data_jury_func');
add_action('wp_ajax_getDataJury', 'get_data_jury_func');

function set_data_jury_func()
{ 
  global $wpdb;
  global	$current_user;
  $current_user_id = $current_user->id;
 
  $competitor_id =  $_POST['competitorId'];
  $competition_id =  $_POST['competitionId'];
  

$current_jury_id = $wpdb->get_var( "SELECT id FROM `wp_gma_jury` WHERE user_id = $current_user_id" );

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
  $oldComment =  $competitor_results['comment'];

  if ($oldScore != $score) {
    $score;
  } else {
    $score = $oldScore;
  }
  
  if ($oldComment != $comment) {
    $comment;
  } else {
    $comment = $oldComment;
  }
 
if( $competitor_results != NULL && $oldScore != NULL || $oldComment != NULL )
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


print_r($_POST);
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