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
        {% for item in menu %}
        <li class="">
            {% if item['child'] is defined %}
            <a href="javascript:;">
                <i class="icon-cogs"></i>
                <span class="title">{{ item['tittle'] }}</span>
                <span class="arrow "></span>
            </a>
            <ul class="sub-menu">
                {% for item in item['child'] %}
                <li>
                    <a href="{{ item['href'] }}"><i class="{{ item['icon'] }}"></i>{{ item['tittle'] }}</a>
                </li>
                {% endfor %}
            {% else %}

                <a href="{{ item['href'] }}">
                    <i class="{{ item['icon'] }}"></i>
                    <span class="title">{{ item['tittle'] }}</span>
                    <span class=" "></span>
                </a>
                {% endif %}
        </li>
        {% endfor %}
    </ul>
    <!-- END SIDEBAR MENU -->
</div>