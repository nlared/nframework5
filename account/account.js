function re(){
	var form=$('#newform');
	var top = parseInt(($(window).height() - form.outerHeight()) / 2);
	form.css({top: (top > 0 ? top : 0)+'px'});
}

$( window ).resize(function() {
   re();
});
re();
function invalidForm(){
    var form  = $(this);
    form.addClass("ani-ring");
    setTimeout(function(){
        form.removeClass("ani-ring");
    }, 1000);
}

function validateForm(){
    $(".login-form").animate({
        opacity: 0
    });
}