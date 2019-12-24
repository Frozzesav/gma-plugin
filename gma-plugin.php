<?php

/*
Plugin Name: Gma Plugin For Competition
Description: Тест плагина для конкурса Grand music art
Author: Александр Артёмов
Author URI: https://music-competition.ru/
*/
error_reporting(E_ALL);

ini_set('display_errors', 0);

$plugin_url = plugins_url('/gma-plugin');
$gma_plugin_url = plugin_dir_path( __FILE__ );

define("GMA_PLUGIN_DIR", plugin_dir_path( __FILE__ ));

// echo GMA_PLUGIN_DIR;

// define('GMA_COMPETITION_TEST', $wpdb->prefix . "gma_competition";);

function newMusician()
{
    wp_enqueue_script('newMusician', plugins_url('includes/lk-competitor/js/forForm.js', __FILE__) );
}

function gma_plugin_styles() {
	wp_register_style('gma-style', plugins_url('css/style.css', __FILE__), array(), 5.4);
	wp_enqueue_style('gma-style');
	}

	wp_register_style('gma-style-bootstrap', '//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css');

function gma_plugin_js() {
	wp_register_script('gma-script', plugins_url('js/script.js', __FILE__), array('jquery'));
	wp_enqueue_script('gma-script');
	}

require_once plugin_dir_path(__FILE__) . 'shortcodes.php';

require_once plugin_dir_path(__FILE__) . 'includes/gma-functions.php';


require_once plugin_dir_path(__FILE__) . 'includes/lk-competitor/lk-competitor.php'; 


//Подключитл файлы ниже в lk-competitor.php
// require_once plugin_dir_path(__FILE__) . 'includes/lk-competitor/form.php';
// require_once plugin_dir_path(__FILE__) . 'includes/lk-competitor/form-actions/new-competitor.php';
// require_once plugin_dir_path(__FILE__) . 'includes/lk-competitor/form-actions/get-results.php';

require_once plugin_dir_path(__FILE__) . 'includes/lk-jury/form-actions/get-results.php';
require_once plugin_dir_path(__FILE__) . 'includes/lk-jury/form-actions/set-data-jury.php';
require_once plugin_dir_path(__FILE__) . 'includes/lk-jury/lk-jury.php';

require_once plugin_dir_path(__FILE__) . 'includes/lk-admin/form-actions/get-results.php';
require_once plugin_dir_path(__FILE__) . 'includes/lk-admin/form-actions/set-data-admin.php';
require_once plugin_dir_path(__FILE__) . 'includes/lk-admin/lk-gma-admin.php';


// require_once plugin_dir_path(__FILE__) . 'includes/classes/login.php'; // Пока отключил, чтобы не было конфликта с плагином TML
require_once plugin_dir_path(__FILE__) . 'includes/gma-create-tables.php';






register_activation_hook( __FILE__, 'gma_install' );
register_activation_hook( __FILE__, 'gma_install_data' );
// register_deactivation_hook( __FILE__, 'gma_uninstall' );



function test_ajax() {
	wp_enqueue_script('jquery.validate.min', plugins_url('/includes/lk-competitor/js/jquery.validate.min.js', __FILE__), array('jquery'));
	wp_enqueue_script('test-ajax', plugins_url('/includes/lk-competitor/js/test-ajax.js', __FILE__), array('jquery', 'jquery.validate.min'), 19.12);
	wp_localize_script('test-ajax', 'gmaPlugin', array(
		'ajaxurl' => admin_url('admin-ajax.php'),
	));
}

function get_results_ajax() {
	wp_enqueue_script('jquery.validate.min', plugins_url('/includes/lk-competitor/js/jquery.validate.min.js', __FILE__), array('jquery'));

	if (is_page('48937') || is_page('48930') ) {
		wp_enqueue_script('get-results-ajax', plugins_url('/includes/lk-competitor/js/get-results-ajax.js', __FILE__), array('jquery', 'jquery.validate.min'), 5.4);
		require_once ('includes/lk-competitor/form-actions/getter-db-query.php');
	}
	
	if (is_page('48935')) {
		require_once ('includes/lk-jury/form-actions/getter-db-query.php');
		wp_enqueue_script('get-results-ajax', plugins_url('/includes/lk-jury/js/get-results-ajax.js', __FILE__), array('jquery', 'jquery.validate.min'));
		wp_enqueue_script('editTable', plugins_url('/includes/lk-jury/js/editTable.js', __FILE__), array('jquery','get-results-ajax', 'jquery.validate.min'));
	}
	
	if (is_page('48965')) {
		require_once ('includes/lk-admin/form-actions/getter-db-query.php');
		wp_enqueue_script('get-results-ajax', plugins_url('/includes/lk-admin/js/get-results-ajax.js', __FILE__), array('jquery', 'jquery.validate.min'));
		wp_enqueue_script('editTable', plugins_url('/includes/lk-admin/js/editTable.js', __FILE__), array('jquery','get-results-ajax', 'jquery.validate.min'));
	}
	
}
add_action('wp_enqueue_scripts', 'get_results_ajax', 99);

