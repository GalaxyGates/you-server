<li class="{{$class or null }}" id="chat-contact" data-contact-status="{{$status or 'offline'}}"
    data-contact-name="{{$name or null}}" data-contact-email="{{$email or 'null'}}">
    <a id="contact-tab" data-toggle="{{$data_toggle or null}}" data-target="{{$data_target or null}}">
        <img class="img-avatar" id="contact-avatar" src="{{$avatar or '/assets/img/avatars/avatar3.jpg'}}" alt="">
        <i class="fa fa-circle {{$status_icon or null}}" id="contact-status-icon"></i>
        <span id="contact-name">{{$name or null}}</span>
        <div class="font-w400 text-muted" id="contact-email-div">
            <small>{{$email or null}}</small>
        </div>
    </a>
</li>


<!--

我们希望 status 可以为:
online away offline

-->