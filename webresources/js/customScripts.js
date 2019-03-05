if ("https:" == location.protocol)
    var protocolPath  = 'https://';
else
    var protocolPath  = 'http://';

if (window.location.hostname == 'localhost') {
    var  path       = protocolPath + 'localhost/MBTM/';
    var actionPath	= protocolPath + 'localhost/MBTM/';
}else{
	var  path       = protocolPath +'/';
    var actionPath	= protocolPath + '/';
}

function changeDate(ev){
	$(this).datepicker('hide');
	var dateObj = ev.date;
	var selectedDate = dateObj.getFullYear();
	$('.current-year-sel').html(selectedDate);
	changeYear(3);
}

function changeYear(selValue){
	var selectedDate = '';
	if(selValue == 1){
		selectedDate = $('.prev-year-sel').html();
	}else if(selValue == 2){
		selectedDate = $('.next-year-sel').html();
	}else{
		selectedDate = $('.current-year-sel').html();
	}
	$.ajax({
	        type: "POST",
	        url: actionPath+"models/ajaxAction.php",
	        data: 'action=GET_YEAR&page=0&year='+selectedDate,
	        success: function (result){
				//alert("--"+result);
				$('.current-year-sel').html(selectedDate);
				$('.prev-year-sel').html(selectedDate-1);
				$('.next-year-sel').html(parseInt(selectedDate)+1);
				if(result != ''){
					//alert('This order already assigned for some other service');
					$("#magazineList").html(result);
					$('#loadmore').val(1)
					return false;
				}
	        }			
	    });

}
$(document).ready(function() {

/* $('.container').infiniteScroll({
  // options
  path: actionPath+"models/ajaxAction.php?loadmore=1",
  append: '#magazineList',
  history: false,
}); */

/* $(".container").infiniteScroll({
    preloaderColor: "#007bff",
    files: [
        actionPath+"models/ajaxAction.php?loadmore=1"
    ],
    beforeLoadNewContent: function () { alert("The content is loaded"); },
    onEnd: function () { alert("End"); }
}); */

});

