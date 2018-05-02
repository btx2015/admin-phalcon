<div class="layui-fluid">
    <div class="layui-row">
        <div class="layui-card">
            <div class="layui-card-header">角色管理</div>
            <div class="layui-card-body">
                <div class="layui-form-item">
                <div class="layui-inline layui-col-md12">
                    <div class="layui-input-inline" style="width: 330px;">
                        <div class="layui-btn-group">
                            <button class="layui-btn">增加</button>
                            <button class="layui-btn">编辑</button>
                            <button class="layui-btn layui-btn-normal">启用</button>
                            <button class="layui-btn layui-btn-warm">禁用</button>
                            <button class="layui-btn layui-btn-danger">删除</button>
                        </div>
                    </div>
                    <div class="layui-layout-right">
                        <div class="layui-input-inline" style="width: 100px;">
                            <input type="text" class="layui-input" placeholder="角色名称">
                        </div>
                        <div class="layui-input-inline" style="width: 100px;">
                            <select name="city" class="layui-input">
                                <option value="">状态</option>
                                <option value="1">启用</option>
                                <option value="2">禁用</option>
                            </select>
                        </div>
                        <div class="layui-input-inline" style="width: 300px;">
                            <input type="text" class="layui-input" id="test1" placeholder="创建时间">
                        </div>
                        <div class="layui-input-inline" style="width: 150px;">
                            <div class="layui-btn-group">
                                <button class="layui-btn">查询</button>
                                <button class="layui-btn">刷新</button>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <table id="test"></table>
            </div>
        </div>
    </div>
</div>
<!--<script type="text/html" id="barDemo">-->
    <!--<a class="layui-btn layui-btn-xs" lay-event="detail">查看</a>-->
    <!--<a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>-->
    <!--<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>-->
<!--</script>-->