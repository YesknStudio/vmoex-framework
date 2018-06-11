if ($.support.pjax) {
    $(document).on('submit', 'form[data-pjax]', function(event) {
        $.pjax.submit(event, '.content-body', {
            fragment:'.content-body', timeout:6000
        })
    });

    $(document).pjax('a[data-pjax]', '.content-body', {
        fragment: '.content-body',
        timeout: 200000000,
        show: 'fade',
        cache: true,
        push: true,
        replace:false,
    });

    $(document).on('pjax:start', function () {
        alert('start');
        NProgress.start();
    });

    $(document).on('pjax:send', function () {
    });

    $(document).on('pjax:success', function (data, status, xhr, options) {});

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