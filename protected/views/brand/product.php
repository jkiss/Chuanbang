<!-- brand show page -->
<section class="content" id="b_s_show">
    <div class="fou-nav">
        秀场
    </div>
    <?php if(!empty($products)):?>
    <div class="select-wrap no-select">
        <i class="icon-tri-down"></i>
        <em class="title" data-select="selected">全部时装秀</em>
        <ul class="show-type hide">
            <?php foreach($products as $product):?>
            <li data-id="<?php echo $product['id'];?>" class="transition">
                <p class="text-overflow"><?php echo $product['name'];?></p>
            </li>
            <?php endforeach;?>
        </ul>
    </div>

    <div class="product">
        <?php $product = $products[0];?>
        <h1 class="name"><?php echo $product['name'];?></h1>
        <p class="desc"><?php echo $product['description'];?></p>
        <div class="grid four sep clearfix" id="page_panel">
            <?php foreach($product['images'] as $image):?>
            <a href="javascript:;" class="column float">
                <div class="image scale-1">
                    <img src="<?php echo $image;?>?w=209&h=313" alt="">
                </div>
            </a>
            <?php endforeach;?>
        </div>
        <?php endif;?>
    </div>
</section>
<script>
    function invokeHere(){
        var ajaxing = false;
        $('#b_s_show').find('.select-wrap').click(function(event) {
            var show_type = $(this).find('.show-type');
            if(!ajaxing){
                if(show_type.hasClass('hide')){
                    show_type.css('display', 'block');
                    show_type.animate({
                        'opacity': 1,
                        'top': '50px'
                    },
                        300, function() {
                        show_type.toggleClass('hide appear');
                    });
                } else if(show_type.hasClass('appear')){
                    show_type.animate({
                        'opacity': 0,
                        'top': '55px'
                    },
                        300, function() {
                        show_type.toggleClass('hide appear');
                        $(this).css('display', 'none');
                    });
                }
            }
        });

        $('.show-type').find('li').click(function(event) {
            var _me = $(this),
                select_wrap = $('.select-wrap'),
                text = _me.find('p').text(),
                id = _me.attr('data-id'),
                protuct = $('.product'),
                loadingHTML = '<i class="loading-1"><img src="<?php echo Yii::app()->baseUrl;;?>/images/loading-rot-full.gif" alt="loading"></i>',
                page_panel = $('#page_panel');

            if(!ajaxing){
                ajaxing = true;
                $.ajax({
                    url: '/brand/product',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        id: id
                    },
                    beforeSend: function(){
                        page_panel.html(loadingHTML);
                    },
                    success: function(data, status, xhr){
                        var content = '';
                        protuct.find('.name').text(data.name);
                        protuct.find('.desc').text(data.description);
                        for(var i = 0; i < data.images.length; i++){
                            content += '<a href="javascript:;" class="column float"><div class="image scale-1"><img src="'+ data.images[i] +'?w=209&h=313" alt=""></div></a>';
                        }
                        page_panel.html(content);
                        select_wrap.find('em').attr('class', 'selected').text(text);
                    },
                    error: function(xhr, status, msg){
                        console.log(msg);
                    },
                    complete: function(xhr, status){
                        ajaxing = false;
                        select_wrap.trigger('click');
                    }
                });
            }

        });
    }
</script>