<!-- Main Container -->
<main id="main-container">
    <!-- Page Content -->
    <!-- Chat layout and demo functionality is initialized in js/pages/base_ui_chat.js -->
    <!--
        You can use the following JS function to add a message to a chat window:

        BaseUIChat.addMessage($chatId, $chatMsg, $chatMsgLevel)

        $chatId         : the data-chat-id attribute of your chat window
        $chatMsg        : the message you would like to add
        $chatMsgLevel   : 'self' the user sends the message, '' empty if the user receives the message (changes its style)
    -->
    <div class="js-chat-container content content-full" data-chat-mode="full">
        <!-- Full Chat -->
        <div class="block remove-margin-b">
            @component("chat.component.tabnav")
            @endcomponent
            <div class="tab-content overflow-hidden" id="windows">
                <!--这里提供一个标准的窗口-->
                @component("chat.component.window")
                    'null'
                @endcomponent

                @component("chat.component.contacts")
                @endcomponent


            </div>
        </div>
        <!-- END Full Chat -->
    </div>
    <!-- END Page Content -->
</main>

