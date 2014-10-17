<!-- brand designer page -->
<section class="content" id="b_s_designer">
    <div class="fou-nav">
        设计师
    </div>
    <?php foreach($designers as $designer):?>
    <div class="designer one">
        <div class="profile">
            <div class="img-wrap">
                <img src="<?php echo $designer['avatar'];?>?w=90&h=90" alt="">
            </div>
            <p class="name"><em>
                <?php echo $designer['name_cn'];?><br><?php echo $designer['name_en'];?>
            </em></p>
        </div>
        <p class="desc">
            <?php echo $designer['description'];?>
        </p>
    </div>
    <?php endforeach;?>
</section>