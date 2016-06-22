(function () {
    var dropDown = $('li.dropdown.user-dropdown');
    dropDown.mouseover(function () {
        $(this).addClass('open');
    });
    dropDown.mouseout(function () {
        dropDown.removeClass('open');
    })
})();
$(document).ready(function () {

    $('.copy').popover({
        trigger: 'hover',
        placement: 'top'
    });


    if ($.support.pjax) {
        $(document).pjax('a[data-pjax]', 'body', {        // 本页面的ID
            fragment: 'body',                          //来源也的ID
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
            // alert('sended');
        });

        $(document).on('pjax:success', function (data, status, xhr, options) {
            //  alert('succeed');
            $('title').text(data.relatedTarget.innerText + ' - JetBlog');

        });
        $(document).on('pjax:complete', function () {
            NProgress.done();
            //alert('complete');
        });

        $(document).on('pjax:error', function () {
            //  alert("error!");
        });
    }
});