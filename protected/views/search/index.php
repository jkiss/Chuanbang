<!-- The search content -->
<section id="search">
    <ul class="s-header no-select">
        <li class="active">
            <a href="javascript:;">全部</a>
        </li>
        <li>
            <a href="<?php echo Yii::app()->createUrl('search/celebrity',array('word'=>$word));?>">名人</a>
        </li>
        <li>
            <a href="<?php echo Yii::app()->createUrl('search/brand',array('word'=>$word));?>">品牌</a>
        </li>
        <li>
            <a href="<?php echo Yii::app()->createUrl('search/topic',array('word'=>$word));?>">话题</a>
        </li>
        <li>
            <a href="<?php echo Yii::app()->createUrl('search/compare',array('word'=>$word));?>">对比</a>
        </li>
        <li>
            <a href="<?php echo Yii::app()->createUrl('search/user',array('word'=>$word));?>">用户</a>
        </li>
    </ul>
    <?php if(array_key_exists('celebrity', $result)):?>
    <!-- 相关名人 -->
    <div class="module s-star">
        <div class="top">
            <i class="icon-black-star"></i>
            <h3>相关名人</h3>
            <span class="count">（共<?php echo $result['celebrity']['total'];?>人）</span>
            <a href="<?php echo Yii::app()->createUrl('search/celebrity',array('word'=>$word));?>" class="more">更多名人&bullet;&bullet;&bullet;</a>
        </div>
        <div class="grid seven sep clearfix">
            <?php foreach($result['celebrity']['data'] as $star):?>
            <a href="<?php echo Yii::app()->createUrl('celebrity/view',array('id'=>$star['id']));?>" class="column float">
                <div class="image s-scale">
                    <img src="<?php echo $star['img'];?>" alt="#">
                </div>
                <div class="caption">
                    <p class="name text-overflow"><?php echo $star['name'];?></p>
                    <p class="fans text-overflow">粉丝数111个</p>
                </div>
            </a>
            <?php endforeach;?>
        </div><hr>
    </div>
    <?php endif;?>
    <!-- 相关答案 -->
    <!-- <div class="module s-choice">
        <div class="top">
            <i class="icon-black-star"></i>
            <h3>相关精选</h3>
            <span class="count">（共3人）</span>
            <a href="#" class="more">更多精选&bullet;&bullet;&bullet;</a>
        </div>
        <div class="grid six sep clearfix">
            <a href="#" class="column float">
                <div class="image cho-scale">
                    <img src="images/search/choice1.jpg" alt="#">
                </div>
                <div class="caption">
                    <p class="name text-overflow">秦岚穿Chloe(fdsfsfsfsfs)</p>
                </div>
            </a>
            <a href="#" class="column float">
                <div class="image cho-scale">
                    <img src="images/search/choice1.jpg" alt="#">
                </div>
                <div class="caption">
                    <p class="name text-overflow">秦岚穿Chloe(fdsfsfsfsfs)</p>
                </div>
            </a>
            <a href="#" class="column float">
                <div class="image cho-scale">
                    <img src="images/search/choice1.jpg" alt="#">
                </div>
                <div class="caption">
                    <p class="name text-overflow">秦岚穿Chloe(fdsfsfsfsfs)</p>
                </div>
            </a>
            <a href="#" class="column float">
                <div class="image cho-scale">
                    <img src="images/search/choice1.jpg" alt="#">
                </div>
                <div class="caption">
                    <p class="name text-overflow">秦岚穿Chloe(fdsfsfsfsfs)</p>
                </div>
            </a>
            <a href="#" class="column float">
                <div class="image cho-scale">
                    <img src="images/search/choice1.jpg" alt="#">
                </div>
                <div class="caption">
                    <p class="name text-overflow">秦岚穿Chloe(fdsfsfsfsfs)</p>
                </div>
            </a>
            <a href="#" class="column float">
                <div class="image cho-scale">
                    <img src="images/search/choice1.jpg" alt="#">
                </div>
                <div class="caption">
                    <p class="name text-overflow">秦岚穿Chloe(fdsfsfsfsfs)</p>
                </div>
            </a>
            <a href="#" class="column float">
                <div class="image cho-scale">
                    <img src="images/search/choice1.jpg" alt="#">
                </div>
                <div class="caption">
                    <p class="name text-overflow">秦岚穿Chloe(fdsfsfsfsfs)</p>
                </div>
            </a>
        </div><hr>
    </div> -->
    <?php if(array_key_exists('brand', $result)):?>
    <!-- 相关品牌 -->
    <div class="module s-brand">
        <div class="top">
            <i class="icon-black-star"></i>
            <h3>相关品牌</h3>
            <span class="count">（共<?php echo $result['brand']['total'];?>个）</span>
            <a href="<?php echo Yii::app()->createUrl('search/brand',array('word'=>$word));?>" class="more">更多品牌&bullet;&bullet;&bullet;</a>
        </div>
        <div class="grid seven sep clearfix">
            <?php foreach($result['brand']['data'] as $brand):?>
            <a href="<?php echo Yii::app()->createUrl('brand/view', array('id'=>$brand['id']));?>" class="column float">
                <div class="image s-scale">
                    <img src="<?php echo $brand['img'];?>">
                </div>
                <div class="caption">
                    <p class="name text-overflow"><?php echo $brand['name'];?></p>
                    <p class="name_en text-overflow">DKNY</p>
                    <p class="fans text-overflow">粉丝111个</p>
                </div>
            </a>
            <?php endforeach;?>
        </div><hr>
    </div>
    <?php endif;?>
    <?php if(array_key_exists('topic', $result)):?>
    <!-- 相关话题 -->
    <div class="module s-topic">
        <div class="top">
            <i class="icon-black-star"></i>
            <h3>相关话题</h3>
            <span class="count">（共<?php echo $result['topic']['total'];;?>个）</span>
            <a href="<?php echo Yii::app()->createUrl('search/topic',array('word'=>$word));?>" class="more">更多话题&bullet;&bullet;&bullet;</a>
        </div>
        <div class="grid four sep clearfix">
            <?php foreach($result['topic']['data'] as $topic):?>
            <a href="<?php echo Yii::app()->createUrl('topic/view', array('id'=>$topic['id']));?>" class="column float">
                <div class="image top-scale">
                    <img src="<?php echo $topic['img'];?>" alt="#">
                </div>
                <div class="caption">
                    <p class="name text-overflow"><?php echo $topic['name'];?></p>
                </div>
            </a>
            <?php endforeach;?>
        </div><hr>
    </div>
    <?php endif;?>
    <?php if(array_key_exists('compare', $result)):?>
    <!-- 相关对比 -->
    <div class="module s-compare">
        <div class="top">
            <i class="icon-black-star"></i>
            <h3>相关对比</h3>
            <span class="count">（共<?php echo $result['compare']['total'];?>个）</span>
            <a href="<?php echo Yii::app()->createUrl('search/compare',array('word'=>$word));?>" class="more">更多对比&bullet;&bullet;&bullet;</a>
        </div>
        <div class="grid four sep clearfix">
            <?php foreach($result['compare']['data'] as $compare):?>
            <a href="<?php echo Yii::app()->createUrl('compare/view', array('id'=>$compare['id']));?>" class="column float">
                <div class="image com-scale">
                    <div class="slide-wrap">
                        <img class="img1" src="<?php echo $compare['img'];?>" alt="compare" data-src="">
                    </div>
                    <span class="vs">VS</span>
                </div>
                <div class="caption">
                    <p class="name text-overflow"><?php echo $compare['name'];?></p>
                </div>
            </a>
            <?php endforeach;?>
        </div><hr>
    </div>
    <?php endif;?>
    <?php if(array_key_exists('user', $result)):?>
    <!-- 相关用户 -->
    <div class="module s-user">
        <div class="top">
            <i class="icon-black-star"></i>
            <h3>相关用户</h3>
            <span class="count">（共<?php echo $result['user']['total'];?>人）</span>
            <a href="<?php echo Yii::app()->createUrl('search/user',array('word'=>$word));?>" class="more">更多用户&bullet;&bullet;&bullet;</a>
        </div>
        <div class="grid twelve sep clearfix">
            <?php foreach($result['user']['data'] as $user):?>
            <a href="<?php echo Yii::app()->createUrl('user/view', array('id'=>$user['id']));?>" class="column float">
                <div class="image s-scale">
                    <img src="<?php echo $user['img'];?>" alt="#">
                </div>
                <div class="caption">
                    <p class="name text-overflow"><?php echo $user['name'];?></p>
                    <p class="fans text-overflow">粉丝数111个</p>
                </div>
            </a>
            <?php endforeach;?>
        </div>
    </div>
    <?php endif;?>
</section>
<script>
    $('.grid').on('mouseenter mouseleave', 'a', function(event) {
        event.preventDefault();
        if(event.type === 'mouseenter'){
            var _me = $(this);
            _me.css('background-color', '#333');
            _me.find('.name').css('color', '#ddd');
            _me.find('.fans').css('color', '#ddd');
        }else if(event.type === 'mouseleave'){
            var _me = $(this);
            _me.css('background-color', '#fff');
            _me.find('.name').css('color', '#4a4f55');
            _me.find('.fans').css('color', '#a0a0a0');
        }
    });

    // 调整对比图片宽度
    $(function(){
        $('.s-compare').find('img').each(function(index, el) {
            var init_w = $('.s-compare').find('.image').width() / 2;
            $(this).css('width', init_w + 'px');
        });
    });
    $(window).resize(function(event) {
        $('.s-compare').find('img').each(function(index, el) {
            var init_w = $('.s-compare').find('.image').width() / 2;
            $(this).css('width', init_w + 'px');
        });
    });

    // 对比图片随鼠标滑动
    $('.s-compare').find('.grid').on('mousemove', '.image', function(event) {
        this.img_num = $(this).find('img').length;
        this.moving = false;
        this.zone_num = 0;
        var _this = $(this);
            mouse_offsetX = event.pageX - $(this).offset().left,
            img_width = $(this).find('img').width(),
            $slide_wrap = $(this).find('.slide-wrap');
            wrap_width = $(this).width();
            monitor_zones = [],
            zone_width = Math.floor(wrap_width / (this.img_num - 1));

        // Init
        for (var i = 1; i < this.img_num; i++) {
            monitor_zones.push({
                offsetX: zone_width * (i - 1),
                offsetY: zone_width * i,
                left_dis: img_width * (i - 1),
                num: i
            });
        };
        monitor_zones.contain = function(number){
            return number >= this.offsetX && number <= this.offsetY;
        }

        // Decting mouse_pos & do something
        for (var i = 0; i < monitor_zones.length; i++) {
            if(monitor_zones.contain.call(monitor_zones[i], mouse_offsetX) && monitor_zones[i].num !== this.zone_num){
                $slide_wrap.stop();
                $slide_wrap.animate({'left': -monitor_zones[i].left_dis + 'px'}, 100, 'linear');
                this.zone_num = monitor_zones[i].num;
            }
        };

        // 调试信息
        console.log(this.img_num);
        console.log('event.offsetX:' + event.offsetX); // 火狐不支持offset
        console.log(event.pageX - $(this).offset().left);
    });
</script>