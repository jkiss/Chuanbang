<!-- brand activity page -->
<section class="content" id="b_s_activity">
    <div class="fou-nav">
        去过的活动
    </div>
    <div id="page_panel" data-max-page="<?php echo $total_page;?>">
    <?php foreach($topics as $topic):?>
        <div class="one clearfix">
            <div class="left">
                <div class="image scale">
                    <a href="<?php echo Yii::app()->createUrl('topic/view',array('id'=>$topic['id']));?>"><img src="<?php echo $topic['cover'].'?w=622&h=457';?>" alt=""></a>
                </div>
            </div>
            <div class="right">
                <h1 class="title"><?php echo $topic['title'];?></h1>
                <p class="desc"><?php echo $topic['description'];?></p>
            </div>
        </div>
    <?php endforeach;?>
    </div>
    <ul class="page-num" data-total-page="">
        <div class="num-panel">
        </div>
    </ul>
</section>

<script>
function invokeHere(Pagination, publishDate, Wheel){
    var 
        // Extra Data
        id = $('#b_s_sub').find('a').attr('href').match(/\d+/g)[0],
        extra_data = {
            id: id,
            ajax: true,
            pageSize: 5
        },
        // update eles
        $wrap = $('.page-num').find('.num-panel'),
        $page_panel = $('#page_panel'),
        loadingHTML = '<i class="loading-1"><img src="<?php echo Yii::app()->baseUrl;;?>/images/loading-rot-full.gif" alt="loading"></i>',
        opt = {
            url: '/brand/topic',
            range: 5,
            wrap: $('.page-num').find('.num-panel'),
            max_page: parseInt($('#page_panel').attr('data-max-page')),
            updateContent: function(data, page_panel){  // 更新图片内容
                var content = '',
                    data = data.rows;
                for (var i = 0; i < data.length; i++) {
                    content += '<div class="one clearfix"><div class="left"><a href="/topic/'+ data[i].id +'"><div class="image scale"><img src="'+ data[i].cover +'?w=622&h=457" alt=""></div></a></div><div class="right"><h1 class="title">'+ data[i].title +'</h1><p class="desc">'+ data[i].description +'</p></div></div>';
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
}
</script>