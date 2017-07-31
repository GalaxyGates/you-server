/**
 * Created by 34883 on 2017-07-04.
 */
//初始化处理
z;
BaseUIChat.init(OnSubmit, OnAddEmail);

$(function () {
    read_message();
    setInterval(read_message, 1000);
    BaseUIChat.addContact("sujiayi", "348831271@qq.com", "");
});
function OnAddEmail($email) {
    if (BaseUIChat.getWindow($email).length == 0) {
        $.get('/chat/read_name', {'email': $email}, function (data) {
            if (data == '') {
                //这里处理邮箱不存在的情况
            }
            else {
                BaseUIChat.addContact(data, $email, 'online');
            }
        });
    }

    return false;
}
//按下回车键（提交）的处理逻辑
//
//首先获取了表单的内容
//将文本框清空
//然后发送消息
//等待消息返回后显示到屏幕上
function OnSubmit($t) {
    var $temp = $t.serialize();
    $.post("/chat/send_msg", $temp, function () {
    }).error();
    return false;
}
//通过jQuery+ajax异步读取消息
function read_message() {
    $.get('/chat/read_msg', function () {
    }, "json")
        .done(read_message_done)
        .error(read_message_error);
}
//成功读取消息
function read_message_done(data, textStatus, jqXHR) {
    $.each(data, function (index, value) {
        if (BaseUIChat.getWindow(value.sender).length == 0)
        //说明这个窗口是不存在的
        {
            add_chat_window(value.sender, value.message);
        }
        BaseUIChat.addMessage(value.sender, value.message, '');
    });
}
//读取消息失败
function read_message_error(djqXHR, textStatus, errorThrown) {

}

//只用给出对方的邮箱，就把对方添加到聊天窗口上
//功能自然而然地分为三部分
//创建窗体
//读取对方名称
//再将对方名称显示到窗体上
//返回该窗口的ID
function add_chat_window($email) {
    BaseUIChat.addWindow($email, $email);

    $.get('/chat/read_name', {'email': $email}, function (data) {
        if (data == '') {
            //这里处理邮箱不存在的情况
        }
        else {
            BaseUIChat.setName($email, data);
        }
    });


}
