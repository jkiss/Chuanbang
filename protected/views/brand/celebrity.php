<!-- brand star page -->
<section class="content" id="b_s_brand">
    <div class="fou-nav">
        相关名人
    </div>
    <div id="page_panel" data-max-page="<?php echo $total_page;?>">
    <?php for($i = 0, $len = count($celebrities); $i < $len;$i += 2):?>
        <ul class="one">
            <li>
                <div class="img-wrap">
                    <a href="<?php echo Yii::app()->createUrl('celebrity/view',array('id'=>$celebrities[$i]['id']));?>"><img src="<?php echo $celebrities[$i]['head'];?>?w=130&h=130" alt=""></a>
                </div>
                <p class="cn"><?php echo $celebrities[$i]['name_cn'];?></p>
                <p class="en"><?php echo $celebrities[$i]['name_en'];?></p>
                <div class="m-data"><?php echo $celebrities[$i]['total_qa'];?>身穿搭／<?php echo $celebrities[$i]['total_fans'];?>粉丝</div>
            </li>
            <?php if($i + 1 <= $len -1):?>
                <li>
                    <div class="img-wrap">
                        <a href="<?php echo Yii::app()->createUrl('celebrity/view',array('id'=>$celebrities[$i+1]['id']));?>"><img src="<?php echo $celebrities[$i+1]['head'];?>?w=130&h=130" alt=""></a>
                    </div>
                    <p class="cn"><?php echo $celebrities[$i+1]['name_cn'];?></p>
                    <p class="en"><?php echo $celebrities[$i+1]['name_en'];?></p>
                    <div class="m-data"><?php echo $celebrities[$i+1]['total_qa'];?>身穿搭／<?php echo $celebrities[$i+1]['total_fans'];?>粉丝</div>
                </li>
            <?php endif;?>
        </ul>
    <?php endfor;?>
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
            pageSize: 10
        },
        // update eles
        $wrap = $('.page-num').find('.num-panel'),
        $page_panel = $('#page_panel'),
        loadingHTML = '<i class="loading-1"><img src="<?php echo Yii::app()->baseUrl;;?>/images/loading-rot-full.gif" alt="loading"></i>',
        opt = {
            url: '/brand/celebrity',
            range: 5,
            wrap: $('.page-num').find('.num-panel'),
            max_page: parseInt($('#page_panel').attr('data-max-page')),
            updateContent: function(data, page_panel){  // 更新图片内容
                var content = '',
                    data = data.rows;
                for (var i = 0; i < data.length; i++) {
                    if(i % 2 === 0){
                        if(i === 0){
                            content += '<ul class="one">';
                            content += '<li><div class="img-wrap"><a href="/brand/'+ data[i].id +'"><img src="'+ data[i].head +'?w=130&h=130" alt=""></a></div><p class="cn">'+ data[i].name_cn +'</p><p class="en">'+ data[i].name_en +'</p><div class="m-data">'+ data[i].total_qa +'身穿搭／'+ data[i].total_fans +'粉丝</div></li>';
                        }else{
                            content += '</ul><ul class="one">';
                            content += '<li><div class="img-wrap"><a href="/brand/'+ data[i].id +'"><img src="'+ data[i].head +'?w=130&h=130" alt=""></a></div><p class="cn">'+ data[i].name_cn +'</p><p class="en">'+ data[i].name_en +'</p><div class="m-data">'+ data[i].total_qa +'身穿搭／'+ data[i].total_fans +'粉丝</div></li>';
                        }
                    }else if(i % 2 === 1){
                        if(i === data.length - 1){
                            content += '<li><div class="img-wrap"><a href="/brand/'+ data[i].id +'"><img src="'+ data[i].head +'?w=130&h=130" alt=""></a></div><p class="cn">'+ data[i].name_cn +'</p><p class="en">'+ data[i].name_en +'</p><div class="m-data">'+ data[i].total_qa +'身穿搭／'+ data[i].total_fans +'粉丝</div></li>';
                            content += '</ul>';
                        }else{
                            content += '<li><div class="img-wrap"><a href="/brand/'+ data[i].id +'"><img src="'+ data[i].head +'?w=130&h=130" alt=""></a></div><p class="cn">'+ data[i].name_cn +'</p><p class="en">'+ data[i].name_en +'</p><div class="m-data">'+ data[i].total_qa +'身穿搭／'+ data[i].total_fans +'粉丝</div></li>';
                        }
                    }
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