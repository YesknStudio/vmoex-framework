$(document).on('click', 'li.disabled a', function (e) {
    e.preventDefault();
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
    const locale = $(this).attr('data-locale');

    $.post(window.Yeskn.links.G_set_locale_link, {locale:locale}, function (data) {
        if (data.ret) {
            success(data.msg);
            window.location = window.location;
        } else {
            error(data.msg);
        }
    });
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

$(document).on('click', '.thumb-up', function () {
    var $this = $(this);
    $.ajax({
        method:  'POST',
        url: window.Yeskn.links.G_thumb_up__link,
        data: {
            cid: $this.attr('data-cid')
        },
        success: function (data) {
            if (data.status) {
                var count = $this.find('.thumb-count').text();
                if (count === window.Yeskn.trans.thumbup) {
                    count = 0;
                }
                count = parseInt(count ? count : 0);
                var res = data.detail.info ? count + 1 : count - 1;
                $this.find('.thumb-count').text(res);

                if (data.info) {
                    $this.find('i.fa').removeClass('fa-thumbs-o-up').addClass('fa-thumbs-up').addClass('text-success');
                } else {
                    $this.find('i.fa').removeClass('fa-thumbs-up').addClass('fa-thumbs-o-up').removeClass('text-success');
                }
            } else {
                error(data.message);
            }
        }
    })
});

$(document).on('click', '#addCommentToPost', function () {
    const $this = $(this);

    if ($this.hasClass('disabled')) {
        warning(window.Yeskn.trans.action_too_quick);
        return ;
    }

    const postId = $this.attr('data-postId');

    $this.addClass('disabled');
    const content = $('#editor-comment').html();
    const $colorDots = $('.color-dots');

    let data = {content: content};

    if ($colorDots.attr('data-color')) {
        data.color = $colorDots.attr('data-color');
    }

    $.ajax({
        method: 'POST',
        url: path(window.Yeskn.links.add_comment_to_post, {1: postId}),
        data: data,
        success: function (data) {
            if (data.status) {
                reload();
            } else {
                error(data.message);
                $this.removeClass('disabled');
            }
        }
    })
});

$(document).on('keydown', '.chat-panel input#btn-input', function (e) {
    if (e.keyCode === 13) {
        $('#sendChat').trigger('click');
    }
});

$(document).on('click', '#sendChat', function () {
    $.ajax({
        method: 'POST',
        url: window.Yeskn.links.send_chat,
        data: {content: $('#btn-input').val()},
        success: function (data) {
            data.status ? reload() : error(data.message);
        }
    })
});

$(document).on('click', '#refresh-chat', function () {
    reload();
});

$(document).on('click', '#sign-remark', function () {
    $.ajax({
        method: 'POST',
        url: window.Yeskn.links.G_sign_link,
        success: function (data) {
            if (data.ret) {
                success(data.msg);
                reload();
            } else {
                warning(data.msg);
            }
        }
    })
});

$(document).on('click', '.comment-reply', function () {
    var replyTo = $(this).attr('data-at');
    var replayU = $(this).attr('data-atu');
    window.location.href = '#comment-box';
    $('#editor-comment p:last-child').append('<span data-at="'+replayU+'">@'+replyTo + "</span>&nbsp;");
    $('#editor-comment').focus();
    setEndOfContenteditable(document.getElementById('editor-comment'));
});

$(document).on('click', '.color-dots div', function () {
    $(this).parent().attr('data-color', $(this).css('background-color'));
    const $container = $('#editor-comment');
    const text = $container.text();
    const $span = $('<sapn></sapn>');
    $span.text(text);
    $span.css({
        'color': $(this).css('background-color')
    });
    $container.html($span);
});

$(document).on('click', 'nav .nav-search-bar span', function () {
    $('#site-search').trigger('submit');
});

$(window).scroll(function() {
    if ($(this).scrollTop() > 200) {
        $('.go-top').fadeIn(200);
    } else {
        $('.go-top').fadeOut(200);
    }
});

$(document).on('click', '#navSiteAnnounceAlert button' , function () {
    $.post(window.Yeskn.links.close_alert);
});

$('.go-top').click(function(event) {
    event.preventDefault();
    $('html, body').animate({scrollTop: 0}, 300);
});
