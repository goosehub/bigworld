<script>
var last_message_id = 0;
var at_bottom = true;
var load_messages = true;
var window_active = true;
var page_title = '';
var missed_messages = 0;
var users_array = new Array();
var room_name = 'Big World';
var load_interval = 3000;
var system_user_id = <?php echo SYSTEM_USER_ID ?>;

$(document).on('click', '.message_pin', function(event) {
  pin_action(event);
});

// On tab press in message input
document.querySelector('#message_input').addEventListener('keydown', function (e) {
  if (e.which == 9) {
    autocomplete_username();
    e.preventDefault();
  }
});

$('#toggle_theme').click(function(event) {
  toggle_theme($('#toggle_theme').hasClass('active'));
});

// If client has theme stored and value is light, toggle to light
if (localStorage.getItem('theme') === 'light') {
  toggle_theme(true);
}

// Keep dropdown open on altering color
$('#input_user_color').click(function(event){
  event.stopPropagation();
});

// Update color on change of color
$('#input_user_color').change(function(event){
  data = {};
  data.color = $(this).val();
  ajax_post('user/update_color', data, function(response){
    $('#input_user_color')[0].jscolor.hide();
  });
});

// Detect if user is at bottom
$('#message_content_parent').scroll(function() {
  at_bottom = false;
  if ($('#message_content_parent').prop('scrollHeight') - $('#message_content_parent').scrollTop() <= Math.ceil($('#message_content_parent').height())) {
    at_bottom = true;
  }
});

// Detect if window is open
$(window).blur(function() {
  window_active = false;
});
$(window).focus(function() {
  missed_messages = 0;
  $('title').html(room_name);
  window_active = true;
});

// If hash exists, it is a room id, load that room
if (window.location.hash) {
  // Remove hash to get room id and load room
  var room_id = window.location.hash.replace('#', '');
  load_room(room_id);
}

// Load owned room on click
$('.owned_room_link').click(function(){
  room_id = $(this).attr('room_id');
  load_room(room_id);
});

// Load favorite room on click
$('.favorite_room_link').click(function(){
  room_id = $(this).attr('room_id');
  load_room(room_id);
});

// Load owned room on click
$('#favorite_room_button').click(function(){
  room_id = current_marker.room_id
  favorite_room(room_id);
});

function load_room(room_id) {
  // Get room
  ajax_get('room/get_room/' + room_id, function(room){

    // Ensure chat window is set up
    $('#message_input').show();
    $('#message_input').focus();
    $('#room_parent').fadeIn();

    // Set up room
    window.location.hash = room_id;
    $('#room_name').html(room.name);
    $('#zoom_in_button').show();

    // Switch marker icons
    if (current_marker) {
      current_marker.setIcon(default_marker_img);
    }
    markers[room_id].setIcon(current_marker_img);
    current_marker = markers[room_id];

    // Favorite button
    $('#favorite_room_button').removeClass('btn-success').removeClass('btn-default').show();
    if (room.is_favorite) {
      $('#favorite_room_button').addClass('btn-success');
    }
    else {
      $('#favorite_room_button').addClass('btn-default');
    }

    // Load Messages
    clearInterval(messages_load_interval_id);
    messages_load(room_id, true);
    messages_load_interval_id = setInterval(function() {
      messages_load(room_id, false);
    }, load_interval);
  });
}

function favorite_room(room_id) {
  // Send request
  data = {};
  data.room_id = room_id;
  data.world_id = world_id;
  ajax_post('room/favorite', data, function(response){
    // Activate favorite button
    if ($('#favorite_room_button').hasClass('btn-success')) {
      $('#favorite_room_button').removeClass('btn-success').addClass('btn-default');
    }
    else {
      $('#favorite_room_button').removeClass('btn-default').addClass('btn-success');
    }
  });
}

// Message Load
function messages_load(room_key, inital_load) {
  if (!load_messages) {
    return false;
  }
  if (inital_load) {
    $('#input_room_id').val(room_key);
    $('#input_world_id').val(world_id);
    $("#message_content_parent").html('Loading');
    room_name = $('#room_name').html();
    $('title').html(room_name);
    last_message_id = 0;
  }
  else {
    var room_key = $('#input_room_id').val();
  }
  $.ajax({
    url: "<?=base_url()?>chat/load",
    type: "POST",
    data: {
      room_key: room_key,
      world_key: world_id,
      inital_load: inital_load,
      last_message_id: last_message_id
    },
    cache: false,
    success: function(response) {
      console.log('load messages');
      var html = '';
      // Emergency force reload
      if (response === 'reload') {
        window.location.reload(true);
      }
      if (inital_load) {
        $("#message_content_parent").html('');
      }
      // Parse messages and loop through them
      messages = JSON.parse(response);
      if (!messages) {
        return false;
      }
      // Handle errors
      if (messages.error && load_messages && window_active) {
        // Prevent stacking errors
        load_messages = false;
        // Alert user
        alert(messages.error + '. You\'ll be redirected so you can rejoin the room.');
        // Redirect to try to rejoin user
        window.location = '<?=base_url()?>?room=' + room_id;
        // Prevent more execution
        return false;
      }
      if (!messages.messages) {
        last_message_id = 0;
        return true;
      }
      $.each(messages.messages, function(i, message) {
        // Skip if we already have this message, although we really shouldn't
        if (parseInt(message.id) <= parseInt(last_message_id)) {
          return true;
        }
        // Update latest message id
        last_message_id = message.id;
        // If window is not active, give feedback in tab title
        if (!window_active && !inital_load && message.user_key != system_user_id) {
          missed_messages++;
          $('title').html('(' + missed_messages + ') ' + room_name);
        }
        // System Messages
        if (parseInt(message.user_key) === system_user_id) {
          html += '<div class="system_message ' + message.username + '">' + message.message + '</div>';
          return true;
        }
        // Process message
        var message_message = embedica(message.message);
        // Wrap @username with span
        message_message = convert_at_username(message_message);
        // Detect if youtube
        // build message html
        html += '<div class="message_parent">';
        html += '<span class="message_face glyphicon glyphicon-user" title="' + message.timestamp + ' ET" style="color: ' + message.color + ';"></span>';
        if (use_pin(message_message)) {
          html += '<span class="message_pin glyphicon glyphicon-pushpin" style="color: ' + message.color + ';"></span>';
        }
        html += '<span class="message_username" style="color: ' + message.color + ';">' + message.username + '</span>';
        html += '<span class="message_message">' + message_message + '</span>';
        html += '</div>';
      });
      // Append to div
      $("#message_content_parent").append(html);
      // Stay at bottom if at bottom
      if (at_bottom || inital_load) {
        scroll_to_bottom();
      }
    }
  });
}

// New Message
function submit_new_message(event) {
  // Message input
  var message_input = $("#message_input").val();
  var room_key = $('#input_room_id').val();
  var world_key = $('#input_world_id').val();
  // Empty chat input
  $('#message_input').val('');
  $.ajax({
    url: "<?=base_url()?>chat/new_message",
    type: "POST",
    data: {
      message_input: message_input,
      room_key: room_key,
      world_key: world_key
    },
    cache: false,
    success: function(response) {
      // console.log('submit');
      // All responses are error messsages
      if (response) {
        alert(response);
        return false;
      }
      // Load log so user can instantly see his message
      messages_load(room_key, false);
      // Focus back on input
      $('#message_input').focus();
      // Scroll to bottom
      scroll_to_bottom();
    }
  });
  return false;
}

function autocomplete_username() {
  if ($('#message_input').val().startsWith('@')) {
    var parsed_text_input = $('#message_input').val().replace('@','').toLowerCase();
    for (var i = 0; i < users_array.length; i++) {
      if (users_array[i].username.toLowerCase().startsWith(parsed_text_input)) {
        $('#message_input').val('@' + users_array[i].username);
      }
    }
  }
}

function pin_action(event) {
  if (!$(event.target).hasClass('active_pin')) {
    $('.active_pin').removeClass('active_pin');
    $('.pinned').removeClass('pinned')
    $(event.target).addClass('active_pin');
    $(event.target).parent().addClass('pinned')
  } else {
    $(event.target).removeClass('active_pin');
    $(event.target).parent().removeClass('pinned')
  }
}

function convert_at_username(input) {
  var pattern = /^\@\w+/g;
  if (pattern.test(input)) {
    var at_username = input.split(' ')[0];
    if (!at_username) {
      return input;
    }
    var replacement = '<span class="at_username">' + at_username + '</span>';
    var input = input.replace(pattern, replacement);
  }
  return input;
}

function use_pin(message) {
  if (
    string_contains(message, 'embedica_youtube') ||
    string_contains(message, 'embedica_vimeo') ||
    string_contains(message, 'embedica_twitch') ||
    string_contains(message, 'embedica_soundcloud') ||
    string_contains(message, 'embedica_vocaroo') ||
    string_contains(message, 'embedica_video') ||
    string_contains(message, 'embedica_image')
  ) {
    return true;
  }
  return false;
}

function scroll_to_bottom() {
  $("#message_content_parent").scrollTop($("#message_content_parent")[0].scrollHeight);
}

function toggle_theme(light_theme) {
  if (light_theme) {
    $('#toggle_theme').removeClass('active');
    $('#toggle_icon').removeClass('fa-toggle-off').addClass('fa-toggle-on');
    $('#room_parent').addClass('light');
    $('#message_content_parent').addClass('light');
    localStorage.setItem('theme', 'light');
  } else {
    $('#toggle_theme').addClass('active');
    $('#toggle_icon').removeClass('fa-toggle-on').addClass('fa-toggle-off');
    $('#room_parent').removeClass('light');
    $('#message_content_parent').removeClass('light');
    localStorage.setItem('theme', 'dark');
  }
}

</script>