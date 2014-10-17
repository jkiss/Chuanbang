<script>
    var page="answer-explore";
</script>
<input type="hidden" id="answer_detail_id" value="<?php echo $answer->details[0]->id;?>"/>
<div id="cb-main" class="ui segment cb-letter">
<div class="ui grid cb-content ">
<div class="row cb-compareList cb-answer">
<div class="column cb-leftList">
<div class="cb-answerPic">
    <?php $pictures = $question->pictures;?>
    <?php $total = count($pictures);?>
    <div id="cb-bigPic">
        <ul id="thumbnails">
            <?php foreach($pictures as $pic):?>
            <li data-id="<?php echo $pic->id;?>"><img src="<?php echo $pic->img;?>"></li>
            <?php endforeach;?>
        </ul>

        <i class="icon square cb-icon icon-search cb-bigPhoto" id="cb-bigger"></i>
        <i class="icon circular cb-icon icon-compare cb-addCompare"></i>
        <i class="triangle link left icon"></i>
        <i class="triangle link right icon"></i>
    </div><!--bigPic-->
    <div class="ui items thumb-box" id="cb-thumbPic">
        <div class="row thumbs">
            <?php for($i = 1; $i <= $total; $i++):?>
            <a class="item itemImg" href="#<?php echo $i;?>" data-slide="<?php echo $i;?>">
                <img class="ui image" src="<?php echo $pictures[$i-1]->img;?>">
            </a>
            <?php endfor;?>
            <?php if($total >= 10):?>
            <a class="item">
                <img class="ui image" src="">
            </a>
            <?php endif;?>
        </div>
    </div><!--thumbPic-->
</div>
<div class="ui basic segment">第<span class="cb-imgCur">1</span>张，共<span class="cb-imgTotal">7</span>张</div>

<!-- 支持最多答案 -->
<div class="cb-answerTitle clearfix">
    <div class="ui label cb-iconLabel"><i class="icon-upOver cb-icon "></i><br><?php echo $answer->supports;?></div>
    <div class="content">
        <a class="author">
            <img class="ui image" src="<?php echo $answer->author->head;?>">
            <?php echo $answer->author->nick;?>
        </a>
        <div class="header"><a href="<?php echo Yii::app()->request->baseUrl.'/answer/'.$answer->id;?>"><?php echo $answer->details[0]->star->name;?>身着<?php echo $answer->details[0]->brand->name;?></a></div>
        <?php if($answer->topic !== null):?>
        <?php $times = explode('-', $answer->topic->occurdate);?>
        <div class="ui horizontal list cb-answerInfo">
            <div class="item">
                <?php if(count($times) >= 3):?>
                <?php echo $times[0];?>年<?php echo $times[1];?>月<?php echo $times[2];?>日
                <?php endif;?>
            </div>
            <div class="item"><?php echo $answer->topic->place;?></div>
            <div class="item"><?php echo $answer->topic->title;?></div>
        </div><!--info-->
        <?php endif;?>
        <div class="description"><?php echo $answer->content;?></div>
        <div class="extra images">
            <?php foreach($answer->pictures as $pic):?>
            <img src="<?php echo $pic->img;?>" class="cb-bigPhoto" width="78px" height="78px">
            <?php endforeach;?>
        </div>
    </div>
</div>
<div class="ui right aligned basic segment">
    <div class="ui horizontal list cb-answerInfo">
        <div class="item">
            <i class="icon cb-icon icon-answer-rec"></i>
            <div class="content">
                <div class="description">共<?php echo count($answer->comments);?>条评论</div>
            </div>
        </div>
        <div class="item cb-fav-answer" data-id="<?php echo $answer->id;?>" fav="<?php echo in_array($answer->id, $my_ans_ids)?'y':'n';?>">
            <i class="icon cb-icon icon-collection"></i>
            <div class="content">
                <div class="description <?php echo in_array($answer->id, $my_ans_ids) ? 'hide' : '';?>">收藏</div>
                <div class="description <?php echo !in_array($answer->id, $my_ans_ids) ? 'hide' : '';?>">取消</div>
            </div>
        </div>
        <div class="item cb-share">
            <i class="icon cb-icon icon-share"></i>
            <div class="content">
                <div class="description">分享</div>
            </div>
        </div>
    </div>
</div>

<!-- 关联品牌 -->
<div class="ui basic segment cb-linkBrand">
    <div class="ui pointing right label">
        <i class="icon cb-icon icon-link"></i><br>
        关联品牌
    </div>
    <?php foreach($answer->details as $detail):?>
    <a href="" class="ui image">
        <img src="<?php echo $detail->brand->logo;?>" data-pinit="registered">
    </a>
    <?php endforeach;?>

</div><!--关联品牌-->

<div class="ui tabular menu tiny dividing header cb-tab2">
    <a class="active item" data-tab="cb-tabRow3">
        答案(<span><?php echo $total_answers;?></span>)
    </a>
    <a class="item" data-tab="cb-tabRow4">
        统计(<span>26</span>)
    </a>
    <a class="item" data-tab="cb-tabRow5">
        关注(<span><?php echo $question->followings;?></span>)
    </a>
</div><!--tab-->
<div id="cb-tabRow3" class="ui tab2 active" data-tab="cb-tabRow3">
    <div class="ui tiny dividing header">
        <a href="" class="item" >
            <i class="icon cb-icon icon-byTime"></i>
            时间排序
        </a>
        <a href="" class="item">
            <i class="icon cb-icon icon-byVote"></i>
            票数排序
        </a>
    </div><!--header-->
    <div class="ui feed basic segment cb-ansList">
        <?php foreach($answers_time as $answer):?>
        <div class="event">
            <div class="ui label cb-iconLabel"><i class="cb-icon icon-upOver"></i><br><?php echo $answer->supports;?></div>
            <div class="content">
                <a class="author">
                    <img class="ui image" src="<?php echo $answer->author->head;?>">
                    <?php echo $answer->author->nick;?>
                </a>
                <div class="header"><a href="<?php echo Yii::app()->request->baseUrl.'/answer/'.$answer->id;?>"><?php echo $answer->details[0]->star->name;?>身着<?php echo $answer->details[0]->brand->name;?></a></div>
                <?php if($answer->topic !== null):?>
                    <?php $times = explode('-', $answer->topic->occurdate);?>
                    <div class="ui horizontal list cb-answerInfo">
                        <div class="item">
                            <?php if(count($times) >= 3):?>
                            <?php echo $times[0];?>年<?php echo $times[1];?>月<?php echo $times[2];?>日
                            <?php endif;?>
                        </div>
                        <div class="item"><?php echo $answer->topic->place;?></div>
                        <div class="item"><?php echo $answer->topic->title;?></div>
                    </div><!--info-->
                <?php endif;?>
                <div class="description"><?php echo $answer->content;?></div>
                <div class="extra images">
                    <?php foreach($answer->pictures as $pic):?>
                        <img src="<?php echo $pic->img;?>" class="cb-bigPhoto" width="78px" height="78px">
                    <?php endforeach;?>
                </div>
                <div class="ui horizontal list cb-answerInfo">
                    <div class="item">
                        <i class="icon cb-icon icon-answer-rec"></i>
                        <div class="content">
                            <div class="description">共<?php echo count($answer->comments);?>条评论</div>
                        </div>
                    </div>
                    <div class="item cb-fav-answer" data-id="<?php echo $answer->id;?>" fav="<?php echo in_array($answer->id, $my_ans_ids)?'y':'n';?>">
                        <i class="icon cb-icon icon-collection"></i>
                        <div class="content">
                            <div class="description <?php echo in_array($answer->id, $my_ans_ids) ? 'hide' : '';?>">收藏</div>
                            <div class="description <?php echo !in_array($answer->id, $my_ans_ids) ? 'hide' : '';?>">取消</div>
                        </div>
                    </div>
                    <div class="item cb-share">
                        <i class="icon cb-icon icon-share"></i>
                        <div class="content">
                            <div class="description">分享</div>
                        </div>
                    </div>
                </div><!--imgInfo-->
            </div>
        </div>
        <?php endforeach;?>
    </div><!--list-->
</div>
<!--tabCon-->
<div id="cb-tabRow4" class="ui tab2" data-tab="cb-tabRow4">统计</div>
<div id="cb-tabRow5" class="ui tab2" data-tab="cb-tabRow5">关注</div>
<div class="ui fluid icon button huge cb-btn cb-addAnswer cb-popup">
    <i class="icon cb-icon icon-answerAdd"></i>
    添加我的答案

</div>
<!--addAnswer-->
<!--accordion-->
</div><!--left-->
<div class="ui label" id="cb-showCompare">
    <i class="icon cb-icon icon-compare"></i><span class="cb-compare-count">0</span>
</div>
<div id="cb-compare" class="ui column sidebar right cb-sidebar cb-sidebar-right">
    <h4 class="ui top attached tiny header">
        <i class="icon cb-icon icon-close cb-closeCompare"></i>
        <i class="icon cb-icon icon-compare"></i>
        对比(<span class="cb-compare-count">0</span>)
    </h4>
    <div class="ui segment attached">
        <div class="ui selection list cb-compare-draft">
        </div><!--add-compareList-->
        <a href="<?php echo Yii::app()->request->baseUrl.'/compare/apply';?>" class="ui huge button cb-btn">开始比较</a>
    </div>
</div><!--add-compare-->
</div>

</div>

<div class="ui modal cb-modal" id="cb-share">
    <i class="icon-close cb-icon close"></i>
    <div class="ui tabular menu cb-shareTab">
        <a class="active item" data-tab="cb-shareTab1">
            微博分享
        </a>
        <a class="item" data-tab="cb-shareTab2">
            邮件分享
        </a>
        <a class="item" data-tab="cb-shareTab3">
            站内信分享
        </a>
    </div><!--tab-->
    <div class="ui tab share-active content" data-tab="cb-shareTab1">
        <div class="image">
            <img src="/page/explore/brand/img/4.png">
        </div>
        <div class="actions" id="cb-main">
            <div class="cb-splicing">
                <p>选择图片并拼接</p>
                <i class="icon cb-icon icon-splicing1"></i>
                <i class="icon cb-icon icon-splicing2"></i>
            </div>
            <div class="cb-shareIcon">
                <p>分享至</p>
                <i class="icon cb-icon icon-weibo"></i><i class="icon cb-icon icon-qqweibo"></i><i class="icon cb-icon icon-wechat"></i><i class="icon cb-icon icon-qq"></i>
            </div>

            <form class="ui reply form">
                <div class="field">
                    <textarea row="1" placeholder="输入评论..."></textarea>
                </div>
                <a href="" class="ui cb-del button">取消</a><a href="" class="ui button cb-btn">提交</a>
            </form>
        </div>
    </div>
    <div class="ui tab content" data-tab="cb-shareTab2">
        <div class="image">
            <img src="/page/explore/brand/img/4.png">
        </div>
        <div id="cb-main">
            <div class="ui form">
                <div class="inline field">
                    <label>发给:</label>
                    <input style="width:80%;" type="email" placeholder="邮件地址">
                </div>
                <div class="inline field">
                    <label>内容:</label>
                    <textarea style="width:80%;">内容</textarea>
                </div>
                <a href="" class="ui cb-del button">取消</a><a href="" class="ui button cb-btn">提交</a>
            </div>
        </div>
    </div>
    <div class="ui tab content" data-tab="cb-shareTab3">
        <div class="image">
            <img src="/page/explore/brand/img/4.png">
        </div>
        <div id="cb-main">
            <div class="ui form">
                <div class="inline field">
                    <label>发给:</label>
                    <input style="width:80%;" type="text" placeholder="搜索用户">
                </div>
                <div class="inline field">
                    <label>内容:</label>
                    <textarea style="width:80%;">内容</textarea>
                </div>
                <a href="" class="ui cb-del button">取消</a><a href="" class="ui button cb-btn">提交</a>
            </div>
        </div>
    </div>
</div>
<div class="ui modal cb-modal" id="cb-popup">
    <i class="icon-close cb-icon close"></i>
    <div class="ui content">
        <div class="image">
            <h3 class="ui header">图片列表</h3>

            <img src="/page/explore/brand/img/8.png" alt="" />

            <img src="/page/explore/brand/img/8.png" alt="" />

            <img src="/page/explore/brand/img/8.png" alt="" />

            <img src="/page/explore/brand/img/8.png" alt="" />

            <img src="/page/explore/brand/img/8.png" alt="" />

            <img src="/page/explore/brand/img/8.png" alt="" />

            <img src="/page/explore/brand/img/8.png" alt="" />

            <img src="/page/explore/brand/img/8.png" alt="" />

            <img src="/page/explore/brand/img/8.png" alt="" />

        </div>
        <div id="cb-main">
            <h3 class="ui header">添加答案</h3>
            <div class="cb-changeId">
                <strong>袁艳伯</strong> 切换为 匿名
                <i class="icon cb-icon icon-idAnonymous"></i>
            </div>
            <div class="ui form">
                <div class="cb-moreInfo">
                    <div class="field">
                        <h3 class="ui dividing header cb-changeTit">
                            <input type="text" placeholder="刘德华穿Prada"> <a class="ui button cb-btn">修改</a>
                        </h3>
                    </div>
                    <div class="field">
                        <input placeholder="人名" type="text">
                    </div>
                    <div class="field">
                        <input placeholder="品牌" type="text">
                    </div>
                    <div class="field">
                        <input placeholder="分类" type="text">
                    </div>
                    <div class="field">
                        <div class="ui selection dropdown">
                            <input type="hidden" name="gender">
                            <div class="text">款式</div>
                            <i class="dropdown icon"></i>
                            <div class="menu">
                                <div class="item" data-value="1">款式</div>
                                <div class="item active" data-value="0">款式</div>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <input placeholder="时间" type="text">
                    </div>
                    <div class="field">
                        <input placeholder="地点" type="text">
                    </div>
                    <div class="field">
                        <input placeholder="事件" type="text">
                    </div>
                </div>
                <div class="cb-btnList clearfix">
                    <a class="ui button tiny cb-btn cb-showMore"><i class="icon cb-icon icon-add"></i>添加更多详情</a>
                    <a class="ui button tiny cb-btn cb-closeMore"><i class="icon cb-icon icon-deleteCompare"></i>取消更多详情</a>
                    <a class="ui button tiny cb-btn">添加其他人</a>
                </div>
                <h4 class="ui top attached header">
                    <i class="photo icon"></i>对比图片
                </h4>
                <textarea class="ui segment attached"></textarea>
                <a href="" class="ui button cb-btn cb-submit">确认</a>
            </div>
        </div>
    </div>
</div>
<div class="ui modal cb-modal" id="cb-popup2">
    <i class="icon-close cb-icon close"></i>
    <div class="ui content">
        <div class="left">
            <h3 class="ui header">图片列表</h3>

            <img src="/page/explore/brand/img/8.png" alt="" />

            <img src="/page/explore/brand/img/8.png" alt="" />

            <img src="/page/explore/brand/img/8.png" alt="" />

            <img src="/page/explore/brand/img/8.png" alt="" />

            <img src="/page/explore/brand/img/8.png" alt="" />

            <img src="/page/explore/brand/img/8.png" alt="" />

            <img src="/page/explore/brand/img/8.png" alt="" />

            <img src="/page/explore/brand/img/8.png" alt="" />

            <img src="/page/explore/brand/img/8.png" alt="" />

        </div>
        <div class="right actions cb-letter" id="cb-add">
            <h3 class="ui header">添加答案</h3>
            <div class="cb-changeId">
                <strong>袁艳伯</strong> 切换为 匿名
                <i class="icon cb-icon icon-idAnonymous"></i>
            </div>
            <div class="ui basic segment cb-popup close">取消更多详情<i class="icon cb-icon icon-close"></i></div>
            <div class="ui form">
                <div class="field">
                    <h3 class="ui dividing header cb-changeTit">
                        <input type="text" placeholder="刘德华穿Prada"> <a class="ui button cb-btn">修改</a>
                    </h3>
                </div>
                <div class="field">
                    <input placeholder="人名" type="text">
                </div>
                <div class="field">
                    <input placeholder="品牌" type="text">
                </div>
                <div class="field">
                    <input placeholder="分类" type="text">
                </div>
                <div class="field">
                    <div class="ui selection dropdown">
                        <input type="hidden" name="gender">
                        <div class="text">款式</div>
                        <i class="dropdown icon"></i>
                        <div class="menu">
                            <div class="item" data-value="1">款式</div>
                            <div class="item active" data-value="0">款式</div>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <input placeholder="时间" type="text">
                </div>
                <div class="field">
                    <input placeholder="地点" type="text">
                </div>
                <div class="field">
                    <input placeholder="事件" type="text">
                </div>
                <a href="" class="ui button cb-btn cb-submit">确认</a>
                <div class="ui divider"></div>
                <a class="ui button tiny cb-btn">添加其他人</a>
            </div>
        </div>
    </div>
</div>
<div class="ui modal cb-modal" id="cb-bigPhoto">
    <i class="icon-close cb-icon close"></i>
    <div class="ui content">
        <div class="image">
            <img src="/page/explore/brand/img/4.png" style="height:774px;">
            <div class="info">
                <div class="ui left floated basic segment">
                    第一张,共9张
                </div>
                <div class="ui right floated basic segment text menu">
                    <div class="item">
                        <i class="icon icon-vote cb-icon"></i>
                        喜欢
                    </div>
                    <div class="item cb-share">
                        <i class="icon icon-share cb-icon"></i>
                        分享
                    </div>
                </div>
            </div>
        </div>
        <div class="actions cb-letter" id="cb-main">
            <h2 class="ui header">评论</h2>
            <div class="ui comments cb-comments">

                <div class="comment">
                    <a class="avatar">
                        <img src="/page/explore/brand/img/3.png">
                        <em class="author">马二甲</em>
                    </a>
                    <div class="content">
                        <div class="metadata">
                            <span class="date">发表于 8小时前 </span>
                        </div>
                        <div class="text">话粗理不粗啊!</div>
                    </div>
                </div>

                <div class="comment">
                    <a class="avatar">
                        <img src="/page/explore/brand/img/3.png">
                        <em class="author">马二甲</em>
                    </a>
                    <div class="content">
                        <div class="metadata">
                            <span class="date">发表于 8小时前 </span>
                        </div>
                        <div class="text">话粗理不粗啊!</div>
                    </div>
                </div>

                <div class="comment">
                    <a class="avatar">
                        <img src="/page/explore/brand/img/3.png">
                        <em class="author">马二甲</em>
                    </a>
                    <div class="content">
                        <div class="metadata">
                            <span class="date">发表于 8小时前 </span>
                        </div>
                        <div class="text">话粗理不粗啊!</div>
                    </div>
                </div>

                <form class="ui reply form">
                    <div class="field">
                        <textarea row="1" placeholder="输入评论..."></textarea>
                    </div>
                    <a href="" class="ui button cb-btn">确认</a>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="cb-goTop" class="cb-red"><i class="square inverted triangle up big icon"></i></div>
<div class="ui text menu" id="cb-footer">
    <div class="left floated item cb-footer-logo">
        <img src="/page/footer/img/footer.png">
        <span>京ICP证100054号  ©2013</span>
    </div>
    <div class="right floated item">
        <a href="/page/about.html" class="item">
            关于我们
        </a>
        <a href="/page/help.html" class="item">
            使用帮助
        </a>
        <a href="/page/contact.html" class="item">
            联系我们
        </a>
        <a href="/page/feedback.html" class="item">
            服务条款
        </a>
    </div>
</div>

<!--尾部-->
</div>
<script src="http://cdn.bootcss.com/jquery/1.10.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="/style/base/jquery/jquery.min.js"><\/script>')</script>
<script src="/style/base/sui/javascript/semantic.js"></script>
<script src="/style/base/sui/javascript/jquery.address.js"></script>
<script src="/style/base/slippry/slippry.min.js"></script>
<script>
$(function(){
    //browser
    var browserMozilla = /firefox/.test(navigator.userAgent.toLowerCase());
    var browserWebkit = /webkit/.test(navigator.userAgent.toLowerCase());
    var browserOpera = /opera/.test(navigator.userAgent.toLowerCase());
    var browserMsie = /msie/.test(navigator.userAgent.toLowerCase());
    if(browserMozilla||browserMsie){
        $('.cb-hotList .item .content').css({
            border:'1px solid #fff',
            borderWidth:'0 0 0 1.3em'

        });
    }

    //img load
    $(window).bind("load",function(){
        $('.cb-itemHover .image img').each(function(){
            $(this).attr('src',$(this).attr('data-img'));
        });
    })

    //slippry
    jQuery('#topicScroll').slippry({
        slippryWrapper: '<div id="cb-topicScroll" />'
    });
    //thumbnails
    var thumbs = $('#thumbnails').slippry({
        // general elements & wrapper
        slippryWrapper: '<div class="cb-answerScroll thumbnails" />',
        // options
        transition: 'horizontal',
        pager: false,
        auto: false,
        onSlideBefore: function (el, index_old, index_new) {
            $('.thumbs a').removeClass('active');
            $($('.thumbs a')[index_new]).addClass('active');
            $('.cb-imgCur').text(index_new+1);
            $('.cb-imgTotal').text($('.thumbs .itemImg').length);
            scroll('#cb-thumbPic','.triangle.left','.triangle.right',96,'.thumbs',7);
        }
    });

    $('.thumbs a').click(function () {
        thumbs.goToSlide($(this).data('slide'));
        return false;
    });
    function scroll(el,left,right,width,thumb,num){
        var defualtItem=parseInt(($(el).width())/($(el).find('.item').width()));
        var rowItem=parseInt(($(el).width())/($(el).find('.item').width()));
        var itemNum=$(el).find('.item').length;
        var leftArrow=$(left);
        var rightArrow=$(right);
        var leftDefualt=-width;
        var leftWidth=0;
        var maxLeft=leftDefualt*(itemNum-rowItem);
        if($(thumb).find('a').length>num){
            $(right).show();
        }
        rightArrow.click(function(){
            leftWidth=leftDefualt+leftWidth;
            if(leftWidth>=maxLeft){
                $(el).find('.row').css('left',leftWidth+'px');
                $(left).show();
            }else{
                $(el).find('.row').css('left',0);
                $(left).hide();
                leftWidth=0;
            }
        });
        leftArrow.click(function(){
            if(leftWidth<0){
                leftWidth=-leftDefualt+leftWidth;
                $(el).find('.row').css('left',leftWidth+'px');
                if(leftWidth==0){
                    $(left).hide();
                }
            }
        });
    }



    //sidebar
    $('.cb-sidebar-right')
        .sidebar({
            overlay: true
        })
        .sidebar('attach events', '.cb-addCompare,#cb-showCompare', 'show')
        .sidebar('attach events', '.cb-closeCompare', 'hide')
    ;
//    $('.cb-addCompare').click(function(){
//        $(this).addClass('selected');
//    });

    $('.cb-mainMenu>.item').each(function(){
        var dataNav=$(this).attr('data-nav');
        var dataRegExp=new RegExp(dataNav,'i');
        if (!!dataNav && dataRegExp.test(page)) {
            $(this).addClass('active');
        };
    });

    $('.cb-sidebar').each(function(i){
        var j=i+1;
        var targ=$(this).attr('id');
        var targRegExp=new RegExp(targ,'i');
        $(this).find('.item').each(function(){
            var dataId=$(this).attr('data-id');
            var dataRegExp=new RegExp(dataId,'i');
            if(!!dataId && dataRegExp.test(page)){
                $(this).addClass('active');
            }
        });
        if(page=='answer-explore' && $('.cb-mainMenu .item').attr('data-nav','explore').hasClass('active')){
            $('#cb-main').css('margin-left','335px');
        }
        if(/footlink/i.test(page)){
            $('.cb-side').hide();
        }
        if(targRegExp.test(page)){
            $('#'+targ)
                .sidebar('show')
            ;
            if($('.open'+j).length>0){
                $('.cb-sidebar'+j)
                    .sidebar('attach events', '.open'+j, 'show')
                ;

            }
            $('.cb-sidebar'+j).first()
            ;
        }else{
            if($('.open'+j).length>0){
                $('.cb-sidebar'+j)
                    .sidebar('attach events', '.open'+j, 'show')
                ;

            }
            $('.cb-sidebar'+j).first()
            ;

        }
        $('.open'+j)
            .removeClass('disabled')
        ;
    });
    $('.cb-mainMenu > .open').click(function(){
        if(!$(this).hasClass('active')){
            $(this).toggleClass('active').siblings().removeClass('active');
        }
    });

    //show comapre
    $('#cb-showCompare').click(function(){
        $(this).hide();
    });
    $('.cb-closeCompare').click(function(){
        $('#cb-showCompare').show();
    });
    //tabs
    if($('.cb-tab').length>0){}
    $('.cb-tab .item').tab();
    $('.cb-tab2').hover(function(){
        $('.cb-tab2 .item').tab({
            selector : {
                tabs : '.ui.tab2'
            }
        });
    },function(){
        $('.cb-tab .item').tab();
    });
    $('.cb-shareTab .item').click(function(){
        $(this).addClass('active').siblings().removeClass('active');
        var targ=$(this).attr('data-tab');
        $(".ui.tab[data-tab="+targ+"]").addClass('share-active').siblings().removeClass('share-active');
    });


    //goTop
    function goTop(){
        if($(window).height() < $('#cb-goTop').offset().top) {
            $('#cb-goTop').css({'opacity':1,'right':0});
        }else{
            $('#cb-goTop').css({'opacity':0,'right':-50+'px'});
        }
    };
    goTop();
    $(window).on('scroll',goTop );
    $('#cb-goTop').click(function(){
        $('html, body').animate({scrollTop:0});
    });

    //selected
    $('.ui.dropdown').dropdown();

    //modal
    var modalH=$(window).height();
    $('.cb-modal').each(function(){
        var elem=$(this).attr('id');
        var bigImg=$('.cb-modal#cb-bigPhoto .image img');
        var bigImgH=bigImg.height();
        $('#'+elem)
            .prepend('<div class="loading" style="width:100%;height:100%;background:#fff url(/style/base/slippry/assets/img/sy-loader.gif) no-repeat center center;position:absolute;top:0;left:0;z-index:999;"></div>')
            .modal('setting',{
                transition:'fade',
                duration:100
            })
            .modal('attach events', '.'+elem, 'show')
        ;
        $('.cb-modal').height(modalH);
        $('.cb-modal#cb-bigPhoto').height(modalH);
        if(bigImgH>modalH){bigImg.height(modalH);}
    });
    $('#cb-login')
        .modal('setting',{
            transition:'fade',
            duration:100
        })
        .modal('show')
        .height(modalH)
    ;
    $(window).load(function(){
        $('.loading').remove();
    });
    //popup moreInfo
    $('#cb-popup .cb-showMore').click(function(){
        $('.cb-moreInfo').animate({
            'maxHeight':'1000px'
        });
        $(this).hide();
        $('#cb-popup .cb-closeMore').show();
    });
    $('#cb-popup .cb-closeMore').click(function(){
        $('.cb-moreInfo').animate({
            'maxHeight':'200px'
        });
        $(this).hide();
        $('#cb-popup .cb-showMore').show();
    });

    //top search
    $('#search').focus(function(){
        $(this).next('.cb-searchIcon').removeClass('icon-search').addClass('icon-enter');
    });
    $('*').not('#search').click(function(){
        $('#cb-searchList').hide();
        $('#search .cb-searchIcon').removeClass('icon-enter').addClass('icon-search');
    });
    $('#search').bind('keydown',function(event){
        var cur=$('#search').val().length;
        if(event.which == 8 && cur==1){
            $('#cb-searchList').hide();
        }else{
            $('#cb-searchList').show();
        }
    });


    //accordion
    $('.ui.accordion')
        .accordion()
    ;
    $('.cb-comment').click(function(){
        $('.cb-comment .title .right span').text('展开');
        $('.cb-comment .active.title .right span').text('收起');
    });

    //comment
    $('.cb-commentText').focus(function(){
        $(this).addClass('cb-onFocus');
        var offsetT=$('.reply').offset().top+$('.reply').height();
        var htmlT=$(document).scrollTop()+$(window).height();
        if (offsetT>htmlT) {
            $(document).scrollTop(offsetT-$(window).height());
        };
    }).blur(function(){
            $(this).removeClass('cb-onFocus');
        });

    hotList();
    //scroll
    function hotList(){
        var defualtItem=parseInt(($('#cb-compareList').width())/($('#cb-compareList .cb-item img').width()));
        var hotItem=parseInt(($('#cb-compareList').width())/($('#cb-compareList .cb-item img').width()));
        var hotNum=$('#cb-compareList .cb-item').length;
        var leftArrow=$('.cb-left');
        var rightArrow=$('.cb-right');
        var leftDefualt=-$('#cb-compareList .cb-item img').width()-13;
        var leftWidth=0;
        var maxLeft=leftDefualt*(hotNum-hotItem);
        rightArrow.click(function(){
            leftArrow.show();
            if(hotItem>defualtItem){
                leftWidth=-leftDefualt+leftWidth;
                $('#cb-compareList .row').css('left',leftWidth+'px');
                --hotItem;
                if(hotItem==defualtItem){rightArrow.hide();}
            }
        });
        leftArrow.css('left',-defualtItem*leftDefualt).click(function(){
            rightArrow.show();
            leftWidth=leftDefualt+leftWidth;
            if(leftWidth>=maxLeft){
                $('#cb-compareList .row').css('left',leftWidth+'px');
                ++hotItem;
                if(hotItem==hotNum){leftArrow.hide();}
            }
        });
    }



});
</script>

<script>
    (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
        function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
        e=o.createElement(i);r=o.getElementsByTagName(i)[0];
        e.src='//www.google-analytics.com/analytics.js';
        r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
    ga('create','UA-XXXXX-X');ga('send','pageview');
</script>