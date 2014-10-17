<!-- The sub nav -->
<div class="sub-nav-wrap">
    <div class="sub-nav">
        <nav class="items pending-sub">
            <h1>趋势</h1>
            <ul class="wheel-panel">
                <?php foreach($trend as $q):?>
                <li class="transition"><a href="<?php echo Yii::app()->createUrl('question/view', array('id'=>$q['id']));?>">
                    <div class="top">
                        <div class="img-wrap">
                            <img src="<?php echo $q['img'];?>?w=65&h=100" alt="Pending">
                        </div>
                        <p class="img-data"><em>
                                共<?php echo $q['total_imgs'];?>张图
                                <br>
                                <?php echo $q['total_comments'];?>条评论
                            </em></p>
                    </div>
                    <div class="bottom">
                        <div class="img-wrap">
                            <img src="<?php echo $q['user_head'];?>?w=30&h=30" alt="avatar">
                        </div>
                        <p class="name"><?php echo $q['user_nick'];?></p>
                        <p class="time"><?php echo $q['createtime'];?></p>
                    </div>
                </a></li>
                <?php endforeach;?>
            </ul>
        </nav>
    </div>
</div>
<!-- Wait detail content -->
<section class="content" id="answer" data-ques-id="<?php echo $model['id'];?>">
    <!-- This's the MOST-HOT answer that contain Slider-IMG -->
    <div class="most-hot">
        <div class="view-title"></div>
        <div class="gray-box">
            <!-- 这个标题可能有可能没有 -->
            <?php if($model['content'] != ''):?>
            <div class="comment-title">
                <em><?php echo $model['content'];?></em>
            </div>
            <?php endif;?>
            <!-- slider mode switch -->
            <div class="img-box clearfix show-mode1">
                <div id="thumb_box">
                    <div class="imgs-box">
                        <ul class="imgs-abs-wrap">
                            <?php $i = 0;?>
                            <?php foreach($model['imgs'] as $img):?>
                            <li <?php if(++$i == 1): echo 'class="on"'; endif;?>>
                                <img src="<?php echo $img['url'];?>?w=100" data-support="<?php echo $img['support'];?>" data-compare="" data-id="<?php echo $img['id'];?>" data-url="<?php echo $img['url'];?>">
                            </li>
                            <?php endforeach;?>
                        </ul>
                    </div>
                </div>

                <div class="slider-box">
                    <ul id="slider_box">
                        <?php $j = 0;?>
                        <?php foreach($model['imgs'] as $img):?>
                        <li <?php if(++$j == 1): echo 'class="s-on"'; endif;?>>
                            <img src="<?php echo $img['url'];?>" data-support="<?php echo $img['support'];?>" data-compare="" data-id="<?php echo $img['id'];?>">
                        </li>
                        <?php endforeach;?>
                    </ul>
                    <div class="prev btn transition" id="to_top">
                        <i class="icon-dir-left"></i>
                    </div>
                    <div class="next btn transition" id="to_down">
                        <i class="icon-dir-right"></i>
                    </div>
                </div>
            </div>
            <div class="thr-col-wrap grid three clearfix show-mode2">
                <div class="column one">
                    <?php foreach($model['imgs'] as $img):?>
                    <img src="<?php echo $img['url'];?>?w=300" data-support="<?php echo $img['support'];?>" data-compare="" data-id="<?php echo $img['id'];?>">
                    <?php endforeach;?>
                </div>
            </div>
            <!--  -->
            <div class="mode-ctrl">
                <span class="mode2">
                    列表浏览
                    <i class="icon-slide-2"></i>
                </span>
                <span class="mode1">
                    翻页浏览
                    <i class="icon-slide-1"></i>
                </span>
            </div>
            <div class="author">
                <div class="img-wrap">
                    <img src="<?php echo $model['author']['head'];?>?w=40&h=40" alt="">
                </div>
                <p class="name"><?php echo $model['author']['nick'];?></p>
                <p class="time"><?php echo $model['time'];?></p>
                <div class="comment-num">全部评论<?php echo $model['total_comments'];?></div>
            </div>
        </div>
        <ul class="comment-box clearfix">
            <?php if(empty($model['comments'])):?>
            <div class="no-comment">
            </div>
            <?php else:?>
            <?php foreach($model['comments'] as $comment):?>
            <li>
                <div class="ava-wrap">
                    <img src="<?php echo $comment['author']['head'];?>?w=40&h=40" alt="">
                </div>
                <p class="m-data">
                    <span class="name"><?php echo $comment['author']['nick'];?></span>
                    <span class="time"><?php echo $comment['time'];?></span>
                </p>
                <p class="desc">
                    <?php echo $comment['content'];?>
                </p>
            </li>
            <?php endforeach;?>
            <?php endif;?>

            <div class="more <?php if(empty($model['comments'])): echo 'hide'; endif;?>">
                <span class="more-btn">更多评论</span>
            </div>
            <?php if(isset($user)):?>
            <div class="input" data-user-nick="<?php echo $user['nick'];?>">
                <div class="ava-wrap">
                    <img src="<?php echo $user['head'];?>?w=40&h=40" alt="">
                </div>
                <textarea name="content" id="comment_text"></textarea>
                <div class="input-btn">
                    提 交
                </div>
            </div>
            <?php else:?>
            <div class="input">
                <div class="login-no">
                    <i id="login_btn">登陆后</i>，发牢骚
                </div>
            </div>
            <?php endif;?>
        </ul>
    </div>
    <!--  -->
    <div class="no-other-ans">
        暂无其他解读
    </div>
    <div class="submit-answer no">我来解答</div>

    <!-- 开始对比侧滑面板 -->
    <div class="compare-fix no-select transition">
        <div class="ctrl-btn">
            <i class="icon-compare-1"></i>
        </div>
        <div class="c-f-abs">
            <ul class="c-f-panel">
                <?php foreach($compares as $compare):?>
                <li><i class="com-cancel icon-cancel-1" style="display: none;"></i>
                    <img src="<?php echo $compare['img'];?>" data-id="" alt="#">
                </li>
                <?php endforeach;?>
            </ul>
        </div>
        <div class="c-title">
            <input type="text" name="title" class="word-num-limit" data-max-words="30" id="c_title" placeholder="在这里添加对比标题">
        </div>
        <p class="c-err-msg">
            图片要至少 2 张哦 ^-^
        </p>
        <div class="to-compare">
            开始对比
            <span>(<span id="c_num">1</span>/9)</span>
        </div>
        <div class="c-loading">
            <div class="loading"></div>
        </div>
    </div>
</section>
<!-- Big Photo Modal -->
<div id="big_photo">
    <div class="prev-btn"><i class="icon-dir-left"></i></div>
    <div class="p-wrap">
        <div class="p-box no-select">
            <img src="../images/wait/slide1.jpg" alt="big1">
        </div>
    </div>
    <div class="next-btn"><i class="icon-dir-right"></i></div>
    <div class="close-btn"><i class="icon-cancel-1"></i></div>
    <ul class="ctrl-panel">
        <li class="compare"><i class="icon-compare-1"></i><br>对比</li>
        <li class="zan"><i class="icon-zan-1"></i><br>赞</li>
        <li class="share"><i class="icon-share-1"></i><br>分享</li>
    </ul>
</div>
<!-- The Answer popup -->
<?php if(isset($user)):?>
<div id="a_pop">
    <div class="pop-box clearfix">
        <div class="scroll-wrap">
            <div id="top_shadow"></div>
            <div class="scroll-panel">
                <div class="top">
                    <i class="icon-pop"></i>
                </div>
                <div class="left">
                    <img class="pop-ava" src="<?php echo $user['head'];?>?w=80&h=80" alt="Pop_avatar">
                    <p class="name text-overflow"><?php echo $user['nick'];?></p>
                </div>
                <div class="right form-info clearfix">
                    <!-- 添加穿着 -->
                    <ul class="f-box no-select">
                        <li id="p1" class="p-one clearfix">
                            <div class="l-person">
                                <div class="drop-list" data-hide="">
                                    <input type="text" name="p1" placeholder="刘德华" class="celebrity-name drop-ele">
                                    <ul class="p-list-items">
                                        
                                    </ul>
                                </div>
                                
                                <div id="add_person" style="display:none;">
                                    <i class="icon-follow-1"></i>
                                </div>
                            </div>
                            <ul class="r-wear">
                                <li class="w-one" id="w1">
                                    <div class="drop-list" data-hide="">
                                        <input type="text" name="clothes" class="clothes brand-name drop-ele" placeholder="普拉达">
                                        <ul class="p-list-items">
                                            <li>Nokey</li>
                                        </ul>
                                    </div>
                                    <input type="text" name="when" class="when" placeholder="2014早春款">
                                    <span class="type">
                                        <i class="t-on">上装</i>
                                        <ul class="t-items">
                                            <li>套装</li>
                                            <li>上装</li>
                                            <li>下装</li>
                                            <li>包</li>
                                            <li>鞋</li>
                                        </ul>
                                        <i class="tri-angle"></i>
                                    </span>
                                    <i class="w-cancel icon-cancel-1"></i>
                                </li>
                                <div class="add-wear" style="display:none;">
                                    <i class="icon-follow-1"></i>
                                </div>
                            </ul>
                        </li>
                    </ul>
                    <!-- 添加事件 -->
                    <div class="f-event no-select" style="display:none;">
                        <div class="drop-list" data-hide="">
                            <input type="text" class="matter" placeholder="请输入事件">
                            <ul class="p-list-items">
                                <li>Nokey</li>
                            </ul>
                        </div>
                        <input type="text" class="place" placeholder="请输入地点">
                        <div id="cal_date">
                            <span class="time">2014-07-10</span>
                            <div class="cal">
                                <ul class="cal-head">
                                    <li>
                                        <i class="y-prev icon-tri-left"></i>
                                        <span class="year">2014年</span>
                                        <i class="y-next icon-tri-right"></i>
                                    </li>
                                    <li>
                                        <i class="m-prev icon-tri-left"></i>
                                        <span class="month">07月</span>
                                        <i class="m-next icon-tri-right"></i>
                                    </li>
                                </ul>
                                <ul class="cal-panel clearfix">
                                    <li>1</li>
                                    <li>2</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- 添加评论 -->
                    <textarea name="comment" id="a_comment" class="a-comment no-select word-num-limit" placeholder="请输入评论（140字以内）" data-max-words="300" style="display:none;"></textarea>
                    <!-- 添加对比图片 -->
                    <div class="add-c-img" style="display:none;">
                        <h1>添加对比图：</h1>
                        <ul class="added-imgs clearfix">
                            <!-- <li>
                                <i class="cancel-added-img"></i>
                                <img src="images/slide1.jpg" alt="000">
                            </li> -->
                            <i class="file-btn">
                                <input type="file" multiple="multiple" name="addedImgs" accept="image/*" id="add_imgs_btn">
                            </i>
                        </ul>
                    </div>
                </div>
            </div>
            <div id="bottom_shadow"></div>
        </div>
        <div class="bottom no-select">
            <a href="#" id="sub_btn" class="abs-center">确认发布</a>
        </div>
    </div>
    <div id="a_pop_loading" class="no-select">
        <div class="loading"></div>
    </div>
</div>
<?php endif;?>
<script>
function invokeHere(Pagination, publishDate, Wheel){
    // ========    Celebrity name drop-list when submit answer    ========
    var pop_timer;
    $('#a_pop').find('.f-box').on('input', '.drop-ele', function(event) {
        event.preventDefault();
        var _me = $(this),
            value = $.trim(_me.val()),
            list_items = _me.next(),
            drop_hdie = _me.parent(),
            ajaxing = false,
            r_urls = {
                'celebrity-name': '/answer/suggestCelebrity',
                'brand-name': '/answer/suggestBrand'
            },
            url = (function(){
                if(_me.hasClass('celebrity-name')){
                    return '/answer/suggestCelebrity';
                }else if(_me.hasClass('brand-name')){
                    return '/answer/suggestBrand';
                }
            }()),
            to_ajax = function(){
                if(!ajaxing){
                    ajaxing = true;
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            term: value
                        },
                        success: function(data, status, xhr){
                            var content = '';
                            for (var i = 0; i < data.length; i++) {
                                content += '<li class="list-one" data-id="'+ data[i].id +'">'+ data[i].name +'</li>';
                            };
                            list_items.html(content);
                            list_items.css('display', 'block').animate({
                                'top': '45px',
                                'opacity': 1
                            }, 200);
                        },
                        error: function(xhr, status, msg){

                        },
                        complete: function(xhr, status){
                            ajaxing = true;
                        }
                    });
                }
            };

        pop_timer == null ? void(0) : clearTimeout(pop_timer);
        if(value.length !== 0){
            pop_timer = setTimeout(to_ajax, 800);
        }else{
            list_items.css({
                'display': 'none',
                'opacity': '0'
            });;
        }
    });
    $('#a_pop').find('.f-box').on('click', '.list-one', function(event) {
        var _me = $(this),
            list_items = _me.parent(),
            data_hide = _me.parent().parent();

        
    });
    // ==============     Comment on question    ================
    $('.most-hot').find('.input-btn').click(function(event) {
        var _me = $(this),
            qus_id = $('#answer').attr('data-ques-id'),
            input_domain = $('#comment_text'),
            input_val = input_domain.val(),
            comment_box = $('.comment-box'),
            nick = comment_box.find('.input').attr('data-user-nick');
            ajaxing = false;

        if(input_val === ''){
            input_domain.addClass('warning');
            setTimeout(function(){
                input_domain.removeClass('warning');
            }, 500);
        }else if(!ajaxing){
            ajaxing = true;
            $.ajax({
                url: '/question/comment',
                type: 'POST',
                dataType: 'json',
                data: {
                    id: qus_id,
                    content: input_val
                },
                success: function(data, status, xhr){
                    if(data.msg === 'success'){
                        var content = '<li class="new-one"><div class="ava-wrap"><img src="'+ comment_box.find('.input img').attr('src') +'?w=40&h=40" alt=""></div><p class="m-data"><span class="name">'+ nick +'</span><span class="time">'+ publishDate(Math.floor((new Date()).getTime() / 1000)) +'</span></p><p class="desc">'+ input_val +'</p></li>';
                        comment_box.prepend(content);
                        comment_box.find('.new-one').animate({
                            'opacity': 1
                        },
                            500, function() {
                            $(this).removeClass('new-one');
                        });
                        input_domain.val('');
                    }else{
                        // Fail to comment on this question
                    }
                },
                error: function(xhr, status, msg){

                },
                complete: function(xhr, status){
                    ajaxing = false;
                }
            });
        }
    });
    //===============     二级导航滚动事件    =================
    var 
        wheel_opt = {
            able_w_h: $('.wheel-panel')[0].offsetHeight - $('.sub-nav')[0].offsetHeight + 20,
            w_dis: 200,
            w_panel: $('.wheel-panel'),
            origin_top: parseInt($('.wheel-panel').css('top')),
            time: 200
        },
        sub_nav_wheel = new Wheel(wheel_opt);
    $('.sub-nav').find('.items').addWheelEvent({
        handler: function(down, e){
            down ? sub_nav_wheel.wheelDown() : sub_nav_wheel.wheelUp();
        }
    });

    // ============      switch slide mode     ================
    $('.mode1').on('click', 'i', function(event) {
        $('.show-mode1').css('display', 'block');
        $('.show-mode2').css('display', 'none');
    });
    $('.mode2').on('click', 'i', function(event) {
        $('.show-mode1').css('display', 'none');
        $('.show-mode2').css('display', 'block');
    });
    $('.thr-col-wrap').find('img').hover(function() {
        var _me = $(this);
        $(this).next().stop().animate({'bottom': 0}, 300);
    }, function() {
        var _me = $(this);
        $(this).next().stop().animate({'bottom': '-75px'}, 300);
    });

    // ===========    other before funcs     ===============
    $(function(){
        // 全屏浏览图片 ESC
        $(document).keyup(function(event) {
            // console.log(event.keyCode);
            if($('#big_photo').css('display') === 'block' && event.keyCode === 27){
                $('#big_photo').css('display', 'none');
            }
        });

        // 图片分享，赞，收藏
        var $icon_zan = $('.img-ctrl').find('.zan i');
        function checkZan(img, icon_zan){
            var follow = img.attr('data-support');
            if(follow === 'y'){
                icon_zan.attr('class', 'icon-zan-1 active');
            }else{
                icon_zan.attr('class', 'icon-zan-1');
            }
        }
        checkZan($('#thumb_box').find('.on img'), $icon_zan);
        function clickZan(img, icon_zan){
            var follow = img.attr('data-support'),
                id = img.attr('data-id');
            console.log(follow);
            if(follow !== 'y'){    // 该图片没被赞过
                $.ajax({
                    url: '/question/supportImg',
                    type: 'POST',
                    dataType: 'json',
                    data: {id: id},
                    success: function(data, status, xhr){
                        if(data.ret === 0){
                            img.attr('data-support', 'y');
                            icon_zan.attr('class', 'icon-zan-1 active');
                        }else{
                            alert('赞失败，要不要再点一下试试？');
                        }
                    }
                });
            }else{       // 该图片被赞过
                $.ajax({
                    url: '/question/unsupportImg',
                    type: 'POST',
                    dataType: 'json',
                    data: {id: id},
                    success: function(data, status, xhr){
                        if(data.ret === 0){
                            img.attr('data-support', 'n');
                            icon_zan.attr('class', 'icon-zan-1');
                        }else{
                            alert('取消赞失败，要不要再点一下试试？');
                        }
                    }
                });
            }
        }
        // “赞”按钮 click 事件
        $icon_zan.on('click', function(event){
            clickZan($('#thumb_box').find('.on img'), $icon_zan);
        });

    //===============    Slider Event   ==================
        var thumbs = $('ul.imgs-abs-wrap').children(),
            $p_current = $('.p-current'),
            p_total = parseInt($('.p-total').text());
        if(thumbs.length > 1){
            // Thumb animation
            var over_top,      // abs-wrap 超过 box top 的top值
                over_bottom,   // abs-wrap 超过 box bottom 的top值
                offset_top,    // on所在的元素超过 box top 的高度
                offset_bottom, // on所在的元素超过 box bottom 的高度
                abs_wrap,
                $abs_wrap,
                imgs_box,
                thumb_box;

            abs_wrap = $('ul.imgs-abs-wrap')[0];
            $abs_wrap = $(abs_wrap);
            imgs_box = $('.imgs-box')[0];
            // 轮播工具函数
            function thumbMovedown(callback){
                var _on = $('.on', abs_wrap)[0], _next;
                _on.nextElementSibling === undefined ? _next = _on.nextSibling : _next = _on.nextElementSibling;
                // console.log(_next);
                if(_next !== null){
                    var current = $('.on', abs_wrap).index(),
                        next_one = $(_next).index();
                    jumpTo(eles, 200, next_one);
                    $p_current.text(next_one + 1);

                    // 当图片超出显示区域，滚动
                    offset_bottom = _next.offsetTop + _next.offsetHeight - imgs_box.offsetHeight;
                    if(offset_bottom > 0){
                        $abs_wrap.animate({'top': -offset_bottom + 'px'}, 200);
                    }
                    $(_on).removeClass('on');
                    $(_next).addClass('on');

                    // 检查是否被赞
                    if(callback){
                        callback($(_next).find('img'), $icon_zan);
                    }
                }else{
                    return null;
                }
            }
            function thumbMoveup(callback){
                var _on = $('.on', abs_wrap)[0],
                    _prev = _on.previousElementSibling;

                if(_prev !== null){
                    var current = $('.on', abs_wrap).index(),
                        next_one = $(_prev).index();
                    jumpTo(eles, 200, next_one);
                    $p_current.text(next_one + 1);

                    // 当图片超出显示区域，滚动
                    over_top = parseInt($abs_wrap.css('top'));
                    offset_top = _prev.offsetTop -(-over_top);
                    if(offset_top < 0){
                        $abs_wrap.animate({'top': (over_top - offset_top) + 'px'}, 200);
                    }
                    $(_on).removeClass('on');
                    $(_prev).addClass('on');

                    // 检查是否被赞
                    if(callback){
                        callback($(_prev).find('img'), $icon_zan);
                    }
                }else{
                    return null;
                }
            }
            function thumbJumpTo($ele, callback){
                var $on = $('.on', abs_wrap);
                if($ele !== undefined && !$ele.hasClass('on')){
                    // 第 ？ 张
                    $p_current.text($ele.index());
                    // Main 轮播
                    jumpTo(eles, 200, $ele.index());
                    // 当图片超出显示区域，滚动
                    if($ele.index() > $on.index()){
                        offset_bottom = $ele[0].offsetTop + $ele[0].offsetHeight - imgs_box.offsetHeight;
                        if(offset_bottom > 0){
                            $abs_wrap.animate({'top': -offset_bottom + 'px'}, 200);
                        }
                    }else{
                        over_top = parseInt($abs_wrap.css('top'));
                        offset_top = $ele[0].offsetTop -(-over_top);
                        if(offset_top < 0){
                            $abs_wrap.animate({'top': (over_top - offset_top) + 'px'}, 200);
                        }
                    }
                    // 改变 on 类
                    $on.removeClass('on');
                    $ele.addClass('on');
                    // 检查是否被赞
                    if(callback){
                        callback($ele.find('img'), $icon_zan);
                    }
                }else{
                    return null;
                }
            }
            // 注册轮播事件
            $('#to_down').click(function(event) {
                if(movie_end){
                    thumbMovedown(checkZan);
                }
            });
            $('#to_top').click(function(event) {
                if(movie_end){
                    thumbMoveup(checkZan);
                }
            });
            $('.imgs-abs-wrap').on('click', 'img', function(event) {
                event.preventDefault();
                var to_ele = $(this).parent();
                if(movie_end){
                    thumbJumpTo(to_ele, checkZan);
                }
            });

            // Slider opacity animation
            var eles,
                current = 0,
                next_one = 1,
                max_index,
                movie_end = true;

            eles = $('#slider_box li');
            max_index = eles.length - 1;

            function jumpTo(eles, time, n){
                if(!time) time = 500;
                movie_end = false;
                // $(eles[current]).css({'z-index': 3, 'display': 'block'});
                $(eles[n]).css({'z-index': 2, 'display': 'block'});

                $(eles[current]).animate({
                        opacity: 0
                    },
                    time, function() {
                        $(this).css({
                            'z-index': 1,
                            opacity: 1,
                            'display': 'none'
                        });
                        $(this).removeClass('s-on');

                        current = n;
                        if (current < 0){ current = max_index; }
                        next_one = current + 1;
                        if(next_one > max_index){ next_one = 0; }
                        $(eles[current]).addClass('s-on');
                        movie_end = true;
                    }
                );
            }
        }

    //================   Compare fix event   ==============
        var c_f = $('.compare-fix'), timer;
        c_f.addAImg = function(src, callback){
            var _me = $('.compare-fix'),
                ul = _me.find('ul'),
                $c_title = c_f.find('.c-title');
            // 向服务器发送 id
            $.ajax({
                url: '/compare/addImg',
                type: 'POST',
                dataType: 'json',
                data: {
                    url: src
                },
                success: function(data, status, xhr){
                    if(data.ret === 0){
                        // 添加对比的新图片的动画
                        ul.prepend($('<li><i class="com-cancel icon-cancel-1"></i><img class="new-one" src="' + src +'" alt="#"></li>'));
                        _me.find('.new-one').animate({
                            opacity: 1
                        },
                            200, function() {
                            $(this).removeClass('new-one');
                            $('#c_num').text(_me.find('img').length);
                            // 图片超过 2 张，弹出添加标题框
                            _me.find('img').length > 1 ? $c_title.animate({'bottom': '65px'}, 180) : void(0);
                        });
                    }else{
                        alert('图片添加失败了 o.0');
                    }
                },
                error: function(xhr, status, msg){
                    console.log(msg);
                },
                complete: function(xhr, status){
                    if(callback){
                        callback();
                    }
                }
            });
        };
        c_f.contain = function(src){
            var imgs = this.find('img');
            for (var i = 0; i < imgs.length; i++) {
                if(src === imgs[i].src){
                    return true;
                }
            };
        };
        c_f.init = function(){
            if(c_f.find('img').length > 1){
                c_f.find('.c-title').css('bottom', '65px');
            }
        }
        c_f.init();
        c_f.on('mouseenter mouseleave', 'li', function(event) {
            event.preventDefault();
            if(event.type === 'mouseenter'){
                $(this).find('i.com-cancel').css('display', 'block');
            }else if(event.type === 'mouseleave'){
                $(this).find('i.com-cancel').css('display', 'none');
            }
        });
        c_f.on('click', '.com-cancel', function(event) {
            var src = $(this).next().attr('src'),
                _this = this,
                $c_title = c_f.find('.c-title');
            // console.log(id);
            $.ajax({
                url: '/compare/delImg',
                type: 'POST',
                dataType: 'json',
                data: {
                    url: src
                },
                success: function(data, status, xhr){
                    if(data.ret === 0){
                        $(_this).parent().animate({'width': 0, 'height': 0}, 200, function(){
                            $(this).remove();
                            $('#c_num').text(c_f.find('img').length);
                            // 图片少于 2 张，隐藏添加标题框
                            c_f.find('img').length < 2 ? c_f.find('.c-title').animate({'bottom': '35px'}, 180) : void(0);
                        });
                    }else{
                        alert('删除失败了 o.0');
                    }
                }
            });
        });
        $('.ctrl-btn').click(function(event) {
            if(parseInt(c_f.css('right')) === 0){
                c_f.css('right', '-220px');
            }else{
                c_f.css('right', '0px');
            }
        });
        // 提交对比事件
        c_f.find('.to-compare').click(function(event) {
            var $c_img = c_f.find('img'),
                $c_title = c_f.find('.c-title'),
                $c_err = c_f.find('.c-err-msg'),
                title = $c_title.find('input').val(),
                $loading = c_f.find('.c-loading');
            
            if($c_img.length < 2){
                timer == null ? void(0) : clearTimeout(timer);
                $c_err.stop().animate({
                    'bottom': '65px'
                    },
                    180, function() {
                    timer = setTimeout(function(){
                        $c_err.animate({'bottom': '35px'}, 180);
                    }, 2000);
                });
            }else{
                if(title === ''){
                    $c_title.find('input').stop().shake({times: 2, speed: 50, distance: 10});
                }else{
                    $.ajax({
                        url: '/compare/apply',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            title: title
                        },
                        beforeSend: function(){
                            $loading.css('display', 'block');
                        },
                        success: function(data, status, xhr){
                            // console.log(data.data.id);
                            if(data.ret === 0){
                                window.location.href = 'http://3w.chuanbang.com/compare/' + data.data.id + '?tag=time';
                            }
                        },
                        complete: function(xhr, status){
                            $loading.css('display', 'none');
                        }
                    });
                }
            }
        });

        //===========  “对比”按钮 click 事件（Needn't 飞图 anymore）   ============
        var fly_end = true;
        $('#c_num').text(c_f.find('img').length);          // 初始化添加对比的图片数量
        $('#big_photo').find('.compare').click(function(event) {
            var _me = $(this),
                fly_img = $('#big_photo').find('img')[0],     // 需要飞的图片
                fly_img_id = $(fly_img).attr('data-id');
                // console.log(fly_img_id);
            if(c_f.contain(fly_img.src)){
                alert('您已经添加这张图片了，换一张吧 ^-^');
            }
            // 检查对比图片数量及其他条件
            if(c_f.find('img').length < 9 && fly_end && !c_f.contain(fly_img.src)){
                // 检测“Compare-fix”是否弹出
                if(parseInt(c_f.css('right')) !== 0){
                    c_f.css('right', '0px');
                }
                c_f.addAImg(fly_img.src, function(){
                    _me.find('i').attr('class', 'icon-compare-2');
                });
            }
        });

        // big_photo 添加图片对比事件
        // $('#big_photo').find('.compare').on('click', 'i', function(event) {
        //     var fly_img = $('#big_photo').find('img')[0];     // 需要飞的图片
        //     if(c_f.find('img').length < 9 && !c_f.contain(fly_img.src)){
        //         c_f.addAImg(fly_img.src);
        //     }
        // });
        // big_photo 图片赞按钮事件
        $('#big_photo').find('.zan').on('click', 'i', function(event) {
            clickZan($('#big_photo .p-box').find('img'), $(this));
        });

        // Compare fix wheel event
        var c_f_wheel = {};
        c_f_wheel.has_w_h = 0;
        c_f_wheel.w_end = true;
        c_f_wheel.w_dis = 150;
        c_f_wheel.$panel = $('#answer div.compare-fix ul.c-f-panel');

        // Answer nav wheel event
        var a_nav = {};
        a_nav.has_w_h = 0;
        a_nav.w_end = true;
        a_nav.w_dis = 150;
        a_nav.$panel = $('.answer-nav > ul.a-nav-abs');

        var wheelDown = function(e){
            if(e.has_w_h < e.able_w_h && e.w_end){
                e.w_end = false;
                var diff = e.able_w_h - e.has_w_h;
                if(diff < e.w_dis){
                    e.has_w_h += diff;
                }else{
                    e.has_w_h += e.w_dis;
                }
                e.$panel.animate({
                    'top': -e.has_w_h + 'px'
                    },
                    200, function() {
                    e.w_end = true;
                });
            }
        };
        var wheelUp = function(e){
            if(e.has_w_h > 0 && e.w_end){
                e.w_end = false;
                if(e.has_w_h < e.w_dis){
                    e.has_w_h -= e.has_w_h;
                }else{
                    e.has_w_h -= e.w_dis;
                }
                e.$panel.animate({
                    'top': -e.has_w_h + 'px'
                    },
                    200, function() {
                    e.w_end = true;
                });
            }
        };
        var wheelBottom = function(e){
            e.able_w_h = e.$panel[0].offsetHeight - e.$panel.parent()[0].offsetHeight;
            if(e.able_w_h > 0){   // 自动滚动
                var auto_dis = e.able_w_h - e.has_w_h;
                if(auto_dis > 0){
                    e.has_w_h += auto_dis;
                    e.w_end = false;
                    e.$panel.animate({
                        'top': -e.has_w_h + 'px'
                    },
                        200, function() {
                        e.w_end = true;
                    });
                }
            }
        };
        var wheelTop = function(e){
            
        }
        // 开始对比容器的无滚动条滚动的事件监听
        $('.c-f-abs').addWheelEvent({
            handler: function(down, ev){
                c_f_wheel.able_w_h = c_f_wheel.$panel[0].offsetHeight - c_f_wheel.$panel.parent()[0].offsetHeight;
                if(down){
                    wheelDown(c_f_wheel);
                }else{
                    wheelUp(c_f_wheel);
                }
            }
        });
        // 回答二级导航的无滚动条滚动的事件监听
        $('nav.answer-nav').addWheelEvent({
            handler: function(down, ev){
                a_nav.able_w_h = a_nav.$panel[0].offsetHeight - a_nav.$panel.parent()[0].offsetHeight;
                if(down){
                    wheelDown(a_nav);
                }else{
                    wheelUp(a_nav);
                }
            }
        });

        // Big photo
        var $b_p_imgs = $('#thumb_box .imgs-box').find('img'),
            $b_p = $('#big_photo'),
            s_on_index,
            s_max_index = $b_p_imgs.length - 1;
        $('#slider_box').on('click', 'img', function(event) {  // 注册slide图片的每个点击事件事件委托
            event.preventDefault();
            $b_p.css('display', 'block');
            // Init prev & next btn's postion
            var view_h = $(window).height(),
                $on_img = $('#thumb_box .imgs-box .on').find('img'),
                $b_p_img = $b_p.find('img'),
                $icon_zan = $b_p.find('.zan i');
            $('.prev-btn', $b_p).css('top', view_h / 2 - 30 +'px');
            $('.next-btn', $b_p).css('top', view_h / 2 - 30 +'px');

            // Set img props
            $b_p_img.attr('src', $on_img.attr('data-url'));
            $b_p_img.attr('data-support', $on_img.attr('data-support'));
            $b_p_img.attr('data-compare', $on_img.attr('data-compare'));
            $b_p_img.attr('data-id', $on_img.attr('data-id'));
            // var p_box = $b_p.find('.p-box')[0],
            //     p_box_scale = ($b_p[0].offsetWidth - 200) / ($b_p[0].offsetHeight - 80);

            // Set img position
            $b_p_img.css({
                'max-height': $b_p[0].offsetHeight - 80 + 'px',
                'max-width': $b_p[0].offsetWidth - 200 + 'px'
            });
            // 检查图片是否被赞
            checkZan($b_p_img, $icon_zan);

            s_on_index = $('#thumb_box .imgs-box').find('.on').index();   // 大图浏览的图片的索引
        });          
        $b_p.find('.close-btn').click(function(event) {  // 大图关闭按钮事件
            $b_p.css('display', 'none');
        });

        // 大图左右浏览按钮事件
        $b_p.find('.prev-btn').click(function(event) {
            (--s_on_index < 0) ? s_on_index = s_max_index : void(0) ;
            $b_p.find('img').attr('src', $b_p_imgs[s_on_index].getAttribute('data-url'));
            $b_p.find('img').css({
                'max-height': $b_p[0].offsetHeight - 80 + 'px',
                'max-width': $b_p[0].offsetWidth - 200 + 'px'
            });
        });
        $b_p.find('.next-btn').click(function(event) {
            (++s_on_index > s_max_index) ? s_on_index = 0 : void(0) ;
            $b_p.find('img').attr('src', $b_p_imgs[s_on_index].getAttribute('data-url'));
            $b_p.find('img').css({
                'max-height': $b_p[0].offsetHeight - 80 + 'px',
                'max-width': $b_p[0].offsetWidth - 200 + 'px'
            });
        });

        // 大图自适改变宽高
        $(window).resize(function(){
            // Init prev & next btn's postion
            var view_h = $(window).height();
            var $big_photo = $('#big_photo');
            $('.prev-btn', $big_photo).css('top', view_h / 2 - 30 +'px');
            $('.next-btn', $big_photo).css('top', view_h / 2 - 30 +'px');
            $b_p.find('img').css({
                'max-height': $b_p[0].offsetHeight - 80 + 'px',
                'max-width': $b_p[0].offsetWidth - 200 + 'px'
            });
        });

        // poppup选择type事件
        var $type = $('#a_pop .type');
        var $t_item = $('#a_pop .type').find('.t-items');
        var type_moving = false;
        function hideAllType(){
            $('.f-box').find('.t-items').each(function(index, el) {
                var _this = $(this);
                if(_this.css('display') !== 'none'){
                    _this.css({
                        'display': 'none',
                        'top': 0,
                        'opacity': 0
                    });
                    _this.parent().css({
                        'border': '1px solid #cbcbcb',
                        'background-color': '#fff',
                        'color': '#000'
                    });
                }
            });
        }
        function hideCal(){
            $('#cal_date').find('.cal').css({
                'bottom': '33px',
                'opacity': 0,
                'display': 'none'
            });
            $('#cal_date').css({
                'border': '1px solid #cbcbcb',
                'background-color': '#fff',
                'color': 'rgba(0, 0, 0, .5)'
            });
        }
        $type.click(function(event) {
            hideCal();
            hideAllType();
            var $t_item = $(this).find('.t-items');
            var $type = $(this);
            $t_item.css('display', 'block');
            $(this).css({
                'border': 'none',
                'background-color': '#000',
                'color': '#fff'
            });
            $t_item.animate({'top': '-45px', 'opacity': '1'}, 200);
        });
        $t_item.find('li').click(function(event) {
            event.stopPropagation();
            var _text = this.innerText;
            var $t_item = $(this).parent();
            var $type = $t_item.parent();
            $t_item.animate({
                'top': 0,
                'opacity': 0},
                100, function() {
                $(this).css('display', 'none');
                $type.css({
                    'border': '1px solid #cbcbcb',
                    'background-color': '#fff',
                    'color': '#000'
                });
                $type.find('.t-on')[0].innerText = _text;
            });
        });

        // popup wheel事件   
        var pop_wheel = {};
        pop_wheel.has_w_h = 0;
        pop_wheel.w_end = true;
        pop_wheel.w_dis = 150;
        pop_wheel.$panel = $('.scroll-panel');
        pop_wheel.able_w_h = 0;

        function ctrlShadow(e){
            if(e.has_w_h > 0){
                $('#top_shadow').css('display', 'block');
            }else{
                $('#top_shadow').css('display', 'none');
            }
            if((e.able_w_h - e.has_w_h) > 0){
                $('#bottom_shadow').css('display', 'block');
            }else{
                $('#bottom_shadow').css('display', 'none');
            }
        }
        function detectScroll(){
            var 
                window_h = $(window).height(),
                fix_h = 120,
                free_h = window_h - fix_h,
                $scroll_wrap = $('.scroll-wrap'),
                $scroll_panel = $('.scroll-panel'),
                scroll_panel_h,

            scroll_panel_h = $scroll_panel[0].offsetHeight;
            if(scroll_panel_h > free_h){
                if(!detectScroll.has_scrolled){
                    $scroll_wrap.css({'height': free_h, 'overflow': 'hidden'});
                    $scroll_wrap.find('.scroll-panel').addClass('to-scroll');
                    ctrlShadow(pop_wheel);
                    detectScroll.has_scrolled = true;
                    console.log(detectScroll.has_scrolled);
                }
                // adjustPanelPos
                var wrap_bum = $scroll_wrap[0].getBoundingClientRect().bottom,
                    panel_bum = $('.scroll-panel')[0].getBoundingClientRect().bottom,
                    bum_diff = wrap_bum - panel_bum;
                if(bum_diff > 0){
                    $scroll_panel.animate({'top': '+=' + bum_diff +'px'}, 200);
                }
            }else{
                if(detectScroll.has_scrolled){
                    var h_diff = free_h - scroll_panel_h;
                    $scroll_wrap.find('.scroll-panel').animate({
                        'top': 0},
                        200, function() {
                        $scroll_wrap.css({'height': 'auto', 'overflow': 'visible'});
                        $(this).removeClass('to-scroll');
                    });
                    $('#top_shadow').css('display', 'none');
                    $('#bottom_shadow').css('display', 'none');
                    detectScroll.has_scrolled = false;
                }
            }
        }
        detectScroll.has_scrolled = false;
        $('.scroll-wrap').addWheelEvent({
            handler: function(down, ev){
                pop_wheel.able_w_h = pop_wheel.$panel[0].offsetHeight - pop_wheel.$panel.parent()[0].offsetHeight;
                // console.log(able_w_h);
                if(down){
                    wheelDown(pop_wheel);
                    ctrlShadow(pop_wheel);
                }else{
                    wheelUp(pop_wheel);
                    ctrlShadow(pop_wheel);
                }
            }
        });
        
        // popup答案添加事件
        var wearID,
            personID,
            $r_wear,
            $f_box,
            add_ing = false;
        wearID = 1;
        personID = 1;
        $f_box = $('#a_pop .f-box');
        // var p_model;

        $('.add-wear').click(function(event){
            if(!add_ing){
                add_ing = true;
                hideAllType();          // 隐藏所有弹出的元素
                var $new_wear = $(this).prev().clone(true);
                $new_wear.attr('id', 'w' + (++wearID));
                $new_wear.addClass('new-one');
                $new_wear.css('display', 'none');
                $(this).before($new_wear);
                $new_wear.show(300, function() {
                    add_ing = false;

                    detectScroll();    // 检测是否需要滚动
                    wheelBottom(pop_wheel);
                    ctrlShadow(pop_wheel);
                });
            }
        });
        $('#add_person').click(function(event){
            if(!add_ing){
                add_ing = true;
                hideAllType();     // 隐藏所有弹出的元素
                var $new_person = $('#a_pop').find('.f-box').children().first().clone(true);
                $new_person.find('#add_person').remove();
                $(this).appendTo($new_person.find('.l-person'));
                $new_person.attr('id', 'p' + (++personID));
                $new_person.addClass('new-one');
                var $w_ones = $new_person.find('.w-one');
                for (var i = 1; i <= ($w_ones.length - 1); i++) {
                    $w_ones[i].remove();
                };
                $new_person.css({
                    'display': 'none'
                });
                $new_person.appendTo($f_box);
                $new_person.show(300, function() {
                    add_ing = false;

                    detectScroll();    // 检测是否需要滚动
                    wheelBottom(pop_wheel);
                    ctrlShadow(pop_wheel);
                });
            }
        });
        
        // popup答案删除事件
        $('#a_pop i.w-cancel').click(function(event) {
            if(!add_ing){
                var $f_box_li = $f_box.children();
                var $r_wear_li = $(this).parent().parent().children('li');
                var $p_one = $(this).parent().parent().parent();
                var $now_li = $(this).parent();
                if($r_wear_li.length > 1){
                    $now_li.animate({
                        'height': 0,
                        'margin-bottom': 0
                    },
                        200, function() {
                        $(this).remove();
                        $f_box.find('.w-one').first().removeClass('new-one');

                        detectScroll();     // 检测是否需要滚动
                    });
                }else{
                    if($f_box_li.length > 1){
                        $p_one.animate({
                            'height': 0
                        },
                            200, function() {
                            if($(this).find('#add_person').length !== 0){
                                $(this).find('#add_person').appendTo($(this).prev().find('.l-person'));
                            }
                            $(this).remove();
                            $f_box.find('.p-one').first().removeClass('new-one');

                            detectScroll();     // 检测是否需要滚动
                        });
                    }
                } // endif;
            } // endif;
        });

        // popup日历插件
        var nk_cal = {};
        nk_cal.now = {    // 当前时间
            year: 0,
            month: 0,
            day: 0
        };
        nk_cal.year = 0;       // 选择的年份
        nk_cal.max_year = 2100;   // 最大年份
        nk_cal.min_year = 1970;   // 最小年份
        nk_cal.month = 0;      // 选择的月份
        nk_cal.day = 0;        // 选择的日
        nk_cal.days = 0;       // 选择的月份的天数
        nk_cal.daysAry = [];   // 日历选择月的天数数组
        nk_cal.daysHTML = '';  // 日历面板的HTML，li
        nk_cal.$panel = null;  // 日历天数选择面板
        nk_cal.$year = null;   // 年DOM
        nk_cal.$month = null;  // 月DOM
        nk_cal.draw_end = true;
        nk_cal.init = function(){
            var d = new Date();
            this.now.year = d.getFullYear();
            this.now.month = d.getMonth();
            this.now.day = d.getDate();
            this.year = this.now.year;
            this.month = this.now.month;
            this.day = this.now.day;
            this.$panel = $('#cal_date').find('.cal-panel');
            this.$year = $('#cal_date').find('.year');
            this.$month = $('#cal_date').find('.month');
            
            this.draw();
        };
        nk_cal.countDays = function(y, m){  // 计算当前月份的天数
            var leap_days = [31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
            var nonleap_days = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
            if(this.isLeap(y)){
                return leap_days[m];
            }else{
                return nonleap_days[m];
            }
        };
        nk_cal.isLeap = function(y){         // 判断是不是闰年
            if(y % 100 === 0){
                if(y % 400 === 0){
                    return true;
                }else{
                    return false;
                }
            }else{
                if(y % 4 === 0){
                    return true;
                }else{
                    return false;
                }
            }
        };
        nk_cal.draw = function(){
            this.draw_end = false;
            this.days = this.countDays(this.year, this.month);
            this.daysAry = [];                    // 清空天数数组，保证长度等于天数
            this.daysHTML = '';
            for(var i = 1; i <= this.days; i++){
                this.daysAry[i] = i;
            }
            var days_l = this.daysAry.length;

            for(var i = 1; i < days_l; i++){
                if(this.day === i){
                    this.daysHTML += '<li class="now">' + i + '</li>';
                }else{
                    this.daysHTML += '<li>' + i + '</li>';
                }
            }
            this.$year.text(this.year + '年');
            this.$month.text((this.month + 1) + '月');
            this.$panel.html('');   // 清空panel内容
            this.$panel.html(this.daysHTML);
            this.$panel.find('li').click(function(event) {  // 为每个li添加选中日期事件

                var $cal_date = $('#cal_date');
                $cal_date.find('.time').text(nk_cal.year + ' - ' + (nk_cal.month + 1) + ' - ' + this.innerText);
                $cal_date.find('.cal').animate({
                    'bottom': '33px',
                    'opacity': 0},
                    200, function() {
                    $(this).css('display', 'none');
                    $cal_date.css({
                        'border': '1px solid #cbcbcb',
                        'background-color': '#fff',
                        'color': 'rgba(0, 0, 0, .5)'
                    });
                });
            });
            this.draw_end = true;
        }
        nk_cal.init();
        $('#cal_date').click(function(event) {
            hideAllType();     // 隐藏所有弹出的元素
            var $cal = $(this).find('.cal');
            if($cal.css('display') === 'none'){
                $cal.css('display', 'block').animate({
                    'bottom': '43px',
                    'opacity': 1
                }, 200);
                $(this).css({
                    'border': 'none',
                    'background-color': '#000',
                    'color': 'rgba(255, 255, 255, .5)'
                });
            }else{

            }
        });
        $('#cal_date').find('.y-prev').click(function(event) {
            nk_cal.draw_end ? function(){
                if(nk_cal.year - 1 >= nk_cal.min_year){
                    nk_cal.year--;
                    nk_cal.draw();
                }
            }() : void(0) ;
        });
        $('#cal_date').find('.y-next').click(function(event) {
            nk_cal.draw_end ? function(){
                if(nk_cal.year + 1 <= nk_cal.max_year){
                    nk_cal.year++;
                    nk_cal.draw();
                }
            }() : void(0) ;
        });
        $('#cal_date').find('.m-prev').click(function(event) {
            nk_cal.draw_end ? function(){
                if(nk_cal.month - 1 >= 0){
                    nk_cal.month--;
                    nk_cal.draw();
                }
            }() : void(0) ;
        });
        $('#cal_date').find('.m-next').click(function(event) {
            nk_cal.draw_end ? function(){
                if(nk_cal.month + 1 <= 11){
                    nk_cal.month++;
                    nk_cal.draw();
                }
            }() : void(0) ;
        });

        // 添加对比图片event
        var pop_added_imgs = [],
            added_id = 0,
            $added_imgs = $('#a_pop').find('.added-imgs');
        pop_added_imgs.contain = function(file){
            for (var i = 0; i < this.length; i++) {
                if(this[i].file.name === file.name){
                    return true;
                }
            };
            return false;
        }
        pop_added_imgs.del = function(id){
            for (var i = 0; i < this.length; i++) {
                if(this[i].id === id){
                    this.splice(i, 1);
                }
            };
        }
        $('#add_imgs_btn').on('change', function(event) {
            var _files = event.target.files;
            for(var i = 0; i < _files.length; i++){
                if(pop_added_imgs.length < 9){
                    if(!pop_added_imgs.contain(_files[i])){
                        pop_added_imgs.push({
                            'id': added_id,
                            'file': _files[i],
                            'name': _files[i].name
                        });
                        // console.log(pop_added_imgs);
                        var img = '<img src="' + window.URL.createObjectURL(_files[i]) + '">',
                            li = '<li id="' + added_id + '"><i class="cancel-added-img"></i>' + img + '</li>';
                        added_id++;
                        $(this).parent().before(li);
                        
                        // detectScroll();    // 检测是否需要滚动
                    }else{
                        alert('这张图片已经添加了哦。。。');
                    }
                }else{
                    alert('超过9张图片了。。。');
                    return false;
                }
            }
        });
        $('#a_pop').find('.added-imgs').on('click', '.cancel-added-img', function(event) {
            event.preventDefault();
            var _li = $(event.target).parent();
            var id = parseInt(_li.attr('id'));
            // console.log(id);
            pop_added_imgs.del(id);

            _li.hide(200, function() {
                $(this).remove();
                // detectScroll();    // 检测是否需要滚动
            });;

        });
        $('#a_pop').find('.added-imgs').on('mouseenter mouseleave', 'li', function(event) {
            event.preventDefault();
            event.type === 'mouseenter' ?
            $(this).find('.cancel-added-img').css('display', 'block') :
            $(this).find('.cancel-added-img').css('display', 'none');
        });

        // 隐藏或显示回答面板
        $('#answer').find('.submit-answer').click(function(event) {
            $('#a_pop').css('display', 'block').find('.pop-box').animate({'bottom': '0'}, 300);
        });
        $('#a_pop').click(function(event) {
            if(this === event.target){
                $(this).css('display', 'none').find('.pop-box').css('bottom', '-100%');
            }
        });

        // popup elements one by one
        function showObo($array, interval){
            for (var i = 0; i < $array.length; i++) {
                $array[i].delay(interval * i).show(300);
            };
        }
        // hide elements one by one
        function hideObo($array, interval){
            for (var i = 0; i < $array.length; i++) {
                $array[i].delay(interval * i).hide(300);
            };
        }
        // ====================   popup伸缩事件    ====================
        var $array = [];
        var $pop_box = $('#a_pop').find('.pop-box');
        $array.push($pop_box.find('#add_person'));
        $array.push($pop_box.find('.f-event'));
        $array.push($pop_box.find('#a_comment'));
        $array.push($pop_box.find('.add-c-img'));
        $array.state = 'hide';
        var $scroll_wrap = $('.scroll-wrap');
        $('#a_pop .top').find('.icon-pop').click(function(event) {
            this.origin_wrap_h;
            if($array.state === 'hide'){     // 显示
                if(detectScroll.has_scrolled === true){
                    $scroll_wrap
                        .css({'overflow': 'hidden'})
                        .animate({'height': this.origin_wrap_h}, 400);
                    $scroll_wrap.find('.scroll-panel')
                        .addClass('to-scroll')
                        .animate({'top': -pop_wheel.able_w_h + 'px'}, 200);
                    ctrlShadow(pop_wheel);
                };

                var new_ones = $pop_box.find('.new-one');
                // $array.push($pop_box.find('.add-wear'));
                showObo($array, 150);
                $pop_box.find('.add-wear').show(300);
                $pop_box.find('.w-cancel').show();
                new_ones.show(300);
                $array.state = 'show';
            }else{                            // 隐藏
                console.log('detectScroll.has_scrolled:'+detectScroll.has_scrolled);
                if(detectScroll.has_scrolled === true){
                    // 保存wrap的原始高度
                    this.origin_wrap_h = $scroll_wrap.css('height');
                    $scroll_wrap.css({'height': 'auto', 'overflow': 'visible'});
                    $scroll_wrap.find('.scroll-panel').removeClass('to-scroll');
                    $('#top_shadow').css('display', 'none');
                    $('#bottom_shadow').css('display', 'none');
                }

                var new_ones = $pop_box.find('.new-one');
                // $array.push($pop_box.find('.add-wear'));
                new_ones.hide(300);
                $pop_box.find('.add-wear').hide(300);
                $pop_box.find('.w-cancel').hide();
                hideObo($array, 150);
                $array.state = 'hide';
            }
        });

        // 提交答案 Event
        $('#sub_btn').click(function(event) {
            var fd = new FormData(),       // IE10+
                only_id = 0,
                $a_pop = $('#a_pop'),
                $persons = $a_pop.find('.f-box').children(),
                $wears,
                question_id,
                limit_judge;      // 当必填项都有内容时，值为 true
            question_id = $('#answer').attr('data-ques-id');
            // Form 问题 id
            fd.append('AnswerForm[ques_id]', question_id);
            // Form 人物穿着
            $persons.each(function(index, el) {
                var star_name,
                    brand_name,
                    style,
                    clothes_type,
                    $wears = $(this).find('.r-wear').children('li');

                // 获取 名字 并检查错误
                $(this).find('.l-person input').val() === ''
                    ? $(this).find('.l-person input').addClass('waring').shake({distance: 10, speed: 50, times: 2})
                    : star_name = $(this).find('.l-person input').val();
                // 循环“穿着”
                $wears.each(function(index, el) {
                    // 获取 品牌 并检查错误
                    $(this).find('.clothes').val() === ''
                        ? $(this).find('.clothes').addClass('waring').shake({distance: 10, speed: 50, times: 2})
                        : brand_name = $(this).find('.clothes').val();
                    // 获取 风格 并检查错误
                    $(this).find('.when').val() === ''
                        ? $(this).find('.when').addClass('waring').shake({distance: 10, speed: 50, times: 2})
                        : style = $(this).find('.when').val();
                    // 获取 类别 并检查错误
                    clothes_type = $(this).find('.t-on').text();

                    console.log(star_name+brand_name+style+clothes_type);
                    // 添加到FormData
                    fd.append('AnswerDetail['+ only_id +'][star_name]', star_name);
                    fd.append('AnswerDetail['+ only_id +'][brand_name]', brand_name);
                    fd.append('AnswerDetail['+ only_id +'][style]', style);
                    fd.append('AnswerDetail['+ only_id +'][clothes_type]', clothes_type);

                    only_id++;
                });
            });
            // Form 事件、评论
            var title = $('#a_pop').find('.f-event').find('.matter').val(),
                place = $('#a_pop').find('.f-event').find('.place').val(),
                occurdate = $('#cal_date').find('.time').text(),
                comment = $('#a_comment').val();
            fd.append('Topic[title]', title);
            fd.append('Topic[place]', place);
            fd.append('Topic[occurdate]', occurdate);
            fd.append('Answer[content]', comment);
            // Form 上传的图片 pop_added_imgs
            for (var i = 0; i < pop_added_imgs.length; i++) {
                console.log(pop_added_imgs[i]);
                fd.append('file[]', pop_added_imgs[i].file, pop_added_imgs[i].name);
            };

            // 发送 AJAX
            $.ajax({
                url: '/answer/submit',
                type: 'POST',
                dataType: 'json',
                data: fd,
                contentType: false,
                processData: false,    // 默认会把数据转换成查询字符串，包含 file的FormData 要关闭
                beforeSend: function(xhr, setting){
                    $('#a_pop_loading').show();
                },
                success: function(data, status, xhr){
                    console.log(data);
                    if(data.ret === 0){
                        window.location.reload();
                    }
                },
                error: function(xhr, status, msg){
                    console.log(msg);
                },
                complete: function(xhr, status){
                    $('#a_pop_loading').hide();
                }
            });
        });
    });
}
</script>