<!-- Login Block -->
<div id="login_block" class="center_block">
    <strong>Login</strong>

    <button type="button" class="exit_center_block btn btn-default btn-sm">
        <span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>
    </button>

    <!-- Validation Errors -->
    <?php if ($failed_form === 'login') { echo $validation_errors; } ?>
    <!-- Form -->
    <?php echo form_open('user/login'); ?>
    <div class="form-group">
        <label for="input_username">Username</label>
        <input type="username" class="form-control" id="login_input_username" name="username" placeholder="Username">
    </div>
    <div class="form-group">
        <label for="input_password">Password</label>
        <input type="password" class="form-control" id="login_input_password" name="password" placeholder="Password">
    </div>
    <button type="submit" class="btn btn-action form-control">Login</button>
    </form>
    <hr>
    <div class="row">
        <div class="col-md-6"></div>
        <div class="col-md-4">
            <p class="lead">Not registered?</p>
        </div>
        <div class="col-md-2">
            <button class="register_button btn btn-success form-control">Join</button>
        </div>
    </div>
</div>
<!-- Join Block -->
<div id="register_block" class="center_block">
    <strong>Start Playing</strong>

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
        <label for="input_username">Username</label>
        <input type="username" class="form-control" id="register_input_username" name="username" placeholder="Username">
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="input_password">
                Password
                </label>
                <input type="password" class="form-control" id="register_input_password" name="password" placeholder="Password">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="input_confirm">
                Confirm
                </label>
                <input type="password" class="form-control" id="register_input_confirm" name="confirm" placeholder="Confirm">
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-action form-control">Start Playing</button>
    </form>
    <hr>
    <div class="row">
        <div class="col-md-6"></div>
        <div class="col-md-4">
            <p class="lead">Already a user?</p>
        </div>
        <div class="col-md-2">
            <button class="login_button btn btn-info form-control">Login</button>
        </div>
    </div>
</div>
<!-- create_room_block Block -->
<div id="create_room_block" class="center_block">
    <strong>Create A Room Here</strong>

    <button type="button" class="exit_center_block btn btn-default btn-sm">
        <span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>
    </button>

    <div class="form-group">
        <label for="input_room_name">Room Name</label>
        <input type="room_name" class="form-control" id="input_room_name" name="room_name" placeholder="Room Name">
    </div>
    <button id="create_room_submit" type="submit" class="btn btn-action form-control">Create</button>
</div>