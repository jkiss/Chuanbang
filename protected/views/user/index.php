<!-- The user-home content -->
<section class="content" id="user_home">
    <div class="intro" data-id="<?php echo $user['id'];?>">
        <div class="avatar">
            <img src="<?php echo $user['head'];?>" alt="avatar">
        </div>
        <div class="user-data">
            <h1 class="meta-data">
                <span class="user-name"><?php echo $user['nick'];?></span>
                <i class="icon-qq account" data-type="<?php echo isset($user['type']) ? strtolower($user['type']) : '';?>"></i>
                
                <span class="location">
                    <i class="icon-man sex" data-sex="<?php echo isset($user['gender']) ? strtolower($user['gender']) : '';?>"></i>
                    <?php echo $user['region'];?>&nbsp;&nbsp;<?php echo $user['city'];?>
                </span>
            </h1>
            <h2 class="said"><?php echo $user['signature'];?></h2>
            <a href="<?php echo Yii::app()->createUrl('user/profile');?>" class="edit transition">
                编辑个人资料
            </a>
        </div>
        <div class="data-wrap">
            <p class="summary"><?php echo $user['description'];?></p>
        </div>
    </div>
    <div class="detail-right">
        <p class="r-title">关注我的人<span class="follow-nums"><?php echo $total_fans;?>个</span></p>
        <ul class="right-follow">
            <?php foreach($fans as $fan):?>
            <li>
                <a href="<?php echo Yii::app()->createUrl('user/view',array('id'=>$fan['id']));?>"><img src="<?php echo $fan['head'];?>" alt="follow1"></a>
                <p class="f-name"><?php echo $fan['nick'];?></p>
                <p class="f-info">共有<?php echo $fan['total_answer'];?>个解读</p>
            </li>
            <?php endforeach;?>
            <a href="<?php echo Yii::app()->createUrl('user/follow',array('id'=>$user['id'],'flag'=>'fans'));?>" class="more">
                <i class="icon-dots"></i>
            </a>
        </ul>
    </div>
    <div class="detail-left">
        <div class="detail-nav no-select">
            <span class="answer transition active" data-tab="A">
                <i class="slide-block"></i>
                我的解读</span>
            <span class="question transition" data-tab="Q">我的提问</span>
        </div>
        <div class="grid four sep clearfix" id="page_panel">
            <?php foreach($answers as $answer):?>
            <a href="<?php echo Yii::app()->createUrl('answer/view',array('id'=>$answer['ans_id']));?>" class="column float transition">
                <div class="image home-scale">
                    <img src="<?php echo $answer['img'];?>" alt="#">
                </div>
                <div class="bottom">
                    <p class="title text-overflow">
                        <?php echo $answer['celebrity_name'].$answer['brand_name'];?>
                    </p>
                    <p class="com-num text-overflow">
                        <?php echo $answer['total_comments'];?>条评论
                    </p>
                </div>
            </a>
            <?php endforeach;?>
        </div>
        <ul class="page-num" data-total-page="">
            <div class="num-panel">
                <span class="prev disable">
                    <i data-note="a svg"></i>
                    上一页
                </span>
                <em class="dots">...</em>
                <li class="current" data-page="1">1</li>
                <li data-page="2">2</li>
                <li data-page="3">3</li>
                <li data-page="4">4</li>
                <li data-page="5">5</li>
                <li data-page="6">6</li>
                <em class="dots">...</em>
                <span class="next">
                    <i data-note="a svg"></i>
                    下一页
                </span>
            </div>
            <!-- <div class="page-crtl">
                <li class="page-count no-li">¹²<span id="total_pages"></span>Ò³</li>
                <li class="page-text no-li">Ç°Íù&nbsp;&nbsp;<input type="text" id="spec_num" maxlength="4">&nbsp;&nbsp;Ò³</li>
                <li class="skip-to no-li disable">Ç°Íù</li>
            </div> -->
        </ul>
    </div>
</section>
<script>
    function invokeHere(Pagination, publishDate, Wheel){   // Keep script code in this func
        // Ajax Don't use event proxy
        $('.detail-left').find('a[class*="column"]').hover(function() {
            var _this = $(this);
            _this.css('background-color', '#282828').find('img').css('top', '-10px');
            _this.find('.title').css('top', '-60px');
            _this.find('.com-num').css('top', '0');
            _this.find('.com-ans-num').css('top', '0');
        }, function() {
            var _this = $(this);
            _this.css('background-color', '#fff').find('img').css('top', '0');
            _this.find('.title').css('top', '0');
            _this.find('.com-num').css('top', '60px');
            _this.find('.com-ans-num').css('top', '60px');
        });
        // Pagination
        var
            tab = $('.detail-nav').find('.active').attr('data-tab'),
            extra_data = {
                pageSize: 16
            },
            $wrap = $('.page-num').find('.num-panel'),
            $page_panel = $('#page_panel'),
            loadingHTML = '<i class="loading-1"><img src="<?php echo Yii::app()->baseUrl;;?>/images/loading-rot-full.gif" alt="loading"></i>',
        // 我的解答 pagination widget
            A_opt = {
                url: '/user/answers',
                // max_page: parseInt($('#total_pages').text()),
                range: 5,
                wrap: $('.page-num').find('.num-panel'),
                // update new page content
                updateContent: function(data, page_panel){
                    // console.log(data);
                    var content = '',
                        data = data.data;
                    for (var i = 0; i < data.length; i++) {
                        content += '<a href="'+ data[i].href +'" class="column float transition"><div class="image home-scale"><img src="'+ data[i].img +'?w=306&h=459" alt="#"></div><div class="bottom"><p class="title text-overflow">'+ data[i].celebrity_name +' '+ data[i].brand_name +'</p><p class="com-num text-overflow">'+ data[i].total_comments +'条评论</p></div></a>';
                    };
                    page_panel.html(content);
                },
                // do something before send
                beforeSend: function(){
                    // beforesend...
                    $page_panel.html(loadingHTML);
                    $('html, body').animate({scrollTop: 440}, 200);
                    // Hide pagination
                    $('.num-panel').css('display', 'none');
                },
                // do something after send complete
                sendComplete: function(){
                    // Show pagination
                    $('.num-panel').css('display', 'block');
                    // Ajax Don't use event proxy
                    $('.detail-left').find('a[class*="column"]').hover(function() {
                        var _this = $(this);
                        _this.css('background-color', '#282828').find('img').css('top', '-10px');
                        _this.find('.title').css('top', '-60px');
                        _this.find('.com-num').css('top', '0');
                        _this.find('.com-ans-num').css('top', '0');
                    }, function() {
                        var _this = $(this);
                        _this.css('background-color', '#fff').find('img').css('top', '0');
                        _this.find('.title').css('top', '0');
                        _this.find('.com-num').css('top', '60px');
                        _this.find('.com-ans-num').css('top', '60px');
                    });
                }
            },
            A_pageXHR = new Pagination(A_opt),
        // 我的提问 pagination widget
            Q_opt = {
                url: '/user/questions',
                // max_page: parseInt($('#total_pages').text()),
                range: 5,
                wrap: $('.page-num').find('.num-panel'),
                // update new page content
                updateContent: function(data, page_panel){
                    // console.log(data);
                    var content = '',
                        data = data.data;
                    for (var i = 0; i < data.length; i++) {
                        content += '<a href="'+ data[i].href +'" class="column float transition"><div class="image home-scale"><img src="'+ data[i].img +'?w=306&h=459" alt="#"></div><div class="bottom"><p class="title text-overflow">'+ publishDate(data[i].ques_time) +'上传</p><p class="com-ans-num">'+ data[i].total_comments +'条评论<br>'+ data[i].total_answers +'条解读</p></div></a>';
                    };
                    page_panel.html(content);
                },
                // do something before send
                beforeSend: function(){
                    // beforesend...
                    $page_panel.html(loadingHTML);
                    $('html, body').animate({scrollTop: 440}, 200);
                    // Hide pagination
                    $('.num-panel').css('display', 'none');
                },
                // do something after send complete
                sendComplete: function(){
                    // Show pagination
                    $('.num-panel').css('display', 'block');
                    // Ajax Don't use event proxy
                    $('.detail-left').find('a[class*="column"]').hover(function() {
                        var _this = $(this);
                        _this.css('background-color', '#282828').find('img').css('top', '-10px');
                        _this.find('.title').css('top', '-60px');
                        _this.find('.com-num').css('top', '0');
                        _this.find('.com-ans-num').css('top', '0');
                    }, function() {
                        var _this = $(this);
                        _this.css('background-color', '#fff').find('img').css('top', '0');
                        _this.find('.title').css('top', '0');
                        _this.find('.com-num').css('top', '60px');
                        _this.find('.com-ans-num').css('top', '60px');
                    });
                }
            },
            Q_pageXHR = new Pagination(Q_opt);

        // init, the default page is 我的解答
        A_pageXHR.init(function(){
            A_pageXHR.xhrPage(A_pageXHR.paged, $page_panel, extra_data);
        });

        $('.num-panel').on('click', 'li, span', function(event) {
            event.preventDefault();
            var $target = $(event.target);
            // console.log($target.is('li, span'));

            if(!($target.hasClass('current') || $target.hasClass('disable'))){
                if($target.is('li')){
                    // get page num from <li>
                    tab === 'A'
                        ? A_pageXHR.paged = parseInt($target.attr('data-page'))
                        : Q_pageXHR.paged = parseInt($target.attr('data-page'));
                }else if($target.is('span')){
                    // get page num from <span>, Judge it's prev page or next page
                    if(tab === 'A'){
                        $target.hasClass('prev') ? A_pageXHR.paged -= 1 : A_pageXHR.paged += 1;
                    }else{
                        $target.hasClass('prev') ? Q_pageXHR.paged -= 1 : Q_pageXHR.paged += 1;
                    }
                }
                // request pagination
                tab === 'A'
                    ? A_pageXHR.xhrPage(A_pageXHR.paged, $page_panel, extra_data)
                    : Q_pageXHR.xhrPage(Q_pageXHR.paged, $page_panel, extra_data);
            }
        });

        // 我的解读 & 我的提问 tab switch event
        $('.detail-nav').on('click', 'span', function(event) {
            var $target = $(event.target),
                $sib = $target.siblings(),
                $slide_block = $('.slide-block');

            if(!$target.hasClass('active')){
                $target.addClass('active');
                $sib.removeClass('active');

                tab = $target.attr('data-tab');
                // request pagination
                tab === 'A'
                    ? A_pageXHR.xhrPage(A_pageXHR.paged, $page_panel, extra_data)
                    : Q_pageXHR.xhrPage(Q_pageXHR.paged, $page_panel, extra_data);

                // move slide-block
                console.log(tab);
                tab === 'A'
                    ? $slide_block.animate({'left': '0'}, 300)
                    : $slide_block.animate({'left': '140px'}, 300);
            }
        });
    }
</script>