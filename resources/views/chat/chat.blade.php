@extends("layouts.app")


@section("content")

    @component("chat.mainframe")

    @endcomponent

@endsection

@section("js")
    <script src="assets/js/plugins/jquery-validation/additional-methods.min.js"></script>
    <script src="assets/js/pages/base_forms_validation.js"></script>
    <script src="/assets/js/pages/base_ui_chat.js"></script>
    <script src="/assets/js/app/chat.js"></script>
@endsection