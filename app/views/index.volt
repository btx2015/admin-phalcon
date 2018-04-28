<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>网站后台管理模版</title>
    {{ assets.outputCss()}}
    <style type="text/css">
        .layui-fluid {padding: 15px;}
    </style>
</head>
<body class="layui-layout-body">
    <div class="layui-layout layui-layout-admin">

        {% include('header') %}

        {% include('sidebar') %}

        <div class="layui-body" style="background-color: #eee;">
            {{ content() }}
        </div>

        {% include('footer') %}
    </div>
{{ assets.outputJs()}}
</body>
</html>