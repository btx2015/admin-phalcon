<style type="text/css">
    .layui-icon { padding-right: 10px; }
    .layui-nav { background-color:#20222A; }
    dd a { margin-left: 30px; }
</style>
<div class="layui-side layui-bg-black">
    <div class="layui-side-scroll">
        <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
        <ul class="layui-nav layui-nav-tree" lay-shrink="all" lay-filter="test">
            {% for item in menu %}
                {% if item['active'] is defined %}
                    <li class="layui-nav-item layui-nav-itemed">
                {% else %}
                    <li class="layui-nav-item">
                {% endif %}
                    {% if item['child'] is defined  %}
                        <a href="javascript:;"><i class="layui-icon">{{ item['icon'] }}</i><cite>{{ item['tittle'] }}</cite></a>
                            <dl class="layui-nav-child">
                                {% for item in item['child'] %}
                                    {% if item['active'] is defined %}
                                        <dd class="layui-this">
                                    {% else %}
                                        <dd>
                                    {% endif %}
                                            <a href="{{ item['href'] }}"><cite>{{ item['tittle'] }}</cite></a>
                                        </dd>
                                {% endfor %}
                            </dl>
                    {% else %}
                        <a href="{{ item['href'] }}"><i class="layui-icon">{{ item['icon'] }}</i><cite>{{ item['tittle'] }}</cite></a>
                {% endif %}
                </li>
            {% endfor %}
        </ul>
    </div>
</div>