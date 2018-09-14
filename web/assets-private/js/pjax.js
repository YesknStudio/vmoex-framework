if ($.support.pjax) {
    $(document).pjax('a[data-pjax]', '.content-body', {
        fragment: '.content-body',
        timeout: 12e3
    });

    $(document).on('pjax:start', function () {
        NProgress.start();
    });

    $(document).on('pjax:send', function () {});

    $(document).on('pjax:success', function (data, status, xhr, options) {
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