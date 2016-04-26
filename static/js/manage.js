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
            if(regexp.test(location.pathname)) {
                navActiveCatch = true;
                var $li = $(this).find('li');
                if($li.length>0) {
                    var height = $li.outerHeight(true);
                    $(this).css('height', (height * ($li.length + 1) - 1) + 'px');
                }
                $(this).addClass('active');
            }
        }
    });
    //点击伸缩
    $('body.manage>.sidebar .nav li.parent>a').on('click', function(){
        var $parent = $(this).parent();
        var $li = $parent.find('li');
        if($li.length>0) {
            var height = $li.outerHeight(true);

            if(!$parent.hasClass('active')) {
                $parent.css('height', (height*($li.length+1)-1)+'px');
            } else {
                $parent.css('height', height+'px');
            }
            $parent.toggleClass('active');
            return false;
        }
    });
    /*初始化导航条 end*/
    $('.select-table a.select').on('click', function(){
        $(this).parents('.select-table').eq(0).find('input[type=checkbox]:not(":disabled")').each(function(){
            this.checked = !this.checked;
        });
        return false;
    });
    $('.select-table a.unselect').on('click', function(){
        $(this).parents('.select-table').eq(0).find('input[type=checkbox]:not(":disabled")').each(function(){
            this.checked = false;
        });
        return false;
    });
});