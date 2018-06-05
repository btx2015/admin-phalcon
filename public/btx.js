layui.use('element', function(){
    var element = layui.element;
    var $ = layui.jquery;
    //一些事件监听
    element.on('tab(demo)', function(data){
        console.log(data);
    });

    element.on('nav(sidebar)', function(elem){
        if(typeof elem.attr('lay-href') !== 'undefined'){
            var flag = true;
            $('.layui-tab-title li').each(function(){
                if($(this).attr('lay-id') === elem.attr('lay-href')){
                    flag = false;
                    return false;
                }
            });
            if(flag){
                element.tabAdd('bread', {
                    title: elem.text()
                    ,content: '<div class="layui-tab-item layui-show"><iframe width="100%" height="100%" frameborder="0" scrolling="no" src="'+elem.attr('lay-href')+'"></iframe></div>'
                    ,id: elem.attr('lay-href')
                });
                FrameWH();
            }
            element.tabChange('bread', elem.attr('lay-href'));
        }
    });

    function FrameWH() {
        var h = $(window).height();
        $("iframe").css("height",h+"px");
    }

    $(window).resize(function () {
        FrameWH();
    })
});