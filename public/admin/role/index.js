layui.use(['table','layer','laydate','form'], function(){
    var table = layui.table;
    var layer = layui.layer;
    var laydate = layui.laydate;
    var $ = layui.jquery;
    var form = layui.form;

    function request(url,data,msg){
        $.post(url
            ,data
            ,function(res){
                if(res.code === 0){
                    $(".layui-laypage-btn").click();
                    layer.closeAll();
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
        // var tr = obj.tr; //获得当前行 tr 的DOM对象

        if(layEvent === 'edit'){ //编辑
            layer.open({
                type: 1
                ,title: '编辑'
                ,area: ['520px','500px']
                ,id: 'layerDemo'+ 'edit' //防止重复弹出
                ,content: $('#edit')
                ,btnAlign: 'c' //按钮居中
                ,shade: 0 //不显示遮罩
                ,success: function(layero){
                    for(var key in data){
                        var field = $("#editForm :input[name='"+key+"']");
                        if(field.length > 0){
                            field.val(data[key]);
                            form.render();
                        }
                    }
                }
                ,cancel: function(index, layero){
                    $('#edit').hide();
                    layer.close(index)
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
                ,area: '520px'
                ,id: 'layerDemo'+text //防止重复弹出
                ,content: $('#add')
                ,btnAlign: 'c' //按钮居中
                ,shade: 0 //不显示遮罩
                ,cancel: function(index, layero){
                    $('#add').hide();
                    layer.close(index)
                }
            });
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

    $('#actions .layui-btn').on('click', function(){
        var othis = $(this), method = othis.data('method');
        active[method] ? active[method].call(this, othis) : '';
    });

    form.verify({
        name: function(value, item){ //value：表单的值、item：表单的DOM对象
            if(!new RegExp("^[a-zA-Z0-9_\u4e00-\u9fa5\\s·]+$").test(value)){
                return '用户名不能有特殊字符';
            }
            if(/(^\_)|(\__)|(\_+$)/.test(value)){
                return '用户名首尾不能出现下划线\'_\'';
            }
            if(/^\d+\d+\d$/.test(value)){
                return '用户名不能全为数字';
            }
        }

        //我们既支持上述函数式的方式，也支持下述数组的形式
        //数组的两个值分别代表：[正则匹配、匹配不符时的提示文字]
        ,pid: [
            /^[\S]{6,12}$/
            ,'密码必须6到12位，且不能出现空格'
        ]
    });

    form.on('submit(addSubmit)', function(data){
        layer.confirm('确认增加吗', {icon: 3, title:'提示'}, function(index){
            layer.close(index);
            request('add',data.field,'增加成功');
        });
        return false;
    });
    form.on('submit(editSubmit)', function(data){
        layer.confirm('确认修改吗', {icon: 3, title:'提示'}, function(index){
            layer.close(index);
            request('edit',data.field,'编辑成功');
        });
        return false;
    });
});