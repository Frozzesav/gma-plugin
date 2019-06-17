jQuery(document).ready(function() {
jQuery('#newCompetitorForm').validate({
    messages: {
        competition: {
            required: "Выберите конкурс"
        },
        specialty: {
            required: "Выберите специальность"
            },  
     },
    submitHandler: function(form) {
        if ( jQuery('#new-musician').is(":hidden") ) {
            var newMusicianName = null;
            
        }
        
        if ( jQuery('#new-musician').is(":visible") && jQuery('#soloOrNot').val() == "solo"
        ) {
            var newMusicianName = jQuery("#last_name").val().trim() + " " + 
            jQuery("#first_name").val().trim() + " " + 
            jQuery("#father_name").val().trim();
        } else { var newMusicianName =  jQuery("#ensemble_name").val().trim(); }

        var musiciaId = jQuery('#musicians').val();
        
        var competitionId = jQuery('#competition').val();
        
        var composition1 = jQuery('#composition1').val();
        var composition2 = jQuery('#composition2').val();
        var composition3 = jQuery('#composition3').val();

        var compositions = [];
        if (composition1) {
            compositions.push(composition1)
        }
        if (composition2) {
            compositions.push(composition2)
        }
        if (composition3) {
            compositions.push(composition2)
        }
        
        

        var linkVideo = jQuery('#linkVideo').val();
        

        var birthday = jQuery("#birthday").val();
        var ageCategory =  jQuery('#category').val();

        if ( jQuery('#subSpecialties').is(":visible") ) {
            
            var specialty = jQuery('#subSpecialties').val();
        } else {
            var specialty = jQuery('#specialties').val();
        }
            
        var telephone = jQuery('#telephone').val().replace(/[-+()/\\' ']/g,'');

        var country = jQuery('#country').val();
        var city = jQuery('#city').val();

        var postIndex = null;
        var postStreet = null;
        var houseNumber = null;
        var flatNumber = null;
        
        if (jQuery("#getDiploma").val() == "byPost" ) {
            postIndex = jQuery('#postIndex').val();
            postStreet = jQuery('#postStreet').val();
            houseNumber = jQuery('#houseNumber').val();
            flatNumber = jQuery('#flatNumber').val();
        }

        var formData = new FormData();

        formData.append("action", 'testAjax');
        formData.append("name", newMusicianName);
        formData.append("musiciaId", musiciaId);
        formData.append("birthday", birthday);
        formData.append("ageCategory", ageCategory);
        formData.append("compositions", JSON.stringify(compositions)); //"[1, 2, 3]"
        formData.append("source", linkVideo);
        // formData.append("typeContent", typeContent);
        formData.append("competitionId", competitionId);
        formData.append("specialty", specialty);
        formData.append("telephone", telephone);
        formData.append("county", country);
        formData.append("city", city);
        formData.append("postIndex", postIndex);
        formData.append("postStreet", postStreet);
        formData.append("houseNumber", houseNumber);
        formData.append("flatNumber", flatNumber);
        if ( jQuery('#specialtyFile').val() != '' ) {
            formData.append('specialtyFile', jQuery('#specialtyFile')[0].files[0]);
        }

        jQuery('#loader').show();

        jQuery.ajax({
            url: gmaPlugin.ajaxurl, 
            type: "POST", 
            enctype: "multipart/form-data",
            // typeContent = "",
             
            data: formData,            
            cache: false,    
            contentType: false,  
            processData: false,      
            success: function(data) {
                jQuery('#loader').hide();
                console.log(data);
                alert('Заявка заполнена! \n Подтверждение придет на ваш E-mail в течение 24 часов (Проверьте папку \'Спам\')');
            },
            error: function(data) {
                jQuery('#loader').hide();
                alert('Что то пошлое не так. Напишите на наш email: music-competiiton@yandex.ru')
                console.log(data);
            }
        });
        return false;
    },
    // other options
    
    
});

});