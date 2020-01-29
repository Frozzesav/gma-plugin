<?php 
function musicians_of_user()
{
				global $wpdb;
				global	$current_user;
				$sql  = "SELECT id, name FROM wp_gma_musicians_of_user WHERE user_id = {$current_user->id} AND removed = 0";
				$queryArray = $wpdb->get_results($sql, ARRAY_A);
				return $queryArray;
}

function getCurrentCompetitions()
{				
				$nowDate = date('Y-m-d');
				global $wpdb;
				
				$sql = "SELECT * FROM wp_gma_competition 
								 WHERE
								 '$nowDate' >= beforeStart 
									AND beforeStart > 0 
									AND '$nowDate' < fromDate  
									AND enabled = 1"; 
									
				$queryArray = $wpdb->get_results($sql, ARRAY_A); 
				return $queryArray;
				
}

function getCurrentCompetitionsForResultsPage()
{				
				$nowDate = date('Y-m-d');
				global $wpdb;
				
				$sql = "SELECT * FROM wp_gma_competition 
								 WHERE enabled = 1";  
									
				$queryArray = $wpdb->get_results($sql, ARRAY_A); 
				return $queryArray;
				
}

function getCurrentSpecialties()
{
	global $wpdb;
	$sql = "SELECT * FROM wp_gma_specialty WHERE removed = 0 AND parent_id IS NULL";
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
