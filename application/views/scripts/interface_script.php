<!-- Interface Script -->
<script>

var user;
<?php if ($user) { ?>
user = <?php echo json_encode($user); ?>;
<?php } ?>

// Error reporting
<?php if ($failed_form === 'register') { ?>
    $('#register_block').show();
<?php } ?>
<?php if ($failed_form === 'login') { ?>
  // Show login form if not logged in and not failed to log in
  if (!user) {
    $('#login_block').show();
  }
<?php } else if (isset($_GET['login'])) { ?>
  // Show login form if URL calls for it
  $('#login_block').show();
<?php } ?>

// Validation errors shown on page load if exist
<?php if ($failed_form === 'login') { ?>
$('#login_block').show();
<?php } else if ($failed_form === 'register') { ?> 
$('#register_block').show();
<?php } ?>

// 
// Center block hide and show logic
// 

$('.exit_center_block').click(function(){
  $('.center_block').hide();
});
$('.login_button').click(function(){
    $('.center_block').hide();
    $('#login_block').show();
});
$('.register_button').click(function(){
    $('.center_block').hide();
    $('#register_block').show();
});
$('.login_button').click(function(){
    $('#login_input_username').focus();
});
$('.register_button').click(function(){
    $('#register_input_username').focus();
});

</script>