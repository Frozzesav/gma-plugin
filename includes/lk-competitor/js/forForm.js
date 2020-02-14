jQuery(document).ready(function () {

	var counter = 2;

	jQuery("#addComposition").on("click", function () {

		if (counter > 3) {
			alert("Только 3 произведения можно подать на конкурс");
			return false;
		}
		var newTextBoxDiv = jQuery(document.createElement('div')).attr("id", 'compositionDiv' + counter);
		newTextBoxDiv.after().html('<br />' + counter + '. <input type="text" name="composition[]" id="composition' + counter + '" value="" >');
		newTextBoxDiv.appendTo("#competitorProgram");

		counter++;

	});

// Суб.Специальности для ВОКАЛА и ЭЛ.Гитары
	jQuery("#specialties").on("change", function() {
		var specialtyId = jQuery(this).find(":selected").val();
		
		var optionCount = 0;
		jQuery("#subSpecialties > option").each(function() {
		  if (jQuery(this).attr('data-parent-id') == specialtyId){
			jQuery(this).show();
				optionCount++;
		  }
		  else {
				jQuery(this).hide();
		  }
		});
		
		if (optionCount > 0) {
			jQuery('#subSpecialtiesContainer').show(300);
		}
		else {
			jQuery('#subSpecialtiesContainer').hide(300);
		}
	
		});
		
		jQuery("#specialties").on("change", function() {
			var specialtyName = jQuery(this).find(":selected").attr('name');
			jQuery("#nomination option").show(); // 
		
	if ( jQuery('#filesContainer').is(':hidden') ) {
		jQuery('#specialtyFile').val(null);
	}

			switch (specialtyName) {
					case 'Фортепиано':
						jQuery("#nomination :contains('Хоры')").hide();
						jQuery("#nomination :contains('Оркестры')").hide();
						jQuery('#nominationContainer').show(300);
						// jQuery('#concertmaster').show(300);
						jQuery('#filesContainer').hide(300);
						break;
					case 'Народные инструменты':
						jQuery("#nomination :contains('Хоры')").hide();
						jQuery("#nomination :contains('Оркестры')").show();
						jQuery('#nominationContainer').show(300);
						// jQuery('#concertmaster').show(300);
						jQuery('#filesContainer').hide(300);
						break;
					case 'Струнно-смычковые инструменты':
						jQuery("#nomination :contains('Хоры')").hide();
						jQuery("#nomination :contains('Оркестры')").show();
						jQuery('#nominationContainer').show(300);
						// jQuery('#concertmaster').show(300);
						jQuery('#filesContainer').hide(300);
						break;
						case 'Духовые и ударные инструменты':
							jQuery("#nomination :contains('Хоры')").hide();
							jQuery("#nomination :contains('Оркестры')").show();
							jQuery('#nominationContainer').show(300);
							jQuery('#filesContainer').hide(300);
							// jQuery('#concertmaster').show(300);
						break;
					case 'Вокал':
						jQuery("#nomination :contains('Хоры')").show();
						jQuery("#nomination :contains('Оркестры')").hide();
						jQuery('#nominationContainer').show(300);
						// jQuery('#concertmaster').show(300);
						jQuery('#filesContainer').hide(300);
						break;
					case 'Классическая гитара':
						jQuery("#nomination :contains('Хоры')").hide();
						jQuery("#nomination :contains('Оркестры')").show();
						jQuery('#nominationContainer').show(300);
						// jQuery('#concertmaster').show(300);
						jQuery('#filesContainer').hide(300);
						break;
					case 'Электрогитара':
						jQuery("#nomination :contains('Хоры')").hide();
						jQuery("#nomination :contains('Оркестры')").show();
						jQuery('#nominationContainer').show(300);
						// jQuery('#concertmaster').show(300);
						jQuery('#filesContainer').hide(300);
						break;
					case 'Композиторы':
						jQuery('#specialtyFile').val(null);
						jQuery('#filesContainer').show(300);
						jQuery('#filesContainer > label').html('<b>Файл для композитора</b>');
						jQuery('#nominationContainer').hide(300);
						// jQuery('#concertmaster').hide(300);
						jQuery('#nomination').val(null); 
						break;
					case 'Музыковедение':
						jQuery('#specialtyFile').val(null)
						jQuery('#filesContainer').show(300);
						jQuery('#filesContainer > label').html('<b>Файл для музыковедов</b>');
						jQuery('#nominationContainer').hide(300);
						// jQuery('#concertmaster').hide(300);
						jQuery('#nomination').val(null); 
						break;
						
						default:
							break;
						}
						
					});
					
	jQuery('#showTeacherForm').click(function(){
		jQuery('#teacher').slideToggle();
	});
	
	jQuery('#showConcertmasterForm').click(function(){
		jQuery('#concertmaster').slideToggle();
	});
					
});

function changeNomination(x) {
	switch (x) {
		case 'solo':
			jQuery('#solo').show(300);
			jQuery('#ensemble').hide(300);
			break;

		case 'ensemble':
			jQuery('#ensemble').show(300);
			jQuery('#solo').hide(300);
			break;

		default:
			break;
	}
}

function getDiplomaFunc(x) {
	switch (x) {
		case 'byPost':
			jQuery('#adress').show(300);
			break;

		default:
			jQuery('#adress').hide(300);
			break;
	}
}

function addNewMusician() {

	if (jQuery('#new-musician').is(":hidden")) {

		jQuery('#newMusician').text('выбрать участника из списка');
		jQuery('#chooseMusicianContainer').hide(300);
		jQuery('#new-musician').show(300);
		jQuery('#chooseMusicianContainer').hide(300);
	} else {
		jQuery('#new-musician').hide(300);
		jQuery('#chooseMusicianContainer').show(300).attr( "style", "display: inline !important;" );
		jQuery('#newMusician').text('добавьте нового участника');

		jQuery(".newMusicianData input[type='text']").val(null); //Очищаем заполненые поля
		
	}

}
