layui.use(['table','layer','laydate','form'], function(){
    var table = layui.table;
    var layer = layui.layer;
    var laydate = layui.laydate;
    var $ = layui.jquery;

    function request(url,data,msg,index){
        $.post(url
            ,data
            ,function(res){
                if(res.code === 0){
                    $(".layui-laypage-btn").click();
                    if(typeof index !== 'undefined'){
                        layer.close(index);
                    }
                    layer.msg(msg,{time:2000});
                    return true;
                }else{
                    layer.msg('[ ' + res.code + ' ] ' + res.msg,{time:5000});
                    return false;
                }
            }
            ,'json');
    }

    function handleCheck(msg){
        var checkStatus = table.checkStatus('table');
        if(checkStatus.data.length < 1){
            layer.msg(msg);
            return false;
        }
        var res = [];
        for(var key in checkStatus.data){
            res.push(checkStatus.data[key].id);
        }
        return res;
    }

    var tableIns = table.render({
        elem: '#table'
        ,url: '/admin/role/list'
        ,where: {}
        ,method:'post'
        ,page: true //开启分页
        ,cols: [[ //表头
            {field: 'id', width:50, sort: true, fixed: 'left',checkbox : true}
            ,{field: 'name', title: '角色名称'}
            ,{field: 'pid_str', title: '父级名称'}
            ,{field: 'state_str', title: '状态'}
            ,{field: 'created_at_str', title: '创建时间'}
            ,{title: '操作',fixed: 'right', width:150, align:'center', toolbar: '#toolbar'}
        ]]
        ,done: function(res, curr, count){
            data = res.data;
        }
    });

    table.on('tool(toolbar)', function(obj){ //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
        var data = obj.data; //获得当前行数据
        var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
        var tr = obj.tr; //获得当前行 tr 的DOM对象

        if(layEvent === 'edit'){ //编辑
            layer.open({
                type: 1
                ,title: '编辑'
                ,area: ['520px','500px']
                ,id: 'layerDemo'+ '编辑' //防止重复弹出
                ,content: $('#edit')
                ,btn: ['提交']
                ,btnAlign: 'c' //按钮居中
                ,shade: 0 //不显示遮罩
                ,cancel: function(index, layero){
                    $('#edit').hide();
                    layer.close(index)
                }
                ,yes: function(index, layero){
                    if(confirm('确认添加吗')){ //只有当点击confirm框的确定时，该层才会关闭
                        request('add',$("#editForm").serialize(),'增加成功',index);
                        //同步更新缓存对应的值
                        obj.update({
                            username: '123'
                            ,title: 'xxx'
                        });
                    }
                    return false;
                }
            });
        }
    });

    function getData(filter){
        tableIns.reload({
            where: filter
            ,page: {
                curr: 1 //重新从第 1 页开始
            }
        });
    }

    //执行一个laydate实例
    laydate.render({
        elem: '#date' //指定元素
        ,type: 'datetime'
        ,range: true
    });

    var active = {
        add: function(othis){
            //示范一个公告层
            var text = othis.text();
            layer.open({
                type: 1
                ,title: text
                ,area: ['520px','500px']
                ,id: 'layerDemo'+text //防止重复弹出
                ,content: $('#add')
                ,btn: ['提交','重置']
                ,btnAlign: 'c' //按钮居中
                ,shade: 0 //不显示遮罩
                ,cancel: function(index, layero){
                    $('#add').hide();
                    layer.close(index)
                }
                ,yes: function(index, layero){
                    layer.confirm('确认增加吗', {icon: 3, title:'提示'}, function(indexs){
                        layer.close(indexs);
                        request('add',$("#addForm").serialize(),'增加成功',index);
                    });
                    return false;
                }
            });
        }
        ,edit: function(){

            // $(".layui-laypage-btn").click()
        }
        ,enable: function(){
            var checkData = handleCheck('请选择要启用的角色');
            if(checkData){
                layer.confirm('确认启用吗', {icon: 3, title:'提示'}, function(indexs){
                    layer.close(indexs);
                    request('enable',{'roles':checkData},'启用成功');
                });
            }
        }
        ,disable: function(){
            var checkData = handleCheck('请选择要禁用的角色');
            if(checkData){
                layer.confirm('确认禁用吗', {icon: 3, title:'提示'}, function(indexs){
                    layer.close(indexs);
                    request('disable',{'roles':checkData},'禁用成功');
                });
            }
        }
        ,delete: function(){
            var checkData = handleCheck('请选择要删除的角色');
            if(checkData){
                layer.confirm('确认删除吗', {icon: 3, title:'提示'}, function(indexs){
                    layer.close(indexs);
                    request('delete',{'roles':checkData},'删除成功');
                });
            }
        }
        ,find: function(){
            var filter = {};
            $(".filter").each(function(){
                if($(this).val() !== ''){
                    filter[$(this).attr('name')] = $(this).val();
                }
            });
            getData(filter)
        }
        ,reload: function(){
            var filter = {};
            $(".filter").each(function(){
                $(this).val('');
            });
            getData(filter)
        }
    };

    $('#button .layui-btn').on('click', function(){
        var othis = $(this), method = othis.data('method');
        active[method] ? active[method].call(this, othis) : '';
    });

    $('#toolbar .layui-btn').on('click', function(){
        var othis = $(this), method = othis.data('method');
        active[method] ? active[method].call(this, othis) : '';
    });
});