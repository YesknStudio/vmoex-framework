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
            $('title').text(data.relatedTarget.innerText + ' - JetBlog');

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
    $(document).on('keyup', '#search-content', function (e) {
        if (e.keyCode === 13) {
            var word = $('#search-content').val();
            if (word.length === 0) {
                alert('搜索内容不能为空');
                return ;
            }
            $.pjax({url: '/search?s='+word, container: '.content-body'})
        }
    })
});