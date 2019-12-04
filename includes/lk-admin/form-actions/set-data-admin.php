<?php 
add_action('wp_ajax_setDataAdmin', 'set_data_admin_func');
// add_action('wp_ajax_getDataJury', 'get_data_jury_func');

function set_data_admin_func()
{ 
  $_POST      = array_map('stripslashes_deep', $_POST);
 
  global $wpdb;
  global	$current_user;
 
  $competitor_id =  $_POST['competitorId'];
  $result =  $_POST['result'];

  
    $wpdb->update(
      'wp_gma_competitor',
      array( 
        'result' => $result,
      ),
      array(
        'id' => $competitor_id,  
      ),
      array( 
        '%d'
      )
    );
    
    echo "Оценка обновлена"; 

print_r ($_POST);
  
  exit;
}
 
