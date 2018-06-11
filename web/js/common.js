function render(template, parameters) {
    for (var key in parameters) {
        if (parameters.hasOwnProperty(key)) {
            var reg = new RegExp('@'+key+'@', 'g');
            template = template.replace(reg, parameters[key]);
        }
    }

    return $(template);
}

function error(msg) {
    bootoast({
        message:msg,
        type: 'danger',
        position: 'top-center',
        icon: undefined,
        timeout: 3,
        animationDuration: 300,
        dismissable: true
    });
}

function warning(msg) {
    bootoast({
        message:msg,
        type: 'warning',
        position: 'top-center',
        icon: undefined,
        timeout: 3,
        animationDuration: 300,
        dismissable: true
    });
}

function success(msg) {
    bootoast({
        message:msg,
        type: 'success',
        position: 'top-center',
        icon: undefined,
        timeout: 3,
        animationDuration: 300,
        dismissable: true
    });
}

function info(msg) {
    bootoast({
        message: msg,
        type: 'info',
        position: 'bottom-left',
        icon: 'info-sign',
        timeout: 5,
        animationDuration: 300,
        dismissable: true
    });
}

function reload() {
    $.pjax.reload('.content-body', {
        fragment: '.content-body',
        timeout: 200000000,
        show: 'fade',
        cache: true,
        push: true
    })
}

function go(url) {
    $.pjax({
        url: url,
        container: '.content-body',
        fragment: '.content-body',
        timeout: 200000000,
        show: 'fade',
        cache: true,
        push: true,
        replace: false
    });
}

/**
 *
 * @param data
 */
function handleNewMessage(data) {
    info(data.msg);

    var message  = data.data;
    var $messageCount = $('#MyMessageCount');

    if ($messageCount.length) {
        var originCount = parseInt($messageCount.text());
        if (originCount > 10) {
            return ;
        }
        $messageCount.text( + 1);
    } else {
        var $messageLabel = $('.nav-message-label');
        var messageLabel = $messageLabel.text();
        var newMessageLabel = messageLabel + '(<b id="MyMessageCount"></b>)';
        $messageLabel.html(newMessageLabel);
        $('b#MyMessageCount').text(1);
    }

    var html = render($('#message-item-tpl').html(), {
        content: message.content,
        username: message.sender_username,
        createdAt: message.createdAt,
        nickname: message.sender
    });

    $('li.messages ul').prepend(html);
}

function handleNewFollower(data) {
    info(data.msg);
    var $label = $('.notification-label');
    var text = $label.attr('data-origin');
    $label.html(text + '(<b style="color: red">new</b>)');
}

$(document).ready(function () {


    var dropDown = $('li.dropdown.user-dropdown');
    dropDown.click(function () {
        $(this).addClass('open');
    });
    dropDown.click(function () {
        dropDown.removeClass('open');
    });

    $('.copy').popover({
        trigger: 'hover',
        placement: 'top'
    });

    if ($.support.pjax) {
        $(document).pjax('a[data-pjax]', '.content-body', {
            fragment: '.content-body',
            timeout: 200000000,
            show: 'fade',
            cache: true,
            push: true,
            replace:false,
        });

        $(document).on('pjax:start', function () {
            NProgress.start();
        });

        $(document).on('pjax:send', function () {
        });

        $(document).on('pjax:success', function (data, status, xhr, options) {
            // if (data.relatedTarget) {
            //     if ($.trim(data.relatedTarget.innerText) === 'Vmoex') {
            //         $('title').text('Vmoex - 打造最美好的二次元社区');
            //     } else {
            //         $('title').text(data.relatedTarget.innerText + '- Vmoex');
            //     }
            // }
        });

        $(document).on('pjax:complete', function () {
            NProgress.done();
        });

        $(document).on('pjax:error', function () {
            NProgress.done();
        });

        $(document).on('pjax:end', function () {
            NProgress.done();
        });
    }

    $('.content-body').on('click', 'li.disabled a', function (e) {
        e.preventDefault();
    });

    // search
    $(document).off('submit', '#site-search');
    $(document).on('submit', '#site-search', function (e) {
        e.preventDefault();
        var word = $('#search-content').val();
        if (word.length === 0) {
            error('搜索内容不能为空');
            return ;
        }

        go('/search?s='+word);

        $('#navbar-collapse').collapse('hide');
        $('#navbar-collapse-user').collapse('hide');
    });

    var tc = sessionStorage.getItem('topClick');
    var a_idx = parseInt(tc) || 1;

    $("body").click(function(e) {
        var $i = $("<span/>").text('+'+a_idx+++'♥');
        var x = e.pageX,
            y = e.pageY;
        $i.css({
            "z-index": 1001,
            "top": y - 20,
            "left": x,
            "position": "absolute",
            "font-weight": "bold",
            "color": "#ff6651"
        });
        $("body").append($i);
        $i.animate({
                "top": y - 180,
                "opacity": 0
            },
            1500,
            function() {
                $i.remove();
            });
        sessionStorage.setItem('topClick', a_idx);
    });

    // info
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

    $(document).on('click', 'li.messages>a', function () {
        $.ajax({
            method: "POST",
            url: window.vmoex.links.G_set_message_red_link,
            success: function () {
                $('li.messages>a span.text').text(' 私信 ');
            }
        });
    });

    $('nav.navbar-static-top a').click(function (e) {
        if ($(this).attr('href') !== '#' && $(this).attr('href') !== '/logout') {
            e.preventDefault();
            go($(this).attr('href'));

            $('#navbar-collapse').collapse('hide');
            $('#navbar-collapse-user').collapse('hide')
        }
    });

    $('.set-locale-link').click(function (e) {
        e.preventDefault();
        var locale = $(this).attr('data-locale');

        $.post(window.vmoex.links.G_set_locale_link, {locale:locale}, function (data) {
            if (data.ret) {
                success(data.msg);
                window.location.reload();
            } else {
                error(data.msg);
            }
        });
    });

    $('.dropdown-notifications a').click(function () {
        var $label = $('.notification-label');
        var text = $label.attr('data-origin');
        $label.text(text);
    });

    // 连接服务端
    var socket = io(window.vmoex.socketHost);

    socket.on('connect', function(){
        socket.emit('login', window.vmoex.user.username);
    });

    socket.on('new_message', handleNewMessage);
    socket.on('new_follower', handleNewFollower);

});