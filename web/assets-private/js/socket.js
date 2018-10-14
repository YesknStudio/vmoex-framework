$(function () {
    var socket = io(window.Yeskn.socketHost);

    socket.on('connect', function(){
        socket.emit('login', {
            username: window.Yeskn.user.username,
            token: window.Yeskn.user.socketToken
        });
    });

    socket.on('new_message', handleNewMessage);
    socket.on('new_follower', handleNewFollower);
    socket.on('new_chat', handleNewChat);
    socket.on('create_blog_event', handleCreateBlogEvent);
    socket.on('update_online_count', function (data) {
        data = JSON.parse(data);

        var totalCnt;

        if (data.onlineCount < parseInt($('#memberCnt').text())) {
            totalCnt = parseInt($('#memberCnt').text());
        } else {
            totalCnt = data.onlineCount;
        }

        $('#onlineCnt').text(totalCnt);
    });
    socket.on('broadcast', function (data) {
        success(data.message);
    });
});
