jQuery(document).ready(function(){
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
                        console.log(data);
                        jQuery('#loader').hide();
                        var competitors = JSON.parse(data);
                        console.log(data);
                        var competitorsInfo = 
                        '<table> \
                            <tr> \
                                <td>№</td> \
                                <td>Ссылка</td> \
                                <td>Файл</td> \
                                <td>ФИО</td> \
                                <td>Специальность</td> \
                                <td>Город</td> \
                            </tr>';
                        var keys = Object.keys(competitors);
                        var i = 1; 
                        keys.forEach(function(key){ 
                            competitorsInfo += 
                            '<tr>' +
                            '<td>' + i++ + '</td>' +
                            (competitors[key].sourceUrl ? 
                                ('<td data-source-type="'+competitors[key].type+'"><a href="' +competitors[key].sourceUrl + '" target="_blank" rel="noopener noreferrer">Ссылка</a></td>')
                                : '<td>- - -</td>')
                            + 
                            (competitors[key].sourceFile ?
                                ('<td data-source-type="'+competitors[key].type+'"><a href="' +competitors[key].sourceFile + '" target="_blank" rel="noopener noreferrer">Файл</a></td>')
                                : '<td>- - -</td>')
                            + 
                            '<td>' + competitors[key].name + '</td>' +
                            '<td>' + competitors[key].specialty + '</td>' +
                            '<td>' + competitors[key].city + '</td>' +
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
                        console.log(data);
                    }
                });
            } else {
                jQuery('#loader').hide();
                alert("Выберите конкурс");
            }    
        });
});

