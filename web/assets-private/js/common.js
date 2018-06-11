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