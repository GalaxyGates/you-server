/*
 *  Document   : base_ui_chat.js
 *  Author     : pixelcave
 *  Description: Custom JS code used in Chat Pages
 */


var BaseUIChat = function () {
    // Helper variables - set in initChat()
    var $lWindow, $lHeader, $lFooter, $cContainer, $cHead, $cTalk, $cPeople, $cform, $cTimeout, $cCount, $currentID,
        $countContacts, $cContactTab;

    // Init chat
    var initChat = function ($sendCallback, $editEmailCallback) {
        // Set variables
        $lWindow = jQuery(window);
        $lHeader = jQuery('#header-navbar');
        $lFooter = jQuery('#page-footer');
        $cContainer = jQuery('.js-chat-container');
        $cHead = jQuery('.js-chat-head');
        $cTalk = jQuery('.js-chat-talk');
        $cPeople = jQuery('.js-chat-people');
        $cform = jQuery('#message_form');
        $cContactTab = $("#contact-tab");
        $cCount = 1;
        $currentID = "";
        $countContacts = 3;

        // Add word wraping to chat content
        $cTalk.css('word-wrap', 'break-word');
        switch ($cContainer.data('chat-mode')) {
            case 'full':
                // Init chat windows' height
                initChatWindows();

                // ..also on browser resize or orientation change
                jQuery(window).on('resize orientationchange', function () {
                    clearTimeout($cTimeout);

                    $cTimeout = setTimeout(function () {
                        initChatWindows();
                    }, 150);
                });
                break;
            case 'fixed':
                // Init chat windows' height with a specific height
                initChatWindows($cContainer.data('chat-height'));
                break;
            case 'popup':
                // Init chat windows' height with a specific height
                initChatWindows($cContainer.data('chat-height'));

                // Adjust chat container
                $cContainer.css({
                    'position': 'fixed',
                    'right': '10px',
                    'bottom': 0,
                    'display': 'inline-block',
                    'padding': 0,
                    'width': '70%',
                    'max-width': '420px',
                    'min-width': '300px',
                    'z-index': '1031'
                });
                break;
            default:
                return false;
        }

        // Enable scroll lock to chat talk window
        $cTalk.scrollLock('enable');

        // Init form submission
        $cform.submit(function (e) {
            // Stop form submission
            e.preventDefault();

            // Get chat input
            var $chatInput = $(this).children("input[id='send_text']");

            //这里调用回掉函数，
            $sendCallback($(this));

            // Add message
            chatAddMessage($chatInput.data('target-chat-email'), $chatInput.val(), 'self', $chatInput);
        });
        $cHead.on('shown.bs.tab', function (e) {
            $currentID = $(this).attr("data-chat-email");
        });
        $cContactTab.click(function () {

            console.log("on contact tab click");

            //获取到信息
            var $contact = $(this).parent("#chat-contact");
            var $email = $contact.data("contact-email");
            var $name = $contact.data("contact-name");


            //没有这个窗口就创建出来
            if (chatGetWindow($email).length == 0) {
                chatAddWindow($name, $email);
            }

            //切换到这个窗口

            var $tab = chatGetTab($email);
            $tab.children("a").click();

        });
        $("#email-input-form").submit(function (e) {
            e.preventDefault();
            var editor = $('#val-email');
            $editEmailCallback(editor.val());
            editor.val('');
        });
        $("#email-input-submit").click(function () {
            $("#email-input-form").submit();
        });
    };

    // Init chat windows' height
    var initChatWindows = function ($customHeight) {
        if ($customHeight) {
            $cHeight = $customHeight;
        } else {
            // Calculate height
            var $cHeight = $lWindow.height() -
                $lHeader.outerHeight() -
                $lFooter.outerHeight() -
                $cHead.outerHeight() -
                (parseInt($cContainer.css('padding-top')) + parseInt($cContainer.css('padding-bottom')));

            // Add a minimum height
            if ($cHeight < 200) {
                $cHeight = 200;
            }
        }

        // Set height to chat windows (+ people window if exists)
        if ($cPeople) {
            $cPeople.css('height', $cHeight);
        }

        $cTalk.css('height', $cHeight - $cform.outerHeight());
    };


    // Add a message to a chat window
    var chatAddMessage = function ($chatEmail, $chatMsg, $chatMsgLevel, $chatInput) {
        // Get chat window
        var $parent = $("[id='chat-window'][data-chat-email='" + $chatEmail + "']");
        var $chatWindow = $parent.children(".js-chat-talk");

        // If message and chat window exists
        if ($chatMsg && $chatWindow.length) {
            var $chatBlockClasses = 'animated fadeIn push-50-l';
            var $chatMsgClasses = 'bg-gray-lighter';

            // Post it to its related window (if message level is 'self', make it stand out)
            if ($chatMsgLevel === 'self') {
                $chatBlockClasses = 'animated fadeInUp push-50-r';
                $chatMsgClasses = 'bg-gray-light';
            }

            $chatWindow.append('<div class="block block-rounded block-transparent push-15 ' + $chatBlockClasses + '">'
                + '<div class="block-content block-content-full block-content-mini ' + $chatMsgClasses + '">'
                + jQuery('<div />').text($chatMsg).html()
                + '</div>'
                + '</div>');

            // Scroll the message list to the bottom
            if ($currentID == $chatEmail) {
                $chatWindow.animate({scrollTop: $chatWindow[0].scrollHeight}, 150);
            }
            else {
                $chatWindow.scrollTop($chatWindow[0].scrollHeight);
            }
            // If input is set, reset it
            if ($chatInput) {
                $chatInput.val('');
            }
        }
    };

    var chatAddWindow = function ($name, $email) {
        //通过模板的克隆操作来建立新的窗口
        var new_window = $("[id='chat-window'][data-chat-email='null']").clone(true);
        var new_tab = $("[id='chat-tab'][data-chat-email='null']").clone(true);
        var new_window_child = new_window.children("div");

        console.log(new_tab);

        //更改模板数据

        //窗体数据
        new_window.attr("data-chat-email", $email);
        new_window_child.filter(".js-chat-talk").attr("data-chat-email", $email);
        new_window_child.filter(".js-chat-form").children("form").children("#send_text").attr("data-target-chat-email", $email);
        new_window_child.filter(".js-chat-form").children("form").children("#send_remote").attr("value", $email);


        //标签数据
        new_tab.attr("data-chat-email", $email);
        new_tab.children("a").attr("data-target", "[id='chat-window'][data-chat-email='" + $email + "']");
        // new_tab.children("a").attr("href","javascript:BaseUIChat.setActive("+$cCount+")");
        new_tab.children("a").children("#chat-name").text($name);
        new_tab.children("a").children("img").attr("src", "assets/img/avatars/avatar10.jpg");


        new_tab.removeClass("hidden");

        //插入
        new_window.insertAfter("[id='chat-window']:last");
        new_tab.insertAfter("[id='chat-tab']:last");

        return $cCount++;
    };

    //杀掉一个窗口
    var chatDeleteWindow = function ($email) {

        $("[id='chat-window'][data-chat-email='" + $email + "']").remove();
        $("[id='chat-tab'][data-chat-email='" + $email + "']").remove();

    };


    //更改窗口名称

    var chatSetName = function ($email, $name) {
        $("[id='chat-tab'][data-chat-email='" + $email + "']").children("a").children("#chat-name").text($name);
    };

    var chatGetWindow = function ($email) {
        return $("[id='chat-window'][data-chat-email='" + $email + "']");
    };
    var chatGetTab = function ($email) {
        return $("[id='chat-tab'][data-chat-email='" + $email + "']");
    };


    var chatAddTime = function ($email, $time) {
        var talk = chatGetWindow($email).children(".js-chat-talk");
        talk.append(' <div class="font-s12 text-muted text-center push-20-t push-15"><em>' + $time + '</em></div>');
    };

    // $status ：状态
    var chatAddContact = function ($name, $email, $status) {
        var $base_email = 0;
        switch ($status) {
            case 'online': {
                $base_email = 'online@status.domain';
                console.log("online");
                break;
            }
            case 'away': {
                $base_email = 'away@status.domain';
                console.log("away");
                break;
            }
            case 'offline': {
                $base_email = 'offline@status.domain';
                console.log("offline");
                break;
            }
            default: {
                $base_email = 'online@status.domain';
                console.log("default");
                break;
            }
        }
        //得到控件
        var base_contact = $("[id='chat-contact'][data-contact-email='" + $base_email + "']");

        var new_contact = base_contact.clone(true);
        var new_contact_a = new_contact.children("a");

        //设置联系人标签下方的各种信息
        new_contact_a.children("#contact-name").text($name);
        new_contact_a.children("#contact-email-div").children("small").text($email);

        //设置联系人本身的信息
        new_contact.attr("data-contact-email", $email);
        new_contact.attr("data-contact-name", $name);
        new_contact.attr("data-contact-id", $countContacts);

        new_contact.removeClass("hide");

        //插入
        console.log("执行次数+1");
        new_contact.insertAfter(base_contact);

        //表明我已经多了一个联系人了
        $countContacts++;

    };


    return {
        init: function ($sendCallback, $cb2) {
            // Init chat
            initChat($sendCallback, $cb2);
        },
        addMessage: function ($email, $chatMsg, $chatMsgLevel) {
            // Add message
            chatAddMessage($email, $chatMsg, $chatMsgLevel, false);
        },
        deleteWindow: function ($email) {
            chatDeleteWindow($email);
        },
        addWindow: function ($name, $email) {
            return chatAddWindow($name, $email);
        },
        current: function () {
            return $currentID
        },

        setName: function ($email, $name) {
            return chatSetName($email, $name);
        },
        addTime: function ($email, $time) {
            return chatAddTime($email, $time);
        },
        addContact: function ($email, $name, $status) {
            return chatAddContact($email, $name, $status);
        },
        getWindow: function ($email) {
            return chatGetWindow($email);
        }

    };
}();

// Initialize when page loads
//jQuery(function(){ BaseUIChat.init(); });