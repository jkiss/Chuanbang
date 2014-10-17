<div class="ui tiny header">
    <a href="javascript:;" class="cb-more"><i class="icon-more cb-icon icon"></i> 更多</a>
    <i class="icon cb-icon icon-event"></i>话题
</div>
<div class="ui four connected items cb-list cb-newsList cb-eventList">
    <div class="row">
        <?php if(!empty($latest_topics)):?>
            <?php foreach($latest_topics as $topic):?>
                <a data-praise='<?php echo $topic['praise'];?>' data-question='<?php echo $topic['ques_id'];?>' data-id='<?php echo $topic['id'];?>'
                   class="item cb-itemHover" href="<?php echo Yii::app()->baseUrl.'/topic/view/'.$topic['id'];?>">
            <div class="image">
                <?php if(!empty($topic['img'])):?>
                    <img class="load" data-img="<?php echo $topic['img'];?>" style="background:url(/style/base/slippry/assets/img/sy-loader.gif) no-repeat center center"
                         src="/page/header/img/img.png">
                <?php endif;?>
            </div>
            <div class="content">
                <h3 class="description"><?php echo $topic['title'];?></h3>
            </div>
        </a>
        <?php endforeach;?>
        <?php endif;?>
    </div>
</div><!--话题-->