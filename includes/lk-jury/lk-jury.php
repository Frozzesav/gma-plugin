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
    <label for="tab1" title="Участники">Участники</label>

    <input id="tab2" type="radio" name="tabs">
    <label for="tab2" title="Перед прослушиванием">Перед прослушиванием</label>
 
    <section id="content-tab1">
<style>
body {font-family: Arial, Helvetica, sans-serif;}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 999999999999999999; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}

/* The Close Button */
.close {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}
</style>
<strong>БОЛЬШАЯ ПРОСЬБА. Оставляйте пожалуйста комментарии для участников.
<br>Они смогут их прочитать после подведения итогов и буду очень благодарны.</strong>
<h2>Система оценок</h2>

<!-- Trigger/Open The Modal -->
<button id="myBtn">Прочитайте пожалуйста перед прослушиванием.</button>

<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <p>1.Баллы округляются до сотых, т.е. можно ставить баллы такого вида: 22.52.<br />
2. <strong>В поле &quot;комментарий&quot; оставляется комментарий для участника. <br />Этот комментарий будет видеть только участник. <br />Если есть, что сказать участнику или дать совет, напишите пожалуйста. Конкурсантам будет полезно.</strong><br />
3. Не забывайте нажимать кнопку <strong>&quot;ОК&quot;</strong>. Иначе данные могут не сохраниться.<br />
<br />
<strong>Далее система оценок:</strong></p>

<img src="<?php global $plugin_url; echo $plugin_url . "/img/scores-help.jpeg" ?>"><br />

<p><strong>Возрастные категории:</strong>
<ul>
	<li>Группа A &ndash; участники возрастом до 9 лет включительно;</li>
	<li>Группа B &ndash; участники возрастом с 10 до 12 лет включительно;</li>
	<li>Группа C &ndash; участники возрастом с 13 до 15 лет включительно;</li>
	<li>Группа D &ndash; участники возрастом с 16 до 18 лет включительно**;</li>
	<li>Группа E &ndash; учащиеся музыкальных училищ и колледжей;</li>
	<li>Группа F - учащиеся и выпускники ВУЗов;</li>
	<li>Группа G &ndash; без возрастных ограничений.</li>
</ul>
</p>
    
  </div>

</div>

<script>
// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>


        <p>
            <?php include('results.php') ?>
        </p>
    </section>  
    <section id="content-tab2">
        <p>
        <?php include('help.php') ?>
        </p>
    </section> 
        
</div>
<?php  

gma_plugin_js();

$lk_competitor_url = get_permalink();
// echo( $lk_competitor_url );


}