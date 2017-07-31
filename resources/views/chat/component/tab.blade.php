<li id="chat-tab" data-chat-email="{{$email or 'null'}}" class="hidden">
    <a data-toggle="tab">
        <!--头像在这里-->
        <img class="img-avatar img-avatar16" src="{{$avatar0 or NULL}}" alt="">

        <!--名字在这里-->
        <span class="push-5-l" id="chat-name">{{$name or NULL}}</span>
    </a>

</li>