$(function(){
    /*初始化导航条*/
    var navActiveCatch = false;
    //初始化伸缩
    $('body.manage>.sidebar .nav li.parent').each(function(){
        if(navActiveCatch)
            return;

        var reg = $(this).data('active-url');
        if(reg) {
            var regexp = new RegExp(reg);
            if(regexp.test(location.href)) {
                navActiveCatch = true;
                var $li = $(this).find('li');
                var height = $li.outerHeight(true);
                $(this).css('height', (height*($li.length+1)-1)+'px');
                $(this).addClass('active');
            }
        }
    });
    //点击伸缩
    $('body.manage>.sidebar .nav li.parent>a').on('click', function(){
        var $parent = $(this).parent();
        var $li = $parent.find('li');
        var height = $li.outerHeight(true);

        if(!$parent.hasClass('active')) {
            $parent.css('height', (height*($li.length+1)-1)+'px');
        } else {
            $parent.css('height', height+'px');
        }

        $parent.toggleClass('active');
        return false;
    });
    /*初始化导航条 end*/
});