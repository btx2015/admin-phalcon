<style type="text/css">
    .layui-nav-tree .layui-icon { padding-right: 10px; }
    .layui-nav { background-color:#20222A; }
    .layui-nav-tree dd a { margin-left: 30px; }
</style>
<div class="layui-side layui-bg-black">
    <div class="layui-side-scroll">
        <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
        <ul class="layui-nav layui-nav-tree" lay-shrink="all" lay-filter="test">
            <?php foreach ($menu as $item) { ?>
                <?php if (isset($item['active'])) { ?>
                    <li class="layui-nav-item layui-nav-itemed">
                <?php } else { ?>
                    <li class="layui-nav-item">
                <?php } ?>
                    <?php if (isset($item['child'])) { ?>
                        <a href="javascript:;"><i class="layui-icon"><?= $item['icon'] ?></i><cite><?= $item['tittle'] ?></cite></a>
                            <dl class="layui-nav-child">
                                <?php foreach ($item['child'] as $item) { ?>
                                    <?php if (isset($item['active'])) { ?>
                                        <dd class="layui-this">
                                    <?php } else { ?>
                                        <dd>
                                    <?php } ?>
                                            <a href="<?= $item['href'] ?>"><cite><?= $item['tittle'] ?></cite></a>
                                        </dd>
                                <?php } ?>
                            </dl>
                    <?php } else { ?>
                        <a href="<?= $item['href'] ?>"><i class="layui-icon"><?= $item['icon'] ?></i><cite><?= $item['tittle'] ?></cite></a>
                <?php } ?>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>