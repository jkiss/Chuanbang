<!DOCTYPE html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>index</title>

    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="/style/base/sui/css/semantic.css">
    <link rel="stylesheet" href="/style/base/slippry/slippry.css">
    <link rel="stylesheet" href="/style/css/main.css">
    <link rel="stylesheet" href="/style/css/cb.css">
    <link rel="stylesheet" href="/style/css/icon.css">

</head>
<body>

<!--[if lt IE 8]>
<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->


<div id="cb-top" class="ui fixed fluid item menu">
    <div id="cb-head" >
        <a class="item" href="/" id="cb-logo">
            <i class="icon cb-icon icon-chuanbang"></i>
        </a>
        <a href="/draft/create" class="item ui icon huge button cb-btn vertical animated" id="cb-upLoad">
            <div class="hidden content">
                <i class="icon cb-icon icon-uploadphoto"></i>
                上传照片即可</div>
            <div class="visible content">
                <i class="icon cb-icon icon-uploadphoto"></i>
                欲知名人穿着?</div>
        </a>
        <div class="item ui huge icon input cb-search">
            <input id="search" type="text" placeholder="输入名牌/明星/话题查找相关">
            <i class="cb-searchIcon icon cb-icon icon-search"></i>
            <div id="cb-searchList">
                <div class="ui top attached segment">
                    <h3 class="ui dividing header"><a class="cb-more" href="/page/explore/brand/search.html"><i class="icon cb-icon icon-more"></i></a><i class="icon cb-icon icon-brand"></i>品牌(共 200个结果)</h3>
                    <div class="ui list">

                        <a class="item" href="">
                            <img class="ui rounded image" src="/page/explore/brand/img/6.png">
                            <div class="content left floated">
                                <div class="header"><span>G</span>ucci 2014秋冬 米兰时装周大放异彩</div>
                            </div>
                        </a>

                        <a class="item" href="">
                            <img class="ui rounded image" src="/page/explore/brand/img/6.png">
                            <div class="content left floated">
                                <div class="header"><span>G</span>ucci 2014秋冬 米兰时装周大放异彩</div>
                            </div>
                        </a>

                        <a class="item" href="">
                            <img class="ui rounded image" src="/page/explore/brand/img/6.png">
                            <div class="content left floated">
                                <div class="header"><span>G</span>ucci 2014秋冬 米兰时装周大放异彩</div>
                            </div>
                        </a>

                    </div>
                </div>
                <div class="ui attached segment">
                    <h3 class="ui dividing header"><a class="cb-more" href="/page/explore/brand/search.html"><i class="icon cb-icon icon-more"></i></a><i class="icon cb-icon icon-explorer"></i>名人(共 5个结果)</h3>
                    <div class="ui list">

                        <a class="item" href="">
                            <img class="ui image" src="/page/explore/brand/img/7.png">
                            <div class="content left floated">
                                <div class="header"><span>G</span>ucci 2014秋冬 米兰时装周大放异彩</div>
                            </div>
                        </a>

                        <a class="item" href="">
                            <img class="ui image" src="/page/explore/brand/img/7.png">
                            <div class="content left floated">
                                <div class="header"><span>G</span>ucci 2014秋冬 米兰时装周大放异彩</div>
                            </div>
                        </a>

                        <a class="item" href="">
                            <img class="ui image" src="/page/explore/brand/img/7.png">
                            <div class="content left floated">
                                <div class="header"><span>G</span>ucci 2014秋冬 米兰时装周大放异彩</div>
                            </div>
                        </a>

                    </div>
                </div>
                <div class="ui bottom attached segment">
                    <h3 class="ui dividing header"><i class="icon cb-icon icon-user"></i>用户(共 0个结果)</h3>
                </div>
            </div>
        </div>
    </div>
</div>
<!--top-->

<div id="cb-side">
<div class="ui cb-side thin sidebar"></div>
<!-- 热门话题 -->
<div class="ui vertical text cb-sidebar cb-sidebar1 thin sidebar menu cb-subMenu cb-menuList" id="index">
    <div class="item ui icon input cb-sideSearch open">
        <input type="text" placeholder="Search...">
        <i class="icon cb-icon icon-search"></i>
    </div>
    <?php $hotTopics = Topic::model()->findAll(array(
        'condition'=>'hot=:hot',
        'params'=>array(':hot'=>'Y'),
        'limit'=>20,
        'order'=>'id desc',
    ));?>
    <?php foreach($hotTopics as $topic):?>
        <a class="item" href="javascript:;"><?php echo $topic->title;?></a>
    <?php endforeach;?>
</div><!-- /热门话题 -->

<div class="ui vertical text cb-sidebar cb-sidebar2 thin menu sidebar cb-subMenu" id="explore">
    <a href="/brand" class="item" data-id="brand">
        <i class="icon cb-icon icon-brand"></i>
        品牌
    </a>
    <a href="/star" class="item" data-id="celebrity">
        <i class="icon cb-icon icon-Celebrity"></i>
        名人
    </a>
    <a href="/question/pending" class="item" data-id="waiting">
        <i class="icon cb-icon icon-waiting_answer"></i>
        待答
    </a>
    <a href="/compare" class="item" data-id="compare">
        <i class="icon cb-icon icon-compare"></i>
        对比
    </a>
    <a href="/topic" class="item" data-id="topic">
        <i class="icon cb-icon icon-event"></i>
        话题
    </a>
</div>
<div class="ui vertical text cb-sidebar cb-sidebar3 thin menu sidebar cb-subMenu" id="message">
    <a class="item" data-id="message" href="/page/notification/index.html">
        <i class="icon cb-icon icon-NotificationAll"></i>
        全部
    </a>
    <a class="item">
        <i class="icon cb-icon icon-vote"></i>
        感谢
    </a>
    <a class="item">
        <i class="icon cb-icon icon-vote"></i>
        赞同
    </a>
    <a class="item">
        <i class="icon cb-icon icon-answer"></i>
        回答
    </a>
</div>
<div class="ui vertical text cb-sidebar cb-sidebar4 thin menu sidebar cb-subMenu" id="user">
    <a href="/" data-id="info" class="item">
        <i class="icon cb-icon icon-user"></i>
        主页
    </a>
    <a href="/user/collection" data-id="collections" class="item">
        <i class="icon cb-icon icon-collection"></i>
        收藏
    </a>
    <a href="/draft" data-id="sketch" class="item">
        <i class="icon cb-icon icon-draft"></i>
        草稿
    </a>
    <a href="/page/user/pm.html" data-id="letter" class="item">
        <i class="icon cb-icon icon-pm"></i>
        私信
    </a>
    <a href="<?php echo Yii::app()->request->baseUrl.'/user/profile';?>" data-id="setting" class="item">
        <i class="icon cb-icon icon-setting"></i>
        设置
    </a>
    <a class="item" data-id="out">
        <i class="icon cb-icon icon-logout"></i>
        退出
    </a>
</div>
<div class="ui vertical text cb-sidebar cb-sidebar5 thin sidebar menu cb-subMenu cb-list" id="search">
    <a class="item open3 cb-back">
        <i class="icon cb-icon icon-leftOver"></i>
        返回
    </a>
    <div class="item">
        　　<i class="icon cb-icon icon-pageCurrent"></i>
        <span>搜索结果</span>
    </div>
    <div class="item ui icon input cb-sideSearch open">
        <input type="text" placeholder="Search...">
        <i class="icon cb-icon icon-search"></i>
    </div>
    <h3 class="ui dividing header">大家都在搜</h3>

    <div class="item cb-item">
        <div class="image">
            <img src="/page/explore/brand/img/5.png" data-pinit="registered">
        </div>
        <a class="content" href="">
            <p class="description">Miranda Kerr in Brand Name  米兰达穿着XX 外套</p>
            <div class="ui vote right aligned basic segment">
                <i class="icon cb-icon icon-answer-rec"></i>
                62508
            </div>
        </a>
    </div>

    <div class="item cb-item">
        <div class="image">
            <img src="/page/explore/brand/img/5.png" data-pinit="registered">
        </div>
        <a class="content" href="">
            <p class="description">Miranda Kerr in Brand Name  米兰达穿着XX 外套</p>
            <div class="ui vote right aligned basic segment">
                <i class="icon cb-icon icon-answer-rec"></i>
                62508
            </div>
        </a>
    </div>

    <div class="item cb-item">
        <div class="image">
            <img src="/page/explore/brand/img/5.png" data-pinit="registered">
        </div>
        <a class="content" href="">
            <p class="description">Miranda Kerr in Brand Name  米兰达穿着XX 外套</p>
            <div class="ui vote right aligned basic segment">
                <i class="icon cb-icon icon-answer-rec"></i>
                62508
            </div>
        </a>
    </div>

</div>

<?php
if($this->id == 'question' && $this->action->id == 'view') {
    $answer = Answer::model()->find(array(
        'condition'=>'ques_id=:ques_id',
        'params'=>array(':ques_id'=>$this->actionParams['id']),
        'with'=>array('details',),
        'order'=>'t.supports desc,t.id asc'
    ));
} else if($this->id == 'answer' && $this->action->id == 'view') {
    $answer = Answer::model()->findByPk($this->actionParams['id']);
} else {}
if(isset($answer)):
    $star = $answer->details[0]->star;
?>
<div class="ui vertical text cb-sidebar cb-sidebar6  sidebar menu cb-subMenu cb-list" id="answer">
    <a class="item open2 cb-back">
        <i class="icon cb-icon icon-leftOver"></i>
        返回
    </a>
    <div class="item">
        <i class="icon cb-icon icon-pageCurrent"></i>
        <span><?php echo $star->name;?></span>
    </div>
    <?php $models = AnswerDetail::model()->findAll(array(
        'condition'=>'star_id=:star_id',
        'params'=>array(':star_id'=>$star->id),
        'with'=>array('celebrity','brand','answer','answer.question','answer.question.pictures'),
        'limit'=>10,
        'order'=>'t.id desc',
    ));?>
    <?php foreach($models as $model):?>
    <a class="item cb-item hover" href="<?php echo Yii::app()->request->baseUrl.'/question/'.$model->answer->ques_id;?>">
        <div class="ui image left floated">
            <img src="<?php echo $model->answer->question->pictures[0]->img;?>" data-pinit="registered">
        </div>
        <div class="content" href="">
            <p class="description"><?php echo $model->star->name;?>穿<?php echo $model->brand->name;?></p>
        </div>
    </a>
    <?php endforeach;?>
</div>
<?php endif;?>

<!-- 热门品牌 -->
<div class="ui vertical text cb-sidebar cb-sidebar8 thin sidebar menu cb-subMenu cb-menuList" id="brand">
    <a class="item open2 cb-back">
        <i class="icon cb-icon icon-leftOver"></i>
        返回
    </a>
    <div class="item">
        　　<i class="icon cb-icon icon-pageCurrent"></i>
        <span>品牌</span>
    </div>
    <div class="item ui icon input cb-sideSearch open">
        <input type="text" placeholder="Search...">
        <i class="icon cb-icon icon-search"></i>
    </div>
    <?php
    $hotBrands = Brand::model()->findAll(array(
        'condition'=>'hot=:hot',
        'params'=>array(':hot'=>'Y'),
        'order'=>'id desc',
        'limit'=>20,
    ));
    ?>
    <?php foreach($hotBrands as $brand):?>
    <a class="item" href="javascript:;"><?php echo $brand->name;?></a>
    <?php endforeach;?>
</div><!-- /热门品牌 -->

<!-- 热门名人 -->
<div class="ui vertical text cb-sidebar cb-sidebar7 thin sidebar menu cb-subMenu cb-menuList" id="celebrity">
    <a class="item open2 cb-back">
        <i class="icon cb-icon icon-leftOver"></i>
        返回
    </a>
    <div class="item">
        　　<i class="icon cb-icon icon-pageCurrent"></i>
        <span>名人</span>
    </div>
    <div class="item ui icon input cb-sideSearch open">
        <input type="text" placeholder="Search...">
        <i class="icon cb-icon icon-search"></i>
    </div>
    <?php
    $hotStars = Star::model()->findAll(array(
        'condition'=>'hot=:hot',
        'params'=>array(':hot'=>'Y'),
        'order'=>'id desc',
        'limit'=>20,
    ));
    ?>
    <?php foreach($hotStars as $star):?>
        <a class="item" href="javascript:;"><?php echo $star->name;?></a>
    <?php endforeach;?>
</div><!-- /热门名人 -->

<!-- 热门待答 -->
<div class="ui vertical text cb-sidebar cb-sidebar7 thin sidebar menu cb-subMenu cb-menuList" id="waiting">
    <a class="item open2 cb-back">
        <i class="icon cb-icon icon-leftOver"></i>
        返回
    </a>
    <div class="item">
        　　<i class="icon cb-icon icon-pageCurrent"></i>
        <span>待答</span>
    </div>
    <div class="item ui icon input cb-sideSearch open">
        <input type="text" placeholder="Search...">
        <i class="icon cb-icon icon-search"></i>
    </div>
</div><!-- /热门待答 -->

<!-- 热门对比 -->
<div class="ui vertical text cb-sidebar cb-sidebar7 thin sidebar menu cb-subMenu cb-menuList" id="compare">
    <a class="item open2 cb-back">
        <i class="icon cb-icon icon-leftOver"></i>
        返回
    </a>
    <div class="item">
        　　<i class="icon cb-icon icon-pageCurrent"></i>
        <span>对比</span>
    </div>
    <div class="item ui icon input cb-sideSearch open">
        <input type="text" placeholder="Search...">
        <i class="icon cb-icon icon-search"></i>
    </div>
</div><!-- /热门对比 -->

<!-- 热门话题 -->
<div class="ui vertical text cb-sidebar cb-sidebar7 thin sidebar menu cb-subMenu cb-menuList" id="topic">
    <a class="item open2 cb-back">
        <i class="icon cb-icon icon-leftOver"></i>
        返回
    </a>
    <div class="item">
        　　<i class="icon cb-icon icon-pageCurrent"></i>
        <span>话题</span>
    </div>
    <div class="item ui icon input cb-sideSearch open">
        <input type="text" placeholder="Search...">
        <i class="icon cb-icon icon-search"></i>
    </div>
    <?php foreach($hotTopics as $topic):?>
        <a class="item" href="<?php echo Yii::app()->request->baseUrl.'/topic/'.$topic->id;?>"><?php echo $topic->title;?></a>
    <?php endforeach;?>
</div><!-- /热门待答 -->

<div class="ui vertical text cb-sidebar thin sidebar menu cb-subMenu" id="footlink">
    <a data-id="about" class="item" href="/page/about.html">关于我们</a>
    <a data-id="help" class="item" href="/page/help.html">使用帮助</a>
    <a data-id="contact" class="item" href="/page/contact.html">联系我们</a>
    <a data-id="feedback" class="item" href="/page/feedback.html">服务条款</a>
</div>
<!--subMenu-->
<div class="ui fixed vertical labeled icon menu cb-mainMenu">
    <a href="/" class="item " data-nav="index">
        <i class="icon cb-icon icon-homepage"></i>
        首页
    </a>
    <a href="/explore" class="item" data-nav="explore">
        <i class="icon cb-icon icon-explorer"></i>
        探索
    </a>
    <a href="/page/notification/index.html" class="item" data-nav="message">
        <i class="icon cb-icon icon-Notification2"></i>
        消息
    </a>
    <?php if(Yii::app()->user->isGuest):?>
    <a href="/sign_in" class="item" data-nav="user">
        <i class="icon cb-icon icon-user"></i>
        登录
    </a>
    <?php else:?>
    <a href="/user" class="item" data-nav="user">
        <i class="icon cb-icon icon-user"></i>
        用户
    </a>
    <?php endif;?>
</div><!--mainMenu-->

</div>
<!--side-->

<?php echo $content; ?>

<script type="text/javascript" src="/src/js/cb.js"></script>
</body>
</html>