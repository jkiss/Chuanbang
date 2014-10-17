window.CB = {
    // 异步加载css+js
    asynloader:{
        loadCss:function(href) {
            var a=document.createElement('link');
            a.setAttribute('type','text/css');
            a.setAttribute('rel','stylesheet');
            a.setAttribute('href',href);
            document.body.appendChild(a);
        },
        loadJs:function(b, callback) {
            if(!(b instanceof Array)) b = [b];
            var i = 0,len = b.length,counter = 0;
            while(i < len) {
                var d=document.createElement('script');
                d.setAttribute('type','text/javascript');
                d.setAttribute('src',b[i]);
                document.body.appendChild(d);
                if(d.readyState) {
                    d.onreadystatechange = function() {
                        if(d.readyState == "loaded" || d.readyState == "complete" ){
                            if(counter++ == len-1) {
                                d.onreadystatechange = null;
                                callback && callback();
                            }
                        }
                    };
                } else {
                    d.onload = function() {
                        if(counter++ == len-1) {
                            d.onload = null;
                            callback && callback();
                        }
                    }
                }
                i++;
            }
        }
    },
    // ajax上传
    uploader:{}
};
//ajax上传
CB.uploader.init = function(options) {
    CB.uploader.options = $.extend({
        action:'/asynUpload/image',
        multiple:true,
        uploadButtonText:'选择文件',
        params:{}
    }, options);
    if(CB.uploader.options.element != undefined) {
        var $ele = $(CB.uploader.options.element),
            crop_w = $ele.attr("crop-width"),
            crop_h = $ele.attr("crop-height");
        if(crop_w != undefined && parseInt(crop_w) > 0) {
            CB.uploader.options.params.w = parseInt(crop_w);
        }
        if(crop_h != undefined && parseInt(crop_h) > 0) {
            CB.uploader.options.params.h = parseInt(crop_h);
        }
    }

    CB.asynloader.loadCss('/style/base/fileuploader/fileuploader.css');
    CB.asynloader.loadJs(
        [
            '/style/base/fileuploader/fileuploader.js'
        ],
        function() {
            var uploader = new qq.FileUploader(CB.uploader.options);
        }
    );
}
$(function() {
    var CONTEXT_URL = window.CONTEXT_URL || '';

    // 验证注册邮箱
    $('form[name="form-register"] input[name="email"]').bind('blur', function() {
        var email = $(this).val();
        $.post(CONTEXT_URL + '/user/verifyEmail', {email:email}, function(rs) {
            if(rs.data.exist != false) {
                $('form[name="form-register"] .tips').text('邮箱已被注册');
            }
        },'json')
    });
    // 注册
    $('#btn-register').click(function() {
        $('form[name="form-register"]').submit();
    });

    // 验证登录邮箱
    $('form[name="form-sign-in"] input[name="email"]').bind('blur', function() {
        var email = $(this).val();
        $.post(CONTEXT_URL + '/user/verifyEmail', {email:email}, function(rs) {
            if(rs.data.exist != true) {
                $('form[name="form-sign-in"] .tips').text('邮箱不存在');
            }
        },'json')
    });
    // 登录
    $('#btn-sign-in').click(function() {
        $('form[name="form-sign-in"]').submit();
    });

    if($(".cb-setting").length > 0) {
        // 上传头像
        CB.uploader.init({
            element:$("#profile-head .cb-upload")[0],
            multiple:false,
            uploadButtonText:'修改头像',
            onComplete:function(id, fileName, rs) {
                $("#profile-head img:first").attr('src', rs.url);
                $.post('/user/uploadHead', {head:rs.url},function() {
                    $(".qq-upload-success").fadeOut('fast');
                },'json');
            }
        });

        // 省市级联
        $("#region .menu .item:not(.active)").click(function() {
            var id = $(this).attr('data-value');
            $.post('/location/getCities', {region:id}, function(rs) {
                var html = '';
                var active = true;
                $.each(rs.data, function(i, v) {
                    html += '<div class="item {active}" data-value="{value}">{label}</div>'.replace('{value}', v.id).replace('{label}', v.name).replace('{active}', active ? 'active' : '');
                    if(active) {
                        $("#city .text:first").text(v.name);
                    }
                    active = false;
                });
                $("#city .menu").html(html);
                $("#city").show();
                $("#city .menu .item:not(.active)").unbind('click').bind('click', function() {
                    $("#city .text:first").text($(this).text());
                    $(this).siblings('.item').removeClass('active');
                    $(this).addClass('active');
                });
            },'json');
        });

        // 提交个人资料
        $('#form-profile #btn-profile').click(function() {
            $("input[name='User[region]']").val($("#region .menu .item.active").text());
            $("input[name='User[city]']").val($("#city .menu .item.active").text());
            $("input[name='User[job]']").val($("#job .menu .item.active").text());
            $('#form-profile').submit();
        });
    }

    // 草稿
    if($(".cb-draft").length > 0) {
        // 草稿-上传图片
        var running = 0;
        CB.uploader.init({
            element:$(".cb-draft .cb-upload")[0],
            uploadButtonText:'选择照片文件',
            onSubmit:function(id,fileName) {
                running++;
            },
            onComplete:function(id, fileName, rs) {
                running--;
                console.log("<input name='images[]' type='hidden' value='{url}'".replace('{url}', rs.url));
                $("#form-draft .cb-uploads").append("<input name='images[]' type='hidden' value='{url}'/>".replace('{url}', rs.url));
                if(running ==0) {
                    $("#form-draft").submit();
                }
            }
        });

        $(".icon-deleteCompare").click(function() {
            var $this = $(this),
                params = {
                id:$(this).attr('data-id')
            };
            $.post('/draft/delete', params, function(rs) {
                $this.parent('.image').parent('.item').remove();
            }, 'json');
        });
    }

    // 收藏-答案
    $(".cb-fav-answer").click(function() {
        var $this=$(this),
            fav = $(this).attr("fav").toLowerCase(),
            params = {
                id:$(this).attr('data-id')
            };
        if(fav == 'n') {
            $.post('/answer/addFav',params, function(rs) {
                if(rs.ret == 0) {
                    $this.find(".description").toggle('hide');
                    $this.attr('fav','y');
                }
            },'json');
        }
        if(fav == 'y') {
            $.post('/answer/cancelFav',params, function(rs) {
                if(rs.ret == 0) {
                    $this.find(".description").toggle('hide');
                    $this.attr('fav','n')
                }
            },'json');
        }
    });

    // 收藏-品牌
    $(".cb-fav-brand").click(function() {
        var $this=$(this),
            fav = $(this).attr("fav").toLowerCase(),
            params = {
                id:$(this).attr('data-id')
            };
        if(fav == 'n') {
            $.post('/brand/addFav',params, function(rs) {
                if(rs.ret == 0) {
                    $this.find(".description").toggle('hide');
                    $this.attr('fav','y');
                    $this.siblings("strong:first").text(parseInt($this.siblings("strong:first").text()) + 1);
                }
            },'json');
        }
        if(fav == 'y') {
            $.post('/brand/cancelFav',params, function(rs) {
                if(rs.ret == 0) {
                    $this.find(".description").toggle('hide');
                    $this.attr('fav','n')
                    $this.siblings("strong:first").text(parseInt($this.siblings("strong:first").text()) - 1);
                }
            },'json');
        }
    });

    // 收藏-名人
    $(".cb-fav-star").click(function() {
        var $this=$(this),
            fav = $(this).attr("fav").toLowerCase(),
            params = {
                id:$(this).attr('data-id')
            };
        if(fav == 'n') {
            $.post('/celebrity/addFav',params, function(rs) {
                if(rs.ret == 0) {
                    $this.find(".description").toggle('hide');
                    $this.attr('fav','y');
                    $this.siblings("strong:first").text(parseInt($this.siblings("strong:first").text()) + 1);
                }
            },'json');
        }
        if(fav == 'y') {
            $.post('/celebrity/cancelFav',params, function(rs) {
                if(rs.ret == 0) {
                    $this.find(".description").toggle('hide');
                    $this.attr('fav','n')
                    $this.siblings("strong:first").text(parseInt($this.siblings("strong:first").text()) - 1);
                }
            },'json');
        }
    });

    if($(".cb-compareList").length > 0) {
        var $compare_container = $(".cb-compare-draft"),
            $counter_wraper = $('.cb-compare-count'),
            answer_detail_id = $("#answer_detail_id").val(),
             tp_item = '<a class="item cb-item" href="javascript:;">'
                    + '    <div class="ui image left floated">'
                    + '        <i class="icon icon-deleteCompare cb-icon" data-id="{id}"></i>'
                    + '        <img src="{img}" data-pinit="registered"/>'
                    + '    </div>'
                    + '    <div class="content">'
                    + '        <p class="description">{celebrity}身着{brand}</p>'
                    + '    </div>'
                    + '</a>';
        var loadCompareInDraft = function($ele) {
            $.post('/compare/list', function(rs) {
                if(rs.ret == 0) {
                    $counter_wraper.html(rs.data.length);
                    $ele.empty();
                    $.each(rs.data, function(i, item) {
                        $ele.append(tp_item.replace('{img}', item.img).replace('{id}', item.id).replace('{celebrity}', item.star).replace('{brand}', item.brand));
                    })

                    // 删除
                    if($(".icon-deleteCompare").length > 0) {
                        $(".icon-deleteCompare").on('click', function() {
                            var $this = $(this),params = {id:$this.attr('data-id')};
                            $.post('/compare/delete', params, function(rs) {
                                if(rs.ret == 0) {
                                    $counter_wraper.html(parseInt($counter_wraper.html())-1);
                                    $this.parents('.cb-item').remove();
                                }
                            },'json');
                        });
                    }
                }
            },'json');
        };
        loadCompareInDraft($compare_container);
        // 对比-添加
        $('.cb-addCompare:not(.selected)').click(function(){
            var $this = $(this),
                $target = $this.parent().find('li.sy-active'),
                qp_id = $target.attr('data-id'),
                params = {qp_id:qp_id,ans_detail_id:answer_detail_id};
            $.post('/compare/add', params, function(rs) {
                if(rs.ret == 0) {
                    $this.addClass('selected');
                    $counter_wraper.html(parseInt($counter_wraper.html()) + 1);
                    loadCompareInDraft($compare_container);
                }
            },'json');
        });
    }
});