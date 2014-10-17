<!-- The brand view page -->
<section class="content" id="b_s_detail">
    <div class="intro" data-id="<?php echo $model['id'];?>">
        <div class="ava-wrap">
            <img src="<?php echo $model['logo'];?>" alt="Avatar" class="avatar">
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

    <?php if(!empty($product)):?>
    <div class="show">
        <a href="<?php echo Yii::app()->createUrl('brand/product', array('id'=>$model['id']));?>">
            <div class="more">
                更多<i class="icon-dots"></i>
            </div>
        </a>
        <p class="title">秀场</p>
        <h1><?php echo $product['name'];?></h1>
        <div class="grid sep nine clearfix">
            <?php foreach($product['images'] as $image):?>
            <a href="javascript:;" class="column float transition">
                <div class="img-wrap scale-1">
                    <img src="<?php echo $image;?>?w=124&h=185" alt="show" class="transition">
                </div>
            </a>
            <?php endforeach;?>
        </div>
    </div>
    <?php endif;?>

    <div class="detail-right">
        <?php if(count($designers) > 0):?>
        <p>设计师</p>
        <?php foreach($designers as $designer):?>
        <div class="designer one">
            <div class="img-wrap f-i-w">
                <img src="<?php echo $designer['avatar'];?>?w=80&h=80" alt="designer">
            </div>
            <p class="cn"><?php echo $designer['name_cn'];?></p>
            <p class="en"><?php echo $designer['name_en'];?></p>
        </div>
        <?php endforeach;?>
        <a href="<?php echo Yii::app()->createUrl('brand/designer', array('id'=>$model['id']));?>">
            <div class="more">
                更多<i class="icon-dots"></i>
            </div>
        </a>
        <?php endif;?>

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
        <a href="<?php echo Yii::app()->createUrl('brand/topic', array('id'=>$model['id']));?>">
            <div class="more">
                更多<i class="icon-dots"></i>
            </div>
        </a>
        <?php endif;?>

        <?php if(count($celebrities) > 0):?>
        <p>相关名人（<?php echo $model['total_celes'];?>）</p>
        <?php foreach($celebrities as $cele):?>
        <div class="brand one">
            <div class="img-wrap b-i-w">
                <a href="<?php echo Yii::app()->createUrl('celebrity/view', array('id'=>$cele['id']));?>"><img src="<?php echo $cele['head'];?>?w=80&h=80" alt=""></a>
            </div>
            <p class="cn"><em><?php echo $cele['name_cn'];?></em></p>
            <p class="en"><em><?php echo $cele['name_en'];?></em></p>
        </div>
        <?php endforeach;?>
        <a href="<?php echo Yii::app()->createUrl('brand/celebrity', array('id'=>$model['id']));?>">
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
                <a href="<?php echo Yii::app()->createUrl('user/view', array('id'=>$fan['id']));?>"><img src="<?php echo $fan['head'];?>?w=60&h=60" alt="fans-avatar"></a>
            </div>
            <p class="name"><?php echo $fan['nick'];?></p>
            <p class="ans">共有<?php echo $fan['total_qa'];?>个解读</p>
        </div>
        <?php endforeach;?>
        <a href="<?php echo Yii::app()->createUrl('brand/fan', array('id'=>$model['id']));?>">
            <div class="more" style="border:0">
                更多<i class="icon-dots"></i>
            </div>
        </a>
        <?php endif;?>
    </div>
    <div class="detail-left">
        <div class="detail-nav">
            品牌动态
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
                                <?php echo $answer['celebrity_name'];?>
                            </p>
                        </div>
                        <div class="author">
                            <?php echo $answer['total_comments'];?>条评论
                        </div>
                    </div>
                </a>
            <?php endforeach;?>
        </div>
    </div>
</section>
<script>
    function invokeHere(Pagination){
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
                        url: '/brand/follow',
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
                        url: '/brand/unfollow',
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