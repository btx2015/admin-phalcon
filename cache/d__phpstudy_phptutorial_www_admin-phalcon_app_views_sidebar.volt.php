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
        <li class="">
            <?php if (isset($item['child'])) { ?>
            <a href="javascript:;">
                <i class="icon-cogs"></i>
                <span class="title"><?= $item['tittle'] ?></span>
                <span class="arrow "></span>
            </a>
            <ul class="sub-menu">
                <?php foreach ($item['child'] as $item) { ?>
                <li>
                    <a href="<?= $item['href'] ?>"><i class="<?= $item['icon'] ?>"></i><?= $item['tittle'] ?></a>
                </li>
                <?php } ?>
            <?php } else { ?>

                <a href="<?= $item['href'] ?>">
                    <i class="<?= $item['icon'] ?>"></i>
                    <span class="title"><?= $item['tittle'] ?></span>
                    <span class=" "></span>
                </a>
                <?php } ?>
        </li>
        <?php } ?>
    </ul>
    <!-- END SIDEBAR MENU -->
</div>