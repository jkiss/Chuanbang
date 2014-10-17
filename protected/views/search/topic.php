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
        <li class="active">
            <a href="javascript:;">话题</a>
        </li>
        <li>
            <a href="<?php echo Yii::app()->createUrl('search/compare',array('word'=>$word));?>">对比</a>
        </li>
        <li>
            <a href="<?php echo Yii::app()->createUrl('search/user',array('word'=>$word));?>">用户</a>
        </li>
    </ul>
    <!-- 相关话题 -->
    <div class="module s-topic">
        <div class="grid four sep clearfix">
            <?php foreach($topics as $topic):?>
            <a href="<?php echo Yii::app()->createUrl('topic/view', array('id'=>$topic['id']));?>" class="column float">
                <div class="image top-scale">
                    <img src="<?php echo $topic['cover'];?>" alt="#">
                </div>
                <div class="caption">
                    <p class="name text-overflow"><?php echo $topic['title'];?></p>
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
    // 搜索结果分页
    var extra_data = {
            word: ''
        },
        url = '/search/listTopic',
        $wrap = $('.page-num').find('.num-panel'),
        $page_panel = $('.grid'),
        loadingHTML = '<i class="loading"></i>',
        updateContent = function(data, page_panel){  // 请求 success 后，更新页面内容
            var content = '';
            for (var i = 0; i < data.length; i++) {
                content += '<a href="'+ data[i].href +'" class="column float"><div class="image top-scale"><img src="'+ data[i].cover +'" alt="#"></div><div class="caption"><p class="name text-overflow">'+ data[i].title +'</p></div></a>';
            };
            page_panel.html(content);
        },
        beforeSend = function(){
            // beforesend...清除当前页的内容，显示 Loading，跳到最顶部，并隐藏页码区
            $page_panel.html(loadingHTML);
            $('html, body').animate({scrollTop: 0}, 200);
        },
        sendComplete = function(){    // Ajax 请求后为为新元素添加事件，没使用代理
            
        };
</script>