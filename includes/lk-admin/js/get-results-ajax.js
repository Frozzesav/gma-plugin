// jQuery(document).ready(function(){
//     jQuery('.showCompetitors').on('click', function(){
//         var competitionId = jQuery('#competitionResults').val();
//         var specialtiesResults = jQuery('#specialtiesResults').val();
//         jQuery('#loader').show();
    
//         if (competitionId != null) {
            
//             jQuery.ajax({
//                     url: gmaPlugin.ajaxurl, 
//                     type: "POST",             
//                     data: {
//                         action: 'getResultsAdmin',
//                         competitionId: competitionId,
//                         specialtyId: specialtiesResults
//                     },
//                     cache: false,             
//                     processData: true,      
//                     success: function(data) {
//                         // console.log(data);
//                         jQuery('#loader').hide();
//                         var competitors = JSON.parse(data);
//                         console.log(competitors);



// /* The function */

// function json2table(json, classes) {
//     var cols = Object.keys(json[0]);
    
//     var headerRow = '';
//     var bodyRows = '';
    
//     classes = classes || '';
  
//     function capitalizeFirstLetter(string) {
//       return string.charAt(0).toUpperCase() + string.slice(1);
//     }
  
//     cols.map(function(col) {
//       headerRow += '<th>' + capitalizeFirstLetter(col) + '</th>';
//     });
  
//     json.map(function(row) {
//       bodyRows += '<tr>';
  
//       cols.map(function(colName) {
//         bodyRows += '<td contenteditable="true">' + row[colName] + '</td>';
//       })
  
//       bodyRows += '</tr>';
//     });
  
//     return '<table class="' +
//            classes +
//            '"><thead><tr>' +
//            headerRow +
//            '</tr></thead><tbody>' +
//            bodyRows +
//            '</tbody></table>';
//   }
  
//   /* How to use it */
  
//   var defaultData = competitors;

//   document.getElementById('tableGoesHere').innerHTML = json2table(defaultData, 'table');
  
//   /* Live example */
  
//   var dom = {
//     table: document.getElementById('tableGoesHere'),
//   };

                        
//                         if (data = '[]') {
//                             jQuery('#results').hide().html("Нет участников").fadeIn(1500);
                            
//                         }
                        
                        
                        
//                         var oldVal;
//                         var editId; // они тут используютя?????
                        
//                         jQuery('.edit').focus(function(){
//                             // jQuery('.edit').nextAll('button').hide(120);
//                             jQuery(this).nextAll('button').show(100);
//                             oldVal = jQuery(this).val();
//                             editId = jQuery(this).attr('data-competitor-id');
//                         });

//                         // jQuery('.edit').blur(function(){
//                         //     jQuery('.edit').nextAll('button').hide(120);
//                         //     // jQuery(this).nextAll('button').show(100);
//                         // });


//                         var newVal;

                       
//                     },
//                     error: function(data) {
//                         jQuery('#loader').hide();
//                         alert('Что то пошлое не так. Напишите на наш email: music-competiiton@yandex.ru')
//                         console.log(data);
//                     }
//                 });
//             } else {
//                 jQuery('#loader').hide();
//                 alert("Выберите конкурс");
//             }    
//         });

        
//     });
    
//     function ajaxSetScore(id, val, comment, competitionId) {
        
//         jQuery.ajax({
//             url: gmaPlugin.ajaxurl, 
//             type: "POST",             
//             data: {
//                 action: 'setDataJury',
//                 competitionId: competitionId,
//                 competitorId: id,
//                 score: val,
//                 comment: comment
//             },
//             cache: false,             
//             processData: true,      
//             success: function(data) {
//                 jQuery('#loader').hide();
//                 alert ("Данные сохранены");
//                 console.log(data);
//             },
//             error: function(data) {
//                 jQuery('#loader').hide();
//                 alert('Что то пошлое не так. Напишите на наш email: music-competiiton@yandex.ru')
//                 console.log(data);
//             }
//         });   
//     }

    
//     function saveScore(competitorId) {
//         var competitionId = jQuery('#competitionResults').val();
//         var newVal = jQuery('#competitor-value-'+ competitorId).val();

//         var newComment = jQuery('#competitor-comment-'+ competitorId).html();

//         newVal = parseFloat(newVal.replace(',','.').replace(' ','')).toFixed(2);

        
//         ajaxSetScore(competitorId, newVal, newComment, competitionId);
//         jQuery('.edit').nextAll('button').hide(100); 
//     }




//     function cancel(id) {
//             jQuery('.edit').nextAll('button').hide(100); // КАК сделать отмн
//     }




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