<div class="modal fade" id="{{$id or null}}" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-popout">
        <div class="modal-content">
            <div class="block block-themed block-transparent remove-margin-b">
                <div class="block-header {{$head_bg_block or 'bg-primary-dark'}}">
                    <ul class="block-options">
                        <li>
                            <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">{{$title or null}}</h3>
                </div>
                <div class="block-content">
                    {{$content or null}}
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-default" type="button"
                        data-dismiss="modal">{{$close_text or '关闭'}}</button>
                <button class="btn btn-sm btn-primary" type="button" data-dismiss="modal"
                        id="{{$submit_button or null}}"><i class="fa fa-check"></i>{{$ok_text or "确认"}}</button>
            </div>
        </div>
    </div>
</div>