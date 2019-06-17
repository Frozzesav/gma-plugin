<?php

require_once('../../../../wp-config.php'); // подключить, иначе не будет работать Запрос Insert

header('Location: ' . $_SERVER['HTTP_REFERER']);

global $wpdb;
print_r($_POST);

if ($_POST['header']) {

    

    $wpdb->insert(

        $wpdb->prefix . "gma_competition",

        [

            "header" => $_POST['header'],
            "description" => $_POST['description'],

            "name" => $_POST['name'],

            "fromDate" => $_POST['fromDate'],

            "toDate" => $_POST['toDate'],

            "beforeStart" => $_POST['beforeStart'],
            "prizes" => $_POST['prizes'],
            "enabled" => 1

        ],

        ['%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d']

    );

    

}

exit;

