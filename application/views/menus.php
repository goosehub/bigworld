<div id="menu_parent">

    <!-- Homepage -->
    <div class="homepage_link_menu_parent menu_element btn-group">
        <a class="btn btn-default text-primary" id="homepage_link" href="<?=base_url()?>">
            <span class="glyphicon glyphicon-globe" aria-hidden="true"></span>
            Big World
        </a>
    </div>

    <!-- World -->
    <div class="worlds_menu_parent menu_element btn-group">
        <button id="worlds_button" class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <span class="glyphicon glyphicon-globe" aria-hidden="true"></span>
            <!-- Filter -->
            <span class="caret"></span>
        </button>
        <ul id="filter_dropdown" class="dropdown-menu dropdown-menu-right" aria-labelledby="worlds_button">
            <li>
                <button class="btn <?php echo $world_is_favorite ? 'btn-success' : 'btn-action'; ?> dropdown-toggle form-control" id="favorite_world_button">
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <?php echo $world['slug']; ?>
                </button>
            </li>
            <?php foreach ($favorite_worlds as $favorite_world) { ?>
            <?php if ($favorite_world['world_key'] === $world['id']) { continue; } ?>
            <li><a class="world_link text-center" href="<?=base_url()?>w/<?php echo $favorite_world['slug']; ?>">
                <?php echo $favorite_world['slug']; ?>
            </a></li>
            <?php } ?>
        </ul>
    </div>

    <!-- Filter -->
    <div class="filters_menu_parent menu_element btn-group">
        <button id="filters_button" class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <i class="fa fa-clock-o" aria-hidden="true"></i>
            <!-- Filter -->
            <span class="caret"></span>
        </button>
        <ul id="filter_dropdown" class="dropdown-menu dropdown-menu-right" aria-labelledby="filters_button">
            <?php foreach ($filters as $filter) { ?>
            <li><a class="filter_link text-center" href="<?=base_url()?>w/<?php echo $world['slug']; ?>/?last_activity=<?php echo $filter['slug']; ?>">
                <?php if ($current_last_activity_filter['slug'] === $filter['slug']) { ?>
                <i class="fa fa-chevron-right" aria-hidden="true"></i>
                <?php } ?>
                <?php echo deslug($filter['slug']) ?>
            </a></li>
            <?php } ?>
        </ul>
    </div>

    <!-- Favorites -->
    <?php if ($user && !empty($user['favorite_rooms'])) { ?>
    <div class="favorite_rooms_menu_parent menu_element btn-group">
        <button id="favorite_rooms_button" class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <i class="fa fa-star" aria-hidden="true"></i>
            <!-- Favorites -->
            <span class="caret"></span>
        </button>
        <ul id="favorites_dropdown" class="dropdown-menu dropdown-menu-right" aria-labelledby="favorite_rooms_button">
            <?php foreach ($user['favorite_rooms'] as $favorite) { ?>
            <li><a class="favorite_room_link text-center" room_id="<?php echo $favorite['room_key']; ?>" href="<?=base_url()?>w/<?php echo $world['slug']; ?>/#<?php echo $favorite['id']; ?>">
                <?php echo $favorite['name'] ?>
            </a></li>
            <?php } ?>
        </ul>
    </div>
    <?php } ?>

    <!-- Owned Rooms Dropdown -->
    <!-- Disabled -->
    <?php if (false) { ?>
    <?php if ($user && !empty($user['rooms'])) { ?>
    <div class="owned_rooms_menu_parent menu_element btn-group">
        <button id="owned_rooms_button" class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <i class="fa fa-map-marker" aria-hidden="true"></i>
            <!-- <i class="fa fa-square" aria-hidden="true"></i> -->
            <!-- Owned Rooms -->
            <span class="caret"></span>
        </button>
        <ul id="owned_rooms_dropdown" class="dropdown-menu dropdown-menu-right" aria-labelledby="owned_rooms_button">
            <?php foreach ($user['rooms'] as $user_room) { ?>
            <li><a class="owned_room_link text-center" room_id="<?php echo $user_room['id']; ?>" href="<?=base_url()?>w/<?php echo $world['slug']; ?>/#<?php echo $user_room['id']; ?>">
                <?php echo $user_room['name'] ?>
            </a></li>
            <?php } ?>
        </ul>
    </div>
    <?php } ?>
    <?php } ?>

    <!-- User Dropdown -->
    <?php if ($user) { ?>
    <div class="user_menu_parent menu_element btn-group">
        <button id="user_button" class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <i class="fa fa-user" aria-hidden="true"></i>
            <span class="menu_username_text">
                <?php echo $user['username']; ?>
            </span>
            <span class="caret"></span>
        </button>
        <ul id="user_dropdown" class="dropdown_item dropdown-menu dropdown-menu-right" aria-labelledby="user_button">
            <li><p class="text-center"><?php echo site_name(); ?></p></li>
            <li><input type="text" class="jscolor color_input form-control" id="input_user_color" name="input_user_color" value="<?php echo $user['color']; ?>"></li>
            <li>
                <form id="update_location_form" onsubmit="update_location(); return false;">
                    <input type="text" class="form-control" id="input_user_location" name="input_user_location" value="<?php echo $user['location']; ?>">
                </form>
            </li>
            <li><a class="btn btn-info" href="https://github.com/goosehub/bigworld" target="_blank">
                <i class="fa fa-github" aria-hidden="true"></i>
                GitHub
            </a></li>
            <li><a class="btn btn-success" href="https://gooseweb.io/" target="_blank">
                <i class="fa fa-code" aria-hidden="true"></i>
                GooseWeb
            </a></li>
            <li><a class="btn btn-primary" href="https://www.reddit.com/r/bigworldio/" target="_blank">
                <i class="fa fa-reddit-alien" aria-hidden="true"></i>
                /r/bigworldio
            </a></li>
            <li><a class="report_bugs_button btn btn-warning" href="javascript:;">
                <i class="fa fa-bug" aria-hidden="true"></i>
                Report Bugs
            </a></li>
            <li><a class="logout_button btn btn-danger" href="<?=base_url()?>user/logout">
                <i class="fa fa-sign-out" aria-hidden="true"></i>
                Logout
            </a></li>
<!--             <li>
                <small>Get your friends on</small>
                <div class="fb-like" data-href="https://landgrab.xyz/" data-layout="button" data-="recommend" data-show-faces="false" data-share="true"></div>
            </li> -->
        </ul>
    </div>
    <?php } ?>

    <!-- Login and Join -->
    <?php if (!$user) { ?>
    <button class="login_button menu_element btn btn-primary">
        <i class="fa fa-sign-in" aria-hidden="true"></i>
        Login
    </button>
    <button class="register_button menu_element btn btn-action">
        <i class="fa fa-user-plus" aria-hidden="true"></i>
        Join
    </button>
    <?php } ?>

</div>