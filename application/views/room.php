<div id="room_parent">

    <div id="room_toolbar">

        <strong id="room_name">Big World</strong>

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

        <div id="zoom_in_button" class="btn btn-warning pull-right" style="display: none;">
            <i class="fa fa-search-plus" aria-hidden="true"></i>
        </div>

        <div id="zoom_out_button" class="btn btn-action pull-right" style="display: none;">
            <i class="fa fa-search-minus" aria-hidden="true"></i>
        </div>

    </div>

    <div id="message_outer_parent">
        <div id="message_content_parent">
            <div id="empty_room_message" class="text-center">
                <p>Welcome to Big World</p>
                <p>Click on any pin to join a conversation</p>
                <p>Make an account to create your own pins</p>
            </div>
        </div>
        <div id="message_input_parent">
            <form id="new_message" onsubmit="return submit_new_message()">
                <input type="hidden" id="input_room_id" name="room_id" value=""/>
                <input type="hidden" id="input_world_id" name="world_id" value=""/>
                <input type="text" name="message_input" class="form-control" id="message_input" autocomplete="off" value="" placeholder="" style="display: none;"/>
                <!-- submit button positioned off screen -->
                <input name="submit_message" type="submit" id="submit_message" value="true" style="position: absolute; left: -9999px">
            </form>
        </div>
    </div>

</div>