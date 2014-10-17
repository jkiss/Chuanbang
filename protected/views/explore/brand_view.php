<script>
    var page="brand-explore";
</script>
<div id="cb-main" class="ui segment">
  <div class="ui grid cb-content" id="cb-brand">
    <div class="row cb-brand-logo">
      <div class="four wide column cb-brand-img">
        <img src="<?php echo $brand->logo;?>">
      </div>
      <div class="eight wide column cb-brand-info">
        <p><?php echo $brand->summary;?></p>
      </div>
      <div class="center aligned four wide column cb-brand-notice">
        <p>
            <strong><?php echo $total_follows;?></strong>人<br>
            <a href="javascript:;" class="ui  huge button cb-btn cb-fav-brand" data-id="<?php echo $brand->id;?>" fav="<?php echo in_array($brand->id, $my_brand_ids)?'y':'n';?>">
                <span class="description <?php echo in_array($brand->id, $my_brand_ids) ? 'hide' : '';?>">关注</span>
                <span class="description <?php echo !in_array($brand->id, $my_brand_ids) ? 'hide' : '';?>">取消关注</span>
            </a>
        </p>
      </div>
    </div><!--品牌关注--> 
    <div class="row">
      <div class="twelve wide column cb-leftList tab">
        <div class="ui tabular menu dividing tiny header cb-tab">
          <a class="active item" data-tab="cb-tabRow1">
            <i class="icon cb-icon icon-answer-rec"></i>
            最新动态
          </a>
        </div><!--tab-->
        <div class="ui four connected items cb-list cb-newList cb-newsList tab active" data-tab="cb-tabRow1">
        <?php $size = 4;?>
        <?php for($i=0, $len=count($answers); $i < $len; $i++):?>
            <?php $answer = $answers[$i];?>
            <?php if($i % $size == 0):?><div class="row"><?php endif;?>
            <a class="column item cb-itemHover" href="<?php echo Yii::app()->request->baseUrl.'/question/'.$answer->answer->ques_id;?>" >
                <div class="image">
                    <img src="<?php echo $answer->answer->question->pictures[0]->img;?>" data-pinit="registered">
                </div>
                <div class="content">
                    <p class="description"><?php echo $answer->star->name;?>身着<?php echo $answer->brand->name;?></p>
                </div>
            </a>
            <?php if(($i+1) % $size == 0 || $i == $len-1):?></div><?php endif;?>
        <?php endfor;?>
        </div><!--tabContent1-->
      </div><!--leftTablist-->
      <div class="four wide column cb-rightList">
        <div class="ui tiny header">
          <a class="cb-more" href="javascript:;"><i class="icon cb-icon icon-more"></i></a>
          品牌设计师
        </div>
        
        <a class="item cb-item" href="javascript:;">
          <div class="ui right floated image">
            <img src="/page/explore/brand/img/3.png" data-pinit="registered">
          </div>
          <div class="content">
            <p class="description"><?php echo $brand->designer;?></p>
            <div class="vote">
              <i class="icon cb-icon icon-answer"></i>
              4128
            </div>
          </div>
        </a>
        <!--品牌设计师-->
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



<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
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
    .sidebar('attach events', '.cb-addCompare', 'show')
    .sidebar('attach events', '.cb-closeCompare', 'hide')
  ;
  $('.cb-addCompare').click(function(){
    $(this).addClass('selected');
  });

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

</body>
</html>