<!-- The sub nav -->
<div class="sub-nav-wrap">
    <div class="sub-nav">
        <nav class="items index-sub">
            <h1>趋势</h1>
            <ul class="wheel-panel">
                <?php foreach($trend as $cele):?>
                    <li class="transition"><a href="<?php echo Yii::app()->createUrl('celebrity/'.$cele['id']);?>">
                        <div class="img-wrap">
                            <img src="<?php echo $cele['head'];?>?w=80&h=80" alt="#">
                        </div>
                        <p><em>
                        <?php if(isset($cele['name_cn']) && !empty($cele['name_cn'])):?>
                            <?php echo $cele['name_cn'];?><br>
                        <?php endif;?>
                        <?php if(isset($cele['name_en']) && !empty($cele['name_en'])):?>
                            <?php echo $cele['name_en'];?>
                        <?php endif;?>
                        </em></p>
                    </a></li>
                <?php endforeach;?>
            </ul>
        </nav>
    </div>
</div>
<!-- The star content -->
<section class="content" id="b_s_model">
    <div class="thr-nav">
        <i class="icon-star"></i>
        明星
    </div>
    <div class="c-top">
        <div class="grid two sep-1 clearfix" id="page_panel">
            <?php foreach($hots as $model):?>
            <i class="column float transition pos-r mb-30">
                <div class="profile">
                    <a href="<?php echo Yii::app()->createUrl('celebrity/'.$model['id']);?>" class="transition">
                        <div class="img-wrap">
                            <img src="<?php echo $model['head'];?>?w=120&h=120" alt="ava">
                        </div>
                        <p class="cn"><?php echo $model['name_cn'];?></p>
                        <p class="en"><?php echo $model['name_en'];?></p>
                    </a>
                    <div class="p-data">
                        <span class="ins"><?php echo $model['total_qa'];?>身穿搭</span>/
                        <span class="fans"><?php echo $model['total_fans'];?>粉丝</span>
                    </div>
                    <?php if($model['follow'] == 'Y'):?>
                        <div class="fo-btn yes transition">
                            <i class="icon-yes"></i>
                            <em class="text">已关注</em>
                        </div>
                    <?php else:?>
                        <div class="fo-btn no transition">
                            <i class="icon-follow-1"></i>
                            <em class="text">加关注</em>
                        </div>
                    <?php endif;?>
                </div>
                <div class="relate">
                    <div class="grid two sep clearfix">
                        <?php foreach($model['details'] as $data):?>
                        <a href="<?php echo Yii::app()->createUrl('answer/'.$data['id']);?>" class="column float transition">
                            <div class="image scale-1">
                                <img src="<?php echo $data['img'];?>?w=202&h=302" alt="r1">
                            </div>
                            <div class="bottom">
                                <div class="title">
                                    <p class="t_name text-overflow"><?php echo $model['name'];?></p>
                                    <i class="icon-in"></i>
                                    <p class="t_clothes">
                                        <?php echo $data['brand'];?>
                                    </p>
                                </div>
                                <div class="author new-au">
                                    <i class="avatar">
                                        <img src="<?php echo $data['author']['head'];?>" alt="">
                                    </i>
                                    <p class="a_name"><?php echo $data['author']['nick'];?></p>
                                    <p class="a_time"><?php echo $data['createtime'];?></p>
                                </div>
                            </div>
                        </a>
                        <?php endforeach;?>
                    </div>
                </div>
            </i>
            <?php endforeach;?>
        </div>
    </div>
    
    <div class="c-bottom">
        <h2>
            更多热门明星
        </h2>
        <div class="grid six sep clearfix">
            <?php foreach($more as $model):?>
            <a href="<?php echo Yii::app()->createUrl('celebrity/'.$model['id']);?>" class="column float transition">
                <div class="image scale-2">
                    <img src="<?php echo $model['head'];?>?w=196&h=196" alt="more-star">
                </div>
                <div class="b-s-bottom">
                    <div class="b-s-name">
                        <p class="cn text-overflow"><?php echo $model['name_cn'];?></p>
                        <p class="en text-overflow"><?php echo $model['name_en'];?></p>
                    </div>
                    <div class="b-s-suit text-overflow">
                        <?php echo $model['total_qa'];?>身穿搭
                    </div>
                </div>
            </a>
            <?php endforeach;?>
        </div>
    </div>
    <!-- more search -->
    <div class="more-search">
        <div class="no-more">
            没有更多热门明星了
        </div>
        <a href="#"><div class="search-cb transition">探索穿帮</div></a>
    </div>
</section>
<script>
    function invokeHere(Pagination, publishDate, Wheel){
        // change column by js
        // var res_ele = $('#page_panel'),
        //     res_switch = {
        //         lt1366: {
        //             contain: function(w){
        //                 if(w < 1366){
        //                     return true;
        //                 }else{
        //                     return false;
        //                 }
        //             },
        //             className: 'five'
        //         },
        //         is1366_1566: {
        //             contain: function(w){
        //                 if(w >= 1366 && w < 1566){
        //                     return true;
        //                 }else{
        //                     return false;
        //                 }
        //             },
        //             className: 'six'
        //         },
        //         is1566_1766: {
        //             contain: function(w){
        //                 if(w >= 1566 && w < 1766){
        //                     return true;
        //                 }else{
        //                     return false;
        //                 }
        //             },
        //             className: 'seven'
        //         },
        //         gt1766: {
        //             contain: function(w){
        //                 if(w > 1766){
        //                     return true;
        //                 }else{
        //                     return false;
        //                 }
        //             },
        //             className: 'eight'
        //         }
        //     }
        // function response(ele, state){
        //     var win_width = $(window).width();
        //     ele.toggleClass(state.lt1366.className, state.lt1366.contain(win_width));
        //     ele.toggleClass(state.is1366_1566.className, state.is1366_1566.contain(win_width));
        //     ele.toggleClass(state.is1566_1766.className, state.is1566_1766.contain(win_width));
        //     ele.toggleClass(state.gt1766.className, state.gt1766.contain(win_width));
        // }
        // response(res_ele, res_switch);
        // $(window).resize(function(event) {
        //     response(res_ele, res_switch);
        // });
        

        // Grid event
        $('a[class*="column"]').hover(function(){
            var _that = $(this);
            _that.css('background-color', '#282828').find('img').css('top', '-10px');
            _that.find('.title').css('top', '80px');
            _that.find('.author').css('top', '0');
            _that.find('.b-s-suit').css('top', '0');
            _that.find('.b-s-name').css('top', '48px');
        }, function(){
            var _that = $(this);
            _that.css('background-color', '#fff').find('img').css('top', '0');
            _that.find('.title').css('top', '0');
            _that.find('.author').css('top', '80px');
            _that.find('.b-s-suit').css('top', '48px');
            _that.find('.b-s-name').css('top', '0');
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

        // Follow Event
        $('#b_s_model').find('.fo-btn').hover(function() {
            var _me = $(this);
            if(_me.hasClass('yes')){
                _me.find('i').attr('class', 'icon-minus');
                _me.find('.text').text('取消关注');
                _me.css({
                    'color': '#000',
                    'background-color': '#cbcbcb'
                });
            }
        }, function() {
            var _me = $(this);
            if(_me.hasClass('yes')){
                _me.find('i').attr('class', 'icon-yes');
                _me.find('.text').text('已关注');
                _me.css({
                    'color': '#452CC5',
                    'background-color': '#fff'
                });
            }
        });

        $('.fo-btn').click(function(event) {
            var _me = $(this),
                id = _me.siblings('a').attr('href').match(/\d+/g)[0],
                data = {
                    id: id
                },
                ajaxing = false;
            if(!ajaxing){
                ajaxing = true;
                if(_me.hasClass('no')){
                    $.ajax({
                        url: '/celebrity/follow',
                        type: 'POST',
                        dataType: 'json',
                        data: data,
                        success: function(data, status, xhr){
                            if(data.msg === 'success'){
                                _me.toggleClass('yes no');
                                _me.find('i').attr('class', 'icon-yes');
                                _me.find('.text').text('已关注');
                            }else{
                                // Fail to follow ...
                            }
                        },
                        error: function(xhr, status, msg){
                            console.log(msg);
                        },
                        complete: function(xhr, status){
                            ajaxing = false;
                        }
                    });
                }else if(_me.hasClass('yes')){
                    $.ajax({
                        url: '/celebrity/unfollow',
                        type: 'POST',
                        dataType: 'json',
                        data: data,
                        success: function(data, status, xhr){
                            if(data.msg === 'success'){
                                _me.toggleClass('yes no');
                                _me.find('i').attr('class', 'icon-follow-1');
                                _me.find('.text').text('加关注');
                                _me.css({
                                    'color': '#4a4f55',
                                    'background-color': '#fff'
                                });
                            }else{
                                // Fail to unfollow ...
                            }
                        },
                        error: function(xhr, status, msg){
                            console.log(msg);
                        },
                        complete: function(xhr, status){
                            ajaxing = false;
                        }
                    });
                }
            }
        });
            
    }
</script>
