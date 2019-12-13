<!-- Во время заоплнения заявки установить зависимось для ансамбле от количества участников.<br /><br />
Если больше 9, то давать выбрать только хор или оркестр.
</h6> -->
<?php
// add_action('wp_enqueue_scripts', 'get_results_ajax', 99);
?>

<div style="display:block;margin: auto;">
	<button class="showJuryDetails" style="heigth:20px;" >Узнать свой балл</button>
	<span><button class="getDiploma" style="heigth:20px;" >Скачать диплом</button></span>
</div>

<div id="getDiploma" style="display:none;"></div>
<div id="juryDetails" style="display:none;">
<b >Максимальный балл - 25</b></div><br>
<b>Если не отображаются результаты, следует очистить кэш браузера за всё время <a href="https://help.mail.ru/mail-help/helpful/cache" target="_blank">Как очистить кэш?</a></b>

<b>Выберите конкурс</b><br />

<div id="competitionResultsContainer">
	<select id="competitionResults" name="competition" size="1" class="required">
		<option value="0" id="0" disabled selected>--- Выберите конкурс ---</option>
		
		<?php
			$getCurrentCompetitions = getCurrentCompetitionsForResultsPage();
			
			foreach ($getCurrentCompetitions as $currentCompetition):?>
		<?php 
				$id = $currentCompetition['id'];
				$header = $currentCompetition['header'];
				$description = $currentCompetition['description'];
				$name = $currentCompetition['name'];
				
				$fromDate = $currentCompetition['fromDate']; 
				$toDate = $currentCompetition['toDate'];
				$beforeStart = $currentCompetition['beforeStart'];
				$fromDate = date( 'd-m-Y', strtotime($fromDate) ); 
				$toDate = date( 'd-m-Y', strtotime($toDate) ); 
				$beforeStart = date( 'd-m-Y', strtotime($beforeStart) ); 
				?>

<option value="<?php echo $id; ?>">
		<?php echo $name . " – " . $header . " (Заявки принимаются до $fromDate )"; ?>
	</option> 
	
	<?php endforeach; ?>		
</select><br />
</div>

<div id='containerSpecialtiesResults'>
	<b>Специальность</b><br />

		<select id="specialtiesResults" name="specialtyResults" size="1" class="required">
		<option value="0" disabled="" selected="">Выберите специальность</option>

		<?php $getCurrentSpecialties = getCurrentSpecialties();
		 foreach ($getCurrentSpecialties as $currentSpecialty):
			$id = $currentSpecialty['id']; 
		 	$specialty = $currentSpecialty['name'];?>
	
		<option value="<?php echo $id; ?>" name="<?php echo $specialty; ?>">
	<?php echo ($specialty); ?>
		</option>
	<?php endforeach; ?>		

		</select> <br />
	
</div>


<div style="width:100px; margin: auto;">
<button class="showCompetitors">Показать</button>
</div>
<div id="loader" style="width:200px; height:20px; margin:auto; display:none; opacity:0.5">
	<img src="<?php global $plugin_url; echo $plugin_url . "/includes/lk-competitor/img/ajax-loader.gif" ?>">
</div>
<div id="results">
</div>	

