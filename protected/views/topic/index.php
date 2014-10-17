<!-- The sub nav -->
<div class="sub-nav-wrap">
    <div class="sub-nav">
        <nav class="items topic-sub">
            <h1>趋势</h1>
            <ul class="wheel-panel">
            <?php foreach($trend as $tp):?>
                <li class="transition"><a href="<?php echo Yii::app()->createUrl('topic/view',array('id'=>$tp['id']));?>">
                        <div class="img-wrap">
                            <img src="<?php echo $tp['cover'];?>?w=155&h=115" alt="#">
                        </div>
                        <h6 class="text-overflow"><?php echo $tp['title'];?></h6>
                    </a></li>
            <?php endforeach;?>
            </ul>
        </nav>
    </div>
</div>
<!-- The Topic Content -->
<section class="content" id="topic">
    <div class="thr-nav">
        <i class="icon-topic"></i>
        话题
    </div>
    <div class="c-top">
        <div class="grid three sep clearfix">
            <?php foreach($hots as $model):?>
            <i class="column float">
                <div class="one-wrap">
                    <a href="<?php echo Yii::app()->createUrl('topic/view',array('id'=>$model['id']));?>">
                        <div class="cover image scale-4">
                            <img src="<?php echo $model['cover'];?>?w=486&h=360" alt="<?php echo $model['title'];?>">
                        </div>
                    </a>
                    <div class="grid six sep-2 clearfix">
                        <?php foreach($model['celebrities'] as $cele):?>
                        <div class="column float">
                            <a href="<?php echo Yii::app()->createUrl('celebrity/view',array('id'=>$cele['id']));?>">
                                <div class="image scale-2">
                                    <img src="<?php echo $cele['head'];?>?w=77&h=77" alt="">
                                </div>
                            </a>
                        </div>
                        <?php endforeach;?>
                    </div>
                    <p class="star-num">共<?php echo $model['total_cele'];?>位名人穿搭</p>
                    <p class="title">
                        <em><?php echo $model['title'];?></em>
                    </p>
                </div>
            </i>
            <?php endforeach;?>
        </div>
    </div>

    <div class="c-bottom">
        <h2>
            更多热门话题
        </h2>
        <div class="grid four sep clearfix">
            <?php foreach($more as $tp):?>
            <a href="<?php echo Yii::app()->createUrl('topic/view',array('id'=>$tp['id']));?>" class="column float transition">
                <div class="image scale-5">
                    <img src="<?php echo $tp['cover'];?>?w=377&h=276" alt="">
                </div>
                <div class="bottom-wrap">
                    <div class="title-1">
                        <?php echo $tp['title'];?>
                    </div>
                    <div class="title-2">
                        <?php echo $tp['total_fans'];?>人关注
                    </div>
                </div>
            </a>
            <?php endforeach;?>
        </div>
    </div>
</section>
<script>
    function invokeHere(Pagination, publishDate, Wheel){
        $('a[class*="column"]').hover(function() {
            var _me = $(this);
            _me.css('background-color', '#282828').find('img').css('top', '-10px');
            _me.find('.title-1').css('top', '60px');
            _me.find('.title-2').css('top', '0');
        }, function() {
            var _me = $(this);
            _me.css('background-color', '#fff').find('img').css('top', '-0');
            _me.find('.title-1').css('top', '0');
            _me.find('.title-2').css('top', '60px');
        });

        // 二级导航滚动事件
        var 
            wheel_opt = {
                able_w_h: $('.wheel-panel')[0].offsetHeight - $('.sub-nav')[0].offsetHeight + 75,
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
    }
    // slider
    $(function(){
        var box_items,
            items_num,
            max_index,
            prev_one,       // 上一张图片数组下标
            current,        // 当前图片数组下标
            next_one,       // 下一张图片数组下标
            hide_four,      // 第四张图片数组下标
            movie_end = true,
            dot_ctrl = false;

        box_items = $('#topic_slider_box').children();
        // 初始化 ctrl_dot 数量
        function resetDotNum(n){
            var $dot_wrap = $('#topic').find('.dot-control'),
                dotHTML = '';
            try{
                if(n < 4){
                    throw "图片轮播数量好像少于 4 张哦！";
                }else{
                    for (var i = 0; i < n; i++) {
                        i === 1
                            ? dotHTML += '<a href="#" class="dot-active"></a>'
                            : dotHTML += '<a href="#"></a>';
                    };
                    $dot_wrap.html(dotHTML);
                }
            }catch(err){
                console.log(err);
            }
        }
        resetDotNum(box_items.length);
        
        // if no element to stop
        try{
            if(box_items.length < 1){
                console.log('there is no element or just one ...');
                return ;
            }else if(box_items.length === 1){

            }else if(box_items.length === 2){

            }else if(box_items.length === 3){   // 当图片轮播数量小于 4 出现 BUG
                var first = $('#topic_slider_box').children().first().clone();
                $('#topic_slider_box').append(first);
                box_items = $('#topic_slider_box').children();
                console.log(box_items);
            }else {

            }
        }catch(err){
            console.log(err);
        }
        
        // Init
        items_num = box_items.length;
        max_index = items_num - 1;
        current = 1;
        current - 1 < 0 ? prev_one = max_index : prev_one = current - 1;
        current + 1 > max_index ? next_one = 0 : next_one = current + 1;

        // 向左滑动
        function slideToLeft(time, callback){
            var time = time || 500;
            movie_end = false;

            // Init hide_four left value
            next_one + 1 > max_index ? hide_four = 0 : hide_four = next_one + 1;
            $(box_items[hide_four]).css('left', '125%');

            // Start Animate
            $(box_items[current]).animate({ 'left': '-25%' }, time);
            $(box_items[next_one]).animate({ 'left': '25%' }, time);
            $(box_items[hide_four]).animate({ 'left': '75%' }, time);
            $(box_items[prev_one]).animate({
                    'left': '-75%'
                },
                time, function() {
                    ++current > max_index ? current = 0 : void(0);
                    current - 1 < 0 ? prev_one = max_index : prev_one = current - 1;
                    current + 1 > max_index ? next_one = 0 : next_one = current + 1;

                    // Change Dot
                    $('.dot-control a').each(function(index, el) {
                        if(index === current){
                            $(this).addClass('dot-active');
                        }else{
                            $(this).removeClass('dot-active');
                        }
                    });
                    if(callback){ callback(); }

                    movie_end = true;
            });
        }

        // 向右滑动
        function slideToRight(time, callback){
            var time = time || 500;
            movie_end = false;

            // Init hide_four left value
            prev_one - 1 < 0 ? hide_four = max_index : hide_four = prev_one - 1;
            $(box_items[hide_four]).css('left', '-75%');

            // Start Animate
            $(box_items[current]).animate({ 'left': '75%' }, time);
            $(box_items[next_one]).animate({ 'left': '125%' }, time);
            $(box_items[hide_four]).animate({ 'left': '-25%' }, time);
            $(box_items[prev_one]).animate({
                    'left': '25%'
                },
                time, function() {
                    --current < 0 ? current = max_index : void(0);
                    current - 1 < 0 ? prev_one = max_index : prev_one = current - 1;
                    current + 1 > max_index ? next_one = 0 : next_one = current + 1;

                    // Change Dot
                    $('.dot-control a').each(function(index, el) {
                        if(index === current){
                            $(this).addClass('dot-active');
                        }else{
                            $(this).removeClass('dot-active');
                        }
                    });
                    if(callback){ callback(); }

                    movie_end = true;
            });
        }
        
        // Mouseover slider event
        var timer, autoplay;
        autoplay = setInterval(slideToLeft, 4000);
        $('.left-ctrl').hover(function() {
            clearTimeout(autoplay);
            if(movie_end){
                function a(){
                    slideToRight(500);
                    timer = setTimeout(a, 1200);
                }
                a();
            }
        }, function() {
            clearTimeout(timer);
            autoplay = setInterval(slideToLeft, 3000);
        });
        $('.right-ctrl').hover(function() {
            clearTimeout(autoplay);
            if(movie_end){
                function a(){
                    slideToLeft(500);
                    timer = setTimeout(a, 1200);
                }
                a();
            }
        }, function() {
            clearTimeout(timer);
            autoplay = setInterval(slideToLeft, 3000);
        });
        // Title popup
        $('#topic_slider_box').on('mouseenter mouseleave', 'li', function(event) {
            console.log(this);
            var $slide_meta = $(this).find('.slide-meta');
            $slide_meta.stop();
            event.type === 'mouseenter'
                ? $slide_meta.animate({'bottom': 0}, 150)
                : $slide_meta.animate({'bottom': '-70px'}, 200);
        });
    });
</script>