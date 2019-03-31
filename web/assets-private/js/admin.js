$(function () {
    $('.search-container .btn-dropdown').click(function () {
        if ($('.panel-body').is(':visible')) {
            $('.panel-body').slideUp();
            $('.panel-footer').slideUp();
            $(this).find('i').removeClass('fa-chevron-up').addClass('fa-chevron-down')
        } else {
            $('.panel-body').slideDown();
            $('.panel-footer').slideDown();
            $(this).find('i').removeClass('fa-chevron-down').addClass('fa-chevron-up')
        }
    })
});
