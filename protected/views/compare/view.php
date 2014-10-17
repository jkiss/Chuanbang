<!-- The sub nav -->
<div class="sub-nav-wrap">
    <div class="sub-nav">
        <nav class="items compare-sub">
            <h1>趋势</h1>
            <ul class="wheel-panel">
                <?php foreach($trend as $item):?>
                    <li class="transition"><a href="<?php echo Yii::app()->createUrl('compare/view',array('id'=>$item['id']));?>">
                    <div class="image c-scale">
                        <div class="slide-wrap">
                            <?php for($i = 0,$len = min(2,count($item['imgs'])); $i < $len; $i++):?>
                            <img src="<?php echo $item['imgs'][$i];?>?w=88&h=130" alt="">
                            <?php endfor;?>
                        </div>
                        <span class="vs transition">VS</span>
                    </div>
                    <p class="title"><?php echo $item['title'];?></p>
                    <p class="comment"><?php echo $item['total_comments'];?>评论</p>
                </a></li>
                <?php endforeach;?>
            </ul>
        </nav>
    </div>
</div>
<!-- The compare content (slider) -->
<section class="content" id="compare_detail">
    <div class="cd-one">
        <h1 class="title" id="compare_id" data-id="<?php echo $model['id'];?>">
            <span class="vs">VS</span>
            <?php echo $model['title'];?>
        </h1>
        <div class="profile">
            <div class="img-wrap">
                <img src="<?php echo $model['author']['head'];?>?w=40&h=40" alt="avatar">
            </div>
            <span class="name"><?php $model['author']['nick'];?></span>
            <span class="date"><?php echo date('Y-m-d H:i:s', $model['createtime']);?>创建</span>
        </div>
        <!-- two status transform -->
        <div class="slide-wrap show-mode1">
            <ul class="slide-panel translate3D">
                <?php $i=0; ?>
                <?php foreach($model['imgs'] as $item):?>
                <li <?php if(++$i == 1): echo 'class="active"';?><?php endif;?>>
                    <img src="<?php echo $item['url'];?>?h=380" alt="">
                    <?php if(!empty($item['celebrity'])):?>
                    <p class="star"><?php echo $item['celebrity'];?></p>
                    <p class="clothes">
                        <i class="icon-in"></i>
                        <?php echo $item['brand'].' '.$item['style'];?>
                    </p>
                    <?php endif;?>
                </li>
                <?php endforeach;?>
            </ul>
            <div class="arrow left transition">
                <i class="icon-dir-left"></i>
            </div>
            <div class="arrow right transition">
                <i class="icon-dir-right"></i>
            </div>
        </div>
        <div class="thr-col-wrap grid three clearfix show-mode2">
            <div class="column one">
                <?php $i=0; ?>
                <?php foreach($model['imgs'] as $item):?>
                    <a href="javascript:;" class="img-wrap">
                        <img src="<?php echo $item['url'];?>?h=380" alt="">
                        <?php if(!empty($item['celebrity'])):?>
                        <div class="title">
                            <em><?php echo $item['celebrity'];?>穿<?php echo $item['brand'].' '.$item['style'];?></em>
                        </div>
                        <?php endif;?>
                    </a>
                <?php endforeach;?>
            </div>
        </div>
        <!--  -->
        <div class="slide-ctrl">
            <?php if($model['follow'] == 'N'):?>
            <span class="collect no">
                <i class="icon-collect-1"></i>
            <?php else:?>
            <span class="collect yes">
                <i class="icon-collect-2"></i>
            <?php endif;?>
                收藏
                <span class="num"><?php echo $model['total_follows'];?></span>
            </span>

            <?php if($model['support'] == 'N'):?>
            <span class="zan no">
                <i class="icon-zan-1"></i>
            <?php else:?>
            <span class="zan yes">
                <i class="icon-zan-2"></i>
            <?php endif;?>
                赞
                <span class="num"><?php echo $model['total_ups'];?></span>
            </span>
            <span class="share">
                <script>
                (function(){
                    var p = {
                        url: 'http://3w.chuanbang.com',    // 要分享的网址
                        appkey: '',
                        title: '穿帮，你的选择',     // 分享的标题       
                        pic: '',   // 分享的缩略图
                        ralateUid: '3197845034',    // 分享后需要 @ 的 ID
                        searchPic: true,            // 是否开启抓图
                        language: 'zh_cn'
                    }
                    var s = [];
                    for(var i in p){
                        s.push(i + '=' + encodeURIComponent(p[i]||''));
                    }
                    document.write(['<a href="http://service.weibo.com/share/share.php?', s.join('&'), '" target="_blank"><i class="icon-share-1"></i></a>'].join(''));
                }());
                </script>
                分享
            </span>
            
            <span class="total-imgs">
                共<?php echo $model['total_imgs'];?>图
            </span>
            <span class="mode2">
                列表浏览
                <i class="icon-slide-2"></i>
            </span>
            <span class="mode1">
                翻页浏览
                <i class="icon-slide-1"></i>
            </span>
        </div>
        <ul class="comment-box clearfix">
            <?php if(!empty($model['comments'])):?>
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
            <div class="more">
                <span class="more-btn">更多评论（全部<?php echo $model['total_comments'];?>条）</span>
            </div>
            <?php endif;?>
            <?php if(isset($user)):?>
            <div class="input">
                <div class="ava-wrap">
                        <img src="<?php echo $user['head'];?>" alt="">
                </div>
                <textarea name="content" id="comment-text"></textarea>
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
</section>
<script>
    function invokeHere(){
        $(window).on('resize', function(event) {
            $('.compare-sub').find('.image').find('img').each(function(index, el) {
                var init_w = $('.compare-sub').find('.image').width() / 2;
                $(this).css('width', init_w + 'px');
            });
        }).trigger('resize');
        // set slide's clothes width equal img
        $('.slide-panel').find('img').load(function() {
            var _me = $(this),
                _w = _me.width();

            _me.siblings('.clothes').css('width', _w);
        });

        // favorite & zan event
        $('.slide-ctrl').find('.collect').click(function(event) {
            var _me = $(this);
                num = parseInt(_me.find('.num').text());
                id = $('#compare_id').attr('data-id'),
                ajaxing = false;

            if(_me.hasClass('yes') && !ajaxing){
                $.ajax({
                    url: '/compare/unfollow',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: id
                    },
                    success: function(data, status, xhr){
                        if(data.msg === 'success'){
                            _me.toggleClass('yes no');
                            _me.find('i').attr('class', 'icon-collect-1');
                            _me.find('.num').text(--num);
                        }else{
                            // Fail to unfollow
                        }
                    },
                    error: function(xhr, status, msg){

                    },
                    complete: function(xhr, status){
                        ajaxing = true;
                    }
                });
            }else if(_me.hasClass('no') && !ajaxing){
                $.ajax({
                    url: '/compare/follow',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: id
                    },
                    success: function(data, status, xhr){
                        if(data.msg === 'success'){
                            _me.toggleClass('yes no');
                            _me.find('i').attr('class', 'icon-collect-2');
                            _me.find('.num').text(++num);
                        }else{
                            // Fail to follow
                        }
                    },
                    error: function(xhr, status, msg){

                    },
                    complete: function(xhr, status){
                        ajaxing = true;
                    }
                });
            }
        });
        $('.slide-ctrl').find('.zan').click(function(event) {
            var _me = $(this);
                num = parseInt(_me.find('.num').text());
                id = $('#compare_id').attr('data-id'),
                ajaxing = false;

            if(_me.hasClass('yes') && !ajaxing){
                $.ajax({
                    url: '/compare/unsupport',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: id
                    },
                    success: function(data, status, xhr){
                        if(data.msg === 'success'){
                            _me.toggleClass('yes no');
                            _me.find('i').attr('class', 'icon-zan-1');
                            _me.find('.num').text(--num);
                        }else{
                            // Fail to unfollow
                        }
                    },
                    error: function(xhr, status, msg){

                    },
                    complete: function(xhr, status){
                        ajaxing = true;
                    }
                });
            }else if(_me.hasClass('no') && !ajaxing){
                $.ajax({
                    url: '/compare/support',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: id
                    },
                    success: function(data, status, xhr){
                        if(data.msg === 'success'){
                            _me.toggleClass('yes no');
                            _me.find('i').attr('class', 'icon-zan-2');
                            _me.find('.num').text(++num);
                        }else{
                            // Fail to follow
                        }
                    },
                    error: function(xhr, status, msg){

                    },
                    complete: function(xhr, status){
                        ajaxing = true;
                    }
                });
            }
        });

        // speed slider
        var com_detail_opt = {
            'wrap': $('#compare_detail').find('.slide-wrap'),
            'panel': $('#compare_detail').find('.slide-panel'),
            'time': 300
        }
        function SpeedSlider(opt){
            this.wrap = opt.wrap;
            this.panel = opt.panel;
            this.wrap_w = opt.wrap.width();
            this.panel_real_w = (function(){
                var num = 0;
                opt.panel.children('li').each(function(index, el) {
                    num += $(this).width() + 10;
                });
                return num - 10;
            }());
            this.enable_slide_w = this.panel_real_w - this.wrap_w;
            this.already_slide_w = 0;
            this.ing = false;
            this.time = opt.time;
        }
        SpeedSlider.prototype.start = function(){
            this.arrowShowHide();
        };
        SpeedSlider.prototype.arrowShowHide = function(){
            if(this.panel_real_w < this.wrap.width()){
                this.wrap.find('.arrow').css('display', 'none');
            }else{
                this.wrap.find('.arrow').css('display', 'block');
            }
        };
        SpeedSlider.prototype.resetEnableSlideWidth = function(){
            this.enable_slide_w = this.panel_real_w - this.wrap.width();
        };
        SpeedSlider.prototype.getActive = function(){
            return this.panel.find('.active');
        }
        SpeedSlider.prototype.slideLeft = function(){
            var _this = this;
            if(this.enable_slide_w > 0){
                this.ing = true;
                var slide_w = this.getActive().width() + 10;
                if(slide_w > this.enable_slide_w){
                    slide_w = this.enable_slide_w;
                    this.getActive().removeClass('active');
                    this.panel.find('li').last().addClass('active');
                }else{
                    this.getActive().next().length !== 0
                    ? this.getActive().removeClass('active').next().addClass('active')
                    : void(0);
                }
                this.panel.animate({
                    'left': '-=' + slide_w
                },
                    _this.time, function() {
                    _this.enable_slide_w -= slide_w;
                    _this.already_slide_w += slide_w;
                    console.log('enable_slide_w '+_this.enable_slide_w);
                    console.log('already_slide_w '+_this.already_slide_w);
                    _this.ing = false;
                });
            }
        };
        SpeedSlider.prototype.slideRight = function(){
            var _this = this;
            if(this.already_slide_w > 0){
                this.ing = true;
                var slide_w = this.getActive().width() + 10;
                if(slide_w > this.already_slide_w){
                    slide_w = this.already_slide_w;
                    this.getActive().removeClass('active');
                    this.panel.find('li').first().addClass('active');
                }else{
                    this.getActive().prev().length !== 0
                    ? this.getActive().removeClass('active').prev().addClass('active')
                    : void(0);
                }
                this.panel.animate({
                    'left': '+=' + slide_w
                },
                    _this.time, function() {
                    _this.enable_slide_w += slide_w;
                    _this.already_slide_w -= slide_w;
                    console.log('enable_slide_w '+_this.enable_slide_w);
                    console.log('already_slide_w '+_this.already_slide_w);
                    _this.ing = false;
                });
            }
        };

        var speed_slider;
        $(window).on('load', function(event) {
            speed_slider = new SpeedSlider(com_detail_opt);
            speed_slider.start();
            console.log(speed_slider.enable_slide_w);
        });
        $(window).on('resize', function(event) {
            speed_slider.resetEnableSlideWidth();
            speed_slider.arrowShowHide();
            console.log(speed_slider.enable_slide_w);
        });
        $('.slide-wrap').find('.right').on('click', function(event) {
            if(!speed_slider.ing){
                speed_slider.slideLeft();
            }
        });
        $('.slide-wrap').find('.left').on('click', function(event) {
            if(!speed_slider.ing){
                speed_slider.slideRight();
            }
        });      // END speed slider

        // switch slide mode
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
    }

    $(function(){
        // 图片切换按钮的动画
        function resetThumbBtn(ele_h, time){
            if(!time) time = 500;
            $('#compare_sub').find('.btn-ctrl').css({'height': ele_h + 'px'});
        }
        // Thumbnail  animate
        var $pp = $('.photo-panel'),      // thumbnail amount
            $pp_items = $pp.children(),
            max_index,
            now_index,
            imgs = $pp.find('img'),
            movie_end = true;
        // 重置控制按钮
        $pp.find('.on').find('img').load(function() {
            console.log($pp.find('.on').innerHeight());
            $('#compare_sub').find('.btn-ctrl').css('height', $pp.find('.on').innerHeight() + 'px');
        });
        // imgs.eq(now_index).css('opacity', 1);
        function slideToTop($ele, time){
            if(!time) time = 500;
            var _on = $pp.find('.on'), _next = _on.next();
            now_index = _on.index();

            if(_next.length !== 0){
                movie_end = false;
                _on.css('opacity', '.5');
                _next.animate({'opacity': 1}, time - 100);
                $ele.animate({
                        'top': '-=' + _on.innerHeight()
                    },
                    time + 50, function() {
                        _on.removeClass('on');
                        _next.addClass('on');
                        movie_end = true;
                });
                // 控制按钮的动画
                resetThumbBtn(_next.innerHeight());
                // 轮播主图
                jumpTo($slide_items, 500, now_index + 1);
            }
        }
        function slideToDown($ele, time){
            if(!time) time = 500;
            var _on = $pp.find('.on'), _next = _on.prev();
            now_index = _on.index();

            if(_next.length !== 0){
                movie_end = false;
                _on.css('opacity', '.5');
                _next.animate({'opacity': 1}, time - 100);
                $ele.animate({
                        'top': '+=' + _next.innerHeight()
                    },
                    time + 50, function() {
                        _on.removeClass('on');
                        _next.addClass('on');
                        movie_end = true;
                });
                // 控制按钮的动画
                resetThumbBtn(_next.innerHeight());
                // 轮播主图
                jumpTo($slide_items, 500, now_index - 1);
            }
        }
        // 通用 Fade 图片轮播
        var $slide_items = $('#co_slider_box').children(),
            current = 0,
            next_one = current + 1,
            max_index = $slide_items.length - 1;
        function jumpTo(eles, time, to_next){
            if(!time) time = 500;
            movie_end = false;
            // $(eles[current]).css({'z-index': 3, 'display': 'block'});
            $(eles[to_next]).css({'z-index': 2, 'display': 'block'});
            $(eles[current]).animate({
                    opacity: 0
                },
                time, function() {
                    $(this)
                        .removeClass('s-on')
                        .css({
                            'z-index': 1,
                            opacity: 1,
                            'display': 'none'
                        });
                    // $(this).removeClass('s-on');

                    current = to_next;
                    if (current < 0){ current = max_index; }
                    next_one = current + 1;
                    if(next_one > max_index){ next_one = 0; }
                    $(eles[current]).addClass('s-on');
                    movie_end = true;
                }
            );
        }
        // 轮播按钮控制
        $('.top').click(function(event) {
            if(movie_end){
                slideToDown($pp, 500);
            }
        });
        $('.down').click(function(event) {
            if(movie_end){
                slideToTop($pp, 500);
            }
        });
    });
</script>