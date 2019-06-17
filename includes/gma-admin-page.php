 
<form action="<?php echo plugins_url('gma-plugin/action-forms/new-competition.php') ?>" method="post">
<h3>Добавить конкурс</h3>
<label for="header">header</label>
<input type="text" name="header" id="header">
<br />
<label for="description">description</label><br />
<textarea name="description" id="description" cols="30" rows="3"></textarea>
<br />
<label for="name">name</label>
<input type="text" name="name" id="name">
<br />
<label for="fromDate">Начало прослушиваний</label>
<input type="date" name="fromDate" id="fromDate">
<br />
<label for="toDate">Конец прослушиваний</label>
<input type="date" name="toDate" id="toDate">
<br />
<label for="beforeStart">Начало приема заявок</label>
<input type="date" name="beforeStart" id="beforeStart">
<label for="prizes">prizes</label>
<input type="text" name="prizes" id="prizes">
<button type="submit">ЖМИ</button>
</form>



<?php
add_action('admin_print_footer_scripts', 'my_action_javascript', 99);
function my_action_javascript() {
	?>
	<script>
	jQuery(document).ready(function($) {
		var data = {
			action: 'my_action',
			whatever: 1234
		};

		// с версии 2.8 'ajaxurl' всегда определен в админке
		jQuery.post( ajaxurl, data, function(response) {
			alert('Получено с сервера: ' + response);
		});
	});
	</script>
	<?php
}
?>


