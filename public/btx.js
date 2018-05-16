layui.use('element', function(){
    var element = layui.element;
    var $ = layui.jquery;
    //一些事件监听
    element.on('tab(demo)', function(data){
        console.log(data);
    });

    element.on('nav(sidebar)', function(elem){
        if(typeof elem.attr('lay-href') !== 'undefined'){
            console.log(elem.attr('lay-href')); //得到当前点击的DOM对象
            console.log(elem.text());
            $('#breadcrumb-title .layui-this').removeClass('layui-this');
            $('#breadcrumb-content .layui-show').removeClass('layui-show');
            $('#breadcrumb-title').append('<li class="layui-this">'+elem.text()+'</li>');
            $('#breadcrumb-content').append('<div class="layui-tab-item layui-show"><iframe width="100%" height="100%" frameborder="0" scrolling="no" src="'+elem.attr('lay-href')+'"></iframe></div>');
        }
    });
});