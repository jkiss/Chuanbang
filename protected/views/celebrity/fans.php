<!-- star fans page -->
<section class="content" id="b_s_fans">
    <div class="fou-nav">
        粉丝
    </div>
    <div id="page_panel" data-max-page="<?php echo $total_page;?>">
        <?php foreach($fans as $user):?>
        <div class="one">
            <div class="img-wrap avatar">
                <a href="<?php echo Yii::app()->createUrl('user/view',array('id'=>$user['id']));?>"><img src="<?php echo $user['head'];?>?w=80&h=80" alt=""></a>
            </div>
            <?php if($user['follow'] == 'N'):?>
            <div class="flo-btn no transition">
                <i class="icon-follow-1"></i>
                加关注
            </div>
            <?php else:?>
            <div class="flo-btn yes transition">
                <i class="icon-yes"></i>
                <em class="text">已关注</em>
            </div>
            <?php endif;?>
            <p class="profile">
                <span class="name"><?php echo $user['nick'];?></span>
                <?php if($user['gender'] == 'M'):?>
                <i class="icon-man"></i>
                <?php else:?>
                <i class="icon-woman"></i>
                <?php endif;?>
                <span class="area"><?php echo $user['region'];?></span>
                <span class="city"><?php echo $user['city'];?></span>
            </p>
            <p class="m-data">
                <span class="ans"><?php echo $user['total_qa'];?>解读</span>
                <span class="que"><?php echo $user['total_q'];?>提问</span>
            </p>
            <p class="desc">
                <?php echo $user['signature'];?>
            </p>
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
    $('.flo-btn').hover(function() {
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
            url: '/celebrity/fans',
            range: 5,
            wrap: $('.page-num').find('.num-panel'),
            max_page: parseInt($('#page_panel').attr('data-max-page')),
            updateContent: function(data, page_panel){  // 更新图片内容
                var content = '',
                    follow,
                    sex,
                    data = data.rows;
                for (var i = 0; i < data.length; i++) {
                    data[i].follow.toLowerCase() === 'y' ? follow = 'y' : follow = 'n';
                    data[i].gender.toLowerCase() === 'm' ? sex = 'm' : sex = 'f';
                    content += '<div class="one"><div class="img-wrap avatar"><a href="/user/'+ data[i].id +'"><img src="'+ data[i].head +'?w=80&h=80" alt=""></a></div>';
                    if(follow === 'y'){
                        content += '<div class="flo-btn yes transition"><i class="icon-yes"></i><em class="text">已关注</em></div>';
                    }else{
                        content += '<div class="flo-btn no transition"><i class="icon-follow-1"></i>加关注</div>';
                    }
                    content += '<p class="profile"><span class="name">'+ data[i].nick +'</span>';
                    if(sex === 'm'){
                        content += '<i class="icon-man"></i>';
                    }else{
                        content += '<i class="icon-woman"></i>';
                    }
                    content += '<span class="area">'+ data[i].region +'</span><span class="city">'+ data[i].city +'</span></p><p class="m-data"><span class="ans">'+ data[i].total_qa +'解读</span><span class="que">'+ data[i].total_q +'提问</span></p><p class="desc">'+ data[i].signature +'</p></div>';
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