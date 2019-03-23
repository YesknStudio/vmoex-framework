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

$.fn.onPjax = function(event, $target, callback) {
  $(this).off(event, $target);
  $(this).on(event, $target, callback);
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

/**
 *
 * @param name 插件名称
 * @param callback 插件加载完后执行的函数
 * @param refresh 重复获取时执行的函数
 * @returns {*}
 */
window.YesknPlugins.get = function (name, callback, refresh) {
    const self = window.YesknPlugins;

    if (self[name].initialized === true) {
        refresh && refresh(self[name].result);
        return  callback && callback(eval(self[name].object));
    }

    const scripts = self[name].scripts;
    const links = self[name].links;

    for (const key in scripts) {
        let scriptElm = document.createElement('script');
        if (scripts.hasOwnProperty(key)) {
            scriptElm.setAttribute('src', scripts[key]);
            document.getElementsByTagName('head')[0].insertBefore(scriptElm, null);
        }

        if (key * 1 === scripts.length - 1) {
            scriptElm.onload = function () {
                self[name].result = callback && callback(eval(self[name].object));
            };
        }
    }

    for (const key in links) {
        let linkElm = document.createElement('link');
        if (links.hasOwnProperty(key)) {
            linkElm.setAttribute('href', links[key]);
            linkElm.setAttribute('rel', 'stylesheet');
            document.getElementsByTagName('head')[0].insertBefore(linkElm, null);
        }
    }

    self[name].initialized = true;

    return self[name];
};
