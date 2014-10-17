<!-- The sub nav -->
<div class="sub-nav-wrap">
    <div class="sub-nav">
        <nav class="items index-sub">
            <h1>趋势</h1>
            <ul class="wheel-panel">
                <?php foreach($trend as $item):?>
                    <li class="transition">
                        <?php if($item['tag'] == 'celebrity'):?>
                        <a href="<?php echo Yii::app()->createUrl('celebrity/view',array('id'=>$item['id']));?>">
                        <?php elseif($item['tag'] == 'brand'):?>
                        <a href="<?php echo Yii::app()->createUrl('brand/view',array('id'=>$item['id']));?>">
                        <?php else:;?>
                        <a href="javascript:;">
                        <?php endif;?>
                            <div class="img-wrap">
                                <img src="<?php echo $item['img'];?>?w=80&h=80" alt="#">
                            </div>
                            <p><em>
                            <?php if($item['tag'] == 'celebrity' && isset($item['name_cn']) && !empty($item['name_cn'])):?>
                                <?php echo $item['name_cn'];?><br>
                            <?php endif;?>
                            <?php if(isset($item['name_en']) && !empty($item['name_en'])):?>
                                <?php echo $item['name_en'];?>
                            <?php endif;?>
                            </em></p>
                        </a>
                    </li>
                <?php endforeach;?>
            </ul>
        </nav>
    </div>
</div>
<section class="content" id="cb_index">
    <section class="hot bottom-hr">
        <h3 class="hot-mark">
            <i class="icon-hot-topic"></i>
            热门话题
        </h3>

        <?php if(isset($topics) && is_array($topics) && count($topics) >= 3):?>
        <div class="main">
            <div class="slide-panel">
                <ul class="wrapper translate3D">
                    <?php $size = count($topics);?>
                    <li class="one">
                        <a href="<?php echo Yii::app()->createUrl('topic/view', array('id'=>$topics[$size-2]['id']));?>">
                            <img src="<?php echo $topics[$size-2]['cover'].'?w=780&h=500';?>" alt="">
                        </a>
                        <a href="#"><div class="title">
                            <?php echo $topics[$size-2]['title'];?>
                        </div></a>
                    </li>
                    <li class="one">
                        <a href="<?php echo Yii::app()->createUrl('topic/view', array('id'=>$topics[$size-1]['id']));?>">
                            <img src="<?php echo $topics[$size-1]['cover'].'?w=800&h=500';?>" alt="">
                        </a>
                        <a href="#"><div class="title">
                            <?php echo $topics[$size-1]['title'];?>
                        </div></a>
                    </li>
                    
                    <?php foreach($topics as $topic):?>
                        <?php if(isset($topic['cover'])):?>
                        <li class="one">
                            <a href="<?php echo Yii::app()->createUrl('topic/view', array('id'=>$topic['id']));?>">
                                <img src="<?php echo $topic['cover'].'?w=800&h=500';?>" alt="">
                            </a>
                            <a href="#"><div class="title">
                                <?php echo $topic['title'];?>
                            </div></a>
                        </li>
                        <?php endif;?>
                    <?php endforeach;?>
                    <li class="one">
                        <a href="<?php echo Yii::app()->createUrl('topic/view', array('id'=>$topics[0]['id']));?>">
                            <img src="<?php echo $topics[0]['cover'].'?w=800&h=500';?>" alt="">
                        </a>
                        <a href="#"><div class="title">
                            <?php echo $topics[0]['title'];?>
                        </div></a>
                    </li>
                    <li class="one">
                        <a href="<?php echo Yii::app()->createUrl('topic/view', array('id'=>$topics[1]['id']));?>">
                            <img src="<?php echo $topics[1]['cover'].'?w=800&h=500';?>" alt="">
                        </a>
                        <a href="#"><div class="title">
                            <?php echo $topics[1]['title'];?>
                        </div></a>
                    </li>
                </ul>
                <div class="arrow left">
                    <i class="icon-dir-left"></i>
                </div>
                <div class="arrow right">
                    <i class="icon-dir-right"></i>
                </div>
                <div class="mask-l"></div>
                <div class="mask-r"></div>
            </div>
        </div>
        <?php endif;?>
    </section>
    <!-- <i class="hr"></i> -->
    <section class="new">
        <h3 class="news-mark">
            <i class="icon-new-trend"></i>
            最新动态
        </h3>
        <div class="news-box grid four sep clearfix">
        <?php for($i=0, $len=count($answers); $i < $len; $i++):?>
            <?php $answer = $answers[$i];?>
            <a class="column float transition" href="<?php echo Yii::app()->createUrl('answer/view',array('id'=>$answer['ans_id']));?>">
                <div class="image scale-1">
                    <img src="<?php echo $answer['img'].'?w=306&h=458';?>" alt="img" data-img="">
                </div>
                <div class="bottom">
                    <div class="title">
                        <p class="t_name text-overflow"><?php echo $answer['celebrity_name'];?></p>
                        <i class="icon-in new-in"></i>
                        <p class="t_clothes">
                            <?php echo $answer['brand_name'];?>
                        </p>
                    </div>
                    <div class="author new-au">
                        <i class="avatar">
                            <img src="<?php echo $answer['user_head'];?>" alt="">
                        </i>
                        <p class="a_name"><?php echo $answer['user_nick'];?></p>
                        <p class="a_time"><?php echo $answer['ans_time'];?></p>
                    </div>
                </div>
            </a>
        <?php endfor;?>
        </div>
    </section>
    <!-- more search -->
    <div class="more-search">
        <div class="no-more">
            没有更多最新动态了
        </div>
        <a href="#"><div class="search-cb transition">探索穿帮</div></a>
    </div>
</section>

<script>
    function invokeHere(Pagination, publishDate, Wheel){
        // Hot Events 
        $('a[class*="column"]').hover(function(){
            var _that = $(this);
            _that.css('background-color', '#282828').find('img').css('top', '-10px');
            _that.find('.title').css('top', '80px');
            _that.find('.author').css('top', '0');
        }, function(){
            var _that = $(this);
            _that.css('background-color', '#fff').find('img').css('top', '0');
            _that.find('.title').css('top', '0');
            _that.find('.author').css('top', '80px');
        });
        // change news columns by js
        var res_ele = $('.news-box'),   // 需要响应的元素
            res_switch = {
                lt1440: {
                    contain: function(w){
                        if(w <= 1440){
                            return true;
                        }else{
                            return false;
                        }
                    },
                    className: 'five'
                },
                is1440_1920: {
                    contain: function(w){
                        if(w > 1440 && w <= 1920){
                            return true;
                        }else{
                            return false;
                        }
                    },
                    className: 'six'
                },
                gt1920: {
                    contain: function(w){
                        if(w > 1920){
                            return true;
                        }else{
                            return false;
                        }
                    },
                    className: 'eight'
                }
            };
        function response(ele, state){
            var win_width = $(window).width();
            ele.toggleClass(state.lt1440.className, state.lt1440.contain(win_width));
            ele.toggleClass(state.is1440_1920.className, state.is1440_1920.contain(win_width));
            ele.toggleClass(state.gt1920.className, state.gt1920.contain(win_width));
        };
        response(res_ele, res_switch);
        $(window).resize(function(event) {
            response(res_ele, res_switch);
        });

        // Hot-topic slider
        var slider_opt = {
            time: 500,
            autoplay: true,
            panel: $('#cb_index').find('.slide-panel'),
            wrapper: $('#cb_index').find('.wrapper'),
            one_w: 800,
            num: 5,
            left_btn: $('#cb_index').find('.main .left'),
            right_btn: $('#cb_index').find('.main .right')
        };
        function Slider(opt){
            this.time = opt.time;
            this.autoplay = opt.autoplay;
            this.panel = opt.panel;
            this.wrapper = opt.wrapper;
            this.first_pos = '-1600px';
            this.last_pos = '-4800px';
            this.left_btn = opt.left_btn;
            this.right_btn = opt.right_btn;
            this.ing = false;
            this.timer;
        };
        Slider.prototype.toLeft = function(){
            var _me = this;

            _me.wrapper.animate({
                'left': '-=800px'
            },
                _me.time, function() {
                if(_me.isAfterLast()){        // move to the first img in the moment
                    _me.wrapper.css('left', _me.first_pos);
                }
            });
        };
        Slider.prototype.toRight = function(){
            var _me = this;

            _me.wrapper.animate({
                'left': '+=800px'
            },
                _me.time, function() {
                if(_me.isBeforeFirst()){        // move to the first img in the moment
                    _me.wrapper.css('left', _me.last_pos);
                }
            });
        };
        Slider.prototype.isAfterLast = function(){
            return parseInt(this.wrapper.css('left')) < parseInt(this.last_pos);
        };
        Slider.prototype.isBeforeFirst = function(){
            return parseInt(this.wrapper.css('left')) > parseInt(this.first_pos);
        };
        Slider.prototype.start = function(){
            var _me = this;

            _me.left_btn.on('click', function(event) {
                _me.toRight();
            });
            _me.right_btn.on('click', function(event) {
                _me.toLeft();
            });
            _me.panel.on('mouseenter mouseleave', function(event) {
                if(event.type === 'mouseenter'){
                    clearTimeout(_me.timer);
                }else if(event.type === 'mouseleave'){
                    _me.timer = setInterval(function(){
                        _me.toLeft();
                    }, 4000);
                }
            });

            if(_me.autoplay){
                _me.timer = setInterval(function(){
                    _me.toLeft();
                }, 4000);
            }
        };
        (new Slider(slider_opt)).start();

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
</script>