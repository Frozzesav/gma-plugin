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
                        let hasFiles = competitors.some(x => x.sourceFile);
                        var competitorsInfo = 
                        '<table> \
                            <tr> \
                                <td>№</td> \
                                <td>Ссылка</td>' +
                                (hasFiles ? '<td>Файл</td>' : '') +
                                '<td>ФИО</td> \
                                <td>Специальность</td> \
                                <td>Город</td> \
                                <td>Программа</td> \
                            </tr>';
                        
                            competitors.forEach(function(item, i){ 
                            competitorsInfo += 
                            '<tr>' +
                            '<td>' + (i+1) + '. ' + '</td>' +
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
                            '<td>' + item.name + '</td>' +
                            '<td>' + item.specialty + '</td>' +
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
                        console.log(data);
                    }
                });
            } else {
                jQuery('#loader').hide();
                alert("Выберите конкурс");
            }    
        });
});

