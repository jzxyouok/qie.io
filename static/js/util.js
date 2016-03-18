/*
* 常用工具
* http://xhyo.com/
* Copyright (c) 2015 Bill Chen(chenguibiao@yy.com/48838096@qq.com)
*
*/

(function(window){
    //"use strict";
    var document = window.document;
    //console debug
    window.console = window.console || {log:function(){alert(Array.prototype.join.call(arguments, ','));},info:function(){alert(Array.prototype.join.call(arguments, ','));}};

    //命名空间
    var util = {};

    /*
     * 常用工具
     */
    //css类名操作
    var classList = util.classList = {
        add: function(dom, name) {
            var className = dom.className.replace(/(?:^\s*|\s*$)/g, '');

            if(className == '') {
                //console.log('className == \'\'');
                dom.className = name;
            } else if(className != name) {
                //console.log('className != name');
                if(className.indexOf(name) < 0) {
                    //console.log('className.indexOf(name) < 0');
                    dom.className += ' '+name;
                } else {
                    var regStr = '(?:^'+name+'\\s|\\s'+name+'\\s|\\s'+name+'$)';
                    var reg = new RegExp(regStr);
                    if(!reg.test(className)) {
                        dom.className += ' '+name;
                    }// else {console.log('catch it')}
                }
            }
        },
        remove: function(dom, name) {
            var className = dom.className;
            if(className.indexOf(name) > -1) {
                var regStr = '(?:^'+name+'\\s|\\s'+name+'\\s|\\s'+name+'$)';
                var reg = new RegExp(regStr);
                dom.className = className.replace(reg, '').replace(/(?:^\s*|\s*$)/g, '');
            }
        },
        contains: function(dom, name) {
            var regStr = '(?:^'+name+'\\s|\\s'+name+'\\s|\\s'+name+'$)';
            var reg = new RegExp(regStr);

            return reg.test(dom.className);
        }
    };
    //绑定环境变量
    util.bind = function(context, fn) {
        return function() {fn.apply(context, arguments)}
    };
    util.event = window.addEventListener? {
        add: function(dom, type, fn, cap){dom.addEventListener(type, fn, cap);},
        remove: function(dom, type, fn, cap){dom.removeEventListener(type, fn, cap);}
    } : (window.attachEvent? {
        add: function(dom, type, fn){dom.attachEvent('on'+type, fn)},
        remove: function(dom, type, fn){dom.detachEvent('on'+type, fn)}
    } : {
        add: function(dom, type, fn){dom.attachEvent('on'+type, fn)},
        remove: function(dom, type, fn){dom['on'+type]= null}
    });


    /*
     * html5和css3检测和兼容
     */
    //检测html5 API
    var html5Test = util.html5Test = {
        transition: false, //transition
        transform: false, //transform
        transform3d: false, //transform translate3d
        borderRadius: false, //border-radius
        rgba: false, //background:rgba
        animation: false, //animation
        flex: false, //flex
        dataset: 'dataset' in document.documentElement, //dataset
        localStorage: 'localStorage' in window, //localStorage
        classList: 'classList' in document.documentElement //classList
    };
    var cssPrefix = '';

    if(window.addEventListener) {
        //标准浏览器或者ie9以上
        var tmpDiv = document.createElement('div');
        var style = window.getComputedStyle(document.documentElement);

        try {
            //css前缀(浏览器前缀)
            cssPrefix = util.cssPrefix = [].slice.call(style).join('').match(/-(webkit|ms|moz|o)-/i)[1];
        } catch(e) {
            console.log('no css prefix info',e)
        }

        //检测css属性
        var cssText = 'width:1px; height:1px; position: absolute;'+
                //'-'+cssPrefix+'-border-radius:1px; border-radius:1px;'+
                //'-'+cssPrefix+'-animation:test .1s infinite; animation:test .1s infinite;'+
            '-'+cssPrefix+'-transition: all .1s linear; transition: all .1s linear;' +
            '-'+cssPrefix+'-transform: translate3d(0,0,0); transform: translate3d(0,0,0);'+
            'display: -webkit-box; display: -moz-box; display: -ms-flexbox; display: -webkit-flex;'+
            'background:rgba(0,0,0,.5);';

        style = tmpDiv.style;
        if(typeof style.transition == 'string' || typeof style[cssPrefix+'Transition'] == 'string') {
            html5Test.transition = true;
        }
        if(typeof style.transform == 'string' || typeof style[cssPrefix+'Transform'] == 'string') {
            html5Test.transform = true;
        }
        if(typeof style.animation == 'string' || typeof style[cssPrefix+'Animation'] == 'string') {
            html5Test.animation = true;
            if(!('on'+html5Test.onAnimationEnd in window) && typeof style[cssPrefix+'Animation'] == 'string') {
                html5Test.onAnimationEnd = cssPrefix+'AnimationEnd';
            }
        }
        if(typeof style.borderRadius == 'string' || typeof style[cssPrefix+'BorderRadius'] == 'string') {
            html5Test.borderRadius = true;
        }

        tmpDiv.style.cssText = cssText;
        if(style.backgroundColor) {
            html5Test.rgba = true;
        }
        if(style.display) {
            html5Test.flex = true;
        }
        if(style.transform || style[cssPrefix+'Transform']) {
            html5Test.transform3d = true;
        }

        tmpDiv = null;
    } else if(window.attachEvent) {
        cssPrefix = util.cssPrefix = 'ms';
        classList.add(document.documentElement, 'old-ie'); //ie9以下
    }

    //写class
    for(var key in html5Test) {
        //console.log(key,';',html5Test[key]);
        if(!html5Test[key]) {
            var name = 'no-'+key.replace(/([A-Z])/g, '-$1').toLowerCase();
            if(html5Test.classList)
                document.documentElement.classList.add(name);
            else
                classList.add(document.documentElement, name);
        }
    }

    //检测requestAnimationFrame和cancelAnimationFrame
    if (!window.requestAnimationFrame) {
        if (window[cssPrefix + 'RequestAnimationFrame']) {
            window.requestAnimationFrame = window[cssPrefix + 'RequestAnimationFrame'];
            window.cancelAnimationFrame = window[cssPrefix + 'CancelAnimationFrame'];
        } else {
            window.requestAnimationFrame = function(fn) {
                window.setTimeout(fn,1000/60);
            }
            window.cancelAnimationFrame = window.clearTimeout;
        }
    }

    //console.info(util);
    window.$u = util;
})(window);