<?php 

add_action('wp_ajax_getResultsAdmin', 'get_results_admin_func');
add_action('wp_ajax_getResultList', 'get_results_list');
add_action('wp_ajax_getResultsAdminForSocialPost', 'get_results_admin_for_social_post');

function get_results_admin_for_social_post()
{
    $competition_id = $_POST['competitionId'];
    $specialty_id = $_POST['specialtyId'];
    
    global $wpdb;
    global	$current_user;
    $current_user_id = $current_user->id;
  
    $current_jury_id = $wpdb->get_var( "SELECT id FROM `wp_gma_jury` WHERE user_id = $current_user_id" );

    $getCurrentJuries = "
  SELECT id, 
         fio 
    FROM wp_gma_jury J

    JOIN wp_gma_jury_specialty JS
      ON JS.jury_id = J.id
     AND JS.specialty_id = $specialty_id
        
   WHERE J.competition_id = $competition_id";

  $juries = $wpdb->get_results($getCurrentJuries);

  $sql_select =  "

    SELECT C.id AS id,
           C.result AS Результат, 
           TRUNCATE(IFNULL(S.average, 0),2) as СрБалл, 
           MU.name AS Участник,
           CASE 
            WHEN S_MAIN.name IS NOT NULL THEN CONCAT(S_MAIN.name, ' (', S_CURRENT.name, ')') 
            ELSE CONCAT(S_CURRENT.name,  ' (', N.name, ')') END AS specialty,
           C.city AS Город,
           C.telephone AS Телефон,
           C.age_category AS Категория

    ";

           $sql_from = "
           
      FROM wp_gma_competitor C
      
      JOIN wp_gma_musicians_of_user MU
      ON MU.id = C.musician_id
      AND C.competition_id = $competition_id
      
      JOIN wp_gma_specialty_for_competitor SC
      ON SC.competitor_id = C.id
      AND SC.specialty_id = $specialty_id

      LEFT JOIN scores S
      ON S.competitor_id = C.id 

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
        $sql_select .= ", SJ{$row->id}.score AS '{$row->fio} (Балл)', SJ{$row->id}.comments AS '$row->fio'";

        $sql_from .= "

        LEFT JOIN wp_gma_scores SJ{$row->id}
          ON SJ{$row->id}.competitor_id = C.id
            AND SJ{$row->id}.jury_id = {$row->id}
        
        ";
  }

  $sql_where = "WHERE C.isConfirm = 1  ORDER BY N.id, Категория, Average desc";

  $globalSql = $sql_select . ' ' . $sql_from . ' ' . $sql_where;



  	$sql1 = "
     
  CREATE TEMPORARY TABLE average 
	SELECT competitor_id,
       	   AVG(score) AS average 
  	  FROM wp_gma_scores 
   	 WHERE competition_id = $competition_id
	 GROUP 
     	BY competitor_id;
       ";

    $sql2 = "
     CREATE TEMPORARY TABLE scores
     SELECT S.competitor_id, AVG(S.score) as average 
       FROM wp_gma_scores S
       JOIN average A
         ON A.competitor_id = S.competitor_id
         
      WHERE S.competition_id = $competition_id
        AND ABS(A.average - S.score) < 3
      GROUP  
         BY S.competitor_id;
            ";

     $sql3 = "
     
  SELECT C.id AS id,
            U.user_email AS email,
            R.name AS result,
           TRUNCATE(IFNULL(S.average, 0),2) as average, 
           MU.name AS name,
           CCU.source AS sourceUrl,
           CCF.source AS sourceFile,
           CASE 
            WHEN S_MAIN.name IS NOT NULL THEN CONCAT(S_MAIN.name, ' (', S_CURRENT.name, ')') 
            ELSE CONCAT(S_CURRENT.name,  ' (', N.name, ')') END AS specialty,
           C.compositions AS compositions,
           C.city AS city,
           C.school AS school,
           C.teacher AS teacher,
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

 LEFT JOIN scores S
     ON S.competitor_id = C.id 
     
 LEFT JOIN wp_gma_results R
        ON R.id = C.result

      JOIN wp_users U
        on U.id = MU.user_id
    
     WHERE C.competition_id = $competition_id
       AND C.isConfirm = 1
       AND C.result <= 4
       AND IFNULL(S_MAIN.id, S_CURRENT.id) = $specialty_id
        
       GROUP by C.id

       ORDER BY specialty, N.id, ageCategory, C.result        

       ";

    $wpdb->query($sql1);
    $wpdb->query($sql2);

    $queryArray = $wpdb->get_results($sql3, ARRAY_A);
    
    echo json_encode($queryArray);

    exit;
}

function get_results_list()
{
  global $wpdb;
  global	$current_user;

  $sql = "SELECT * FROM wp_gma_results";

  $resultList = $wpdb->get_results($sql, OBJECT_A);

  echo  json_encode($resultList);
  exit;  
}

function get_results_admin_func()
{
    $competition_id = $_POST['competitionId'];
    $specialty_id = $_POST['specialtyId'];
    
    global $wpdb;
    global	$current_user;
    $current_user_id = $current_user->id;
  
    $current_jury_id = $wpdb->get_var( "SELECT id FROM `wp_gma_jury` WHERE user_id = $current_user_id" );

    $getCurrentJuries = "
  SELECT id, 
         fio 
    FROM wp_gma_jury J

    JOIN wp_gma_jury_specialty JS
      ON JS.jury_id = J.id
     AND JS.specialty_id = $specialty_id
        
   WHERE J.competition_id = $competition_id";

  $juries = $wpdb->get_results($getCurrentJuries);

  $sql_select =  "

    SELECT C.id AS id,
           C.result AS Результат, 
           TRUNCATE(IFNULL(S.average, 0),2) as СрБалл, 
           MU.name AS Участник,
           CASE 
            WHEN S_MAIN.name IS NOT NULL THEN CONCAT(S_MAIN.name, ' (', S_CURRENT.name, ')') 
            ELSE CONCAT(S_CURRENT.name,  ' (', N.name, ')') END AS specialty,
           C.city AS Город,
           C.telephone AS Телефон,
           C.age_category AS Категория

    ";

           $sql_from = "
           
      FROM wp_gma_competitor C
      
      JOIN wp_gma_musicians_of_user MU
      ON MU.id = C.musician_id
      AND C.competition_id = $competition_id
      
      JOIN wp_gma_specialty_for_competitor SC
      ON SC.competitor_id = C.id
      AND SC.specialty_id = $specialty_id

      LEFT JOIN scores S
      ON S.competitor_id = C.id 

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
        $sql_select .= ", SJ{$row->id}.score AS '{$row->fio} (Балл)', SJ{$row->id}.comments AS '$row->fio'";

        $sql_from .= "

        LEFT JOIN wp_gma_scores SJ{$row->id}
          ON SJ{$row->id}.competitor_id = C.id
            AND SJ{$row->id}.jury_id = {$row->id}
        
        ";
  }

  $sql_where = "WHERE C.isConfirm = 1 ORDER BY N.id, Категория, Average desc";

  $globalSql = $sql_select . ' ' . $sql_from . ' ' . $sql_where;



  	$sql1 = "
     
  CREATE TEMPORARY TABLE average 
	SELECT competitor_id,
       	   AVG(score) AS average 
  	  FROM wp_gma_scores 
   	 WHERE competition_id = $competition_id
	 GROUP 
     	BY competitor_id;
       ";

    $sql2 = "
     CREATE TEMPORARY TABLE scores
     SELECT S.competitor_id, AVG(S.score) as average 
       FROM wp_gma_scores S
       JOIN average A
         ON A.competitor_id = S.competitor_id
         
      WHERE S.competition_id = $competition_id
        AND ABS(A.average - S.score) < 3
      GROUP  
         BY S.competitor_id;
            ";

     $sql3 = "
     
  SELECT C.id AS id,
            U.user_email AS email,
            R.name AS result,
           TRUNCATE(IFNULL(S.average, 0),2) as average, 
           MU.name AS name,
           CCU.source AS sourceUrl,
           CCF.source AS sourceFile,
           CASE 
            WHEN S_MAIN.name IS NOT NULL THEN CONCAT(S_MAIN.name, ' (', S_CURRENT.name, ')') 
            ELSE CONCAT(S_CURRENT.name,  ' (', N.name, ')') END AS specialty,
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

 LEFT JOIN scores S
     ON S.competitor_id = C.id 
     
 LEFT JOIN wp_gma_results R
        ON R.id = C.result

      JOIN wp_users U
        on U.id = MU.user_id
    
     WHERE C.competition_id = $competition_id
       AND C.isConfirm = 1
       AND IFNULL(S_MAIN.id, S_CURRENT.id) = $specialty_id
        
       GROUP by C.id

       ORDER BY specialty, N.id, ageCategory        

       ";

    $wpdb->query($sql1);
    $wpdb->query($sql2);

    $queryArray = $wpdb->get_results($sql3, ARRAY_A);
    
    echo json_encode($queryArray);

    exit;
}

