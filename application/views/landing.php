<style>
body {
	background-color: #669999;
}
</style>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-4">
      
    </div>
    <div class="col-md-4">
      
      <h1><?php echo site_name(); ?></h1>

      <p class="lead">
        Create your own world of chat
      </p>

      <h2>Worlds</h2>
      <ul>
        <?php foreach ($worlds as $world) { ?>
        <li><?php echo $world['slug']; ?></li>
        <?php } ?>
      </ul>

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

      <?php if ($user) { ?>
      <h2>Create World</h2>

      <form class="form-inline" id="create_world_form" action="<?=base_url()?>create_world" method="post">
        <div class="form-group">
          <label for="world_slug">World Slug</label>
          <input type="text" name="slug" class="form-control" id="world_slug" placeholder="">
        </div>
        <button type="submit" class="btn btn-action">A Whole New World</button>
      </form>
      <?php } ?>

    </div>
    <div class="col-md-4">
      
    </div>
  </div>
</div>