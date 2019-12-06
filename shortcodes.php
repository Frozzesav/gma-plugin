<?php

add_shortcode('gma_current_competition', 'gma_current_competition_func');

function gma_current_competition_func()
{
    global $wpdb;
    $sql = "SELECT name FROM wp_gma_competition WHERE id = 8";
    $queryArray = $wpdb->get_results($sql, ARRAY_A);
    return($queryArray[0]["name"]);
}


function getCurrentSpecialtiesForShortCodes()
{
    global $wpdb;
    $sql = "SELECT * FROM wp_gma_specialty AS S

    LEFT JOIN wp_gma_competition_specialty CS
    
    ON CS.specialty_id = S.id
    
    WHERE removed = 0 AND parent_id IS NULL
        AND competition_id = 8";
    $queryArray = $wpdb->get_results($sql, ARRAY_A);
    return $queryArray;
}

add_shortcode( 'gma_current_specialities', 'gma_current_specialities_func' );

function gma_current_specialities_func()
{

    // $specialitiesList = "";

    $specialitiesCount =  count(getCurrentSpecialtiesForShortCodes());

    foreach (getCurrentSpecialtiesForShortCodes() as $key => $value) {
        $specialitiesList .= " " . $value["name"];

        if ($key < $specialitiesCount - 1) {
            $specialitiesList .= ",";
        }else {
            $specialitiesList .= ".";
        }      
    }
     
    return "<b>" . $specialitiesCount . " специальностей: </b>" . $specialitiesList;  
}