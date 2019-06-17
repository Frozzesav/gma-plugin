jQuery(document).ready(function($) {

function sendData (id, val) {
	$.ajax({
		url:'/wp-content/plugins/gma-plugin/includes/update-data-cell.php',
		type: 'POST',
		data: {score: val, id: id},
		success: function (data){
			alert(data);
			//$('.edit').blur();
		},
		error: function (){
			//alert("Error!");
		}
	});
}
	var oldVal;
	var editId;
 	$('.edit').focus(function() {
		oldVal = $(this).text();
		editId = $(this).data('id');
	});

 	$('.edit').keypress(function(e) {
		var newVal = $(this).text();
		if (e.which == 13 && oldVal != newVal) {
			sendData(editId, newVal);
			return false;
		}

	});







 // 	$('.edit').blur(function() {



	// 	var newVal = $(this).text();



	// 	console.log('blur newVal = ' + newVal);



 // 		if (newVal != oldVal) { sendData(editId, newVal); }



	// });







});





// Вариант с кнопкой ОК



// var table = document.getElementById('zebra');







// var editingTd;







// table.onclick = function(event) {



     //editId = 



//   var target = event.target;







//   while (target != table) {



//     if (target.className == 'edit-cancel') {



//       finishTdEdit(editingTd.elem, false);



//       return;



//     }







//     if (target.className == 'edit-ok') {



//       finishTdEdit(editingTd.elem, true);



//       return;



//     }







//     if (target.nodeName == 'TD') {



//       if (editingTd) return; // already editing







//       makeTdEditable(target);



//       return;



//     }







//     target = target.parentNode;



//   }



// }







// function makeTdEditable(td) {



//   editingTd = {



//     elem: td,



//     data: td.innerHTML



//   };







//   td.classList.add('edit-td'); // td, not textarea! the rest of rules will cascade







//   var textArea = document.createElement('textarea');



//   textArea.style.width = td.clientWidth + 'px';



//   textArea.style.height = td.clientHeight + 'px';



//   textArea.className = 'edit-area';







//   textArea.value = td.innerHTML;



//   td.innerHTML = '';



//   td.appendChild(textArea);



//   textArea.focus();







//   td.insertAdjacentHTML("beforeEnd",



//     '<div class="edit-controls"><button class="edit-ok">OK</button><button class="edit-cancel">CANCEL</button></div>'



//   );



// }







// function finishTdEdit(td, isOk) {



//   if (isOk) {



//     td.innerHTML = td.firstChild.value;



//     //sendData(editId, td.firstChild.value);



//   } else {



//     td.innerHTML = editingTd.data;



//   }



//   td.classList.remove('edit-td'); // remove edit class



//   editingTd = null;



// }