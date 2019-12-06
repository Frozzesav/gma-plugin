<?php 
// require_once ('form-actions/getter-db-query.php');
require_once 'form.php';
require_once 'form-actions/new-competitor.php';
require_once 'form-actions/get-results.php';

add_action( 'wp_enqueue_scripts', 'newMusician' );
add_action( 'wp_enqueue_scripts', 'gma_plugin_styles' );

gma_plugin_styles();
wp_enqueue_style('gma-style-bootstrap');
add_shortcode( 'gma_lk_competitor', 'gma_lk_competitor_func' );

 function gma_lk_competitor_func(){

	if ( is_user_logged_in() ) {
			global	$current_user;
			get_currentuserinfo();
            echo "Здравствуйте, <b>" . $current_user->display_name . "!</b>";
			echo "<br />";
		}else exit ("<div style='display:block; width:400px; margin: auto; text-align:center'><h1>Вам нужно войти на сайт</h1> <br />" . "<form action='/wp-admin'><button style='text-align:center' class='enterGmaLk' type='submit'>Войти</button></form></div>");
	// if (!current_user_can ( 'jury' ) && !current_user_can ('competitor')) {
	// 	wp_die("У ВАС НЕТ ПРАВ ДОСТУПА");
	// } else

?>
        

<div class="tabs">
<a href="https://music-competition.ru/test-application/" target="_blank" style="display:none">Заполнить заявку</a><br />

<input id="tab1" type="radio" name="tabs" checked>
<label for="tab1" title="Ваши результаты">Ваши результаты</label>
    
<input id="tab2" type="radio" name="tabs">
<label for="tab2" title="Заполнить заявку">Заполнить заявку</label>
   
 
    <section id="content-tab1">
        <p>
        <?php include('results.php') ?>
        </p>
    </section>  
    <section id="content-tab2">
        <p>
        </p>
    </section> 
        
</div>
<?php  

gma_plugin_js();

$lk_competitor_url = get_permalink();
// echo( $lk_competitor_url );


}