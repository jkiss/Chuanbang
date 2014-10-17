<!-- The b_s_new page -->
<section class="content" id="b_s_new">
    <div class="fou-nav">
        名人动态
    </div>
    <div id="page_panel" data-max-page="<?php echo $total_page;?>">
        <?php foreach($answers as $answer):?>
        <div class="one">
            <div class="img-wrap">
                <a href="<?php echo Yii::app()->createUrl('answer/view',array('id'=>$answer['id']));?>">
                    <img src="<?php echo $answer['img'];?>?w=355&h=345" alt="">
                </a>
            </div>
            <?php foreach($answer['details'] as $detail):?>
            <div class="clothes">
                <i class="icon-in"></i>
                <span class="brand"><?php echo $detail['brand'];?></span>
                <span class="type"><?php echo $detail['clothes_type'];?></span>
                <span class="style"><?php echo $detail['style'];?></span>
            </div>
            <?php endforeach;?>
            <div class="event">
                <span class="title"><?php echo $answer['happens'];?></span>
                <span class="date"><?php echo $answer['occurdate'];?></span>
                <span class="place"><?php echo $answer['place'];?></span>
            </div>
            <div class="m-data">
                <div class="detail">
                    <span>共<?php echo $answer['total_imgs'];?>张图</span>
                    <span><?php echo $answer['total_comments'];?>条评论</span>
                    <span><?php echo max(0,intval($answer['total_answers'])-1);?>条其他解读</span>
                </div>
                <div class="author">
                    <div class="avatar">
                        <img src="<?php echo $answer['author']['head'];?>" alt="">
                    </div>
                    <p class="name"><?php echo $answer['author']['nick'];?></p>
                    <p class="time"><?php echo $answer['createtime'];?></p>
                </div>
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
            url: '/celebrity/fresh',
            range: 5,
            wrap: $('.page-num').find('.num-panel'),
            max_page: parseInt($('#page_panel').attr('data-max-page')),
            updateContent: function(data, page_panel){  // 更新图片内容
                var content = '',
                    data = data.rows;
                for (var i = 0; i < data.length; i++) {
                    content += '<div class="one"><div class="img-wrap"><a href="/answer/'+ data[i].id +'"><img src="'+ data[i].img +'?w=355&h=345" alt=""></a></div>';
                    for (var j = 0; j < data[i].details.length; j++) {
                        content += '<div class="clothes"><i class="icon-in"></i><span class="brand">'+ data[i].details[j].brand +'</span><span class="type">'+ data[i].details[j].style +'</span><span class="style">'+ data[i].details[j].clothes_type +'</span></div>';
                    };
                    content += '<div class="event"><span class="title">'+ data[i].happens +'</span><span class="date">'+ data[i].occurdate +'</span><span class="place">'+ data[i].place +'</span></div><div class="m-data"><div class="detail"><span>共'+ data[i].total_imgs +'张图</span><span>'+ data[i].total_comments +'条评论</span><span>'+ data[i].total_answers +'条其他解读</span></div><div class="author"><div class="avatar"><img src="'+ data[i].author.head +'" alt=""></div><p class="name">'+ data[i].author.nick +'</p><p class="time">'+ publishDate(data[i].author.createtime) +'</p></div></div></div>';
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