<?php 

add_action('wp_ajax_getResultsJury', 'get_results_jury_func');
function get_results_jury_func()
{
    $competition_id = $_POST['competitionId'];
    $specialty_id = $_POST['specialtyId'];
    
    global $wpdb;
    global	$current_user;
    $current_user_id = $current_user->id;



    $current_jury_id = $wpdb->get_var( "SELECT id FROM `wp_gma_jury` WHERE user_id = $current_user_id" );

	$sql = "
     
    SELECT C.id AS id,
           IFNULL (S.score, '') AS score,
           IFNULL(S.comments, '') AS comment,
           MU.name AS name,
           CCU.source AS sourceUrl,
           CCF.source AS sourceFile,
           IFNULL(S_MAIN.name, S_CURRENT.name) AS specialty,
           C.compositions AS compositions,
           C.city AS city,
           C.telephone AS tel,
           C.age_category AS ageCategory
    
      FROM wp_gma_competitor C

      JOIN wp_gma_musicians_of_user MU
        ON MU.id = C.musician_id

      JOIN wp_gma_specialty_for_competitor SC
        ON SC.competitor_id = C.id

      JOIN wp_gma_specialty S_CURRENT
        ON S_CURRENT.id = SC.specialty_id
      
 LEFT JOIN wp_gma_specialty S_MAIN
        ON S_MAIN.id = S_CURRENT.parent_id

      JOIN wp_gma_competitor_content CCU 
        ON CCU.competitor_id = C.id 
        AND CCU.type = 0

LEFT JOIN wp_gma_competitor_content CCF 
        ON CCF.competitor_id = C.id 
        AND CCF.type = 1

LEFT JOIN wp_gma_scores S
        ON S.competitor_id = C.id
      AND S.jury_id = $current_jury_id


    
     WHERE C.competition_id = $competition_id
       AND IFNULL(S_MAIN.id, S_CURRENT.id) = $specialty_id
        
       GROUP BY name
       ";
    
    $queryArray = $wpdb->get_results($sql, ARRAY_A);

    
    // if ( current_user_can ('jury') ) {
    //   echo  json_encode("Вы член жюри"); 
    // } else echo '0';
    
    
    echo json_encode($queryArray);
    exit;
}

