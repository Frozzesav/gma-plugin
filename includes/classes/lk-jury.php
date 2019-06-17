<?php 
add_shortcode( 'gma_lk_jury', 'lk_jury' );

 function lk_jury()
{
require_once('wp-config.php');


	if (is_user_logged_in()) {
			global	$current_user;
  	 		get_currentuserinfo();

			echo "Здравствуйте, <b>"  . $current_user->display_name . "!</b>";
			echo "<br />";
		}else echo "Стоит залогиниться <br />" ;

	if (!current_user_can ( 'administrator' ) && !current_user_can ('jury')) {
		die("Вы не Администратор и не жюри");

	} else echo "Личный кабинет работает";
	
global $options;
?>
<table class="zebra">
	<thead>
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>Score</th>
		</tr>		
	</thead>

	<tbody>
		<?php foreach ($options as $option):
			?>
		<tr>
			<td><?php echo $option['id'] ?></td>
			<td><?php echo $option['name'] ?></td>
	<td><div class="edit" data-id ="<?php echo $option['id'] ?>"  contenteditable><?php echo $option['score'] ?></div></td>
		</tr>		
	<?php endforeach; ?>
	</tbody>
</table>

<!-- <script scr="http://code.jquery.com/jquery-latest.js"></script>
<script src="/wp-content/plugins/gma-plugin/js/script.js"></script> -->
<?php  
gma_plugin_js();


}


