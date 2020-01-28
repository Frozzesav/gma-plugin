/*
Функция копирования для постов в VK.
*/

function vkCopy(elem) {
    jQuery(elem).select();
    document.execCommand("copy");
}

var categories = {
    'A': "Группа A – участники возрастом до 9 лет включительно",
    'B': "Группа B – участники возрастом с 10 до 12 лет включительно",
    'C': "Группа C – участники возрастом с 13 до 15 лет включительно",
    'D': "Группа D – участники возрастом с 16 до 18 лет включительно",
    'E': "Группа E – учащиеся музыкальных училищ и колледжей",
    'F': "Группа F - учащиеся и выпускники ВУЗов",
    'G': "Группа G – без возрастных ограничений"
    }


function tableForSocialPost() {
    var competitionId = jQuery('#competitionResults').val();
    var specialtiesResults = jQuery('#specialtiesResults').val();
    
    if (competitionId != null) {

        // console.log(competitionId);
        // console.log(specialtiesResults);
        jQuery('#loader').show();
            
        jQuery.ajax({
                url: gmaPlugin.ajaxurl, 
                type: "POST",             
                data: {
                    action: 'getResultsAdminForSocialPost',
                    competitionId: competitionId,
                    specialtyId: specialtiesResults
                },
                cache: false,             
                processData: true,      
                success: function(data) {
                    jQuery('#loader').hide();
                    // console.log(data);
                    var competitors = JSON.parse(data);
                    // console.log(competitors);
                    let hasFiles = competitors.some(x => x.sourceFile);
                    var competitorsInfo = 
                    '<table> \
                        <tr> \
                            <th>№</th> \
                            <th style="max-width:50px">ФИО</th> \
                            <th>link</th> \
                            <th>ПОСТЫ VK</th> \
                        </tr>';
                    
                        competitors.forEach(function(item, i){
                        competitorsInfo += 
                        '<tr>' +
                        '<td>' + (i+1) + '. ' + '</td>' +                    
                        '<td style="max-width:150px">' + item.name + '</td>' +
                        (item.sourceUrl ? 
                            ('<td data-source-type="'+item.type+'"><a href="' +item.sourceUrl + '" target="_blank" rel="noopener noreferrer">Ссылка</a></td>')
                            : '<td>- - -</td>') +
                        '<td><textarea onclick="vkCopy(this)">' 
                            + item.result + " @club115932488 (VII GRAND MUSIC ART)\n" +
                            item.name  +

                            "\n\nУчаствуйте в нашем конкурсе:\
                            \n↪ https://vk.com/app5898182_-115932488#u=814417&s=118887&force=1\
                            \n📅 Заявки принимаются до 1 марта 2020 года" + "\n\n" + 

                              item.city + "\n" +  
                             categories[item.ageCategory] + "\n" +
                             item.specialty + "\n" +

                         JSON.parse(item.compositions).map((x, index) => "\n" + (index+1) + '. ' + x + ';').join('') + 
                         
                         
                         (item.school ? "\n\n" +  item.school : "")
                         
                          + 
                          (item.teacher ? "\nПреподаватель: " + item.teacher : "") + 
                         

                         '</textarea></td>' +
                         
                         '</tr>';
                    });
                    competitorsInfo += '</table>';

                    if (data != '[]') {
                        
                        jQuery('#results').hide().html(competitorsInfo).fadeIn(1500);
                    } else {
                        jQuery('#results').hide().html("Нет участников").fadeIn(1500);
                    }

                    // getResultList();

                },
                error: function(data) {
                    jQuery('#loader').hide();
                    alert('Что то пошлое не так. Напишите на наш email: music-competiiton@yandex.ru')
                }
            });
    }
}

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
                        action: 'getResultsAdmin',
                        competitionId: competitionId,
                        specialtyId: specialtiesResults
                    },
                    cache: false,             
                    processData: true,      
                    success: function(data) {
                        jQuery('#loader').hide();
                        console.log(data);
                        var competitors = JSON.parse(data);
                        console.log(competitors);
                        let hasFiles = competitors.some(x => x.sourceFile);
                        var competitorsInfo = 
                        '<table> \
                            <tr> \
                                <th>№</th> \
                                <th>Выбор Результата</th> \
                                <th>Текущий Результата</th> \
                                <th>Ср.балл</th> \
                                <th>Ссылка</th>' +
                                (hasFiles ? '<th>Файл</th>' : '') +
                                '<th style="max-width:50px">ФИО</th> \
                                <th>Специальность</td> \
                                <th>Категория</th> \
                                <th>Город</th> \
                                <th>Email</th> \
                                <th>Программа</th> \
                            </tr>';
                        
                            competitors.forEach(function(item, i){
                            competitorsInfo += 
                            '<tr>' +
                            '<td>' + (i+1) + '. ' + '</td>' +
                            '<td><select id ="'+ item.id +'" onchange="javascript: setResult(' + item.id + ');"> \
                            <option  disabled selected value="0">Изменить результат</option> \
                            <option  value="1">Гран-при</option> \
                            <option  value="2">Лауреат I Степени</option> \
                            <option  value="3">Лауреат II Степени</option> \
                            <option  value="4">Лауреат III Степени</option> \
                            <option  value="5">Диплом</option> \
                            <option  value="6">Диплом участника</option> \
                            </select></td>' +
                            '<td>' + item.result + '</td>' +
                            '<td>' + item.average + '</td>' +
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
                            '<td>' + item.email + '</td>' +
                            '<td>' + JSON.parse(item.compositions).map((x, index) => '<div>' + (index+1) + '. ' + x + ';</div>').join('') + '</td>' +
                            '</tr>';
                        });
                        competitorsInfo += '</table>';

                        if (data != '[]') {
                            
                            jQuery('#results').hide().html(competitorsInfo).fadeIn(1500);
                        } else {
                            jQuery('#results').hide().html("Нет участников").fadeIn(1500);
                        }

                        // getResultList();

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
    
    function getResultList() {
        jQuery.ajax({
            url: gmaPlugin.ajaxurl, 
            type: "POST",             
            data: {
                action: 'getResultList'
            },
            cache: false,             
            processData: true,      
            success: function(data) {
                jQuery('#loader').hide();
                var resultList = JSON.parse(data);
                console.log(resultList);

            jQuery.each(resultList, function(key, value) {   
                         jQuery('#resultList')
                            .append(jQuery("<option></option>")
                            .attr("value",key[0])
                            .text(value[0])); 
            });
                
            },
            error: function(data) {
                jQuery('#loader').hide();
                alert('Что то пошлое не так. Напишите на наш email: music-competiiton@yandex.ru')
            }
        });    
    }


    

});

function setResult(competitorId) {
    var newResult = jQuery('#' + competitorId).val()
    
    jQuery.ajax({
        url: gmaPlugin.ajaxurl, 
        type: "POST",             
        data: {
            action: 'setDataAdmin',
            competitorId: competitorId,
            result: newResult
        },
        cache: false,             
        processData: true,      
        success: function(data) {
            jQuery('#loader').hide();
            // alert ("Данные сохранены");
            console.log(data);
        },
        error: function(data) {
            jQuery('#loader').hide();
            alert('Что то пошлое не так. Напишите на наш email: music-competiiton@yandex.ru')
            console.log(data);
        }
    });   
}