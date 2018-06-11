$(document).on('click', 'body', function(e) {
    var tc = sessionStorage.getItem('topClick');
    var a_idx = parseInt(tc) || 1;

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

$(document).on('click', 'li.disabled a', function (e) {
    e.preventDefault();
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

$(document).on('click', 'nav.navbar-static-top a', function (e) {
    if ($(this).attr('href') !== '#' && $(this).attr('href') !== '/logout') {
        e.preventDefault();
        go($(this).attr('href'));

        $('#navbar-collapse').collapse('hide');
        $('#navbar-collapse-user').collapse('hide')
    }
});

$(document).on('click', '.set-locale-link', function (e) {
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

$(document).on('click', '.dropdown-notifications a', function () {
    var $label = $('.notification-label');
    var text = $label.attr('data-origin');
    $label.text(text);
});

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

$(document).on('click', '.nav-chat-item', function () {
    $('.nav-chat-dot').removeClass('push-notifications-count');
});

$(document).on('click', '.navbar-item-alert, .navbar-item-message', function () {
    $(this).find('span').addClass('nav-common-dot').removeClass('nav-warn-dot').text(0);
});