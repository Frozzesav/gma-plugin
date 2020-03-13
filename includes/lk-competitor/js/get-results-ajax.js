jQuery(document).ready(function(){
    if(jQuery('.tabs')){
        getResultsDetails();
    }

    jQuery('.showJuryDetails').on('click', function () {
        jQuery('#juryDetails').toggle(400);
    })


    jQuery('.getDiploma').on('click', function () {
        getDiploma();
    })


    jQuery('.showCompetitors').on('click', function(){
        var competitionId = jQuery('#competitionResults').val();
        var specialtiesResults = jQuery('#specialtiesResults').val();
        jQuery('#loader').show();
    
        if (competitionId != null) {
            jQuery.ajax({
                    url: gmaPlugin.ajaxurl, 
                    type: "POST",             
                    data: {
                        action: 'getResults',
                        competitionId: competitionId,
                        specialtyId: specialtiesResults
                    },
                    cache: false,             
                    processData: true,      
                    success: function(data) {
                        jQuery('#loader').hide();
                        var competitors = JSON.parse(data);
                        let hasFiles = competitors.some(x => x.sourceFile);
                        var competitorsInfo = 
                        '<table> \
                            <tr> \
                                <th>№</th> \
                                <th>Результат</th> \
                                <th>Ссылка</th>' +
                                (hasFiles ? '<th>Файл</th>' : '') +
                                '<th style="max-width:50px">ФИО</th> \
                                <th>Специальность</td> \
                                <th>Категория</th> \
                                <th>Город</th> \
                                <th>Программа</th> \
                            </tr>';
                        
                            competitors.forEach(function(item, i){
                                competitorsInfo += 
                                '<tr>' +
                                '<td>' + (i+1) + '. ' + '</td>' +
                                (item.result ?
                                    '<td>' + item.result + '</td>' 
                                    : '<td>- - -</td>' 
                                ) +
                            (item.sourceUrl ? 
                                ('<td data-source-type="'+item.type+'"><a href="' +item.sourceUrl + '" target="_blank" rel="noopener noreferrer">Ссылка</a></td>')
                                : '<td>- - -</td>')
                            + 
                            (hasFiles ? 
                                (item.sourceFile ?
                                    ('<td data-source-type="'+item.type +'"><a href="' +item.sourceFile + '" target="_blank" rel="noopener noreferrer">Файл</a></td>')
                                    : '<td>- - -</td>')
                                : '')
                            + 
                            '<td style="max-width:150px">' + item.name + '</td>' +
                            '<td>' + item.specialty + '</td>' +
                            '<td>' + item.ageCategory + '</td>' +
                            '<td>' + item.city + '</td>' +
                            '<td>' + JSON.parse(item.compositions).map((x, index) => '<div>' + (index+1) + '. ' + x + ';</div>').join('') + '</td>' +
                            '</tr>';
                        });
                        competitorsInfo += '</table>';

                        if (data != '[]') {
                            
                            jQuery('#results').hide().html(competitorsInfo).fadeIn(1500);
                        } else {
                            jQuery('#results').hide().html("Нет участников").fadeIn(1500);
                        }

                    },
                    error: function(data) {
                        jQuery('#loader').hide();
                        alert('Что то пошлое не так. Напишите на наш email: music-competiiton@yandex.ru')
                    }
                });
            } else {
                jQuery('#loader').hide();
                alert("Выберите конкурс");
            }    
        });
});


function getResultsDetails() {
    var competitionId = jQuery('#competitionResults').val();
    var specialtiesResults = jQuery('#specialtiesResults').val();
    var buttonShowJuryDetails = jQuery('.showJuryDetails');
    jQuery.ajax({
        url: gmaPlugin.ajaxurl, 
        type: "POST",             
        data: {
            action: 'getResultsDetails',
            competitionId: competitionId,
            specialty_id: specialtiesResults
        },
        cache: false,             
        processData: true,      
        success: function(data) {
            // console.log(data);
            jQuery('#loader').hide();
            var obj = JSON.parse(data);
            var divId = jQuery('#juryDetails');
            buttonShowJuryDetails.show();
                    var res = "";
                    for(var i=0;i<obj.length;i++){
                        for(var keys in obj[i]){
                        // console.log(keys +": "+obj[i][keys]);
                        res += "<br/><b>"+ keys +"</b>: "+obj[i][keys];
                        // Как Сделать переносы??? 
                    }
                    res += "<hr/>"
                }
            divId.html(res);
        },
        error: function(data) {
            jQuery('#loader').hide();
            // alert('Что то пошлое не так. Напишите на наш email: music-competiiton@yandex.ru')
            console.log(data);
        }
    });   
}

function getDiploma() {
    
    var getDiplomaDiv = jQuery("#getDiploma");

    jQuery.ajax({
        url: gmaPlugin.ajaxurl, 
        type: "POST",             
        data: {
            action: 'getDiploma'
        },
        cache: false,             
        processData: true,      
        success: function(data) {
            // console.log(data);
            jQuery('#loader').hide();
            var obj = JSON.parse(data);

            var diplomaInfo = "";

                obj.forEach(function(item, i){
                    diplomaInfo += '<b>' + item.name + '</b><br>'; 
                    diplomaInfo += '<a href="/wp-content/uploads/gma-plugin/result-files/gma_id_7/' + item.id + '_diploma.jpg" download>Диплом</a><br>'
                    diplomaInfo += '<a href="/wp-content/uploads/gma-plugin/result-files/gma_id_7/' + item.id + '_blag2.jpg" download>Благодарность преподавателю</a><br>'
                    diplomaInfo += '<a href="/wp-content/uploads/gma-plugin/result-files/gma_id_7/' + item.id + '_blag.jpg" download>Благодарность Концертмейстеру</a>'
                    // console.log(item.id);
                    diplomaInfo += "<hr/>";
 
                }
                
                );

            getDiplomaDiv.html(diplomaInfo);
            // getDiplomaDiv.html("/wp-content/uploads/gma-plugin/result-files/gma_id_7/specialty_id_1/239_blag2.jpg");
            // getDiplomaDiv.html('<a href="' + data + '" download>Привет</a>');
            getDiplomaDiv.show();

            
        },
        error: function(data) {
            jQuery('#loader').hide();
            // alert('Что то пошлое не так. Напишите на наш email: music-competiiton@yandex.ru')
            console.log(data);
        }
    });   
}