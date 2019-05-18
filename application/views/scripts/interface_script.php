<!-- Interface Script -->
<script>

var user;
<?php if ($user) { ?>
user = <?php echo json_encode($user); ?>;
var user_load_polling_seconds = <?php echo USER_LOAD_POLLING_SECONDS; ?>
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

$('#input_user_location').click(function(event){
    event.stopPropagation();
});

function update_location() {
    user_location = $('#input_user_location').val();
    if (!user_location) {
        return false;
    }
    data = {};
    data.location = user_location;
    ajax_post('user/update_location', data, function(response){
        $('.user_menu_parent').dropdown('toggle');
    });
}

$('.report_bugs_button').click(function(){
    $('#report_bugs_block').show();
});

// Room close
$('#room_exit').click(function(){
    $('#room_parent').fadeOut();
});

// Load favorite rooms on interval
load_user();
setInterval(function(){
    load_user();
}, user_load_polling_seconds * 1000);

function load_user() {
    var url = 'main/load_user/' + world_id;
    ajax_get(url, function(result){
        console.log(result);
    });
}

// 
// Center block hide and show logic
// 

$('.exit_center_block').click(function(){
    $('.center_block').hide();
    $('.landing_center_block').hide();
});
$('.login_button').click(function(){
        $('.center_block').hide();
        $('#login_block').show();
        $('#login_input_username').focus();
        $('.current_url').val(window.location.href);
});
$('.register_button').click(function(){
        $('.center_block').hide();
        $('#register_block').show();
        $('#register_input_username').focus();
        $('.current_url').val(window.location.href);
});
$('.landing_login_button').click(function(){
        if ($(this).hasClass('btn-primary')) {
            $('.landing_center_block').hide();
            $('.landing_login_button').removeClass('btn-primary').addClass('btn-default')
            return;
        }
        $('.landing_login_button').addClass('btn-primary').removeClass('btn-default');
        $('.landing_register_button').removeClass('btn-primary').addClass('btn-default');
        $('.landing_center_block').hide();
        $('#login_block').show();
});
$('.landing_register_button').click(function(){
        if ($(this).hasClass('btn-primary')) {
            $('.landing_center_block').hide();
            $('.landing_register_button').removeClass('btn-primary').addClass('btn-default')
            return;
        }
        $('.landing_register_button').addClass('btn-primary').removeClass('btn-default');
        $('.landing_login_button').removeClass('btn-primary').addClass('btn-default');
        $('.landing_center_block').hide();
        $('#register_block').show();
});
</script>