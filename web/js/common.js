function render(template, parameters) {
    for (var key in parameters) {
        if (parameters.hasOwnProperty(key)) {
            var reg = new RegExp('@'+key+'@', 'g');
            template = template.replace(reg, parameters[key]);
        }
    }

    return $(template);
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
        $(document).pjax('a[data-pjax]', '.content-body', {        // 本页面的ID
            fragment: '.content-body',                          //来源也的ID
            timeout: 200000000,
            show: 'fade',
            cache: true,  //是否使用缓存
            push: true,
            replace: true
            //scrollTo: 250,
        });

        $(document).on('pjax:start', function () {
            NProgress.start();
        });
        $(document).on('pjax:send', function () { //pjax链接点击后显示加载动画
        });

        $(document).on('pjax:success', function (data, status, xhr, options) {
            console.log(data);
            if (data.relatedTarget) {
                $('title').text(data.relatedTarget.innerText + ' - JetBlog');

            }
        });
        $(document).on('pjax:complete', function () {
            NProgress.done();
        });

        $(document).on('pjax:error', function () {
        });
    }

    $('.content-body').on('click', 'li.disabled a', function (e) {
        e.preventDefault();
    });

    // search
    $(document).off('keyup', '#search-content');
    $(document).on('keyup', '#search-content', function (e) {
        if (e.keyCode === 13) {
            var word = $('#search-content').val();
            if (word.length === 0) {
                alert('搜索内容不能为空');
                return ;
            }

            $.pjax({
                url: '/search?s='+word,
                container: '.content-body',
                fragment: '.content-body',                          //来源也的ID
                timeout: 200000000,
                show: 'fade',
                cache: true,  //是否使用缓存
                push: true,
                replace: true
                //scrollTo: 250,
            })
        }
    });

    var a_idx = 1;

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
    });

    // info
    $.ajax({
        method: "GET",
        url: G_info_link,
        success: function (data) {
            if (data.messages) {
                var messages = data.messages;
                $('li.messages>a span.text').text(' 私信('+messages.length+') ');
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
            url: G_set_message_red_link,
            success: function () {
                $('li.messages>a span.text').text(' 私信 ');
            }
        });
    })
});