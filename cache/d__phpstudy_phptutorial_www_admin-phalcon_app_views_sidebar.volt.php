<div class="page-sidebar nav-collapse collapse">
    <!-- BEGIN SIDEBAR MENU -->
    <ul class="page-sidebar-menu">
        <li>
            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
            <div class="sidebar-toggler hidden-phone"></div>
            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
        </li>
        <li>
            <!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
            <form class="sidebar-search">
                <div class="input-box">
                    <a href="javascript:;" class="remove"></a>
                    <input type="text" placeholder="Search..." />
                    <input type="button" class="submit" value=" " />
                </div>
            </form>
            <!-- END RESPONSIVE QUICK SEARCH FORM -->
        </li>
        <?php foreach ($menu as $item) { ?>
            <?php if (isset($item['active'])) { ?>
                <li class="active">
            <?php } else { ?>
                <li>
            <?php } ?>
                <?php if (isset($item['child'])) { ?>
                    <a href="javascript:;">
                        <i class="<?= $item['icon'] ?>"></i>
                        <span class="title"><?= $item['tittle'] ?></span>
                        <?php if (isset($item['active'])) { ?>
                        <span class="selected"></span>
                        <span class="arrow open"></span>
                        <?php } else { ?>
                        <span class="arrow "></span>
                        <?php } ?>
                    </a>
                    <ul class="sub-menu">
                        <?php foreach ($item['child'] as $item) { ?>
                        <?php if (isset($item['active'])) { ?>
                        <li class="active">
                            <?php } else { ?>
                        <li>
                        <?php } ?>
                            <a href="<?= $item['href'] ?>"><i class="<?= $item['icon'] ?>"></i><?= $item['tittle'] ?></a>
                        </li>
                        <?php } ?>
                    </ul>
                <?php } else { ?>
                    <a href="<?= $item['href'] ?>">
                        <i class="<?= $item['icon'] ?>"></i>
                        <span class="title"><?= $item['tittle'] ?></span>
                        <?php if (isset($item['active'])) { ?>
                        <span class="selected"></span>
                        <?php } else { ?>
                        <span class=""></span>
                        <?php } ?>
                    </a>
                <?php } ?>
            </li>
        <?php } ?>
    </ul>
    <!-- END SIDEBAR MENU -->
</div>