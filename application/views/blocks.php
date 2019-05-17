<!-- create_room_block Block -->
<div id="create_room_block" class="center_block">
    <?php if ($user) { ?>
    <strong>
        <i class="fa fa-map-marker" aria-hidden="true"></i>
        Create A Space Here
    </strong>

    <button type="button" class="exit_center_block btn btn-default btn-sm">
        <span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>
    </button>

    <div class="form-group">
        <label for="input_room_name">
            <i class="fa fa-comments" aria-hidden="true"></i>
            Name
        </label>
        <input type="text" class="form-control" id="input_room_name" name="room_name" placeholder="">
        <input type="hidden" id="input_world_key" name="world_key" value="<?php echo $world['id']; ?>">
    </div>
    <button id="create_room_submit" type="submit" class="btn btn-action form-control">
        Create
        <i class="fa fa-arrow-right" aria-hidden="true"></i>
    </button>
    <?php } else { ?>
    <strong>
        <i class="fa fa-key" aria-hidden="true"></i>
        Login to create a room
    </strong>

    <button type="button" class="exit_center_block btn btn-default btn-sm">
        <span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>
    </button>

    <hr>

    <button class="login_button btn btn-info">
        <i class="fa fa-sign-in" aria-hidden="true"></i>
        Login
    </button>
    <button class="register_button btn btn-action">
        <i class="fa fa-user-plus" aria-hidden="true"></i>
        Register
    </button>

    <?php } ?>
</div>

<!-- Report Bugs Block -->
<div id="report_bugs_block" class="center_block">
    <strong>Report Bugs</strong>

    <button type="button" class="exit_center_block btn btn-default btn-sm">
      <span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>
    </button>
    <hr>

    <p>Please report all bugs to 
        <strong>
            <a href="mailto:goosepostbox@gmail.com" target="_blank">goosepostbox@gmail.com </a>
        </strong>
    </p>
</div>
