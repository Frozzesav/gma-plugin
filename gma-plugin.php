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
	wp_register_style('gma-style', plugins_url('css/style.css', __FILE__));
	wp_enqueue_style('gma-style');
	}

	wp_register_style('gma-style-bootstrap', '//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css');

function gma_plugin_js() {
	wp_register_script('gma-script', plugins_url('js/script.js', __FILE__), array('jquery'));
	wp_enqueue_script('gma-script');
	}

require_once plugin_dir_path(__FILE__) . 'includes/gma-functions.php';
// require_once plugin_dir_path(__FILE__) . 'includes/gma-add-new-fields-role.php'; Для настройки специальности у пользователя
require_once plugin_dir_path(__FILE__) . 'includes/lk-competitor/lk-competitor.php';
require_once plugin_dir_path(__FILE__) . 'includes/lk-competitor/form.php';
require_once plugin_dir_path(__FILE__) . 'includes/lk-competitor/form-actions/new-competitor.php';
require_once plugin_dir_path(__FILE__) . 'includes/lk-competitor/form-actions/get-results.php';
require_once plugin_dir_path(__FILE__) . 'includes/classes/lk-jury.php';
require_once plugin_dir_path(__FILE__) . 'includes/classes/login.php';
require_once plugin_dir_path(__FILE__) . 'includes/gma-create-tables.php';

require_once plugin_dir_path(__FILE__) . 'includes/lk-jury/lk-jury.php';




register_activation_hook( __FILE__, 'gma_install' );
register_activation_hook( __FILE__, 'gma_install_data' );
register_deactivation_hook( __FILE__, 'gma_uninstall' );



function test_ajax() {
	wp_enqueue_script('jquery.validate.min', plugins_url('/includes/lk-competitor/js/jquery.validate.min.js', __FILE__), array('jquery'));
	wp_enqueue_script('test-ajax', plugins_url('/includes/lk-competitor/js/test-ajax.js', __FILE__), array('jquery', 'jquery.validate.min'));
	wp_localize_script('test-ajax', 'gmaPlugin', array(
		'ajaxurl' => admin_url('admin-ajax.php'),
	));
}

function get_results_ajax() {
	wp_enqueue_script('jquery.validate.min', plugins_url('/includes/lk-competitor/js/jquery.validate.min.js', __FILE__), array('jquery'));
	wp_enqueue_script('get-results-ajax', plugins_url('/includes/lk-competitor/js/get-results-ajax.js', __FILE__), array('jquery', 'jquery.validate.min'));
}

