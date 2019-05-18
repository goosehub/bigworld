<div class="container-fluid">
  <div class="row">
    <div class="col-md-6 left-landing blue-background-color">
      <h1 class="text-center landing-site-title black-color">
        <?php echo site_name(); ?>
      </h1>
      <br>
      <div class="row">
        <div class="col-sm-2">
        </div>
        <div class="col-sm-8">
          <p class="lead black-color landing-lead-text">
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            Create A Map of the world
          </p>
          <br>
          <p class="lead black-color landing-lead-text">
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            Fill your world with Chatrooms
          </p>
          <br>
          <p class="lead black-color landing-lead-text">
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            It's your world, connect with it
          </p>
        </div>
        <div class="col-sm-2">
        </div>
      </div>
      <br>

      <!-- Login and Join -->
      <?php if (!$user) { ?>
      <div class="text-center">
        <button class="landing_login_button menu_element btn btn-primary">
          <i class="fa fa-sign-in" aria-hidden="true"></i>
          Login
        </button>
        <button class="landing_register_button menu_element btn btn-action">
          <i class="fa fa-user-plus" aria-hidden="true"></i>
          Join
        </button>
      </div>
      <?php } ?>

      <!-- Login Block -->
      <div class="row">
        <div class="col-sm-2">
        </div>
        <div class="col-sm-8">
          <hr>
          <?php if (!$user) { ?>
          <div id="login_block" class="landing_center_block well">
            <strong>
              <i class="fa fa-sign-in" aria-hidden="true"></i>
              Login
            </strong>

            <!-- Validation Errors -->
            <span class="text-danger">
              <?php if ($failed_form === 'login') { echo $validation_errors; } ?>
            </span>
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
          </div>
          <?php } ?>

          <!-- Join Block -->
          <?php if (!$user) { ?>
          <div id="register_block" class="landing_center_block well">
            <strong>
              <i class="fa fa-user-plus" aria-hidden="true"></i>
              Join
            </strong>

            <!-- Validation Errors -->
            <span class="text-danger">
              <?php if ($failed_form === 'register') { echo $validation_errors; } ?>
            </span>
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
          </div>
          <?php } ?>

          <?php if ($user) { ?>
          <div class="well">
            <h2>Create World</h2>
            <hr>
            <!-- Validation Errors -->
            <span class="text-danger">
              <?php if ($failed_form === 'create_world') { echo $validation_errors; } ?>
            </span>
            <form class="form" id="create_world_form" action="<?=base_url()?>world/create_world" method="post">
              <div class="form-group">
                <label for="world_slug">
                  World Slug
                  <small class="text-info">(no spaces)</small>
                </label>
                <input type="text" name="slug" class="form-control" id="world_slug" placeholder="">
              </div>
              <button type="submit" class="btn btn-action">Create Your World</button>
            </form>
          </div>
          <?php } ?>
        </div>
        <div class="col-sm-2">
        </div>
      </div>
    </div>
    <div class="col-md-6 no-float right-landing black-background-color">
      <br>
      <div class="well">
        <h2>
          <span class="glyphicon glyphicon-globe" aria-hidden="true"></span>
          Discover Worlds
        </h2>
        <div class="landing-world-list-container">
          <?php foreach ($worlds as $world) { ?>
          <span class="landing-world-container">
            <a href="<?=base_url()?>w/<?php echo $world['slug']; ?>" class="btn btn-default landing-world-button">
              <?php echo $world['slug']; ?>
            </a>
          </span>
          <?php } ?>
          <?php for ($i = 0; $i < 1000; $i++) { ?>
          <span class="landing-world-container">
            <a href="<?=base_url()?>w/foobar" class="btn btn-default landing-world-button">
              <?php echo str_repeat(rand(0, 9999), rand(1, 3)); ?>
            </a>
          </span>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>