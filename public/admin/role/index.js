layui.use(['table','layer','laydate','form','element'], function(){

    var table = layui.table;
    var layer = layui.layer;
    var laydate = layui.laydate;
    var $ = layui.jquery;
    var form = layui.form;
    var element = layui.element;

    function request(url,data,msg){//提交
        $.post(url
            ,data
            ,function(res){
                if(res.code === 0){
                    $(".layui-laypage-btn").click();
                    layer.closeAll();
                    layer.msg(msg,{time:2000});
                }else{
                    layer.msg('[ ' + res.code + ' ] ' + res.msg,{time:5000});
                }
            }
            ,'json');
        getRoles();//添加编辑角色，重置角色下拉框
    }

    function handleCheck(msg){//整理checkbox，便于批量处理
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

    var tableIns = table.render({//渲染表格
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
    });

    //执行一个laydate实例
    laydate.render({
        elem: '#date' //指定元素
        ,type: 'datetime'
        ,range: true
    });

    function getRoles(){//获取角色父级下拉框
        $.post('all',{},function(res){
            if(res.code === 0){
                var options = $("select[name='pid']");
                options.each(function(){
                    $(this).empty();
                    $(this).append('<option value="">请选择角色父级</option>');
                    for(var key in res.data){
                        $(this).append('<option value="'+ res.data[key].id + '">'+ res.data[key].name +'</option>');
                    }
                });
                form.render();
            }else{
                layer.msg('[ ' + res.code + ' ] ' + res.msg,{time:5000});
            }
        },'json');
    }

    getRoles();

    table.on('tool(toolbar)', function(obj){ //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
        var data = obj.data; //获得当前行数据
        var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
        // var tr = obj.tr; //获得当前行 tr 的DOM对象
        active[layEvent] ? active[layEvent].call(this, data) : '';
    });

    function getData(filter){//查询和重置
        tableIns.reload({
            where: filter
            ,page: {
                curr: 1 //重新从第 1 页开始
            }
        });
    }

    function getRolesAccess(rid){
        $.post('access',{rid:rid},function(res){
            if(res.code === 0){
                var roleAccess = res.data;
                var access = $("input[name='access[]']");
                access.each(function(){
                    for(var key in roleAccess){
                        if($(this).val() === roleAccess[key]){
                            $(this).prop('checked',true)
                            delete roleAccess[key];
                        }
                    }
                });
                $("input[name='rid']").val(rid);
                form.render('checkbox','assignForm');
            }else{
                layer.msg('[ ' + res.code + ' ] ' + res.msg,{time:5000});
            }
        },'json');
    }

    var active = {
        add: function(othis){
            //示范一个公告层
            var text = othis.text();
            layer.open({
                type: 1
                ,title: text
                ,area: '520px'
                ,id: 'layerDemoAdd' //防止重复弹出
                ,content: $('#add')
                ,btnAlign: 'c' //按钮居中
                ,shade: 0 //不显示遮罩
                ,cancel: function(index, layero){
                    $('#add').hide();
                    layer.close(index)
                }
            });
        }
        ,edit: function(data){
            layer.open({
                type: 1
                ,title: '编辑'
                ,area: '520px'
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
            getData(filter);
            getRoles();
        }
        ,assign: function(data){
            getRolesAccess(data.id);
            layer.open({
                type: 1
                ,title: '分配权限'
                ,area: '700px'
                ,id: 'layerDemo'+ 'assign' //防止重复弹出
                ,content: $('#assign')
                ,btnAlign: 'c' //按钮居中
                ,shade: 0 //不显示遮罩
                ,cancel: function(index, layero){
                    $('#assign').hide();
                    layer.close(index)
                }
            });
        }
    };

    $('#actions .layui-btn').on('click', function(){
        var othis = $(this), method = othis.data('method');
        active[method] ? active[method].call(this, othis) : '';
    });

    form.on('checkbox(controller)', function(data){
        var actions = $('.'+data.value);
        if(data.elem.checked){
            actions.prop('checked',true);
        }else{
            actions.prop('checked',false);
        }
        handleAssign();
    });

    form.on('checkbox(action)', function(data){
        var action = $('.action_'+data.value);
        var classes = action.attr('class').split(' ');
        var pid = classes[1];
        var controller = $('.controller_'+pid);
        if(data.elem.checked){
            controller.prop('checked',true);
        }else{
            var actions = $('.'+pid);
            var total = 0;
            actions.each(function(){
                if($(this).prop('checked') === true){
                    total ++;
                    return false;
                }
            });
            if(total === 0){
                controller.prop('checked',false);
            }
        }
        handleAssign();
    });

    function handleAssign(){
        $(".module").prop('checked',false);
        var controllers = $("input[class^='controller_']");
        controllers.each(function(){
            if($(this).prop('checked') === true){
                var classes = $(this).prop('class').split(' ');
                var pid = classes[1];
                $('.module_'+pid).prop('checked',true);
            }
        });
        form.render('checkbox','assignForm');
    }

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

    form.on('submit(assignSubmit)',function(data){
        layer.confirm('确认分配权限吗', {icon: 3, title:'提示'}, function(index){
            layer.close(index);
            request('assign',data.field,'分配成功');
        });
        return false;
    });
});