<!-- The Setting content -->
<section class="content" id="set">
    <form method="post" id="user-profile" role="form" action="/user/profile" target="_self">
    <ul class="h-centra">
        <li class="ava-box">
            <span class="panel">
                <img src="<?php echo strpos($user->head,'chuanbang') === false ? $user->head : $user->head;?>"/>
            </span>
            <span class="ctrl">
                <i class="icon-draft-blank"></i>
                <input type="file" name="uploadAva" id="avatar">
                修改头像
            </span>
        </li>
        <li>
            <label for="">昵称</label>
            <input type="text" name="User[nick]" value="<?php echo $user->nick;?>">
        </li>
        <li>
            <label for="">签名</label>
            <input type="text" name="User[signature]" value="<?php echo $user->signature;?>">
        </li>
        <li>
            <label for="">地址</label>
            <ul class="addr">
                <div class="styled-select">
                    <select data-city="None" id="area" name="User[region]">
                        <option value="不填写">不填写</option>
                        <option value="北京">北京</option>
                        <option value="上海">上海</option>
                        <option value="广东">广东</option>
                        <option value="安徽">安徽</option>
                        <option value="重庆">重庆</option>
                        <option value="福建">福建</option>
                        <option value="甘肃">甘肃</option>
                        <option value="广西">广西</option>
                        <option value="贵州">贵州</option>
                        <option value="海南">海南</option>
                        <option value="河南">河南</option>
                        <option value="河北">河北</option>
                        <option value="湖北">湖北</option>
                        <option value="湖南">湖南</option>
                        <option value="内蒙古">内蒙古</option>
                        <option value="江苏">江苏</option>
                        <option value="江西">江西</option>
                        <option value="吉林">吉林</option>
                        <option value="辽宁">辽宁</option>
                        <option value="宁夏">宁夏</option>
                        <option value="四川">四川</option>
                        <option value="黑龙江">黑龙江</option>
                        <option value="山东">山东</option>
                        <option value="山西">山西</option>
                        <option value="青海">青海</option>
                        <option value="西藏">西藏</option>
                        <option value="云南">云南</option>
                        <option value="天津">天津</option>
                        <option value="新疆">新疆</option>
                        <option value="浙江">浙江</option>
                        <option value="陕西">陕西</option>
                        <option value="台湾">台湾</option>
                        <option value="香港">香港</option>
                        <option value="澳门">澳门</option>
                        <option value="海外">海外</option>
                    </select>
                </div>
                <div class="styled-select ar">
                    <select name="User[city]" id="city" style="display: none;"></select>
                </div>
            </ul>
        </li>
        <li>
            <label for="">公司</label>
            <input type="text" name="User[company]" value="<?php echo $user->company;?>">
        </li>
        <li>
            <label for="">职位</label>
            <input type="text" name="User[job]" value="<?php echo $user->job;?>">
        </li>
        <li>
            <label for="" class="last">自我介绍</label>
            <textarea name="User[description]" id="self" class="self-intro"><?php echo $user->description;?></textarea>
        </li>
        <li class="form-ctrl">
            <span class="sure">确&nbsp;&nbsp;认</span>
        </li>
    </ul>
    </form>
</section>
<script>
    jQuery.fn.doOnce = function(func, arguments) {
        this.length && func.apply(this, arguments);
        return this;
    }
    var g = {
        "北京": ["东城区", "西城区", "朝阳区", "丰台区", "石景山区", "海淀区", "门头沟区", "房山区", "通州区", "顺义区", "昌平区", "大兴区", "怀柔区", "平谷区", "密云县", "延庆县"],
        "上海": ["黄浦区", "卢湾区", "徐汇区", "长宁区", "静安区", "普陀区", "闸北区", "虹口区", "杨浦区", "闵行区", "宝山区", "嘉定区", "浦东新区", "金山区", "松江区", "青浦区", "南汇区", "奉贤区", "崇明区"],
        "广东": ["广州", "韶关", "深圳", "珠海", "汕头", "佛山", "江门", "湛江", "茂名", "肇庆", "惠州", "梅州", "汕尾", "河源", "阳江", "清远", "东莞", "中山", "潮州", "揭阳", "云浮"],
        "安徽": ["合肥", "芜湖", "蚌埠", "淮南", "马鞍山", "淮北", "铜陵", "安庆", "黄山", "滁州", "阜阳", "宿州", "巢湖", "六安", "亳州", "池州", "宣城"],
        "重庆": ["万州区", "涪陵区", "渝中区", "大渡口区", "江北区", "沙坪坝区", "九龙坡区", "南岸区", "北碚区", "万盛区", "双桥区", "渝北区", "巴南区", "黔江区", "长寿区", "綦江县", "潼南县", "铜梁县", "大足县", "荣昌县", "璧山县", "梁平县", "城口县", "丰都县", "垫江县", "武隆县", "忠县", "开县", "云阳县", "奉节县", "巫山县", "巫溪县", "石柱土家族自治县", "秀山土家族苗族自治县", "酉阳土家族苗族自治县", "彭水苗族土家族自治县", "江津市", "合川市", "永川区", "南川市"],
        "福建": ["福州", "厦门", "莆田", "三明", "泉州", "漳州", "南平", "龙岩", "宁德"],
        "甘肃": ["兰州", "嘉峪关", "金昌", "白银", "天水", "武威", "张掖", "平凉", "酒泉", "庆阳", "定西", "陇南", "临夏", "甘南"],
        "广西": ["南宁", "柳州", "桂林", "梧州", "北海", "防城港", "钦州", "贵港", "玉林", "百色", "贺州", "河池"],
        "贵州": ["贵阳", "六盘水", "遵义", "安顺", "铜仁", "黔西南", "毕节", "黔东南", "黔南"],
        "海南": ["海口", "三亚", "其他"],
        "河北": ["石家庄", "唐山", "秦皇岛", "邯郸", "邢台", "保定", "张家口", "承德", "沧州", "廊坊", "衡水"],
        "黑龙江": ["哈尔滨", "齐齐哈尔", "鸡西", "鹤岗", "双鸭山", "大庆", "伊春", "佳木斯", "七台河", "牡丹江", "黑河", "绥化", "大兴安岭"],
        "河南": ["郑州", "开封", "洛阳", "平顶山", "安阳", "鹤壁", "新乡", "焦作", "濮阳", "许昌", "漯河", "三门峡", "南阳", "商丘", "信阳", "周口", "驻马店"],
        "湖北": ["武汉", "黄石", "十堰", "宜昌", "襄樊", "鄂州", "荆门", "孝感", "荆州", "黄冈", "咸宁", "随州", "恩施土家族苗族自治州"],
        "湖南": ["长沙", "株洲", "湘潭", "衡阳", "邵阳", "岳阳", "常德", "张家界", "益阳", "郴州", "永州", "怀化", "娄底", "湘西土家族苗族自治州"],
        "内蒙古": ["呼和浩特", "包头", "乌海", "赤峰", "通辽", "鄂尔多斯", "呼伦贝尔", "兴安盟", "锡林郭勒盟", "乌兰察布盟", "巴彦淖尔盟", "阿拉善盟"],
        "江苏": ["南京", "无锡", "徐州", "常州", "苏州", "南通", "连云港", "淮安", "盐城", "扬州", "镇江", "泰州", "宿迁"],
        "江西": ["南昌", "景德镇", "萍乡", "九江", "新余", "鹰潭", "赣州", "吉安", "宜春", "抚州", "上饶"],
        "吉林": ["长春", "吉林", "四平", "辽源", "通化", "白山", "松原", "白城", "延边朝鲜族自治州"],
        "辽宁": ["沈阳", "大连", "鞍山", "抚顺", "本溪", "丹东", "锦州", "营口", "阜新", "辽阳", "盘锦", "铁岭", "朝阳", "葫芦岛"],
        "宁夏": ["银川", "石嘴山", "吴忠", "固原"],
        "青海": ["西宁", "海东", "海北", "黄南", "海南", "果洛", "玉树", "海西"],
        "山西": ["太原", "大同", "阳泉", "长治", "晋城", "朔州", "晋中", "运城", "忻州", "临汾", "吕梁"],
        "山东": ["济南", "青岛", "淄博", "枣庄", "东营", "烟台", "潍坊", "济宁", "泰安", "威海", "日照", "莱芜", "临沂", "德州", "聊城", "滨州", "菏泽"],
        "四川": ["成都", "自贡", "攀枝花", "泸州", "德阳", "绵阳", "广元", "遂宁", "内江", "乐山", "南充", "眉山", "宜宾", "广安", "达州", "雅安", "巴中", "资阳", "阿坝", "甘孜", "凉山"],
        "天津": ["和平区", "河东区", "河西区", "南开区", "河北区", "红桥区", "塘沽区", "汉沽区", "大港区", "东丽区", "西青区", "津南区", "北辰区", "武清区", "宝坻区", "宁河县", "静海县", "蓟县", "滨海新区", "保税区"],
        "西藏": ["拉萨", "昌都", "山南", "日喀则", "那曲", "阿里", "林芝"],
        "新疆": ["乌鲁木齐", "克拉玛依", "吐鲁番", "哈密", "昌吉", "博尔塔拉", "巴音郭楞", "阿克苏", "克孜勒苏", "喀什", "和田", "伊犁", "塔城", "阿勒泰", "石河子"],
        "云南": ["昆明", "曲靖", "玉溪", "保山", "昭通", "楚雄", "红河", "文山", "思茅", "西双版纳", "大理", "德宏", "丽江", "怒江", "迪庆", "临沧"],
        "浙江": ["杭州", "宁波", "温州", "嘉兴", "湖州", "绍兴", "金华", "衢州", "舟山", "台州", "丽水"],
        "陕西": ["西安", "铜川", "宝鸡", "咸阳", "渭南", "延安", "汉中", "榆林", "安康", "商洛"],
        "台湾": ["台北", "高雄", "其他"],
        "香港": ["香港"],
        "澳门": ["澳门"],
        "海外": ["美国", "英国", "法国", "俄罗斯", "加拿大", "巴西", "澳大利亚", "印尼", "泰国", "马来西亚", "新加坡", "菲律宾", "越南", "印度", "日本", "新西兰", "韩国", "瑞典", "其他"],
        "不填写": ["保密"]
    };
    var init_select = {
        'area': '<?php echo $user->region;?>',
        'city': '<?php echo $user->city;?>'
    }
    $("#area").doOnce(function() {
        var _this = this,
            $city = $("#city");
        _this.change(function() {
            var select_area = _this.find("option:selected").val(),
                city_array = g[select_area];
            $city.empty().show();
            select_area in g
                ? $.each(city_array, function(select_area, city_array) {
                    var new_city = $("<option/>").text(city_array).attr("value", city_array),
                        f = _this.data("city");
                    city_array === f && new_city.attr("selected", "selected"), new_city.appendTo($city)
                })
                : $city.html('<option value="保密">保密</option>').hide();
        });
        _this.keyup(function($city) {
            ($city.keyCode === 38 || $city.keyCode === 40) && _this.trigger("change")
        });

        if(init_select){
            var
                i_area = init_select.area,
                i_city = init_select.city,
                i_city_array = g[i_area];
            
            _this.val(i_area);   // 初始化地区

            i_area in g
                ? function(){
                    var new_city = '';
                    for (var i = 0; i < i_city_array.length; i++) {
                        new_city += '<option value="'+ i_city_array[i] +'">'+ i_city_array[i] +'</option>';
                    };
                    $city.html(new_city).show().val(i_city);  // 初始化城市
                }()
                : $city.html('<option value="保密">保密</option>').hide();
        }else{
            _this.trigger("change");
        }
    }, [init_select]);

    // upload avatar event
    $('#avatar').on('change', function(event) {
        var _file = this.files[0],
            url = '/user/avatar',
            fd = new FormData();
        fd.append('file', _file, _file.name);
        // console.log(_file);
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: fd,
            contentType: false,
            processData: false,
            success: function(data, status, xhr){
                console.log(data);
                if(data.ret !== 0){
                    alert('Some Error');
                }else{
                    $('.panel').find('img').attr('src', data.data.url);
                }
            }
        });
    });

    // submit form
    $('.sure').on('click', function(event) {
        event.preventDefault();
        $('form').submit();
    });
</script>