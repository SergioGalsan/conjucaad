/*
var close = document.getElementById('btnClosePopup');
var popup = document.getElementById('popup');

close.addEventListener("click", function() {
  popup.style.display = 'none';
});
*/
var url = $('meta[name=base-url]').attr('content')

$(document).on('change','select', function(){
    if($(this).val() != '')
        location.href = url + "/" + $(this).attr('path') + "/" + $(this).val();
})


