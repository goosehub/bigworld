<div id="room_parent" class="center_block">
    <strong>Room</strong>

    <span id="toolbar">
        <span id="toggle_theme" class="btn btn-sm btn-danger active">Switch to Light Theme</span>
        <span id="user_count_parent" class="btn btn-default" title="">
            <span id="user_count">0</span>
            <span id="user_list_parent" style="display: none;"> | <span id="user_list"></span></span>
        </span>
    </span>

    <button type="button" class="exit_center_block btn btn-default btn-sm">
        <span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>
    </button>

    <div id="message_outer_parent">
        <div id="message_content_parent">
            Loading...
        </div>
        <div id="message_input_parent">
            <form id="new_message" onsubmit="return submit_new_message()">
                <input type="hidden" id="input_room_id" name="room_id" value=""/>
                <input type="text" name="message_input" class="form-control" id="message_input" autocomplete="off" value="" placeholder="" />
                <!-- submit button positioned off screen -->
                <input name="submit_message" type="submit" id="submit_message" value="true" style="position: absolute; left: -9999px">
            </form>
        </div>
    </div>

</div>