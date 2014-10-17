<!-- The index content -->
<section id="add_suit">
    <div class="u-p-wrap">
        <div class="upload-panel abs-center">
            <p>把照片或文件拖放到这里或</p>
            <div class="upload-btn" id="file_uploader">
                <input type="file" id="upload_imgs" name="files[]" multiple="multiple" data-url="/nokey_cn/upload.php" accept="image/*">
                选择照片文件
            </div>
            <p class="limit-text">*一次上传最多8张照片</p>
        </div>
    </div>

    <div class="upload-preview">
        <ul class="box grid five sep clearfix">
            <li class="column float plus-btn">
                <div class="add-btn">
                    <div class="btn-wrap">
                        <i class="icon-plus"></i>
                    </div>
                </div>
                <input type="file" class="continue-add" name="files" multiple="multiple" data-url="/nokey_cn/upload.php" accept="image/*">
            </li>
        </ul>
        <div class="says">
            <input type="text" name="says" id="question_title" placeholder="说些什么吧">
        </div>
        <div class="sure-or-not">
            <div class="sure transition">确 认</div>
            <i class="not transition">取消</i>
        </div>
    </div>
</section>

<script>
    // if there is a draft, show upload-preview or show u-p-wrap
    $.ajax({
        url: '/question/draft',
        type: 'GET',
        dataType: 'json',
        success: function(data, status, xhr){
            console.log(data.length);
            if(data.length === 0){
                $('.u-p-wrap').css('display', 'block').animate({'opacity': 1}, 150);
            }else{
                var upload_preview = $('.upload-preview'),
                    draft_html = '';
                upload_preview.css('display', 'block').animate({'opacity': 1}, 150);
                for (var i = 0; i < data.length; i++) {
                    draft_html += '<li class="column float"><div class="img-wrap" id="img' + data[i].id + '" data-url="'+ data[i].img +'"><i class="cancel-btn icon-cancel-1"></i><img src="'+ data[i].img + '" alt="#"><div class="progress-wrap"><i class="progress-bar progress-bar-move"></i></div><div class="upload-result">上传失败</div></div><p class="img-title text-overflow">Draft' + data[i].id + '</p></li>';
                };
                upload_preview.find('.plus-btn').before(draft_html);
            }
        },
        error: function(xhr, status, msg){
            console.log(msg);
        },
        complete: function(xhr, status){

        }
    });
    // upload img file
    $('.upload-preview').find('.box').on('mouseenter mouseleave', '.img-wrap', function(event) {
        if(event.type === 'mouseenter'){
            $(this).find('.cancel-btn').css('display', 'block');
        }else if(event.type === 'mouseleave'){
            $(this).find('.cancel-btn').css('display', 'none');
        }
    });
    $('.upload-preview').find('.box').on('click', '.cancel-btn', function(event) {
        event.preventDefault();
        $(this).parent().parent().hide('fast', function(){
            $(this).remove();
        });
    });
    
    // Upload Imgs
    (function($){
        var
            drop_zones = [],             // 拖拽区域元素数组
            inputs = [],                 // 文本域输入数组
            preview_zone,                // 图片预览区域
            preview_wrap_HTML,           // 预览图片外围HTML
            img_files = [],              // 图片文件数组
            max_img_num = 8,             // 允许选择的最大图片数量
            url = '<?php echo Yii::app()->createUrl('image/upload');?>',
            method = 'post',
            data_type = 'json',
            upload_btn,
            uploading = false,
            queuePos = -1,
            unique_id = 0;

        // 初始化各变量
        drop_zones.push($('.upload-panel')[0]);
        inputs.push($('.continue-add')[0]);
        preview_zone = $('.upload-preview').find('.box');
        preview_wrap_HTML = '<li class="column float"><div class="img-wrap"><i class="cancel-btn"></i><img src="'+ 'img_url' + '" alt="#"><div class="progress-wrap"><i class="p-bar"></i></div></div><p class="img-title text-overflow">' + 'this.img_name' + '</p></li>';

        upload_btn = $('.sure');
        // console.log(drop_zones[0]);
        function Init(){
            // 拖拽事件
            $(drop_zones).each(function(index, el) {
                $(this).on('drop', function(event) {
                    event.preventDefault();
                    var files = event.originalEvent.dataTransfer.files;
                    // console.log(files);
                    imgQueue(files);

                    // switch to preview mode
                    $('.u-p-wrap').css('display', 'none');
                    $('.upload-preview').css('display', 'block').animate({'opacity': 1}, 150);
                    // ....upload imgs immediately
                    processQueue();
                });
            });

            // 选择文件事件
            $('input[type=file]').on('change', function(event) {
                var files = event.target.files;
                // console.log(files);
                imgQueue(files);
                $(this).val('');

                // switch to preview mode & ....
                if(this.id === 'upload_imgs'){
                    $('.u-p-wrap').css('display', 'none');
                    $('.upload-preview').css('display', 'block').animate({'opacity': 1}, 150);
                }
                // ....upload imgs immediately
                processQueue();
            });

            // Register img Delete-Btn event
            delBtn();

            // Resgister upload img event
            upload_btn.on('click', function(event) {
                var content = $('#question_title').val(),
                    data = {
                        content: content
                    };

                $.ajax({
                    url: '/question/ask',
                    type: 'POST',
                    dataType: 'json',
                    data: data,
                    success: function(data, status, xhr){
                        console.log(data);
                        if(data.msg === 'success'){
                            window.location.href = 'http://3w.chuanbang.com/question/index';
                        }else{
                            // Fail to Public
                        }
                    },
                    error: function(xhr, status, msg){
                        console.log(msg);
                    },
                    complete: function(xhr, status){

                    }
                });
            });
        }

        function delBtn(){
            preview_zone.on('mouseenter mouseleave', 'li', function(event) {
                if(event.type === 'mouseenter'){
                    // console.log(this);
                    $(this).find('.cancel-btn').css('display', 'block');
                }else if(event.type === 'mouseleave'){
                    // console.log(event.type);
                    $(this).find('.cancel-btn').css('display', 'none');
                }
            });
            preview_zone.on('click', '.cancel-btn', function(event) {
                var id = $(this).parent().attr('id').match(/\d+/)[0];
                // delete img from server
                delImg(parseInt(id));
            });
        }

        function delImg(id){
            // Below is del native imgs
            // for (var i = 0; i < img_files.length; i++) {
            //     if(img_files[i].id === id){
            //         var del = img_files.splice(i, 1);
            //         // console.log(del);
            //     }
            // };

            var img_wrap = $('#img' + id),
                img_url = img_wrap.attr('data-url'),
                data = {
                    url: img_url
                };
            console.log(img_url);
            $.ajax({
                url: '/question/delimg',
                type: 'post',
                data: data,
                dataType: 'json',
                success: function(data, status, xhr){
                    console.log(data);
                    if(data.msg === 'success'){
                        delResult(id, 'success');
                    }else{
                        delResult(id, 'fail');
                    }
                },
                error: function(xhr, status, msg){
                    console.log(msg);
                },
                complete: function(xhr, status){

                }
            });
        }

        function previewImg(id, file){
            preview_zone.find('.plus-btn').before($('<li class="column float"><div class="img-wrap" id="img' + id + '"><i class="cancel-btn icon-cancel-1"></i><img src="'+ window.URL.createObjectURL(file) + '" alt="#"><div class="progress-wrap"><i class="progress-bar progress-bar-move"></i></div><div class="upload-result">上传失败</div></div><p class="img-title text-overflow">' + file.name + '</p></li>'));
        }

        function imgQueue(files){
            for (var i = 0; i < files.length; i++) {
                if(img_files.length >= max_img_num){
                    alert('超过' + max_img_num + '张图片了');
                    break;
                }else{
                    if(isSameImg(files[i])){
                        alert('这是一张相同的图片。。。');
                        continue;
                    }else{
                        img_files.push({
                            'id': unique_id,
                            'file': files[i]
                        });
                        previewImg(unique_id, files[i]);
                        unique_id++;
                    }
                }
            };
        }

        function isSameImg(file){
            for (var i = 0; i < img_files.length; i++) {
                if(img_files[i].file.name === file.name){
                    return true;
                }
            };
            return false;
        }

        function imgProcessBar(id, percent){
            $('#img' + id).find('.progress-bar').css('width', percent + '%');
        }

        function delResult(imgId, flag){
            var img_wrap = $('#img' + imgId),
                result = img_wrap.find('.upload-result');

            if(flag === 'success'){
                img_wrap.parent().hide('fast', function(){
                    $(this).remove();
                });
            }else if(flag === 'fail'){
                result.text('删除失败').animate({
                    'bottom': 0
                },
                    200, function() {
                    setTimeout(function(){
                        result.animate({'bottom': '-50px'}, 200);
                    }, 500);
                });
            }
        }

        function processResult(imgId, flag){
            var img_wrap = $('#img' + imgId),
                result = $('.upload-result');

            if (flag === 'success') {
                result.text('上传成功').animate({
                    'bottom': 0
                },
                    200, function() {
                    setTimeout(function(){
                        result.animate({'bottom': '-50px'}, 200);
                    }, 500);
                });
            }else if(flag === 'fail'){
                result.text('上传失败').animate({
                    'bottom': 0
                },
                    200, function() {
                    setTimeout(function(){
                        result.animate({'bottom': '-50px'}, 200);
                    }, 500);
                });
            }
        }

        function setReturnUrl(imgId, url){
            var img_wrap = $('#img' + imgId);

            img_wrap.attr('data-url', url);
        }

        function processQueue(){
            uploading = true;

            queuePos++;
            if(queuePos >= img_files.length){
                // 此时上传已经结束
                queuePos = -1;
                uploading = false;
                img_files = [];
                return ;
            }

            var id = img_files[queuePos].id,
                file = img_files[queuePos].file,
                fd = new FormData();
            fd.append('file', file);
            fd.append('tag', 'q');

            // Before Upload

            uploading = true;
            $.ajax({
                url: url,
                type: method,
                dataType: data_type,
                data: fd,
                cache: false,
                contentType: false,
                processData: false,
                async: true,
                xhr: function() {
                    var xhrobj = $.ajaxSettings.xhr();
                    if (xhrobj.upload) {
                        xhrobj.upload.addEventListener('progress', function(event) {
                            var percent = 0,
                                position = event.loaded || event.position,
                                total = event.total || e.totalSize;
                            if (event.lengthComputable) {
                                percent = Math.ceil(position / total * 100);
                            }
                            console.log(percent);
                            // 进度条事件
                            imgProcessBar(img_files[queuePos].id, percent);

                        }, false);
                    }

                    return xhrobj;
                },
                success: function(data, message, xhr) {
                    console.log(data);
                    if(data.success === true){
                        processResult(id, 'success');
                        setReturnUrl(id, data.url);
                    }else{
                        processResult(id, 'fail');
                    }
                },
                error: function(xhr, status, errMsg) {
                    console.log(errMsg);
                },
                complete: function(xhr, textStatus) {
                    processQueue();
                }
            });
        }
        Init();

        // -- Disable Document D&D events to prevent opening the file on browser when we drop them
        $(document).on('dragenter', function(e) {
            e.stopPropagation();
            e.preventDefault();
        });
        $(document).on('dragover', function(e) {
            e.stopPropagation();
            e.preventDefault();
        });
        $(document).on('drop', function(e) {
            e.stopPropagation();
            e.preventDefault();
        });
    }(jQuery));
</script>