<?php 
//доБАВИТЬ В ЗАПРОС оценки если Current user JURY

add_action('wp_ajax_getResults', 'get_results_func');
add_action('wp_ajax_getDiploma', 'get_diploma_func');
add_action('wp_ajax_getResultsDetails', 'get_results_details_func');


function get_diploma_func()
{
  global $wpdb;
  global	$current_user;


  $sql = "
  
  SELECT C.id AS id,MU.name AS name,concertmaster,teacher 
    
    FROM wp_gma_competitor C

LEFT JOIN wp_gma_musicians_of_user MU 
        ON MU.id = C.musician_id

    WHERE  
      C.user_id = $current_user->id;

  ";

  $query = $wpdb->get_results($sql);

    // $upload_dir = wp_get_upload_dir();

		// $diploma = "/wp-content/uploads/gma-plugin/result-files/gma_id_7/specialty_id_1/239_blag2.jpg";

  echo json_encode($query);

  exit;
}

function get_results_details_func()
{
  global $wpdb;
  global	$current_user;

  $competition_id = $_POST['competitionId'];
  $specialty_id = $_POST['specialty_id'];

 
  $sql1 = "
     
  CREATE TEMPORARY TABLE average 
	SELECT competitor_id,
       	   AVG(score) AS average 
  	  FROM wp_gma_scores 
   	 WHERE competition_id = 7
	 GROUP 
     	BY competitor_id;
       ";

    $sql2 = "
     CREATE TEMPORARY TABLE scores
     SELECT S.competitor_id, AVG(S.score) as average 
       FROM wp_gma_scores S
       JOIN average A
         ON A.competitor_id = S.competitor_id
         
      WHERE S.competition_id = 7
        AND ABS(A.average - S.score) < 3
      GROUP  
         BY S.competitor_id;
            ";

    $getCurrentJuries = "
    SELECT J.id as id, 
           fio,
           score
      FROM wp_gma_jury J
        
      JOIN wp_gma_scores S
        ON S.jury_id = J.id

      JOIN wp_gma_competitor C
        ON C.id = S.competitor_id 
          
     WHERE J.competition_id = 7
        AND C.user_id = $current_user->id
        AND C.isConfirm = 1 
     GROUP BY J.id
     ";
  
  $juries = $wpdb->get_results($getCurrentJuries);
  
  $sql_select =  "
  
      SELECT MU.name AS Участник,
             TRUNCATE(IFNULL(S.average, 0),2) as 'Средний балл', 
             R.name AS Результат,
             CASE 
              WHEN S_MAIN.name IS NOT NULL THEN CONCAT(S_MAIN.name, ' (', S_CURRENT.name, ')') 
              ELSE CONCAT(S_CURRENT.name,  ' (', N.name, ')') END AS Специальность,
             C.age_category AS Категория
  
      ";
  
             $sql_from = "
             
        FROM wp_gma_competitor C
        
        JOIN wp_gma_musicians_of_user MU
          ON MU.id = C.musician_id
        
        JOIN wp_gma_specialty_for_competitor SC
          ON SC.competitor_id = C.id

        LEFT JOIN scores S
          ON S.competitor_id = C.id 

        LEFT JOIN wp_gma_results R
          ON R.id = C.result
  
        JOIN wp_gma_specialty S_CURRENT
          ON S_CURRENT.id = SC.specialty_id
      
  LEFT JOIN wp_gma_specialty S_MAIN
         ON S_MAIN.id = S_CURRENT.parent_id
  
  LEFT JOIN wp_gma_nomination_for_competitor NC
        ON NC.competitor_id = C.id
  
  LEFT JOIN wp_gma_nomination N
         ON N.id = NC.nomination_id
  
        ";
        
        foreach ($juries as $row) {
          $sql_select .= ", SJ{$row->id}.comments AS '$row->fio'";
  
          $sql_from .= "
  
          LEFT JOIN wp_gma_scores SJ{$row->id}
            ON SJ{$row->id}.competitor_id = C.id
              AND SJ{$row->id}.jury_id = {$row->id}
          
          ";
  }
  
  $sql_where = "WHERE 
                C.user_id = $current_user->id
                AND C.isConfirm = 1 
                AND C.competition_id = 7
                
                  ORDER BY N.id, Категория, Average desc
                  ";
  
  $globalSql = $sql_select . ' ' . $sql_from . ' ' . $sql_where;
  if ( is_user_logged_in() ) {
    $wpdb->query($sql1);
    $wpdb->query($sql2);
  
    $queryArray = $wpdb->get_results($globalSql, ARRAY_A);
    echo json_encode($queryArray);
  }

  // print_r($globalSql);
  // echo $current_user->id;

  exit;
}

function get_results_func()
{
    $competition_id = $_POST['competitionId'];
    $specialty_id = $_POST['specialtyId'];
    
    global $wpdb;
    global	$current_user;
    $current_user_id = $current_user->id;



    $current_jury_id = $wpdb->get_var( "SELECT id FROM `wp_gma_jury` WHERE user_id = $current_user_id" );

	$sql = "
     
    SELECT C.id AS id,
          R.name AS result,
           MU.name AS name,
           CCU.source AS sourceUrl,
           CCF.source AS sourceFile,
           CASE 
            WHEN S_MAIN.name IS NOT NULL THEN CONCAT(S_MAIN.name, ' (', S_CURRENT.name, ')') 
            ELSE CONCAT(S_CURRENT.name,  ' (', N.name, ')') END AS specialty,
           C.compositions AS compositions,
           C.city AS city,
           C.school AS school,
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

 LEFT JOIN wp_gma_nomination_for_competitor NC
        ON NC.competitor_id = C.id

 LEFT JOIN wp_gma_nomination N
        ON N.id = NC.nomination_id

      JOIN wp_gma_competitor_content CCU 
        ON CCU.competitor_id = C.id 
        AND CCU.type = 0

LEFT JOIN wp_gma_competitor_content CCF 
        ON CCF.competitor_id = C.id 
        AND CCF.type = 1

LEFT JOIN wp_gma_results R
       ON R.id = C.result

     WHERE C.competition_id = $competition_id
       AND C.isConfirm = 1
       AND IFNULL(S_MAIN.id, S_CURRENT.id) = $specialty_id

    ORDER BY specialty, N.id, ageCategory     
       ";
    
    $queryArray = $wpdb->get_results($sql, ARRAY_A);

    
    // if ( current_user_can ('jury') ) {
    //   echo  json_encode("Вы член жюри"); 
    // } else echo '0';
    
    
    echo json_encode($queryArray);
    exit;
}

  