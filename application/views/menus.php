<div id="menu_parent">

    <!-- Unread messages -->
    <?php if ($user) { ?>
    <div class="unread_pm_rooms_menu_parent menu_element btn-group" style="display: none;">
        <button id="unread_pm_rooms_button" class="btn btn-sm btn-action dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <i class="fa fa-envelope" aria-hidden="true"></i>
            <span class="caret"></span>
        </button>
        <ul id="unread_pm_rooms" class="dropdown-menu dropdown-menu-right" aria-labelledby="unread_pm_rooms_button">
        </ul>
    </div>
    <?php } ?>

    <!-- Homepage -->
    <div class="homepage_link_menu_parent menu_element btn-group">
        <a class="btn btn-sm btn-default text-primary" id="homepage_link" title="Homepage" href="<?=base_url()?>">
            <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
        </a>
    </div>

    <div class="share_menu_parent menu_element btn-group">
        <button class="btn btn-sm btn-default dropdown-toggle" id="share_button" title="Share" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <i class="fa fa-share-square" aria-hidden="true"></i>
        </button>
        <ul id="filter_dropdown" class="dropdown-menu dropdown-menu-right" aria-labelledby="share_button">
            <li>
                <div class="world_link text-center">
                    <p>URL copied to clipboard</p>
                </div>
            </li>
        </ul>
    </div>

    <!-- Filters -->
    <div class="filters_menu_parent menu_element btn-group">
        <button id="filters_button" class="btn btn-sm btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <i class="fa fa-clock-o" aria-hidden="true"></i>
            <span class="caret"></span>
        </button>
        <ul id="filter_dropdown" class="dropdown-menu dropdown-menu-right" aria-labelledby="filters_button">
            <li>
                <div class="world_link text-center">
                    <i class="fa fa-circle" aria-hidden="true"></i>
                    Filter By Last Active
                    <i class="fa fa-circle" aria-hidden="true"></i>
                </div>
            </li>
            <?php foreach ($filters as $filter) { ?>
            <li>
                <a class="filter_link text-center" filter="<?php echo $filter['slug']; ?>">
                    <?php if ($current_last_activity_filter['slug'] === $filter['slug']) { ?>
                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                    <?php } ?>
                    <?php echo deslug($filter['slug']) ?>
                </a>
            </li>
            <?php } ?>
        </ul>
    </div>

    <!-- Favorite Rooms -->
    <?php if ($user) { ?>
    <div class="favorite_rooms_menu_parent menu_element btn-group">
        <button id="favorite_rooms_button" class="btn btn-sm btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <i class="fa fa-star" aria-hidden="true"></i>
            <span class="caret"></span>
        </button>
        <ul id="favorites_dropdown" class="dropdown-menu dropdown-menu-right" aria-labelledby="favorite_rooms_button">
            <li>
                <div class="world_link text-center">
                    <i class="fa fa-circle" aria-hidden="true"></i>
                    Starred Places
                    <i class="fa fa-circle" aria-hidden="true"></i>
                </div>
            </li>
            <?php // Loaded by javascript instead ?>
            <?php if (false) { ?>
            <?php foreach ($user['favorite_rooms'] as $favorite) { ?>
            <li class="favorite_room_listing">
                <a class="favorite_room_link text-center" room_id="<?php echo $favorite['room_key']; ?>">
                    <?php echo $favorite['name'] ?>
                </a>
            </li>
            <?php } ?>
            <?php } ?>
        </ul>
    </div>
    <?php } ?>

    <!-- Favorite Worlds -->
    <?php if ($user) { ?>
    <div class="favorite_worlds_menu_parent menu_element btn-group">
        <button id="favorite_worlds_button" class="btn btn-sm btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <span class="glyphicon glyphicon-globe" aria-hidden="true"></span>
            <span class="caret"></span>
        </button>
        <ul id="filter_dropdown" class="dropdown-menu dropdown-menu-right" aria-labelledby="favorite_worlds_button">
            <li>
                <div class="world_link text-center">
                    <i class="fa fa-circle" aria-hidden="true"></i>
                    Starred Worlds
                    <i class="fa fa-circle" aria-hidden="true"></i>
                </div>
            </li>
            <li>
                <a class="world_link text-center <?php echo $world_is_favorite ? 'active' : ''; ?>" id="favorite_world_button" href="javascript:void(0)">
                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                    <span class="text-default">
                        <?php echo $world['slug']; ?>
                    </span>
                    <button id="favorite_world_remove_icon" class="btn btn-sm btn-link" aria-hidden="true" style="<?php echo $world_is_favorite ? '' : 'display: none;'; ?>">
                        (Un-Star)
                    </button>
                    <button id="favorite_world_add_icon" class="btn btn-sm btn-link" aria-hidden="true" style="<?php echo $world_is_favorite ? 'display: none;' : ''; ?>">
                        (Make Starred)
                    </button>
                </a>
            </li>
            <?php foreach ($favorite_worlds as $favorite_world) { ?>
            <?php if ($favorite_world['world_key'] === $world['id']) { continue; } ?>
            <li>
                <a class="world_link text-center" href="<?=base_url()?><?php echo $favorite_world['slug']; ?>">
                    <?php echo $favorite_world['slug']; ?>
                </a>
            </li>
            <?php } ?>
        </ul>
    </div>
    <?php } ?>

    <!-- User Dropdown -->
    <?php if ($user) { ?>
    <div class="user_menu_parent menu_element btn-group">
        <button id="user_button" class="btn btn-sm btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <i class="fa fa-user" aria-hidden="true"></i>
            <span class="menu_username_text">
                <?php echo $user['username']; ?>
            </span>
            <span class="caret"></span>
        </button>
        <ul id="user_dropdown" class="dropdown_item dropdown-menu dropdown-menu-right" aria-labelledby="user_button">
            <li><p class="text-center">
                <i class="fa fa-circle" aria-hidden="true"></i>
                <?php echo site_name(); ?>
                <i class="fa fa-circle" aria-hidden="true"></i>    
            </p></li>
            <li><input type="text" class="jscolor color_input form-control" id="input_user_color" name="input_user_color" value="<?php echo $user['color']; ?>"></li>
            <li>
                <form id="update_location_form" onsubmit="update_location(); return false;">
                    <input type="text" class="form-control" id="input_user_location" name="input_user_location" value="<?php echo $user['location']; ?>">
                </form>
            </li>
            <li>
                <a class="btn btn-info" href="https://github.com/goosehub/bigworld" target="_blank">
                    <i class="fa fa-github" aria-hidden="true"></i>
                    GitHub
                </a>
            </li>
            <li>
                <a class="btn btn-success" href="https://gooseweb.io/" target="_blank">
                    <i class="fa fa-code" aria-hidden="true"></i>
                    GooseWeb
                </a>
            </li>
            <li>
                <a class="btn btn-primary" href="https://www.reddit.com/r/bigworldio/" target="_blank">
                    <i class="fa fa-reddit-alien" aria-hidden="true"></i>
                    /r/bigworldio
                </a>
            </li>
            <li>
                <a class="report_bugs_button btn btn-warning" href="javascript:;">
                    <i class="fa fa-bug" aria-hidden="true"></i>
                    Report Bugs
                </a>
            </li>
            <li>
                <a class="logout_button btn btn-danger" href="<?=base_url()?>user/logout">
                    <i class="fa fa-sign-out" aria-hidden="true"></i>
                    Logout
                </a>
            </li>
<!--             <li>
                <small>Get your friends on</small>
                <div class="fb-like" data-href="https://landgrab.xyz/" data-layout="button" data-="recommend" data-show-faces="false" data-share="true"></div>
            </li> -->
        </ul>
    </div>
    <?php } ?>

    <!-- Login and Join -->
    <?php if (!$user) { ?>
    <button class="login_button menu_element btn btn-sm btn-default">
        <i class="fa fa-sign-in" aria-hidden="true"></i>
        Login
    </button>
    <button class="register_button menu_element btn btn-sm btn-default">
        <i class="fa fa-user-plus" aria-hidden="true"></i>
        Join
    </button>
    <?php } ?>

</div>