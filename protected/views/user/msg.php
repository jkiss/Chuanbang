<!-- user MSG page -->
<section class="content" id="user_msg">
    <div class="user-main">
        <div class="top">
            <div class="send-msg transition">
                <i class="icon-msg-1"></i>
                发私信
            </div>
            <div class="total">
                共261组私信
            </div>
        </div>
        <ul class="msg-box">
            <li class="one">
                <!-- if is a new: -->
                <div class="tip"></div>
                <!-- endif; -->
                <div class="cancel icon-cancel-2"></div>
                <div class="img-wrap">
                    <img src="/images/star/star1.jpg" alt="">
                </div>
                <p class="some-data">
                    <span class="name">lydia</span>
                    <span class="date">2012年6月12日</span>
                    <span class="time">09：12</span>
                </p>
                <p class="desc">
                    感谢您关注我！想看到更多精彩独家内容，就订阅我吧，回复DY即可。感谢您关注我！想看到更多精彩独家内容，就订阅我吧，回复DY即可。感谢您关注我！想看到更多精彩独家内容，就订阅我吧，回复DY即可。
                </p>
            </li>
            <li class="one">
                <!-- if is a new: -->
                <div class="tip"></div>
                <!-- endif; -->
                <div class="cancel icon-cancel-2"></div>
                <div class="img-wrap">
                    <img src="/images/star/star1.jpg" alt="">
                </div>
                <p class="some-data">
                    <span class="name">lydia</span>
                    <span class="date">2012年6月12日</span>
                    <span class="time">09：12</span>
                </p>
                <p class="desc">
                    感谢您关注我！想看到更多精彩独家内容，就订阅我吧，回复DY即可。感谢您关注我！想看到更多精彩独家内容，就订阅我吧，回复DY即可。感谢您关注我！想看到更多精彩独家内容，就订阅我吧，回复DY即可。
                </p>
            </li>
        </ul>
    </div>
    <!-- Write msg box -->
    <div id="write_msg">
        <div class="panel">
            <i class="cancel icon-cancel-1"></i>
            <div class="nick">
                <label for="nick_name">发送给</label>
                <input type="text" class="name" id="nick_name" placeholder="请输入用户昵称">
                <ul class="drop-list">
                    <li class="transition">燃烧的小宇宙</li>
                    <li class="transition">名字要很长很长很长很长很长很长很长很长</li>
                    <li class="transition">萌萌哒小胖子噼噼啪啪</li>
                    <li class="transition">燃烧的小宇宙</li>
                    <li class="transition">燃烧的小宇宙</li>
                    <li class="transition">燃烧的小宇宙</li>
                </ul>
            </div>
            <div class="words">
                <label for="nick_say">内&nbsp;&nbsp;&nbsp;容</label>
                <textarea name="words" id="nick_say" class="says" placeholder="请输入私信内容"></textarea>
            </div>
            <div class="ctrl">
                <span class="clear transition">清 空</span>
                <span class="send transition">发送</span>
            </div>
        </div>
    </div>
</section>

<script>
function invokeHere(){
    // =================   Write Msg box   =================
    $('.send-msg').on('click', function(event) {
        $('#write_msg').css('display', 'block').animate({
            'opacity': 1
        }, 150);
    });
    $('#write_msg').find('.cancel').on('click', function(event) {
        $('#write_msg').animate({
            'opacity': 0
        },
            150, function() {
            $(this).css('display', 'none');
        });
    });
}
</script>