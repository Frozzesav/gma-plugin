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

		 WHERE beforeStart < '$nowDate' 
			 AND beforeStart > 0
			 AND enabled = 1 
			  
			 ";



	$queryArray = $wpdb->get_results($sql, ARRAY_A); 
	return $queryArray;
}

function getCurrentSpecialties()
{
	global $wpdb;

	global	$current_user;
    $current_user_id = $current_user->id;

	//Как добавить 2 члена жюри если один аккаунт?
    $current_jury_id = $wpdb->get_var( "SELECT id FROM `wp_gma_jury` WHERE user_id = $current_user_id" );

	$sql = "
	
	SELECT * 
		FROM wp_gma_competition C
	
		JOIN wp_gma_competition_specialty CS
		ON CS.competition_id = C.id
		
   		JOIN wp_gma_specialty S
		ON S.id = CS.specialty_id

		WHERE removed = 0 
			AND parent_id IS NULL

		GROUP BY CS.specialty_id 
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


