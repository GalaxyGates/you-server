<div class="tab-pane fade fade-up" id="chat-people">
    <div class="js-chat-people overflow-y-auto block-content block-content-full" style="height: 725px;">
        <div class="row remove-margin">
            <div class="col-sm-4">
                <h3 class="text.-success push-20-t push">在线</h3>
                <ul class="nav-users push-10-t push">
                    @component("chat.component.contact")
                        @slot("class")
                            hide
                        @endslot
                        @slot("status")
                            online
                        @endslot
                        @slot('status_icon')
                            text-success
                        @endslot
                        @slot('email')
                            online@status.domain
                        @endslot
                    @endcomponent
                    @component("chat.component.contact")
                        @slot("status")
                        @endslot
                        @slot('status_icon')
                        @endslot
                        @slot('name')
                            添加联系人
                        @endslot
                        @slot('email')
                            点击添加联系人
                        @endslot
                        @slot('data_target')
                            #email-input
                        @endslot
                        @slot('data_toggle')
                            modal
                        @endslot
                    @endcomponent
                </ul>
            </div>
            <div class="col-sm-4">
                <h3 class="text-warning push-20-t push">离开</h3>
                <ul class="nav-users push-10-t push">
                    @component("chat.component.contact")
                        @slot("class")
                            hide
                        @endslot
                        @slot("status")
                            away
                        @endslot
                        @slot('status_icon')
                            text-success
                        @endslot
                        @slot('email')
                            away@status.domain
                        @endslot
                    @endcomponent
                </ul>
            </div>
            <div class="col-sm-4">
                <h3 class="text-muted push-20-t push">离线</h3>
                <ul class="nav-users push-10-t push">
                    @component("chat.component.contact")
                        @slot("class")
                            hide
                        @endslot
                        @slot("status")
                            offline
                        @endslot
                        @slot('status_icon')
                            text-success
                        @endslot
                        @slot('email')
                            offline@status.domain
                        @endslot
                    @endcomponent
                </ul>
            </div>
        </div>
    </div>
</div>

@component('component.modal')
    @slot('id')
        email-input
    @endslot
    @slot('title')
        添加联系人
    @endslot
    @slot('submit_button')
        email-input-submit
    @endslot
    @slot('content')
        <form class="js-validation-bootstrap form-horizontal" novalidate="novalidate" id="email-input-form">
            <div class="form-group">
                <label class="col-md-4 control-label" for="val-email">Email <span class="text-danger">*</span></label>
                <div class="col-md-7">
                    <input class="form-control" type="text" id="val-email" name="val-email" placeholder="输入对方的Email地址">
                </div>
            </div>
        </form>
    @endslot
@endcomponent