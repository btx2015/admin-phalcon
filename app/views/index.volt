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
<body class="layui-layout-body" style="height:100%;">
    {{ content() }}

    {{ assets.outputJs()}}
</body>
</html>