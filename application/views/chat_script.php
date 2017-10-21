<script>
var room_key = 1;
var slug = '';
var last_message_id = 0;
var at_bottom = true;
var load_messages = true;
var window_active = true;
var page_title = '';
var missed_messages = 0;
var users_array = new Array();

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
  toggle_theme(event);
});

// Message Load
function messages_load(inital_load) {
  if (!load_messages) {
    return false;
  }
  if (inital_load) {
    $("#message_content_parent").html('Loading');
  }
  $.ajax({
    url: "<?=base_url()?>chat/load",
    type: "POST",
    data: {
      room_key: room_key,
      slug: slug,
      inital_load: inital_load,
      last_message_id: last_message_id
    },
    cache: false,
    success: function(response) {
      console.log('load chat');
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
        window.location = '<?=base_url()?>join_start/' + slug;
        // Prevent more execution
        return false;
      }
      console.log(messages);
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
        if (!window_active && !inital_load) {
          missed_messages++;
          $('title').html('(' + missed_messages + ') ' + page_title);
        }
        // System Messages
        var system_user_id = 1;
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
  // Empty chat input
  $('#message_input').val('');
  $.ajax({
    url: "<?=base_url()?>chat/new_message",
    type: "POST",
    data: {
      message_input: message_input,
      slug: ''
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
      messages_load(false);
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

function string_contains(string, sub_string) {
  if (string.indexOf(sub_string) !== -1) {
    return true;
  }
  return false;
}

function toggle_theme(event) {
  if ($(event.target).hasClass('active')) {
    $(event.target).text('Switch to Dark Theme');
    $(event.target).removeClass('active');
    $('#room_parent').addClass('light');
    $('#message_content_parent').addClass('light');
  } else {
    $(event.target).text('Switch to Light Theme');
    $(event.target).addClass('active');
    $('#room_parent').removeClass('light');
    $('#message_content_parent').removeClass('light');
  }
}

</script>