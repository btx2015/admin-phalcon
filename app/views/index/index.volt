<div class="layui-layout layui-layout-admin">

    {% include('header') %}

    {% include('sidebar') %}

    <div class="layui-body" style="background-color: #eee;">
        <div class="layui-tab"  lay-allowClose="true" lay-filter="demo" style="background-color: #fff;margin-top:0;">
            <ul class="layui-tab-title" id="breadcrumb-title">
                <li class="layui-this">首页</li>
            </ul>
            <div class="layui-tab-content" id="breadcrumb-content">
                <div class="layui-tab-item layui-show">
                    <h1>首页</h1>
                    <h1>首页</h1>
                    <h1>首页</h1>
                    <h1>首页</h1>
                    <h1>首页</h1>
                </div>
            </div>
        </div>
    </div>

    {% include('footer') %}
</div>