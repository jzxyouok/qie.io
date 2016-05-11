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
    /*搜索form*/
    var node = document.getElementById('search_form');
    if(node)
        node.addEventListener('submit', function(e){
            var data = $u.getFormValues(e.target || e.srcElement);
            if(!data.word) {
                e.preventDefault();
                alert('请填写关键词');
            }
        });
    //点击table变换checkbox
    node = document.querySelector('.select-table table');
    if(node)
        node.addEventListener('click', function(e){
            if(e.target.nodeName.toLowerCase() == 'td') {
                var checkbox = e.target.parentNode.querySelector('input[type=checkbox]');
                if(checkbox)
                    checkbox.checked = !checkbox.checked;
            }
        });
    /*选择按钮*/
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
    //快捷编辑
    $('.panel table tbody td.edit input').on('change', function(){
        $.ajax({url:this.dataset.action,
            method: "post",
            data: {"field":this.dataset.field,"value":this.value},
            dataType: "json",
            success: function(data){
                if(data.status< 1) {
                    alert(data.result);
                }
            },
            error: function(xhr, data) {}
        });
    });
    //按钮转ajax
    $('.panel .body .manage a.ajax').on('click', function(e){
        $.get(this.href,function(data){
            if(data.status< 1) {
                alert(data.result);
            } else {
                alert(data.result?data.result:'操作成功');
            }}, 'json');
        return false;
    });
});