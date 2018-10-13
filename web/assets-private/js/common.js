$(function () {
    var $modal = $('.modal');
    $modal.on('show.bs.modal', function () {
        $('body').addClass("modal-open-noscroll");
    });

    $modal.on('hidden.bs.modal', function () {
        $('body').removeClass("modal-open-noscroll");
    });
});

$.fn.findName = function (name) {
    return $(this).find('[name='+name+']');
};

$.fn.nameVal = function (name) {
    return $(this).findName(name).val();
};

$.extend({
    round: function (value, precision) {
        if (precision === undefined) {
            precision = 2;
        }

        var times = Math.pow(10, precision);

        return Math.round(value * times) / times
    }
});
