<div class="layui-fluid">
    <div class="layui-row">
        <div class="layui-card">
            <div class="layui-card-header">角色管理</div>
            <div class="layui-card-body">
                <div class="layui-form-item">
                    <div class="layui-inline layui-col-md12" id="actions">
                        <div class="layui-input-inline" style="width: 330px;">
                            <div class="layui-btn-group">
                                <button class="layui-btn" data-method="add">增加</button>
                                <button class="layui-btn layui-btn-normal" data-method="enable">启用</button>
                                <button class="layui-btn layui-btn-warm" data-method="disable">禁用</button>
                                <button class="layui-btn layui-btn-danger" data-method="delete">删除</button>
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
            <label class="layui-form-label">角色父级</label>
            <div class="layui-input-block">
                <select name="pid" lay-verify="required" lay-search>
                    <option value="">请选择角色父级</option>
                    <option value="1">上海</option>
                    <option value="2">广州</option>
                    <option value="3">深圳</option>
                    <option value="4">杭州</option>
                    <option value="0">北京</option>
                    <option value="1">上海</option>
                    <option value="2">广州</option>
                    <option value="3">深圳</option>
                    <option value="4">杭州</option>
                    <option value="0">北京</option>
                    <option value="1">上海</option>
                    <option value="2">广州</option>
                    <option value="3">深圳</option>
                    <option value="4">杭州</option>
                </select>
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
                <button class="layui-btn" lay-submit lay-filter="addSubmit">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>
</div>

<div id="edit" style="width:490px;padding-top: 20px !important;display: none;">
    <form class="layui-form" id="editForm">
        <div class="layui-form-item">
            <input type="hidden" name="id">
            <label class="layui-form-label">角色父级</label>
            <div class="layui-input-block">
                <select name="pid" lay-verify="required" lay-search>
                    <option value="">请选择角色父级</option>
                    <option value="1">上海</option>
                    <option value="2">广州</option>
                    <option value="3">深圳</option>
                    <option value="4">杭州</option>
                    <option value="0">北京</option>
                    <option value="1">上海</option>
                    <option value="2">广州</option>
                    <option value="3">深圳</option>
                    <option value="4">杭州</option>
                    <option value="0">北京</option>
                    <option value="1">上海</option>
                    <option value="2">广州</option>
                    <option value="3">深圳</option>
                    <option value="4">杭州</option>
                </select>
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
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
</script>
