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
                        <p class="name"><?php echo $q['user_head'];?></p>
                        <p class="time"><?php echo $q['createtime'];?></p>
                    </div>
                </a></li>
                <?php endforeach;?>
            </ul>
        </nav>
    </div>
</div>
<!-- Wait content -->
<section class="content" id="pending">
    <div class="thr-nav">
        <i class="icon-wait"></i>
        待答
    </div>
    <div class="grid five sep limit-height clearfix main" id="page_panel" data-max-page="<?php echo $total_page;?>">
        <?php foreach($models as $q):?>
            <a href="<?php echo Yii::app()->createUrl('question/view', array('id'=>$q['id']));?>" class="column float transition">
                <div class="image star-scale">
                    <img src="<?php echo $q['img'];?>?w=300&h=452" alt="Question" data-src="">
                </div>
                <div class="bottom">
                    <div class="info">
                        <div class="img-wrap">
                            <img src="<?php echo $q['user_head'];?>?w=30&h=30" alt="">
                        </div>
                        <p class="name"><?php echo $q['user_nick'];?></p>
                        <p class="time"><?php echo $q['createtime'];?></p>
                        <p class="comment-num"><?php echo $q['total_comments'];?>条评论</p>
                    </div>
                    <div class="title text-overflow">
                        我要回答
                    </div>
                </div>
            </a>
        <?php endforeach;?>
    </div>
    <ul class="page-num" data-total-page="">
        <div class="num-panel">
        </div>
    </ul>
</section>
<script>
function invokeHere(Pagination, publishDate, Wheel){
    $('a[class*="column"').hover(function() {
        var _this = $(this);
        _this.css('background-color', '#282828').find('img').css('top', '-10px');
        _this.find('.info').css('top', '55px');
        _this.find('.title').css('top', '0');
    }, function() {
        var _this = $(this);
        _this.css('background-color', '#fff').find('img').css('top', '0');
        _this.find('.info').css('top', '0');
        _this.find('.title').css('top', '55px');
    });
    // change news columns by js
    var res_ele = $('#page_panel'),   // 需要响应的元素
        res_switch = {
            lt1600: {
                contain: function(w){
                    if(w <= 1600){
                        return true;
                    }else{
                        return false;
                    }
                },
                className: 'four'
            },
            is1600_1800: {
                contain: function(w){
                    if(w > 1600 && w < 1800){
                        return true;
                    }else{
                        return false;
                    }
                },
                className: 'five'
            },
            gt1800: {
                contain: function(w){
                    if(w >= 1800){
                        return true;
                    }else{
                        return false;
                    }
                },
                className: 'six'
            }
        }
    // console.log(res_switch.lt1600);
    function response(ele, state){
        var win_width = $(window).width();
        ele.toggleClass(state.lt1600.className, state.lt1600.contain(win_width));
        ele.toggleClass(state.is1600_1800.className, state.is1600_1800.contain(win_width));
        ele.toggleClass(state.gt1800.className, state.gt1800.contain(win_width));
    }
    response(res_ele, res_switch);
    $(window).resize(function(event) {
        response(res_ele, res_switch);
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
            url: '/question/list',
            range: 5,
            wrap: $('.page-num').find('.num-panel'),
            max_page: parseInt($('#page_panel').attr('data-max-page')),
            updateContent: function(data, page_panel){  // 更新图片内容
                var content = '',
                    data = data.models;
                for (var i = 0; i < data.length; i++) {
                    content += '<a href="/question/'+ data[i].id +'" class="column float transition"><div class="image star-scale"><img src="'+ data[i].img +'?w=300&h=452" alt="Question" data-src=""></div><div class="bottom"><div class="info"><div class="img-wrap"><img src="'+ data[i].user_head +'?w=30&h=30" alt=""></div><p class="name">'+ data[i].user_nick +'</p><p class="time">'+ publishDate(data[i].createtime) +'</p><p class="comment-num">'+ data[i].total_comments +'条评论</p></div><div class="title text-overflow">我要回答</div></div></a>';
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