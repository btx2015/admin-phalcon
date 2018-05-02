layui.use('table', function(){
    var table = layui.table;

    //第一个实例
    table.render({
        elem: '#test'
        ,url: '/admin/role/list' //数据接口
        ,method:'post'
        ,page: true //开启分页
        ,cols: [[ //表头
            {field: 'id', width:50, sort: true, fixed: 'left',checkbox : true}
            ,{field: 'name', title: '角色名称'}
            ,{field: 'pid_str', title: '父级名称'}
            ,{field: 'state_str', title: '状态'}
            ,{field: 'created_at_str', title: '创建时间'}
        ]]
    });
});

layui.use('laydate', function(){
    var laydate = layui.laydate;

    //执行一个laydate实例
    laydate.render({
        elem: '#test1' //指定元素
        ,type: 'datetime'
        ,range: true
    });
});