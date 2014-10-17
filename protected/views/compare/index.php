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
                            <?php foreach($item['imgs'] as $img):?>
                            <img src="<?php echo $img;?>?w=88&h=130" alt="">
                            <?php endforeach;?>
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
<!-- The compare Content -->
<section class="content" id="compare">
    <div class="thr-nav">
        <i class="icon-compare-1"></i>
        对比
    </div>
    <div class="main">
        <div class="grid three sep limit-height clearfix" id="page_panel" data-max-page="<?php echo $total_page;?>">
            <?php foreach($models as $model):?>
            <a href="<?php echo Yii::app()->createUrl('compare/view',array('id'=>$model['id']));?>" class="column float transition">
                <div class="image c-scale">
                    <div class="slide-wrap">
                        <?php for($i = 0, $len = count($model['imgs']); $i < $len; $i++):?>
                        <img src="<?php echo $model['imgs'][$i]['url'];?>?w=254&h=382" alt="">
                        <?php endfor;?>
                    </div>
                    <span class="vs transition">VS</span>
                </div>
                <div class="c-bottom">
                    <div class="b1">
                        <p class="c-title"><?php echo $model['title'];?></p>
                    </div>
                    <div class="b2">
                        <div class="img-wrap">
                            <img src="<?php echo $model['author']['head'];?>?w=30&h=30" alt="">
                        </div>
                        <p class="name"><?php echo $model['author']['nick'];?></p>
                        <p class="time"><?php echo $model['createtime'];?></p>
                        <p class="comment"><?php echo $model['total_comments'];?>条评论</p>
                    </div>
                </div>
            </a>
            <?php endforeach;?>
        </div>
        <ul class="page-num" data-total-page="">
            <div class="num-panel">
            </div>
        </ul>
    </div>
</section>

<script>
function invokeHere(Pagination, publishDate, Wheel){
    $('a[class*="column"]').hover(function() {
        var _this = $(this);
        _this.css('background-color', '#282828');
        _this.find('.compare-title').css('color', '#bfbfbf');
        _this.find('.c-title').css('color', '#eee');
        _this.find('.vs').css('background-color', '#452CC5');
        _this.find('.b1').css('top', '85px');
        _this.find('.b2').css('top', '0');
    }, function() {
        var _this = $(this);
        _this.css('background-color', '#fff');
        _this.find('.compare-title').css('color', '#4a4f55');
        _this.find('.c-title').css('color', '#4a4f55');
        _this.find('.vs').css('background-color', '#000');
        _this.find('.b1').css('top', '0');
        _this.find('.b2').css('top', '85px');
    });
    // change column by js
    var res_ele = $('#page_panel'),
        res_switch = {
            lt1440: {
                contain: function(w){
                    if(w < 1440){
                        return true;
                    }else{
                        return false;
                    }
                },
                className: 'three'
            },
            is1440_1920: {
                contain: function(w){
                    if(w >= 1440 && w <= 1920){
                        return true;
                    }else{
                        return false;
                    }
                },
                className: 'four'
            },
            gt1920: {
                contain: function(w){
                    if(w > 1920){
                        return true;
                    }else{
                        return false;
                    }
                },
                className: 'five'
            }
        }
    function response(ele, state, callback){
        var win_width = $(window).width();
        ele.toggleClass(state.lt1440.className, state.lt1440.contain(win_width));
        ele.toggleClass(state.is1440_1920.className, state.is1440_1920.contain(win_width));
        ele.toggleClass(state.gt1920.className, state.gt1920.contain(win_width));

        if(callback) callback();
    }
    // $(window).on('resize', function(event) {
    //     response(res_ele, res_switch, function(){
    //         setTimeout(function(){
    //             $('.image').find('img').each(function(index, el) {
    //                 var init_w = $('.image').width() / 2;
    //                 $(this).css('width', init_w + 'px');
    //             });
    //         }, 300);
    //     });
    // }).trigger('resize');
    $(window).on('resize', function(event) {
        $('#page_panel').find('.image').find('img').each(function(index, el) {
            var init_w = $('#page_panel').find('.image').width() / 2;
            $(this).css('width', init_w + 'px');
        });
    }).trigger('resize');
    

    // 随鼠标滑动进行图片切换
    $('.grid').on('mousemove', '.image', function(event) {
        this.img_num = $(this).find('img').length;
        this.moving = false;
        this.zone_num = 0;
        var _this = $(this),
            mouse_offsetX = event.pageX - $(this).offset().left,
            img_width = _this.find('img').width(),
            $slide_wrap = _this.find('.slide-wrap'),
            wrap_width = _this.width(),
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
    });

    // Pagination
    var 
        // Extra Data
        extra_data = {
            pageSize: 15
        },
        // update eles
        $wrap = $('.page-num').find('.num-panel'),
        $page_panel = $('#page_panel'),
        loadingHTML = '<i class="loading-1"><img src="<?php echo Yii::app()->baseUrl;;?>/images/loading-rot-full.gif" alt="loading"></i>',
        opt = {
            url: '/compare/list',
            range: 5,
            wrap: $('.page-num').find('.num-panel'),
            max_page: parseInt($('#page_panel').attr('data-max-page')),
            updateContent: function(data, page_panel){  // 更新图片内容
                var content = '',
                    data = data.compares;
                for (var i = 0; i < data.length; i++) {
                    content += '<a href="/compare/'+ data[i].id +'" class="column float transition"><div class="image c-scale"><div class="slide-wrap">';
                    for (var j = 0; j < Things.length; j++) {
                        content += '<img src="'+ data[i].imgs[j].url +'?w=254&h=382" alt="">';
                    };
                    content += '</div><span class="vs transition">VS</span></div><div class="c-bottom"><div class="b1"><p class="c-title">'+ data[i].title +'</p></div><div class="b2"><div class="img-wrap"><img src="'+ data[i].author.head +'?w=30&h=30" alt=""></div><p class="name">'+ data[i].author.nick +'</p><p class="time">'+ publishDate(data[i].createtime) +'</p><p class="comment">'+ data[i].total_comments +'条评论</p></div></div></a>';
                };
                page_panel.html(content);
            },
            beforeSend: function(){
                // beforesend...清除当前页的内容，显示 Loading，跳到最顶部，并隐藏页码区
                $page_panel.html(loadingHTML);
                $('html, body').animate({scrollTop: 395}, 200);
                // Hide pagination
                $('.num-panel').css('display', 'none');
            },
            sendComplete: function(){
                // Show pagination
                $('.num-panel').css('display', 'block');
            }
        },
        pageXHR = new Pagination(opt);

    // Init the first page
    pageXHR.init(function(){
        pageXHR.updateNum(pageXHR.paged);
    });

    // This is a page num switch Proxy
    $('.num-panel').on('click', 'li, span', function(event) {
        var $target = $(event.target);
        // console.log($target.is('li, span'));
        if(!($target.hasClass('current') || $target.hasClass('disable'))){
            if($target.is('li')){
                // get page num from <li>
                pageXHR.paged = parseInt($target.attr('data-page'));
            }else if($target.is('span')){
                // get page num from <span>, Judge it's prev page or next page
                $target.hasClass('prev') ? pageXHR.paged -= 1 : pageXHR.paged += 1;
            }
            // request pagination
            pageXHR.xhrPage(pageXHR.paged, $page_panel, extra_data);
        }
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
</script>