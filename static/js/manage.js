$(function(){
    $('body.manage>.sidebar .nav li.parent a').on('click', function(){
        $(this).parent().toggleClass('active');
        return false;
    });
})