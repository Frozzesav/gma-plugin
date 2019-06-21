<?php 

function getCurrentCompetitions()
{
				$nowDate = date('y-m-d');
				global $wpdb;
				global	$current_user;

	$sql = "
	
	SELECT C.id AS id,
			C.header AS header,
			C.description AS decription,
			C.name AS name,
			C.fromDate AS fromDate,
			C.toDate AS toDate,
			C.beforeStart AS beforeStart
			
		FROM wp_gma_competition C 
		 
		JOIN wp_gma_jury J
		ON J.competition_id = C.id  

		 WHERE beforeStart < '$nowDate' 
			 AND beforeStart > 0
			 AND enabled = 1 
			  
		GROUP BY J.competition_id --ПРАВИЛЬНО ЛИ Я СДЕЛАЛ? ЧТОБЫ не отображалось по 2 раза 
			 ";
	
	$queryArray = $wpdb->get_results($sql, ARRAY_A); 
	return $queryArray;
}
 // выБРАТЬ ТОЛЬКО ТЕКУЩИЕ СПЕЦ ЧЛЕНА ЖЮРИ
function getCurrentSpecialties()
{
	global $wpdb;
	$sql = "
	
	SELECT * 
		FROM wp_gma_competition C
	
		JOIN wp_gma_competition_specialty CS
		ON CS.competition_id = C.id
		
   LEFT JOIN wp_gma_specialty S
		ON S.id = CS.specialty_id

		JOIN wp_gma_jury_specialty JS
		ON JS.specialty_id = S.id  

		WHERE removed = 0 
			AND parent_id IS NULL
			
		GROUP BY CS.specialty_id --ПРАВИЛЬНО ЛИ Я СДЕЛАЛ? ЧТОБЫ не отображалось по 2 раза спец ФОно
			";
	
	$queryArray = $wpdb->get_results($sql, ARRAY_A);
	return $queryArray;
}

function getCurrentSubSpecialties()
{
	global $wpdb;
	$sql = "SELECT * FROM wp_gma_specialty WHERE removed = 0 AND parent_id IS NOT NULL";
	$queryArray = $wpdb->get_results($sql, ARRAY_A);
	return $queryArray;
}


function getCurrentNominations()
{
	global $wpdb;
	$sql = "SELECT * FROM wp_gma_nomination";
	$queryArray = $wpdb->get_results($sql, ARRAY_A);
	return $queryArray;
}


