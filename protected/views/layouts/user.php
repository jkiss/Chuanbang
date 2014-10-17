<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="keywords" content="穿帮，明星">
    <meta naem="description" content="发现时尚与潮流，揭穿明星的秘密！">
    <title>穿帮v0.1 - Home</title>
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="<?php echo Yii::app()->baseUrl;;?>/styles/noookey.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->baseUrl;;?>/styles/cb.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->baseUrl;;?>/styles/index.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->baseUrl;;?>/styles/user.css">
    <script src="<?php echo Yii::app()->baseUrl;;?>/scripts/jquery.js"></script>
    <script src="<?php echo Yii::app()->baseUrl;;?>/scripts/jquery.easing.1.3.js"></script>
</head>
<body>
    <!-- The main nav -->
    <nav class="main-nav">
        <ul>
            <li <?php echo $this->id == 'site' ? 'class="active"' : '';?>>
                <a href="<?php echo Yii::app()->createUrl('site/index');?>" class="transition">首页</a>
                <a href="<?php echo Yii::app()->createUrl('site/index');?>" class="icon-home m-nav-icon transition"></a>
            </li>
            <li <?php echo $this->id == 'celebrity' ? 'class="active"' : '';?>>
                <a href="<?php echo Yii::app()->createUrl('celebrity/index', array('tag'=>'hot','letter'=>'A'));?>" class="transition">名人</a>
                <a href="<?php echo Yii::app()->createUrl('celebrity/index', array('tag'=>'hot','letter'=>'A'));?>" class="icon-star m-nav-icon transition"></a>
            </li>
            <li <?php echo $this->id == 'brand' ? 'class="active"' : '';?>>
                <a href="<?php echo Yii::app()->createUrl('brand/index', array('tag'=>'hot','letter'=>'A'));?>" class="transition">品牌</a>
                <a href="<?php echo Yii::app()->createUrl('brand/index', array('tag'=>'hot','letter'=>'A'));?>" class="icon-brand m-nav-icon transition"></a>
            </li>
            <li <?php echo $this->id == 'topic' ? 'class="active"' : '';?>>
                <a href="<?php echo Yii::app()->createUrl('topic/index');?>" class="transition">话题</a>
                <a href="<?php echo Yii::app()->createUrl('topic/index');?>" class="icon-topic m-nav-icon transition"></a>
            </li>
            <li <?php echo $this->id == 'compare' ? 'class="active"' : '';?>>
                <a href="<?php echo Yii::app()->createUrl('compare/index');?>" class="transition">对比</a>
                <a href="<?php echo Yii::app()->createUrl('compare/index');?>" class="icon-compare-1 m-nav-icon transition"></a>
            </li>
            <li <?php echo $this->id == 'question' ? 'class="active"' : '';?>>
                <a href="<?php echo Yii::app()->createUrl('question/index');?>" class="transition">待答</a>
                <a href="<?php echo Yii::app()->createUrl('question/index');?>" class="icon-wait m-nav-icon transition"></a>
            </li>
            <?php if(Yii::app()->user->isGuest):?>
            <li <?php echo $this->id == 'user' ? 'class="active"' : '';?>>
                <a href="javascript:;" class="login-no" class="transition">用户</a>
                <a href="javascript:;" class="icon-user-1 m-nav-icon login-no transition"></a>
            </li>
            <?php else:?>
                <li <?php echo $this->id == 'user' ? 'class="active"' : '';?>>
                    <a href="<?php echo Yii::app()->createUrl('user/index');?>" class="transition">用户</a>
                    <a href="<?php echo Yii::app()->createUrl('user/index');?>" class="icon-user-1 m-nav-icon transition"></a>
                </li>
            <?php endif;?>
        </ul>
    </nav>
    <!-- The top bar -->
    <header class="header">
        <a href="/">
            <div class="logo">
                <i class="icon-star"></i>
            </div>
        </a>
        <div id="search_box">
            <input type="text" id="keywords" name="keywords" autocomplete="off" placeholder="搜索&nbsp;品牌/明星/话题..." value="<?php echo isset($_GET['word']) && !empty($_GET['word']) ? $_GET['word'] : '';?>" required="required" data-focus="false">
            <div class="search-result">
            </div>
        </div>
        <a href="<?php echo Yii::app()->user->isGuest ? 'javascript:;' : Yii::app()->createUrl('question/ask');?>" class="add-star-outfit transition <?php echo Yii::app()->user->isGuest ? 'login-no' : '';?>">
            <i class="icon-plus"></i>
            添加明星穿搭
        </a>
    </header>
    <!-- The sub nav -->
    <?php if($this->role == 'self'):?>
    <div class="sub-nav-wrap">
        <div class="sub-nav">
            <ul class="user-sub">
                <a href="<?php echo Yii::app()->createUrl('user/index');?>">
                    <li <?php echo $this->action->id == 'view' ? 'class="active transition"' : 'class="transition"';?>>
                    <i <?php echo $this->action->id == 'view' ? 'class="icon-user-2"' : 'class="icon-user-1"';?>></i>
                    主页</li>
                </a>
                <a href="<?php echo Yii::app()->createUrl('user/favorite');?>">
                    <li <?php echo $this->action->id == 'favorite' ? 'class="active transition"' : 'class="transition"';?>>
                    <i <?php echo $this->action->id == 'favorite' ? 'class="icon-collect-2"' : 'class="icon-collect-1"';?>></i>
                    收藏</li>
                </a>
                <a href="<?php echo Yii::app()->createUrl('user/follow');?>">
                    <li <?php echo $this->action->id == 'follow' ? 'class="active transition"' : 'class="transition"';?>>
                    <i <?php echo $this->action->id == 'follow' ? 'class="icon-follow-2"' : 'class="icon-follow-1"';?>></i>关注</li>
                </a>
                <a href="<?php echo Yii::app()->createUrl('user/zan');?>">
                    <li <?php echo $this->action->id == 'zan' ? 'class="active transition"' : 'class="transition"';?>>
                    <i <?php echo $this->action->id == 'zan' ? 'class="icon-zan-2"' : 'class="icon-zan-1"';?>></i>赞</li>
                </a>
                <a href="<?php echo Yii::app()->createUrl('user/draft');?>">
                    <li <?php echo $this->action->id == 'draft' ? 'class="active transition"' : 'class="transition"';?>>
                    <i <?php echo $this->action->id == 'draft' ? 'class="icon-draft-2"' : 'class="icon-draft-1"';?>></i>草稿</li>
                </a>
                <a href="<?php echo Yii::app()->createUrl('user/info');?>">
                    <li <?php echo $this->action->id == 'info' ? 'class="active transition"' : 'class="transition"';?>>
                        <i <?php echo $this->action->id == 'info' ? 'class="icon-info-2"' : 'class="icon-info-1"';?>></i>
                        消息
                        <!-- if has a new info: -->
                        <div class="tip"></div>
                        <!-- endif; -->
                    </li>
                </a>
                <a href="<?php echo Yii::app()->createUrl('user/msg');?>">
                    <li <?php echo $this->action->id == 'msg' ? 'class="active transition"' : 'class="transition"';?>>
                        <i <?php echo $this->action->id == 'msg' ? 'class="icon-msg-2"' : 'class="icon-msg-1"';?>></i>
                        私信
                        <!-- if has a new info: -->
                        <div class="tip"></div>
                        <!-- endif; -->
                    </li>
                </a>
                <a href="<?php echo Yii::app()->createUrl('user/profile');?>" <?php echo $this->action->id == 'profile' ? 'class="active"' : '';?>>
                    <li <?php echo $this->action->id == 'profile' ? 'class="active transition"' : 'class="transition"';?>>
                    <i class="icon-set-1"<?php echo $this->action->id == 'profile' ? 'class="icon-set-2"' : 'class="icon-set-1"';?>></i>设置</li>
                </a>
                <a href="<?php echo Yii::app()->createUrl('user/logout');?>">
                    <li class="transition">
                    <i class="icon-quit"></i>退出</li>
                </a>
            </ul>
        </div>
    </div>
    <?php else:?>
    <!-- The sub nav -->
    <div class="sub-nav-wrap">
        <div class="sub-nav">
            <ul class="user-sub">
                <a href="<?php echo Yii::app()->createUrl('user/index');?>">
                    <li <?php echo $this->action->id == 'index' ? 'class="active transition"' : 'class="transition"';?>>
                    <i <?php echo $this->action->id == 'index' ? 'class="icon-user-2"' : 'class="icon-user-1"';?>></i>
                    主页</li>
                </a>
                <a href="<?php echo Yii::app()->createUrl('user/collect');?>">
                    <li <?php echo $this->action->id == 'collect' ? 'class="active transition"' : 'class="transition"';?>>
                    <i class="icon-collect-1"></i>
                    收藏</li>
                </a>
                <a href="<?php echo Yii::app()->createUrl('user/follow');?>">
                    <li <?php echo $this->action->id == 'follow' ? 'class="active transition"' : 'class="transition"';?>>
                    <i class="icon-follow-1"></i>关注</li>
                </a>
            </ul>
        </div>
    </div>
    <?php endif;?>

    <?php echo $content; ?>

    <!-- The footer -->
    <!-- <footer class="footer">
        <div class="download">
            <h4>下载应用</h4>
            <a href="<?php echo Yii::app()->baseUrl;;?>/software/cb_ios.ipa">
                <i class="icon-apple"></i><br>
                <em class="title">iPhone</em>
            </a>
            <a href="<?php echo Yii::app()->baseUrl;;?>/software/cb_android.apk">
                <i class="icon-android"></i>
                <em class="title">Android</em>
            </a>
        </div>
        <div class="share-cb">
            <script type="text/javascript">
                (function(){
                    var p = {
                        url: 'http://3w.chuanbang.com',    // 要分享的网址
                        appkey: '',
                        title: '穿帮，你的选择',     // 分享的标题       
                        pic: 'http://e.hiphotos.baidu.com/image/pic/item/64380cd7912397ddfdfca08b5b82b2b7d1a287b3.jpg',   // 分享的缩略图
                        ralateUid: '3197845034',    // 分享后需要 @ 的 ID
                        language: 'zh_cn'
                    }
                    var s = [];
                    for(var i in p){
                        s.push(i + '=' + encodeURIComponent(p[i]||''));
                    }
                    document.write(['<a class="icon-weibo" href="http://service.weibo.com/share/share.php?', s.join('&'), '" target="_blank"></a>'].join(''));
                }());
            </script>
            <script type="text/javascript">
                (function(){
                    var p = {
                        url: 'http://3w.chuanbang.com',
                        showcount: '1',/*是否显示分享总数,显示：'1'，不显示：'0' */
                        desc: '穿帮，一个时尚的网站',/*默认分享理由(可选)*/
                        summary: '你穿帮，我时尚',/*分享摘要(可选)*/
                        title: '穿帮，你的选择',/*分享标题(可选)*/
                        pics: 'http://e.hiphotos.baidu.com/image/pic/item/64380cd7912397ddfdfca08b5b82b2b7d1a287b3.jpg' /*分享图片的路径(可选)*/
                    };
                    var s = [];
                    for(var i in p){
                        s.push(i + '=' + encodeURIComponent(p[i]||''));
                    }
                    document.write(['<a version="1.0" class="icon-qq" href="http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?',s.join('&'),'" target="_blank"></a>'].join(''));
                })();
            </script>
        </div>
        <div class="official">
            <p class="help-link">
                <span class="right-sep"><a href="?">使用条款</a></span>
                <span class="right-sep"><a href="?">关于我们</a></span>
                <a href="http://www.lagou.com/gongsi/21297.html" target="_blank">加入点五</a>
            </p>
            <p>&copy; 2001-2015 All rights reserved 京ICP备10000000号-1 www.chuanbang.com 版权所有</p>
        </div>
    </footer> -->
    <!-- To top -->
    <div class="to-top link">
        <i class="icon-dir-up"></i>
    </div>
    
    <script src="<?php echo Yii::app()->baseUrl;;?>/scripts/cb.js"></script>
    <script>
        $('#add_suit').length !== 0 ? $('.footer').css('display', 'none') : void(0);
        $('#search').length !== 0 ? $('.footer').css('display', 'none') : void(0);
    </script>
</body>
</html>