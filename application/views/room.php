<div id="room_parent">
    <strong id="room_name">Small World</strong>

    <div id="room_exit" class="btn btn-danger pull-right">
        <i class="fa fa-times-circle" aria-hidden="true"></i>
    </div>

    <?php if ($user) { ?>
    <div id="favorite_room_button" class="btn btn-default pull-right" style="display: none;">
        <i class="fa fa-star" aria-hidden="true"></i>
    </div>
    <?php } ?>

    <div id="toggle_theme" class="btn btn-info pull-right active">
        <i id="toggle_icon" class="fa fa-toggle-off" aria-hidden="true"></i>
    </div>

    <div id="message_outer_parent">
        <div id="message_content_parent">
            <p>Welcome to Small World</p>
            <p>A Social Network on Google Maps</p>
            <p>Click on any pin to join a conversation in progress</p>
            <p>Make an account to create your own rooms</p>
        </div>
        <div id="message_input_parent">
            <form id="new_message" onsubmit="return submit_new_message()">
                <input type="hidden" id="input_room_id" name="room_id" value=""/>
                <input type="text" name="message_input" class="form-control" id="message_input" autocomplete="off" value="" placeholder="" style="display: none;"/>
                <!-- submit button positioned off screen -->
                <input name="submit_message" type="submit" id="submit_message" value="true" style="position: absolute; left: -9999px">
            </form>
        </div>
    </div>

</div>