window.vmoex.emotions = {
    'default' : {
        title: '(●′ω`●) 颜文字',
        data: [
            {
                icon: null,
                text: 'OωO',
                value: 'OωO'
            },
            {
                icon: null,
                text: '|´・ω・)ノ',
                value: '|´・ω・)ノ'
            },
            {
                icon: null,
                text: '⌇●﹏●⌇',
                value: '⌇●﹏●⌇'
            },
            {
                icon: null,
                text: '(ฅ´ω`ฅ)',
                value: '(ฅ´ω`ฅ)'
            },
            {
                icon: null,
                text: '→_→',
                value: '→_→'
            },
            {
                icon: null,
                text: '＞﹏＜',
                value: '＞﹏＜'
            },
            {
                icon: null,
                text: '￣へ￣',
                value: '￣へ￣'
            },
            {
                icon: null,
                text: '（づ￣３￣）づ',
                value: '（づ￣３￣）づ'
            }
        ]
    }
};
$(function () {
    $.ajax({
        method: "GET",
        url: window.vmoex.links.G_info_link,
        success: function (data) {
            if (data.messages) {
                var messages = data.messages;
                var $messageLabel = $('.nav-message-label');
                var messageLabel = $messageLabel.text();
                var newMessageLabel = messageLabel + '(<b id="MyMessageCount"></b>)';
                $messageLabel.html(newMessageLabel);
                $('#MyMessageCount').text(messages.length);

                for (k in messages) {
                    var message = messages[k];
                    var html = render($('#message-item-tpl').html(), {
                        content: message.content,
                        username: message.sender_username,
                        createdAt: message.createdAt,
                        nickname: message.sender
                    });
                    $('li.messages ul').prepend(html);
                }

            }
        }
    });
});