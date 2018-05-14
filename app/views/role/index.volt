<div class="layui-fluid">
    <div class="layui-row">
        <div class="layui-card">
            <div class="layui-card-header">角色管理</div>
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

<div id="assign" style="width:680px;padding: 10px !important;display: none;">
    <form class="layui-form" id="assignForm" lay-filter="assignForm">
        <input type="hidden" name="rid">
    <div class="layui-tab layui-tab-card">
        <ul class="layui-tab-title">
            {% for key,node in nodes %}
                {% if key == 0 %}
                <li class="layui-this">{{ node['tittle'] }}</li>
                {% else %}
                <li>{{ node['tittle'] }}</li>
                {% endif %}
            {% endfor %}
        </ul>
        <div class="layui-tab-content">
            {% for key,node in nodes %}
            <div style="display: none;">
                <input type="checkbox" name="access[]" value="{{ node['id'] }}" class="module_{{ node['id'] }} module">
            </div>
                {% if key == 0 %}
                <div class="layui-tab-item layui-show">
                {% else %}
                <div class="layui-tab-item">
                {% endif %}
                    {% for item in node['child'] %}
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <input type="checkbox" name="access[]" value="{{ item['id'] }}" title="{{ item['tittle'] }}" lay-filter="controller" class="controller_{{ item['id'] }} {{ node['id'] }}">
                        </div>
                        <hr>
                        {% if item['child'] is defined %}
                        <div class="layui-inline" style="margin-left: 50px;">
                            {% for items in item['child'] %}
                            <input type="checkbox" name="access[]" value="{{ items['id'] }}" title="{{ items['tittle'] }}" lay-filter="action" class="action_{{ items['id'] }} {{ item['id'] }}">
                            {% endfor %}
                        </div>
                        {% endif %}
                    </div>
                    <hr class="layui-bg-green">
                    {% endfor %}
                </div>
            {% endfor %}
        </div>
    </div>
        {% if access['assign'] is defined %}
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit lay-filter="assignSubmit">立即提交</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        {% endif %}
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
