<div id="menu_parent">

    <?php if ($user) { ?>
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
    <?php } else { ?>
    <button class="login_button menu_element btn btn-primary">Login</button>
    <button class="register_button menu_element btn btn-action">Join</button>
    <?php } ?>

</div>