<div class="tab-pane fade" id="chat-window" data-chat-email="{{ $email or 'null' }}">
    <div class="js-chat-talk overflow-y-auto block-content block-content-full">
        <!-- Messages -->
    </div>
    <div class="js-chat-form block-content block-content-full block-content-mini">
        <form action="" id="message_form">
            {{ csrf_field() }}
            <input name="remote" id="send_remote" class="js-chat-input form-control" type="hidden"
                   value="{{$send_remote or 0}}">
            <input name="text" id="send_text" class="js-chat-input form-control" type="text"
                   data-target-chat-email="{{ $slot or 'null'}}" placeholder="按下回车键以发送">
        </form>
    </div>
</div>