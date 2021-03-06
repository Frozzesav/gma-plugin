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
                        action: 'getResultsJury',
                        competitionId: competitionId,
                        specialtyId: specialtiesResults
                    },
                    cache: false,             
                    processData: true,      
                    success: function(data) {
                        console.log(data);
                        jQuery('#loader').hide();
                        var competitors = JSON.parse(data);
                        let hasFiles = competitors.some(x => x.sourceFile);
                        var competitorsInfo = 
                        '<table> \
                            <tr> \
                                <th>№</th> \
                                <th>Балл</th> \
                                <th>Комментарий</th> \
                                <th>Ссылка</th>' +
                                (hasFiles ? '<td>Файл</th>' : '') +
                               '<th>ФИО</th> \
                                <th>Специальность</th> \
                                <th>Категория</th> \
                                <th>Программа</th> \
                            </tr>';
                        
                            competitors.forEach(function(item, i){ 
                            competitorsInfo += 
                            '<tr>' +
                            '<td>' + (i+1) + '</div></td>' +
                            '<td ><input style="max-width:50px" value="' + item.score + '"  type="number max="25" " class="edit" id="competitor-value-'+ item.id +'" data-competitor-id="'+ item.id +'" ><button class="ok" onclick="javascript: saveScore(' + item.id + ');" style="display:none">Ok</button><button class="cancel" onclick="javascript: cancel();" style="display:none">Отмена</button></td>' +
                            '<td><div class="edit" contenteditable  id="competitor-comment-'+ item.id +'">' + item.comment + '</div><button class="ok" onclick="javascript: saveScore(' + item.id + ');" style="display:none">Ok</button><button class="cancel" onclick="javascript: cancel();" style="display:none">Отмена</button></td>' +
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
                            '<td>' + JSON.parse(item.compositions).map((x, index) => '<div>' + (index+1) + '. ' + x + ';</div>').join('') + '</td>' +
                            '</tr>';
                        });
                        competitorsInfo += '</table>';

                        
                        if (data != '[]') {
                            
                            jQuery('#results').hide().html(competitorsInfo).fadeIn(1500);
                        } else {
                            jQuery('#results').hide().html("Нет участников").fadeIn(1500);
                        }
                        
                        
                        
                        var oldVal;
                        var editId; // они тут используютя?????
                        
                        jQuery('.edit').focus(function(){
                            // jQuery('.edit').nextAll('button').hide(120);
                            jQuery(this).nextAll('button').show(100);
                            oldVal = jQuery(this).val();
                            editId = jQuery(this).attr('data-competitor-id');
                        });

                        // jQuery('.edit').blur(function(){
                        //     jQuery('.edit').nextAll('button').hide(120);
                        //     // jQuery(this).nextAll('button').show(100);
                        // });


                        var newVal;

                       
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
    
    function ajaxSetScore(id, val, comment, competitionId) {
        
        jQuery.ajax({
            url: gmaPlugin.ajaxurl, 
            type: "POST",             
            data: {
                action: 'setDataJury',
                competitionId: competitionId,
                competitorId: id,
                score: val,
                comment: comment
            },
            cache: false,             
            processData: true,      
            success: function(data) {
                jQuery('#loader').hide();
                alert ("Данные сохранены");
                console.log(data);
            },
            error: function(data) {
                jQuery('#loader').hide();
                alert('Что то пошлое не так. Напишите на наш email: music-competiiton@yandex.ru')
                console.log(data);
            }
        });   
    }

    
    function saveScore(competitorId) {
        var competitionId = jQuery('#competitionResults').val();
        var newVal = jQuery('#competitor-value-'+ competitorId).val();

        var newComment = jQuery('#competitor-comment-'+ competitorId).html();

        newVal = parseFloat(newVal.replace(',','.').replace(' ','')).toFixed(2);

        
        ajaxSetScore(competitorId, newVal, newComment, competitionId);
        jQuery('.edit').nextAll('button').hide(100); 
    }

    function ajaxGetScore(id, competitionId) {

    jQuery.ajax({
        url: gmaPlugin.ajaxurl, 
        type: "POST",             
        data: {
            action: 'getDataJury',
            competitionId: competitionId,
            competitorId: id
        },
        cache: false,             
        processData: true,      
        success: function(data) {
            jQuery('#loader').hide();
            console.log(data);
            
        },
        error: function(data) {
            jQuery('#loader').hide();
            alert('Что то пошлое не так. Напишите на наш email: music-competiiton@yandex.ru')
            console.log(data);
        }
    });   
    }


    function cancel(id) {
            jQuery('.edit').nextAll('button').hide(100); // КАК сделать отмн
    }