<!-- The sub nav -->
<div class="sub-nav-wrap">
    <div class="sub-nav">
        <nav class="items topic-sub">
            <h1>最新推荐</h1>
            <ul class="wheel-panel">
                <?php foreach($suggest as $tp):?>
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
<!-- The Topic detail content -->
<section class="content" id="topic_detail">
    <div class="one">
        <div class="detail" data-id="<?php echo $model['id'];?>">
            <div class="img-wrap">
                <img src="<?php echo $model['cover'];?>?w=550&h=405" alt="">
            </div>
            <div class="right">
                <h1 class="t-name"><?php echo $model['title'];?></h1>
                <p class="desc"><?php echo $model['description'];?></p>
            </div>
            <div class="ctrl-btn">
                <?php if($model['follow'] == 'N'):?>
                <span class="collect no">
                    <i class="icon-collect-1"></i>
                <?php else:?>
                <span class="collect yes">
                    <i class="icon-collect-2"></i>
                    <?php endif;?>
                    收藏
                    <span class="num"><?php echo $model['total_fans'];?></span>
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
                </span>
            </div>
        </div>
        <div class="grid five sep clearfix relate-star" id="page_panel" data-max-page="<?php echo $total_page;?>">
            <?php foreach($answers as $answer):?>
            <a class="column float transition" href="<?php echo Yii::app()->createUrl('answer/view',array('id'=>$answer['id']));?>">
                <div class="image scale-1">
                    <img src="<?php echo $answer['img'];?>?w=300&h=450" alt="img" data-img="">
                </div>
                <div class="bottom">
                    <div class="title">
                        <p class="t_name text-overflow"><?php echo $answer['detail']['celebrity']['name'];?></p>
                        <i class="icon-in new-in"></i>
                        <p class="t_clothes">
                            <?php echo $answer['detail']['brand']['name'];?>
                        </p>
                    </div>
                    <div class="author new-au">
                        <i class="avatar">
                            <img src="<?php echo $answer['author']['head'];?>" alt="">
                        </i>
                        <p class="a_name"><?php echo $answer['author']['nick'];?></p>
                        <p class="a_time"><?php echo $answer['ans_time'];?></p>
                    </div>
                </div>
            </a>
            <?php endforeach;?>
        </div>
        <i class="loading-1 hide-0"><img src="<?php echo Yii::app()->baseUrl;;?>/images/loading-rot-full.gif" alt="loading"></i>
        <ul class="page-num" data-total-page="">
            <div class="num-panel">
            </div>
        </ul>
    </div>
</section>
<script>
    function invokeHere(Pagination, publishDate, Wheel){
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

        // zan & favorite event
        $('#topic_detail').find('.collect').click(function(event) {
            var _me = $(this);
                num = parseInt(_me.find('.num').text());
                id = $('.detail').attr('data-id'),
                ajaxing = false;

            if(_me.hasClass('yes') && !ajaxing){
                $.ajax({
                    url: '/topic/unfollow',
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
                    url: '/topic/follow',
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
        $('#topic_detail').find('.zan').click(function(event) {
            var _me = $(this);
                num = parseInt(_me.find('.num').text());
                id = $('.detail').attr('data-id'),
                ajaxing = false;

            if(_me.hasClass('yes') && !ajaxing){
                $.ajax({
                    url: '/topic/unsupport',
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
                    url: '/topic/support',
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

        // infinity loading
        var 
            infPageturn = {    // pageturn eles need
                loading: $('.loading-1'),
                append_wrap: $('#page_panel'),
                now_page: 1,
                total_page: parseInt($('#page_panel').attr('data-max-page')),
                ing: false
            },
            data = {      // request parameters
                id: $('.detail').attr('data-id'),
                page: 1,
                ajax: true,
                pageSize: 15
            };

        $(window).on('scroll', function(event) {
            var 
                // some data need
                win_h = $(window).height(),
                // loading page or not by judging loaing.gif's postion
                loading_top = parseInt(infPageturn.loading[0].getBoundingClientRect().top);
                
            console.log('LoadingTop:' + loading_top + '; Win_H:' + win_h);
            // Trigger condition
            if(loading_top < (win_h-50) && infPageturn.now_page < infPageturn.total_page && !infPageturn.ing ){
                infPageturn.ing = true;
                data.page = ++infPageturn.now_page;
                console.log(infPageturn.now_page);
                $.ajax({
                    url: '/topic/answer',
                    type: 'GET',
                    dataType: 'json',
                    data: data,
                    beforeSend: function(){
                        infPageturn.loading.removeClass('hide-0');
                    },
                    success: function(data, status, xhr){
                        data = data.rows;
                        var content = '';
                        for (var i = 0; i < data.length; i++) {
                            content += '<a class="column float transition" href="/answer/'+ data[i].id +'"><div class="image scale-1"><img src="'+ data[i].img +'?w=300&h=450" alt="img" data-img=""></div><div class="bottom"><div class="title"><p class="t_name text-overflow">'+ data[i].detail.celebrity.name +'</p><i class="icon-in new-in"></i><p class="t_clothes">'+ data[i].detail.brand.name +'</p></div><div class="author new-au"><i class="avatar"><img src="'+ data[i].author.head +'?w=30&h=30" alt=""></i><p class="a_name">'+ data[i].author.nick +'</p><p class="a_time">'+ publishDate(data[i].ans_time) +'</p></div></div></a>';
                        };
                        infPageturn.append_wrap.append(content);

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
                    },
                    complete: function(xhr, status){
                        infPageturn.loading.addClass('hide-0');
                        infPageturn.ing = false;
                    }
                });
            }
        });
    }
</script>