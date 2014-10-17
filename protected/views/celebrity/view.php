<!-- The b_s_detail page -->
<section class="content" id="b_s_detail">
    <div class="intro" data-id="<?php echo $model['id'];?>">
        <div class="ava-wrap">
            <img src="<?php echo $model['head'];?>?w=260&h=260" alt="Avatar" class="avatar">
        </div>
        <p class="star-name">
            <span class="cn"><?php echo $model['name_cn'];?></span>
            <span class="en"><?php echo $model['name_en'];?></span>
        </p>
        <p class="summary">
            <?php echo $model['description'];?>
        </p>
        <p class="detail-control">
            <?php if($model['follow'] == 'N'):?>
            <span class="fo-btn no transition">
                <i class="icon-follow-1"></i>
                <span class="text">加关注</span>
            </span>
            <?php else:?>
            <span class="fo-btn yes transition">
                <i class="icon-yes"></i>
                <span class="text">已关注</span>
            </span>
            <?php endif;?>
        </p>
    </div>
    <div class="detail-right">
        <?php if(count($topics) > 0):?>
        <p>相关活动（<?php echo $model['total_topics'];?>）</p>
        <?php foreach($topics as $tp):?>
        <div class="activity one">
            <div class="img-wrap">
                <a href="<?php echo Yii::app()->createUrl('topic/view', array('id'=>$tp['id']));?>"><img src="<?php echo $tp['cover'].'?w=140&h=102';?>" alt="Activity"></a>
            </div>
            <p class="a_info"><?php echo $tp['title'];?></p>
        </div>
        <?php endforeach;?>
        <a href="<?php echo Yii::app()->createUrl('celebrity/topics',array('id'=>$model['id']));?>">
            <div class="more">
                更多<i class="icon-dots"></i>
            </div>
        </a>
        <?php endif;?>

        <?php if(count($brands) > 0):?>
        <p>相关品牌（<?php echo $model['total_brands'];?>）</p>
        <?php foreach($brands as $brand):?>
        <div class="brand one">
            <div class="img-wrap b-i-w">
                <a href="<?php echo Yii::app()->createUrl('brand/'.$brand['id']);?>"><img src="<?php echo $brand['logo'];?>?w=80&h=80" alt=""></a>
            </div>
            <p class="cn"><em><?php echo $brand['name_cn'];?></em></p>
            <p class="en"><em><?php echo $brand['name_en'];?></em></p>
        </div>
        <?php endforeach;?>
        <a href="<?php echo Yii::app()->createUrl('celebrity/brands', array('id'=>$model['id']));?>">
            <div class="more">
                更多<i class="icon-dots"></i>
            </div>
        </a>
        <?php endif;?>

        <?php if(count($fans) > 0):?>
        <p>粉丝（<?php echo $model['total_fans'];?>）</p>
        <?php foreach($fans as $fan):?>
        <div class="fans one">
            <div class="img-wrap f-i-w">
                <img src="<?php echo $fan['head'];?>" alt="fans-avatar">
            </div>
            <p class="name"><?php echo $fan['nick'];?></p>
            <p class="ans">共有<?php echo $fan['total_qa'];?>个解读</p>
        </div>
        <?php endforeach;?>
        <a href="<?php echo Yii::app()->createUrl('celebrity/fans', array('id'=>$model['id']));?>">
            <div class="more" style="border:0">
                更多<i class="icon-dots"></i>
            </div>
        </a>
        <?php endif;?>
    </div>
    <div class="detail-left">
        <div class="detail-nav">
            名人动态
        </div>
        <div class="grid four sep clearfix" id="page_panel" data-max-page="<?php echo $total_page;?>">
            <?php foreach($answers as $answer):?>
            <a class="column float transition" href="<?php echo Yii::app()->createUrl('answer/'.$answer['ans_id']);?>">
                <div class="image scale-1">
                    <img src="<?php echo $answer['img'];?>?w=226&h=339" alt="img" data-img="">
                </div>
                <div class="bottom-1">
                    <div class="title">
                        <i class="icon-in"></i>
                        <p class="t_clothes">
                            <?php echo $answer['brand_name'];?> <?php echo $answer['clothes_type'];?> <?php echo $answer['style'];?>
                        </p>
                    </div>
                    <div class="author">
                        <?php echo $answer['total_comments'];?>条评论
                    </div>
                </div>
            </a>
            <?php endforeach;?>
        </div>
        <!-- <ul class="page-num" data-total-page="">
            <div class="num-panel">
                <span class="prev disable">
                    <i class="icon-dir-left"></i>
                    上一页
                </span>
                <em class="dots"><i class="icon-dots"></i></em>
                <li class="current" data-page="1">1</li>
                <li data-page="2">2</li>
                <li data-page="3">3</li>
                <li data-page="4">4</li>
                <li data-page="5">5</li>
                <li data-page="6">6</li>
                <em class="dots"><i class="icon-dots"></i></em>
                <span class="next">
                    下一页
                    <i class="icon-dir-right"></i>
                </span>
            </div>
        </ul> -->
    </div>
</section>
<script>
    function invokeHere(Pagination, publishDate, Wheel){
        // new animate event
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

        // Follow event
        $('.fo-btn').hover(function() {
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
                id = $('.intro').attr('data-id'),
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