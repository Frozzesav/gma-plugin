<?php 
// require_once ('form-actions/getter-db-query.php');

add_action( 'wp_enqueue_scripts', 'newMusician' );
add_action( 'wp_enqueue_scripts', 'gma_plugin_styles' );
// add_action('wp_enqueue_scripts', 'get_results_ajax', 99);

gma_plugin_styles();
wp_enqueue_style('gma-style-bootstrap');
add_shortcode( 'gma_lk_jury', 'gma_lk_jury_func' );

 function gma_lk_jury_func(){

	if ( is_user_logged_in() ) {
			global	$current_user;
			get_currentuserinfo();
			echo "Здравствуйте, <b>" . $current_user->display_name . "!</b>"; 
			echo "<br />";
		}else exit ("Вам нужно войти на сайт <br />" . "<form action='/wp-admin'><button type='submit'>Войти</button></form>");
	if (!current_user_can ( 'administrator' ) && !current_user_can ('jury')) {
		wp_die("У ВАС НЕТ ПРАВ ДОСТУПА");
	} else

?>
        

<div class="tabs">

    <input id="tab1" type="radio" name="tabs" checked>
    <label for="tab1" title="Заполнить заявку">Заполнить заявку</label>
   
    <input id="tab2" type="radio" name="tabs">
    <label for="tab2" title="Участники">Участники</label>
 
    <section id="content-tab1">
        <p>

        </p>
    </section>  
    <section id="content-tab2">
        <p>
        <?php include('results.php') ?>
        </p>
    </section> 
        
</div>
<?php  

gma_plugin_js();

$lk_competitor_url = get_permalink();
// echo( $lk_competitor_url );


}