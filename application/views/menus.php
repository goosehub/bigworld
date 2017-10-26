<div id="menu_parent">

    <!-- Filter -->
    <div class="owned_rooms_menu_parent menu_element btn-group">
        <button class="user_button btn btn-default dropdown-toggle" type="button" id="site_dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            Filter
            <span class="caret"></span>
        </button>
        <ul class="user_dropdown dropdown-menu" aria-labelledby="site_dropdown">
            <?php foreach ($filters as $filter) { ?>
            <li><a class="filter_link text-center" href="<?=base_url()?>?last_activity=<?php echo $filter['last_activity_in_minutes']; ?>">
                <?php echo deslug($filter['slug']) ?>
            </a></li>
            <?php } ?>
        </ul>
    </div>

    <?php if ($user) { ?>
    <!-- Owned Rooms Dropdown -->
    <?php if (!empty($user['rooms'])) { ?>
    <div class="owned_rooms_menu_parent menu_element btn-group">
        <button class="user_button btn btn-default dropdown-toggle" type="button" id="site_dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            Owned Rooms
            <span class="caret"></span>
        </button>
        <ul class="user_dropdown dropdown-menu" aria-labelledby="site_dropdown">
            <?php foreach ($user['rooms'] as $user_room) { ?>
            <li><a class="owned_room_link text-center" room_id="<?php echo $user_room['id']; ?>" href="<?=base_url()?>#<?php echo $user_room['id']; ?>">
                <?php echo $user_room['name'] ?>
            </a></li>
            <?php } ?>
        </ul>
    </div>
    <?php } ?>

    <!-- User Dropdown -->
    <div class="user_menu_parent menu_element btn-group">
        <button class="user_button btn btn-default dropdown-toggle" type="button" id="site_dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <?php echo $user['username']; ?>
            <span class="caret"></span>
        </button>
        <ul class="user_dropdown dropdown-menu" aria-labelledby="site_dropdown">
            <li><p class="text-center">Small World</p></li>
            <li><input type="text" class="jscolor color_input form-control" id="input_user_color" name="input_user_color" value="<?php echo $user['color']; ?>"></li>
            <li>
                <form id="update_location_form" onsubmit="update_location(); return false;">
                    <input type="text" class="form-control" id="input_user_location" name="input_user_location" value="<?php echo $user['location']; ?>">
                </form>
            </li>
            <li><a class="btn btn-info" href="https://github.com/goosehub/smallworld" target="_blank">GitHub</a></li>
            <li><a class="btn btn-success" href="https://gooseweb.io/" target="_blank">GooseWeb</a></li>
            <li><a class="logout_button btn btn-danger" href="<?=base_url()?>user/logout">Logout</a></li>
            <li><small>Get your friends on</small><div class="fb-like" data-href="https://landgrab.xyz/" data-layout="button" data-="recommend" data-show-faces="false" data-share="true"></div></li>
        </ul>
    </div>

    <!-- Login and Join -->
    <?php } else { ?>
    <button class="login_button menu_element btn btn-primary">Login</button>
    <button class="register_button menu_element btn btn-action">Join</button>
    <?php } ?>

</div>