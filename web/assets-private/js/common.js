$(function () {
    var $modal = $('.modal');
    $modal.on('show.bs.modal', function () {
        $('body').addClass("modal-open-noscroll");
    });

    $modal.on('hidden.bs.modal', function () {
        $('body').removeClass("modal-open-noscroll");
    });
});
