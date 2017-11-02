<!-- Login Block -->
<?php if (!$user) { ?>
<div id="login_block" class="center_block">
    <strong>
        <i class="fa fa-sign-in" aria-hidden="true"></i>
        Login
    </strong>

    <button type="button" class="exit_center_block btn btn-default btn-sm">
        <span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>
    </button>

    <!-- Validation Errors -->
    <?php if ($failed_form === 'login') { echo $validation_errors; } ?>
    <!-- Form -->
    <?php echo form_open('user/login'); ?>
    <div class="form-group">
        <label for="input_username">
            <i class="fa fa-user-circle" aria-hidden="true"></i>
            Username
        </label>
        <input type="username" class="form-control" id="login_input_username" name="username" placeholder="Username">
    </div>
    <div class="form-group">
        <label for="input_password">
            <i class="fa fa-key" aria-hidden="true"></i>
            Password
        </label>
        <input type="password" class="form-control" id="login_input_password" name="password" placeholder="Password">
    </div>
    <button type="submit" class="btn btn-action form-control">
        Login
        <i class="fa fa-arrow-right" aria-hidden="true"></i>
    </button>
    </form>
    <hr>
    <div class="row">
        <div class="col-md-6"></div>
        <div class="col-md-4">
            <p class="lead">Not registered?</p>
        </div>
        <div class="col-md-2">
            <button class="register_button btn btn-success btn-sm form-control">
                <i class="fa fa-user-plus" aria-hidden="true"></i>
                Join
            </button>
        </div>
    </div>
</div>
<?php } ?>

<!-- Join Block -->
<?php if (!$user) { ?>
<div id="register_block" class="center_block">
    <strong>
        <i class="fa fa-user-plus" aria-hidden="true"></i>
        Join
    </strong>

    <button type="button" class="exit_center_block btn btn-default btn-sm">
        <span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>
    </button>

    <!-- Validation Errors -->
    <?php if ($failed_form === 'register') { echo $validation_errors; } ?>
    <!-- Form -->
    <?php echo form_open('user/register'); ?>
    <div class="form-group">
        <input type="hidden" name="bee_movie" id="bee_movie" value="">
        <input type="hidden" name="ab_test" id="ab_test" value="">
        <label for="input_username">
            <i class="fa fa-user-circle" aria-hidden="true"></i>
            Username
        </label>
        <input type="username" class="form-control" id="register_input_username" name="username" placeholder="">
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="input_password">
                    <i class="fa fa-key" aria-hidden="true"></i>
                    Password
                </label>
                <input type="password" class="form-control" id="register_input_password" name="password" placeholder="Password">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="input_confirm">
                    <i class="fa fa-key" aria-hidden="true"></i>
                    Confirm
                </label>
                <input type="password" class="form-control" id="register_input_confirm" name="confirm" placeholder="Confirm">
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="register_location">
            <i class="fa fa-map" aria-hidden="true"></i>
            Location
        </label>
        <input type="location" class="form-control" id="register_location" name="register_location" value="<?php echo $location_prepopulate; ?>">
    </div>
    <div class="form-group">
        <label for="register_color">
            <i class="fa fa-commenting-o" aria-hidden="true"></i>
            Color
        </label>
        <input type="text" class="jscolor color_input form-control" id="register_color" name="register_color" value="<?php echo $random_color; ?>">
    </div>
    <button type="submit" class="btn btn-action form-control">
        Join
        <i class="fa fa-arrow-right" aria-hidden="true"></i>
    </button>
    </form>
    <hr>
    <div class="row">
        <div class="col-md-6"></div>
        <div class="col-md-4">
            <p class="lead">Already a user?</p>
        </div>
        <div class="col-md-2">
            <button class="login_button btn btn-info btn-sm form-control">
                <i class="fa fa-sign-in" aria-hidden="true"></i>
                Login
            </button>
        </div>
    </div>
</div>
<?php } ?>

<!-- create_room_block Block -->
<div id="create_room_block" class="center_block">
    <?php if ($user) { ?>
    <strong>
        <i class="fa fa-map-marker" aria-hidden="true"></i>
        Create A Room Here
    </strong>

    <button type="button" class="exit_center_block btn btn-default btn-sm">
        <span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>
    </button>

    <div class="form-group">
        <label for="input_room_name">
            <i class="fa fa-comments" aria-hidden="true"></i>
            Name
        </label>
        <input type="room_name" class="form-control" id="input_room_name" name="room_name" placeholder="">
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