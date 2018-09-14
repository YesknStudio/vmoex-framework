$(function () {
    var socket = io(window.vmoex.socketHost);

    socket.on('connect', function(){
        socket.emit('login', window.vmoex.user.username);
    });
    socket.on('new_message', handleNewMessage);
    socket.on('new_follower', handleNewFollower);
    socket.on('new_chat', handleNewChat);
});