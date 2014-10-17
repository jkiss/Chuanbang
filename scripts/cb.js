/* 
* @Author: Nokey
* @Date:   2014-07-04 10:27:16
* @Last Modified by:   Administrator
* @Last Modified time: 2014-10-12 13:46:20
*/
;(function($){
    //browser
    var browserMozilla = /firefox/.test(navigator.userAgent.toLowerCase());
    var browserWebkit = /webkit/.test(navigator.userAgent.toLowerCase());
    var browserOpera = /opera/.test(navigator.userAgent.toLowerCase());
    var browserMsie = /msie/.test(navigator.userAgent.toLowerCase());
    
    // Header Effective events
    $('.add-star-outfit').hover(function() {
        $(this).find('i').addClass('rotate');
    }, function() {
        $(this).find('i').removeClass('rotate');
    });

    // Main nav effective events
    $('.main-nav').find('li:not(.active)').each(function(index, el) {
        var _me = $(this),
            _index = index,
            $no_act_lis = $('.main-nav').find('li:not(.active)');

        _me.hover(function() {
            $no_act_lis.each(function(index, el) {
                if(index !== _index){
                    $(this).find('a').css('color', '#666');
                }
            });
            // _me.find('a').eq(0).stop().animate({'left': '-65px'}, 200, 'easeOutCirc');
            // _me.find('a').eq(1).stop().animate({'left': '0'}, 200, 'easeOutCirc');
            _me.find('a').eq(0).css('left', '-65px');
            _me.find('a').eq(1).css('left', 0);
        }, function() {
            $no_act_lis.each(function(index, el) {
                if(index !== _index){
                    $(this).find('a').css('color', '#ccc');
                }
            });
            // _me.find('a').eq(0).stop().animate({'left': '0'}, 200, 'easeOutCirc');
            // _me.find('a').eq(1).stop().animate({'left': '65px'}, 200, 'easeOutCirc');
            _me.find('a').eq(0).css('left', 0);
            _me.find('a').eq(1).css('left', '65px');
        });
    });

    // content 图片 Loading 事件
    $('.content').find('img').load(function() {
        $(this).animate({'opacity': 1}, 100);
    });

    // hide img which miss src
    $('img').each(function(index, el) {
        var _src = this.getAttribute('src'),
            _href = _src.slice(0, _src.indexOf('?'));
        if( _href === ''){
            this.style.display = 'none';
        }
    });
    
    // Search focus event
    $('#keywords').on('focus blur', function(event) {
        event.preventDefault();
        var _me = $(this);
        if(event.type === 'focus'){
            _me.attr('data-focus', 'true');
        }else if(event.type === 'blur'){
            _me.attr('data-focus', 'false');
        }
    });
    $.log = function(msg){
        console.log('%c' + msg, 'font-family: "courier new"; color:#000; font-size:30px; font-weight:bold; text-shadow:0 0 6px #22ff22;');
    }
    // Form keyboard submit events
    $(document).keyup(function(event) {
        if(event.keyCode === 13 && ($('.l-r-mask').length === 0 || $('.l-r-mask').css('display') === 'none')){
            var $keyword = $('#keywords'),
                keyword = $.trim($keyword.val());
            if(keyword !== '' && $keyword.attr('data-focus') === 'true'){
                window.location.href = '/search/index?word=' + keyword;
            }
        }
    });

    // Header Search input popup events
    var s_result = $('.search-result');
    s_result.titleHTML = '<a href="#" class="title"><i class="{{icon}}"></i>{{title}}（共{{total}}个结果）<span class="dots">&bullet;&bullet;&bullet;</span></a>';
    s_result.listHTML = '<li><img src="{{src}}" alt="#"><a href="{{href}}">奥黛丽·赫本(Audrey Hepburn)</a></li>';
    var s_type_hrefs = {
            'star': '/search/celebrity?word=',
            'brand': '/search/brand?word=',
            'topic': '/search/topic?word=',
            'compare': '/search/compare?word=',
            'user': '/search/user?word='
        },
        s_detail_hrefs = {
            'star': '/celebrity/',
            'brand': '/brand/',
            'topic': '/topic/',
            'compare': '/compare/',
            'user': '/user/'
        },
        search_icons = {
            'star': 'icon-celebrity',
            'brand': 'icon-brand',
            'topic': 'icon-topic',
            'compare': 'icon-com-blank',
            'user': 'icon-user-blank'
        },
        search_titles = {
            'star': '名人',
            'brand': '品牌',
            'topic': '话题',
            'compare': '对比',
            'user': '用户'
        }
    var timer;
    $('#keywords').on('input', function(e){
        var _me = $(this),
            content = $.trim(_me.val()),
            s_HTML = '',
            $search_box = $('#search_box'),
            to_ajax = function(){
                // 即时搜索并显示结果
                $.ajax({
                    url: '/search/all',
                    type: 'POST',
                    dataType: 'json',
                    data: {word: content},
                    success: function(data, status, xhr){
                        // console.log(data);
                        if(data.length !== 0){
                            for(var prop in data){
                                // console.log(data.[prop]);
                                if(data.hasOwnProperty(prop)){
                                    s_HTML += '<a href="'+ s_type_hrefs[prop] + content +'" class="title"><i class="'+ search_icons[prop] +'"></i>'+ search_titles[prop] +'（共'+ data[prop].total +'个结果）<span class="dots">&bullet;&bullet;&bullet;</span></a>';
                                    s_HTML += '<ul>';
                                    for (var i = 0; i < data[prop].data.length; i++) {
                                        console.log(data[prop].data);
                                        s_HTML += '<li><img src="'+ data[prop].data[i].img +'?w=48&h=48" alt="#"><a href="'+ s_detail_hrefs[prop] + data[prop].data[i].id +'">'+ data[prop].data[i].name +'</a></li>';
                                    };
                                    s_HTML += '</ul>';
                                }
                            }
                            $('.search-result').html(s_HTML).css('display', 'block');
                        }
                    }
                });
            };
        timer == null ? void(0) : clearTimeout(timer);
        if(content.length !== 0){
            timer = setTimeout(to_ajax, 800);
        }else{
            $('.search-result').css('display', 'none');
        }
    });
    // Header Form input hide events
    $('body').click(function(event) {
        var $search_result = $('.search-result');
        if(!($.contains($search_result[0], event.target))){
            $search_result.hide();
        }
    });

    // To-Top Events
    $(window).scroll(function(event) {
        if($(document).scrollTop() > 100){
            $('.to-top').css('display', 'block').animate({'opacity': 1}, 200);
        }else{
            $('.to-top').css({
                'opacity': '0',
                'display': 'none'
            });
        }
    });
    var movie_going = false;
    $('.to-top').click(function(event) {
        if(!movie_going){
            movie_going = true;
            var distance = $(document).scrollTop(),
                time = 300,
                start = (new Date()).getTime();

            function animate(){
                var now = (new Date()).getTime();
                var elapsed = now - start;
                var fraction = elapsed / time;
                if(fraction < 1){
                    var value = distance - distance * Math.sin(Math.PI / 2 * fraction);
                    $(document).scrollTop(value);
                    setTimeout(animate, Math.min(25, (time - elapsed)));
                }else{
                    movie_going = false;
                }
            }
            animate();
        }
    });

    // Sub nav Effective events(After onload)
    $(function(){
        $('.sub-nav').animate({'left': '65px'}, 300);
    });

    // 滚动对象
    function Wheel(opt){
        this.has_w_h = 0;
        this.ing = false;
        this.w_dis = opt.w_dis;
        this.w_panel = opt.w_panel;
        this.able_w_h = opt.able_w_h;
        this.time = opt.time;
        this.origin_top = opt.origin_top;
    };
    Wheel.prototype.wheelDown = function(){
        var _me = this;

        if(_me.has_w_h < _me.able_w_h && !_me.ing){
            _me.ing = true;
            var diff = _me.able_w_h - _me.has_w_h;
            if(diff < _me.w_dis){
                _me.has_w_h += diff;
            }else{
                _me.has_w_h += _me.w_dis;
            }
            _me.w_panel.animate({
                'top': -_me.has_w_h + _me.origin_top + 'px'
                },
                _me.time, 'linear', function() {
                _me.ing = false;
            });
        }
    };
    Wheel.prototype.wheelUp = function(){
        var _me = this;

        if(_me.has_w_h > 0 && !_me.ing){
            _me.ing = true;
            if(_me.has_w_h < _me.w_dis){
                _me.has_w_h -= _me.has_w_h;
            }else{
                _me.has_w_h -= _me.w_dis;
            }
            _me.w_panel.animate({
                'top': -_me.has_w_h + _me.origin_top + 'px'
                },
                _me.time, 'linear', function() {
                _me.ing = false;
            });
        }
    };
    Wheel.prototype.wheelBottom = function(){
        var _me = this;

        if(_me.able_w_h > 0){   // 自动滚动
            var auto_dis = _me.able_w_h - _me.has_w_h;
            if(auto_dis > 0 && !_me.ing){
                _me.has_w_h += auto_dis;
                _me.ing = true;
                _me.w_panel.animate({
                    'top': -_me.has_w_h + _me.origin_top + 'px'
                },
                    _me.time, function() {
                    _me.ing = false;
                });
            }
        }
    };
    Wheel.prototype.wheelTop = function(){
        var _me = this;

        if(!_me.ing){
            _me.ing = ture;
            _me.has_w_h = 0;
            _me.w_panel.animate({
                'top': 0 + _me.origin_top + 'px'
            },
                _me.time, function() {
                _me.ing = false;
            });
        }
    };

    // 简单的添加滚轮上下滚动事件
    $.fn.addWheelEvent = function(user_opts){
        var opts = $.extend({}, $.fn.addWheelEvent.default_opts, user_opts);
        
        return this.each(function(index, el) {
            var isFirefox = (window.navigator.userAgent.toLowerCase().indexOf('firefox') > -1);
            var down = true;
            var wheelFn = function(event){
                var ev = event || window.event;
                down = ev.wheelDelta ? ev.wheelDelta < 0 : ev.detail > 0;
                opts.handler && opts.handler(down, ev);

                if(opts.preventDefault){
                    ev.returnValue = false;
                    ev.preventDefault && ev.preventDefault();
                }
                if(opts.stopPropagation){
                    ev.cancelBubble = true;
                    ev.stopPropagation && ev.stopPropagation();
                }
                return false;
            };
            if(isFirefox){
                this.addEventListener('DOMMouseScroll', wheelFn, false);
            }else{
                // addEvent(ele, 'mousewheel', wheelFn);
                this.onmousewheel = wheelFn;
            }
        });
    };
    $.fn.addWheelEvent.default_opts = {     
        handler: null,
        preventDefault: true,
        stopPropagation: true
    };

    // 飞图
    // =============    Needn't use below fly-img code anymore  ============ 
    // fly_end = false;
    // var $airplane = $('<div id="airplane"></div>').appendTo('body'),
    //     target = $('div.compare-fix')[0].getBoundingClientRect();

    // var left = fly_img.getBoundingClientRect().left,
    //     top = fly_img.getBoundingClientRect().top,
    //     width = fly_img.width,
    //     height = fly_img.height,
    //     src = fly_img.src;
    
    // $airplane.css({
    //     'left': left + 'px',
    //     'top': top + 'px',
    //     'width': width + 'px',
    //     'height': height + 'px',
    //     'background-image': 'url(' + src + ')',
    //     'display': 'block'
    // });
    // $.flyTo($airplane, target.left, target.top + 50, function(){
    //     // ....
    // });
    jQuery.flyTo = function(airplane, to_x, to_y, callback){
        airplane.animate({
                'left': to_x + 'px',
                'top': to_y + 'px',
                'width': '30px',
                'height': '20px',
                'opacity': 0
            },
            500, function() {
                airplane.css({
                    'display': 'none',
                    'opacity': 1
                }).remove();
                if(callback){
                    callback();
                }
            }
        );
    };

    // 获取元素中心的视口坐标
    jQuery.fn.getViewCoor = function(){
        var rect = this.getBoundingClientRect();
        return {
            x: (rect.right - rect.left) / 2,
            y: (rect.bottom - rect.top) / 2
        }
    };

    // 弹出登陆页面
    var login_layer = $('<div class="l-r-mask no-select"><div class="panel abs-center"><div class="login-close-btn icon-cancel"></div><div class="login template"><h1>登录</h1><ul class="author"><a href="http://3w.chuanbang.com/weibo/login" target="_self"><li class="weibo"><i class="icon-weibo"></i><span class="sep">新浪微博登录</span></li></a><a href="http://3w.chuanbang.com/qq/login" target="_self"><li class="qq"><i class="qq-white"></i><span class="sep">QQ登录</span></li></a></ul><ul class="l-r"><li><input type="email" name="email" id="log_email" placeholder="电子邮箱"></li><li><input type="password" name="pwd" id="log_pwd" placeholder="用户密码"></li><li><input type="checkbox" name="remember-me" id="remember_me">记住我<a href="#" class="f-pwd">忘记密码</a></li></ul><div class="login-go">进入</div><p class="to-register">还没有账号么？立即<span class="btn to-regis">注册</span></p></div><div class="sign-up template"><h1>注册</h1><ul class="author"><a href="http://3w.chuanbang.com/weibo/login" target="_self"><li class="weibo"><i class="icon-weibo"></i><span class="sep">新浪微博登录</span></li></a><a href="http://3w.chuanbang.com/qq/login" target="_self"><li class="qq"><i class="qq-white"></i><span class="sep">QQ登录</span></li></a></ul><ul class="l-r"><li><input type="email" name="email" id="sign_email" placeholder="电子邮箱"></li><li><input type="password" name="pwd" id="sign_pwd" placeholder="用户密码"></li><li><input type="checkbox" name="agree-term" id="agree_term">同意服务条款</li></ul><div class="login-go">加入我们</div><p class="to-register">已经注册？立即<span class="btn back-login">登录</span></p></div><div class="forget-pwd template"><h1>忘记密码</h1><input type="email" name="email" id="forget_email" placeholder="电子邮箱"><div class="login-go">进入</div><p class="to-register">已经注册？立即<span class="back-login btn">登录</span></p></div></div></div>');
    $('.login-no').click(function(event) {
        $('.l-r-mask').length === 0 ? function(){
            $('body').append(login_layer);
            // 取消注册或登录界面
            var $l_r_mask = $('.l-r-mask'),
                $login = $('.login'),
                $sign_up = $('.sign-up'),
                $forget_pwd = $('.forget-pwd'),
                moving = false;
            $l_r_mask.click(function(event) {
                if(event.target === this || event.target === $('.login-close-btn')[0]){
                    this.style.display = 'none';
                    $login.css('display', 'block');
                    $sign_up.css('display', 'none');
                    $forget_pwd.css('display', 'none');
                }
            });
            $login.find('.f-pwd').click(function(event) {
                $login.css('display', 'none');
                $forget_pwd.css('display', 'block');
            });
            $login.find('.to-regis').click(function(event) {
                $login.css('display', 'none');
                $sign_up.css('display', 'block');
            });
            $forget_pwd.find('.back-login').click(function(event) {
                $forget_pwd.css('display', 'none');
                $login.css('display', 'block');
            });
            $sign_up.find('.back-login').click(function(event) {
                $sign_up.css('display', 'none');
                $login.css('display', 'block');
            });
            $l_r_mask.find('input[type="email"]').blur(function(event) {
                var email_pattern = /^([a-zA-Z0-9]+[_|\-|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9_\-\.])+[a-zA-Z]+$/;
                // console.log(email_pattern.test($(this).val()));
                var $email = $(this),
                    email = $(this).val();
                if(email !== ''){
                    email_pattern.test(email) ?
                    function(){
                        $email.removeClass('illegal');
                    }() : function(){
                        $email.addClass('illegal');
                        if(!moving){
                            moving = true;
                            $email.shake({speed: 80, times: 2, callback: function(){
                                moving = false;
                            }});
                        }
                    }();
                }
            });
            // 登录
            $login.find('.login-go').click(function(event) {
                var email = $('#log_email').val(),
                    pwd = $('#log_pwd').val(),
                    check = String($('#remember_me').is(':checked')),
                    origin_text = this.innerText,
                    _me = this;

                // console.log(email + pwd + check);
                if(email === '' || pwd === ''){
                    // 错误提示
                    if(!moving){
                        moving = true;
                        this.innerText = '邮箱或密码还没输入哦！';
                        if(email === ''){
                            moving = true;
                            $('#log_email').addClass('illegal');
                            $('#log_email').shake({speed: 80, times: 2, callback: function(){
                                $(this).removeClass('illegal');
                                _me.innerText = origin_text;
                                moving = false;
                            }});
                        }
                        if(pwd === ''){
                            $('#log_pwd').addClass('illegal');
                            $('#log_pwd').delay(30).shake({speed: 80, times: 2, callback: function(){
                                $(this).removeClass('illegal');
                                _me.innerText = origin_text;
                                moving = false;
                            }});
                        }
                    }
                }else{
                    if($('#log_email').attr('class') && $('#log_email').attr('class').search('illegal') !== -1){
                        // 邮箱非法
                        if(!moving){
                            moving = true;
                            _me.innerText = '邮箱貌似有点小差错哦！';
                            $('#log_email').shake({speed: 80, times: 2, callback: function(){
                                _me.innerText = origin_text;
                                moving = false;
                            }});
                        }
                    }else{
                        // 进行验证Loading...
                        _me.innerHTML = '<i class="loading"></i>';
                        $.ajax({
                            url: '/user/login',
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                email: email,
                                password: pwd,
                                rememberMe: check
                            },
                            success: function(data, status, xhr){
                                // 取消Loading
                                console.log(data);
                                if(data.ret === 0){    // 登陆成功
                                    location.reload();
                                }else if(data.ret === 400){      // 登录失败，提示信息
                                    $(_me).addClass('err-msg');
                                    $(_me).text(data.msg);
                                    setTimeout(function(){
                                        $(_me).toggleClass('err-msg');
                                        $(_me).text(origin_text);
                                    }, 1000);
                                }
                            },
                            error: function(xhr, status, msg){

                            },
                            complete: function(xhr, status){

                            }
                        });
                    }
                }
            });
            // 注册
            $sign_up.find('.login-go').click(function(event) {
                var email = $('#sign_email').val(),
                    pwd = $('#sign_pwd').val(),
                    check = String($('#agree_term').is(':checked')),
                    origin_text = this.innerText,
                    _me = this;

                // console.log(email + pwd + check);
                if(email === '' || pwd === ''){
                    // 错误提示
                    if(!moving){
                        moving = true;
                        this.innerText = '邮箱或密码还没输入哦！';
                        if(email === ''){
                            $('#sign_email').addClass('illegal');
                            $('#sign_email').shake({speed: 80, times: 2, callback: function(){
                                $(this).removeClass('illegal');
                                _me.innerText = origin_text;
                                moving = false;
                            }});
                        }
                        if(pwd === ''){
                            $('#sign_pwd').addClass('illegal');
                            $('#sign_pwd').delay(30).shake({speed: 80, times: 2, callback: function(){
                                $(this).removeClass('illegal');
                                _me.innerText = origin_text;
                                moving = false;
                            }});
                        }
                    }
                }else{
                    if($('#sign_email').attr('class') && $('#sign_email').attr('class').search('illegal') !== -1){
                        // 邮箱非法
                        if(!moving){
                            moving = true;
                            this.innerText = '邮箱貌似有点小差错哦！';
                            $('#sign_email').shake({speed: 80, times: 2, callback: function(){
                                _me.innerText = origin_text;
                                moving = false;
                            }});
                        }
                    }else{
                        // 进行注册Loading...
                        _me.innerHTML = '<i class="loading"></i>';
                        $.ajax({
                            url: '/user/register',
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                email: email,
                                password: pwd,
                                agreeTerm: check
                            },
                            success: function(data, status, xhr){
                                // 取消Loading
                                console.log(data);
                                if(data.ret === 0){
                                    window.location.reload();
                                }else if(data.ret === 400){
                                    $(_me).addClass('err-msg');
                                    $(_me).text(data.msg);
                                    setTimeout(function(){
                                        $(_me).toggleClass('err-msg');
                                        $(_me).text(origin_text);
                                    }, 1000);
                                }
                            },
                            error: function(xhr, status, msg){

                            },
                            complete: function(xhr, status){

                            }
                        });
                    }
                }
            });
            // 忘记密码
            $forget_pwd.find('.login-go').click(function(event) {
                var email = $('#forget_email').val(),
                    origin_text = this.innerText,
                    _me = this;
                if(email === '' && !moving){   // 邮箱为空
                    moving = true;
                    this.innerText = '还没有输入邮箱哦！';
                    $('#forget_email').addClass('illegal');
                    $('#forget_email').shake({speed: 80, times: 2, callback: function(){
                        $(this).removeClass('illegal');
                        moving = false;
                        _me.innerText = origin_text;
                    }});
                }else{
                    if($('#forget_email').attr('class') && $('#forget_email').attr('class').search('illegal') !== -1){    // 非法的邮箱
                        if(!moving){
                            moving = true;
                            _me.innerText = '邮箱貌似有点小差错哦！';
                            $('#forget_email').shake({speed: 80, times: 2, callback: function(){
                                _me.innerText = origin_text;
                                moving = false;
                            }});
                        }
                    }else{   // 有效地邮箱
                        // 跳转到邮箱界面
                    }
                }
            });
        }() : function(){
            $('.l-r-mask').css('display', 'block');
        }();
    });

    // shake动画
    $.fn.shake = function(o) {
        if (typeof o === 'function')
            o = {callback: o};
        // Set options
        var o = $.extend({
            direction: "left",
            distance: 20,
            times: 3,
            speed: 140,
            easing: "swing"
        }, o);

        return this.each(function() {
            // Create element
            var el = $(this),
                props = {
                    position: el.css("position"),
                    top: el.css("top"),
                    bottom: el.css("bottom"),
                    left: el.css("left"),
                    right: el.css("right")
                };
            // Change elment position
            props.position === 'absolute' ? void(0) : el.css("position", "relative");
            // Adjust
            var ref = (o.direction == "up" || o.direction == "down") ? "top" : "left";
            var motion = (o.direction == "up" || o.direction == "left") ? "pos" : "neg";

            // Animation
            var animation = {},
                animation1 = {},
                animation2 = {};
            animation[ref] = (motion == "pos" ? "-=" : "+=") + o.distance;
            animation1[ref] = (motion == "pos" ? "+=" : "-=") + o.distance * 2;
            animation2[ref] = (motion == "pos" ? "-=" : "+=") + o.distance * 2;

            // Animate
            el.animate(animation, o.speed, o.easing);
            for (var i = 1; i < o.times; i++) { // Shakes
                el.animate(animation1, o.speed, o.easing).animate(animation2, o.speed, o.easing);
            };
            el.animate(animation1, o.speed, o.easing).
            animate(animation, o.speed / 2, o.easing, function(){ // Last shake
                el.css(props); // Restore
                if(o.callback) o.callback.apply(this, arguments); // Callback
            });
        });
    };

    // 字数限制工具函数（函数式编程思想）
    $('.word-num-limit').on('input', function(event) {
        event.preventDefault();
        var _me = $(this),
            _val = _me.val(),
            _max = parseInt(_me.attr('data-max-words'));
        // console.log(_me.val());
        if(_val.length > _max){
            _me.val(_val.slice(0, _max));
        }
    });

    // publish func tools(eg. published 1 second before)
    function publishDate(s){
        var _date = new Date(),
            _s = Math.ceil(_date.getTime() / 1000),
            _dif = _s - s,
            _T0 = (function(d){
                return _s - d.getSeconds() - d.getMinutes()*60 - d.getHours()*3600;
            }(_date)),
            _Y0 = (function(){
                return _T0 - 24 * 3600;
            }()),
            _B0 = (function(){
                return _Y0 - 24 * 3600;
            }()),
            _filter = {
                S1_59:  function(){
                    if(_dif >= 1 && _dif < 60) return _dif + '秒前';
                    else return false;
                },
                M1_60: function(){
                    if(_dif >=60 && _dif < 3600) return Math.ceil(_dif / 60) + '分钟前';
                    else return false;
                },
                H1_24: function(){
                    if(_dif >= 3600 && _dif < 86400) return Math.ceil(_dif / 3600) + '小时前';
                    else return false;
                },
                Y: function(){
                    if(s >= _Y0 && s < _T0) return '昨天';
                    else return false;
                },
                BY: function(){
                    if(s >= _B0 && s < _Y0) return '前天';
                    else return false;
                },
                D: function(){
                    if(s < _B0 && s >=0){
                        var ago = new Date(s * 1000);
                        return ago.getFullYear()+ '年'+ ago.getMonth()+ '月'+ ago.getDate()+ '日';
                    }else{
                        return '000：时空错乱了！';
                    }
                }
            };
        // console.log(_filter.D());
        return _filter.S1_59() || _filter.M1_60() || _filter.H1_24() || _filter.Y() || _filter.BY() || _filter.D();
    };
    console.log(publishDate(parseInt((new Date()).getTime()/1000)));

    // Pagination Ajax
    function Pagination(opt){
        this.url = opt.url;                                          // 分页请求接口
        this.max_page = opt.max_page;                                // 总页数
        this.paged = 1;                                              // 当前页码
        this.range = opt.range;                                      // 超出显示的最大范围
        this.updateContent = opt.updateContent;
        this.beforeSend = opt.beforeSend;
        this.sendComplete = opt.sendComplete;
        this.wrap = opt.wrap;

        // 上一页的 HTML
        this.prevHTML = {
            'able': '<span class="prev"><i class="icon-dir-left"></i>上一页</span>',
            'disable': '<span class="prev disable"><i class="icon-dir-left"></i>上一页</span>'
        };

        // 下一页的 HTML
        this.nextHTML = {
            'able': '<span class="next">下一页<i class="icon-dir-right"></i></span>',
            'disable': '<span class="next disable">下一页<i class="icon-dir-right"></i></span>'
        };

        // 页码的 HTML
        this.numHTML = '<li data-page="{{num}}">{{num}}</li>';
        // 省略号的 HTML
        this.dotHTML = '<em class="dots"><i class="icon-dots"></i></em>';
    };

    Pagination.prototype.xhrPage = function(num, page_panel, extra_data){
        /*** 请求第 num 页的内容 ***/
        this.beforeSend();
        // 进行 Ajax 请求
        var data = {
                page: num
            },
            _me = this;
        data = $.extend({}, data, extra_data);
        $.ajax({
            url: _me.url,
            type: 'GET',
            dataType: 'json',
            data: data,
            async: true,
            success: function(data, status, xhr){
                // console.log(data.data);
                _me.updateContent(data, page_panel);
                // change total_page
                _me.max_page = data.total_page;
                $('.page-num').attr('data-total-page', data.total_page);
            },
            error: function(xhr, status, errorMsg){
                // 请求出错后的提示信息
                console.log(errorMsg);
            },
            complete: function(xhr, status){
                _me.updateNum(num, _me.wrap);
                _me.sendComplete();
            }
        });
    };

    Pagination.prototype.updateNum = function(paged){     // 更新页码显示
        this.paged = paged;
        var updateHTML = '',
            start = 0,           // 页码起始数
            _me = this;
        if(this.max_page <= this.range){
            start = 1;
            for (var i = start; i <= this.max_page; i++) {
                // updateHTML += '<li class="p-num" data-page="' + i +'">' + i + '</li>';
                // 用模板代替 HTML 字符串
                updateHTML += _me.numHTML.replace(/{{num}}/g, i);
            };
        }else{
            // 前一页按钮
            this.paged <= 1
                ? updateHTML += _me.prevHTML.disable
                : updateHTML += _me.prevHTML.able;

            if(this.paged <= Math.ceil(this.range / 2)){
                start = 1;
                // 中间 Range 的页码
                for (var i = start; i <= this.range; i++) {
                    updateHTML += _me.numHTML.replace(/{{num}}/g, i);
                };
                // 后省略号
                updateHTML += _me.dotHTML;
                // The last page num
                updateHTML += _me.numHTML.replace(/{{num}}/g, this.max_page);
            }else if(this.paged >= (this.max_page - Math.floor(this.range / 2))){
                start = this.max_page - this.range + 1;
                // The first page num
                updateHTML += _me.numHTML.replace(/{{num}}/g, 1);
                // 前省略号
                updateHTML += _me.dotHTML;
                // 中间 Range 的页码
                for (var i = start; i <= this.max_page; i++) {
                    updateHTML += _me.numHTML.replace(/{{num}}/g, i);
                };
            }else{
                start = this.paged - Math.floor(this.range / 2);
                // The first page num
                updateHTML += _me.numHTML.replace(/{{num}}/g, 1);
                // 前省略号
                updateHTML += _me.dotHTML;
                // 中间 Range 的页码
                for (var i = start; i < (this.range + start); i++) {
                    updateHTML += _me.numHTML.replace(/{{num}}/g, i);
                };
                // 后省略号
                updateHTML += _me.dotHTML;
                // The last page num
                updateHTML += _me.numHTML.replace(/{{num}}/g, this.max_page);
            }

            // 后一页按钮
            this.paged >= this.max_page
                ? updateHTML += _me.nextHTML.disable
                : updateHTML += _me.nextHTML.able;
        }
        _me.wrap === '' ? void(0) : function(){
            _me.wrap.html(updateHTML).find('li').each(function(index, el) {
                if(parseInt(this.getAttribute('data-page')) === _me.paged){
                    $(this).addClass('current');
                }
            });
        }();
    };

    Pagination.prototype.init = function(callback){    // first page init
        callback();
    };

    
    // $('.skip-to').on('click', function(event) {
    //     var num = parseInt($('#spec_num').val());
    //     if(!$(this).hasClass('disable')){
    //         if(num >= 1 && num <= pageXHR.max_page){
    //             pageXHR.paged = num;
    //             // console.log(typeof pageXHR.paged);
    //             // 进行xhr请求，在期间隐藏分页区，请求complete后显示
    //             pageXHR.xhrPage(pageXHR.paged, $wrap, $page_panel, extra_data);
    //         }else{
    //             // 提示页码溢出错误
    //         }
    //     }
    // });
    
    // Page Turning validate input value
    // $('#spec_num').on('input', function(event) {
    //     // console.log(this.value);
    //     var isNum = /^[0-9]*[1-9][0-9]*$/;
    //     if(isNum.test(this.value)){
    //         this.value = this.value;
    //         $('.skip-to').removeClass('disable').addClass('able');
    //     }else{
    //         this.value = '';
    //         $('.skip-to').removeClass('able').addClass('disable');
    //     }
    // });
    

    // new & hot 翻页
    $('.tab-nav').find('.latest').click(function(event) {
        if(!$(this).hasClass('active')){
            var _me = $(this);
            $('.tab-nav').find('.most-hot').removeClass('active');
            _me.addClass('active');
            $('.tab-nav').find('.black-frame').animate({'left': '125px'}, 300, 'swing');

            extra_data.tag = _me.attr('data-flag');
            // 请求分页
            pageXHR.paged = 1;
            pageXHR.xhrPage(pageXHR.paged, $page_panel, extra_data);
        }
    });
    $('.tab-nav').find('.most-hot').click(function(event) {
        if(!$(this).hasClass('active')){
            var _me = $(this);
            $('.tab-nav').find('.latest').removeClass('active');
            _me.addClass('active');
            $('.tab-nav').find('.black-frame').animate({'left': 0}, 300, 'swing');

            extra_data.tag = _me.attr('data-flag');
            // 请求分页
            pageXHR.paged = 1;
            pageXHR.xhrPage(pageXHR.paged, $page_panel, extra_data);
        }
    });

    $.log('    Nokey_Hack');
    // this func invoke the <script> in PHP's Module.
    // Must keep this func in the end of this file!!!
    invokeHere === undefined ? void(0) : invokeHere(Pagination, publishDate, Wheel);
}(jQuery));