<div class="ui tiny header">
    <a href="javascript:;" class="cb-more"><i class="icon-more cb-icon icon"></i> 更多</a>
    <i class="icon cb-icon icon-waiting_answer"></i>待答
</div>
<div class="ui five connected items cb-list cb-newList cb-newsList cb-waitList">
    <?php for($i=0, $len=count($pending_questions); $i < $len; $i++):?>
        <?php $question = $pending_questions[$i];?>
        <?php if($i % 5 == 0):?><div class="row"><?php endif;?>
        <a data-praise='<?php echo $question['img_praise'];?>' data-id='<?php echo $question['ques_id'];?>'class="item cb-itemHover" href="javascript:;">
            <div class="image">
                <?php if(!empty($question['img'])):?>
                    <img src="<?php echo $question['img'];?>" data-pinit="registered">
                <?php endif;?>
            </div>
            <div class="content">
                <div class="description"></div>
            </div>
        </a>
        <?php if(($i+1) % 5 == 0 || $i == $len-1):?></div><?php endif;?>
    <?php endfor;?>
</div><!--待答-->