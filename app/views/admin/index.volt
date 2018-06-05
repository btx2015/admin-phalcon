<div class="layui-fluid">
    <div class="layui-row">
        <div class="layui-card">
            <div class="layui-card-body">
                <div class="layui-form-item">
                    <div class="layui-inline layui-col-md12" id="actions">
                        <div class="layui-input-inline" style="width: 330px;">
                            <div class="layui-btn-group">
                                {% if access['add'] is defined %}
                                <button class="layui-btn" data-method="add">增加</button>
                                {% endif %}
                                {% if access['enable'] is defined %}
                                <button class="layui-btn layui-btn-normal" data-method="enable">启用</button>
                                {% endif %}
                                {% if access['disable'] is defined %}
                                <button class="layui-btn layui-btn-warm" data-method="disable">禁用</button>
                                {% endif %}
                                {% if access['delete'] is defined %}
                                <button class="layui-btn layui-btn-danger" data-method="delete">删除</button>
                                {% endif %}
                            </div>
                        </div>
                        <div class="layui-layout-right">
                            <div class="layui-input-inline" style="width: 100px;">
                                <input type="text" class="layui-input filter" name="name" placeholder="角色名称">
                            </div>
                            <div class="layui-input-inline" style="width: 100px;">
                                <select name="state" class="layui-input filter">
                                    <option value="">状态</option>
                                    <option value="1">启用</option>
                                    <option value="2">禁用</option>
                                </select>
                            </div>
                            <div class="layui-input-inline" style="width: 300px;">
                                <input type="text" name="range" class="layui-input filter" id="date" placeholder="创建时间">
                            </div>
                            <div class="layui-input-inline" style="width: 150px;">
                                <div class="layui-btn-group">
                                    <button class="layui-btn" data-method="find">查询</button>
                                    <button class="layui-btn" data-method="reload">重置</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="layui-table" id="table" lay-filter="toolbar"></table>
            </div>
        </div>
    </div>
</div>
<div id="add" style="width:490px;padding-top: 20px !important;display: none;">
    <form class="layui-form" id="addForm">
        <div class="layui-form-item">
            <label class="layui-form-label">角色</label>
            <div class="layui-input-block">
                <select name="role_id" lay-verify="required" lay-search></select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">登录名</label>
            <div class="layui-input-block">
                <input type="text" name="username" lay-verify="required" placeholder="请输入登录名" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">姓名</label>
            <div class="layui-input-block">
                <input type="text" name="true_name" lay-verify="required" placeholder="请输入姓名" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">手机</label>
            <div class="layui-input-block">
                <input type="text" name="phone" lay-verify="required" placeholder="请输入手机" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">邮箱</label>
            <div class="layui-input-block">
                <input type="text" name="email" lay-verify="required" placeholder="请输入邮箱" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="addSubmit">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>
</div>

<div id="edit" style="width:490px;padding-top: 20px !important;display: none;">
    <form class="layui-form" id="editForm" lay-filter="editForm">
        <div class="layui-form-item">
            <input type="hidden" name="id">
            <label class="layui-form-label">角色父级</label>
            <div class="layui-input-block">
                <select name="pid" lay-verify="required" lay-search></select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">角色名称</label>
            <div class="layui-input-block">
                <input type="text" name="name" lay-verify="required" placeholder="请输入角色名称" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="editSubmit">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>
</div>

<script type="text/html" id="toolbar">
    {% if access['edit'] is defined %}
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    {% endif %}
    {% if access['access'] is defined %}
        <a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="assign">分配权限</a>
    {% endif %}
</script>