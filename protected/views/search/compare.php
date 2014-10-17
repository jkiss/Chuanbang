<!-- The search content -->
<section id="search">
    <ul class="s-header no-select">
        <li>
            <a href="<?php echo Yii::app()->createUrl('search/index',array('word'=>$word));?>">全部</a>
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
        <li class="active">
            <a href="javascript:;">对比</a>
        </li>
        <li>
            <a href="<?php echo Yii::app()->createUrl('search/user',array('word'=>$word));?>">用户</a>
        </li>
    </ul>
    <!-- 相关对比 -->
    <div class="module s-compare">
        <div class="grid four sep clearfix">
            <?php foreach($compares as $compare):?>
                <a href="<?php echo Yii::app()->createUrl('compare/view', array('id'=>$compare['id']));?>" class="column float">
                    <div class="image com-scale">
                        <div class="slide-wrap">
                            <img class="img1" src="/images/compare/compare2.jpg" alt="compare" data-src="">
                            <img class="img2" src="/images/compare/compare3.jpg" alt="compare" data-src="">
                            <img class="img3" src="/images/compare/compare4.jpg" alt="compare" data-src="">
                            <img class="img4" src="/images/compare/compare5.jpg" alt="compare" data-src="">
                        </div>
                        <span class="vs">VS</span>
                    </div>
                    <div class="caption">
                        <p class="name text-overflow"><?php echo $compare['title'];?></p>
                    </div>
                </a>
            <?php endforeach;?>
        </div>
    </div>
    <ul class="page-num">
        <div class="num-panel">
        </div>
        <div class="page-crtl">
            <li class="page-count no-li">共<span id="total_pages"><?php echo $total_page;?></span>页</li>
            <li class="page-text no-li">前往&nbsp;&nbsp;<input type="text" id="spec_num" maxlength="4">&nbsp;&nbsp;页</li>
            <li class="skip-to no-li disable">前往</li>
        </div>
    </ul>
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

    // 搜索结果分页
    var extra_data = {
            word: ''
        },
        url = '/search/listCompare',
        $wrap = $('.page-num').find('.num-panel'),
        $page_panel = $('.grid'),
        loadingHTML = '<i class="loading"></i>',
        updateContent = function(data, page_panel){  // 请求 success 后，更新页面内容
            var content = '';
            for (var i = 0; i < data.length; i++) {
                content += '<a href="'+ data[i].href +'" class="column float"><div class="image com-scale"><div class="slide-wrap">';
                content += '<img class="img1" src="/images/compare/compare2.jpg" alt="compare" data-src=""><img class="img2" src="/images/compare/compare3.jpg" alt="compare" data-src=""><img class="img3" src="/images/compare/compare4.jpg" alt="compare" data-src=""><img class="img4" src="/images/compare/compare5.jpg" alt="compare" data-src="">';
                content += '</div><span class="vs">VS</span></div><div class="caption"><p class="name text-overflow">'+ data[i].title +'</p></div></a>';
            };
            page_panel.html(content);
        },
        beforeSend = function(){
            // beforesend...清除当前页的内容，显示 Loading，跳到最顶部，并隐藏页码区
            $page_panel.html(loadingHTML);
            $('html, body').animate({scrollTop: 0}, 200);
        },
        sendComplete = function(){    // Ajax 请求后为为新元素添加事件，没使用代理
            $('.s-compare').find('img').each(function(index, el) {
                var init_w = $('.s-compare').find('.image').width() / 2;
                $(this).css('width', init_w + 'px');
            });
        };
</script>