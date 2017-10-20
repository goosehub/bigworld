<div id="top_right_block">

    <?php if ($user) { ?>
    <!-- User Dropdown -->
    <div class="user_parent menu_element btn-group">
        <button class="user_button btn btn-success" type="button" id="user_dropdown">
            <?php echo $user['username']; ?>
          <span class="caret"></span>
        </button>
    </div>
    <a class="login_button menu_element btn btn-danger" href="<?=base_url()?>user/logout">Logout</a>
    <?php } else { ?>
    <button class="login_button menu_element btn btn-primary">Login</button>
    <button class="register_button menu_element btn btn-action">Join</button>
    <?php } ?>

</div>