$(function () {
    var socket = io(window.Yeskn.socketHost);

    socket.on('connect', function(){
        socket.emit('login', window.Yeskn.user.username);
    });
    socket.on('new_message', handleNewMessage);
    socket.on('new_follower', handleNewFollower);
    socket.on('new_chat', handleNewChat);
    socket.on('create_blog_event', handleCreateBlogEvent)
});