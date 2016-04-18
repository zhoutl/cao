<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo ($meta_title); ?>|商品管理平台</title>
    <link href="/cao/Public/favicon.ico" type="image/x-icon" rel="shortcut icon">
    <link rel="stylesheet" type="text/css" href="/cao/Public/Admin/css/base.css" media="all">
    <link rel="stylesheet" type="text/css" href="/cao/Public/Admin/css/common.css" media="all">
    <link rel="stylesheet" type="text/css" href="/cao/Public/Admin/css/module.css">
    <link rel="stylesheet" type="text/css" href="/cao/Public/Admin/css/style.css" media="all">
	<link rel="stylesheet" type="text/css" href="/cao/Public/Admin/css/<?php echo (C("COLOR_STYLE")); ?>.css" media="all">
     <!--[if lt IE 9]>
    <script type="text/javascript" src="/cao/Public/static/jquery-1.10.2.min.js"></script>
    <![endif]--><!--[if gte IE 9]><!-->
    <script type="text/javascript" src="/cao/Public/static/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="/cao/Public/Admin/js/jquery.mousewheel.js"></script>
    <!--<![endif]-->
    
</head>
<body>
    <!-- 头部 -->
    <div class="header">
        <!-- Logo -->
        <span class="logo"></span>
        <!-- /Logo -->

        <!-- 主导航 -->
        <ul class="main-nav">
            <?php if(is_array($__MENU__["main"])): $i = 0; $__LIST__ = $__MENU__["main"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li class="<?php echo ((isset($menu["class"]) && ($menu["class"] !== ""))?($menu["class"]):''); ?>"><a href="<?php echo (u($menu["url"])); ?>"><?php echo ($menu["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
        <!-- /主导航 -->

        <!-- 用户栏 -->
        <div class="user-bar">
            <a href="javascript:;" class="user-entrance"><i class="icon-user"></i></a>
            <ul class="nav-list user-menu hidden">
                <li class="manager">你好，<em title="<?php echo session('user_auth.username');?>"><?php echo session('user_auth.username');?></em></li>
                <li><a href="<?php echo U('User/updatePassword');?>">修改密码</a></li>
                <li><a href="<?php echo U('User/updateNickname');?>">修改昵称</a></li>
                <li><a href="<?php echo U('Public/logout');?>">退出</a></li>
            </ul>
        </div>
    </div>
    <!-- /头部 -->

    <!-- 边栏 -->
    <div class="sidebar">
        <!-- 子导航 -->
        
            <div id="subnav" class="subnav">
                <?php if(!empty($_extra_menu)): ?>
                    <?php echo extra_menu($_extra_menu,$__MENU__); endif; ?>
                <?php if(is_array($__MENU__["child"])): $i = 0; $__LIST__ = $__MENU__["child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub_menu): $mod = ($i % 2 );++$i;?><!-- 子导航 -->
                    <?php if(!empty($sub_menu)): if(!empty($key)): ?><h3><i class="icon icon-unfold"></i><?php echo ($key); ?></h3><?php endif; ?>
                        <ul class="side-sub-menu">
                            <?php if(is_array($sub_menu)): $i = 0; $__LIST__ = $sub_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li>
                                    <a class="item" href="<?php echo (u($menu["url"])); ?>"><?php echo ($menu["title"]); ?></a>
                                </li><?php endforeach; endif; else: echo "" ;endif; ?>
                        </ul><?php endif; ?>
                    <!-- /子导航 --><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        
        <!-- /子导航 -->
    </div>
    <!-- /边栏 -->

    <!-- 内容区 -->
    <div id="main-content">
        <div id="top-alert" class="fixed alert alert-error" style="display: none;">
            <button class="close fixed" style="margin-top: 4px;">&times;</button>
            <div class="alert-content">这是内容</div>
        </div>
        <div id="main" class="main">
            
            <!-- nav -->
            <?php if(!empty($_show_nav)): ?><div class="breadcrumb">
                <span>您的位置:</span>
                <?php $i = '1'; ?>
                <?php if(is_array($_nav)): foreach($_nav as $k=>$v): if($i == count($_nav)): ?><span><?php echo ($v); ?></span>
                    <?php else: ?>
                    <span><a href="<?php echo ($k); ?>"><?php echo ($v); ?></a>&gt;</span><?php endif; ?>
                    <?php $i = $i+1; endforeach; endif; ?>
            </div><?php endif; ?>
            <!-- nav -->
            

            
    <script type="text/javascript" src="/cao/Public/static/uploadify/jquery.uploadify.min.js"></script>
    <script type="text/javascript" src="/cao/Public/Admin/js/myQuery.js"></script>
    <script type="text/javascript">
        $(function(){
            materialChange();
        });
        function  materialChange(){
            var $pro = $("#material");
            setPurity($pro.val());
        }
        function setPurity(pid){
            var $purity = $("#purity");
            $.ajax({
                url: "<?php echo U('getPurity') ;?>",
                data:{pid:pid},
                type:'post',
                dataType:'json',
                context: document.body,
                success: function(data){
                    var option, modelVal;
                    if(data.status==1) {
                        for (var i = 0, len = data['data'].length; i < len; i++) {
                            modelVal = data['data'][i];
                            option = "<option value='" + modelVal['id'] + "'>" + modelVal['title'] + "</option>";
                            $purity.append(option);
                        }
                    }else{
                        $purity.empty();
                    }

              }
            }
            );


        }
    </script>
    <div class="main-title">
        <h2><?php echo isset($info['id'])?'编辑':'新增';?>商品类别</h2>
    </div>
    <form action="<?php echo U();?>" method="post" class="form-horizontal" enctype="multipart/form-data">
        <div class="form-item">
            <label class="item-label">商品款式选择<span class="check-tips"></span></label>
            <div class="controls" >
                <select name="style_id" class="input-2x">
                    <?php if(is_array($style)): $i = 0; $__LIST__ = $style;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$style): $mod = ($i % 2 );++$i;?><option value="<?php echo ($style["id"]); ?>"><?php echo ($style["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
        </div>
        <div class="form-item">
            <label class="item-label">适合人群<span class="check-tips"></span></label>
            <div class="controls">
                <select name="crowd" class="input-2x">
                    <option value="0">适合人群</option>
                    <option value="1">男性</option>
                    <option value="2">女性</option>
                    <option value="3">中性</option>
                    <option value="4">儿童</option>
                </select>
            </div>
        </div>
        <div class="form-item">
            <label class="item-label">材质<span class="check-tips"></span></label>
            <div class="controls">
                <select name="material" id="material" onchange="materialChange();" class="input-2x">
                    <?php if(is_array($materials)): $i = 0; $__LIST__ = $materials;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cate): $mod = ($i % 2 );++$i;?><option value="<?php echo ($cate["id"]); ?>"><?php echo ($cate["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
                <select name="purity" id="purity" class="input-2x">

                </select>
            </div>
        </div>
        <div class="form-item">
            <label class="item-label">商品名称<span class="check-tips"></span></label>
            <div class="controls">
                <input type="text" class="text input-large" name="item_name" value="<?php echo ((isset($info["item_name"]) && ($info["item_name"] !== ""))?($info["item_name"]):''); ?>">
            </div>
        </div>

        <div class="form-item" id="tab1">
            <label class="item-label">商品图片<span class="check-tips"></span></label>
            <div class="controls">
                <input type="file" id="upload_picture">
                <!--<input type="hidden" name="<?php echo ($field["name"]); ?>" id="cover_id_<?php echo ($field["name"]); ?>" value="<?php echo ($data[$field['name']]); ?>"/>-->
                <div class="upload-img-box">
                    <?php if(!empty($picture)): if(is_array($picture)): $i = 0; $__LIST__ = $picture;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$picture): $mod = ($i % 2 );++$i;?><div class="upload-pre-item">
                                <input type="hidden" name="icon[]" value="<?php echo ($picture['path']); ?>" class="icon" />
                                <img src="/cao<?php echo ($picture['path']); ?>" data-id="<?php echo ($picture['id']); ?>"/>
                                <span class='btn-close' title='删除图片'></span>
                            </div><?php endforeach; endif; else: echo "" ;endif; endif; ?>
                </div>
                <script type="text/javascript">
                    //上传图片
                    /* 初始化上传插件 */
                    $("#upload_picture").uploadify({
                        "height"          : 30,
                        "swf"             : "/cao/Public/static/uploadify/uploadify.swf",
                        "fileObjName"     : "download",
                        "buttonText"      : "上传图片",
                        "uploader"        : "<?php echo U('File/uploadPicture',array('session_id'=>session_id()));?>",
                        "width"           : 120,
                        'removeTimeout'	  : 1,
                        'fileTypeExts'	  : '*.jpg; *.png; *.gif;',
                        "onUploadSuccess" : uploadPicture,
                        'uploadLimit'     :2,
                    'onFallback' : function() {
                        alert('未检测到兼容版本的Flash.');
                      }
                    });
                    function uploadPicture(file, data){
                        var data = $.parseJSON(data);
                        var src = '';
                        if(data.status){
                          //创建img input close-btn
                             $_img_path = $("<input type='hidden' name='icon[]' class='icon' value=''/>");
                            $_img_path.val(data.path);
                            $('#tab1').append($_img_path);
                            src=data.url || '/cao'+data.path;
                            //创建图片节点
                            $_upload_img = $("<img src=" + src +" title='点击显示大图' > ");
                            $_img_del = $("<span class='btn-close' title='删除图片'></span>");

                            //创建div添加图片节点和删除节点
                            $_upload_item=$("<div class='upload-pre-item'></div>");
                            $_upload_item.append($_upload_img);
                            $_upload_item.append($_img_del);
                            $(".icon").parent().find('.upload-img-box').append($_upload_item);
                        } else {
                            updateAlert(data.info);
                            setTimeout(function(){
                                $('#top-alert').find('button').click();
                                $(that).removeClass('disabled').prop('disabled',false);
                            },1500);
                        }
                    }
                </script>
            </div>
        </div>


        <div class="form-item">
            <label class="item-label">重量（克）<span class="check-tips"></span></label>
            <div class="controls">
                <input type="text" class="text input-large" name="weight" value="<?php echo ((isset($info["weight"]) && ($info["weight"] !== ""))?($info["weight"]):'0'); ?>">
            </div>
        </div>
        <div class="form-item">
            <?php if($info['stock']){ ?>
                <label class="item-label">库存:<span class="check-tips"></span>    <input type="text" class="text input-x" name="stock" value="<?php echo ($info["stock"]); ?>" disabled="ture"></label>
            <div class="controls">
            <label class="item-label">增加库存:<span class="check-tips"></span>   <input type="text" class="text input-x" name="add_stock" value="0"></label>
            </div>
            <?php }else{ ?>
                <label class="item-label">库存<span class="check-tips"></span></label>
                <div class="controls">
                    <input type="text" class="text input-large" name="stock" value="<?php echo ((isset($info["stock"]) && ($info["stock"] !== ""))?($info["stock"]):'1'); ?>">
                </div>
            <?php } ?>

        </div>
        <div class="form-item">
            <label class="item-label">详细描述<span class="check-tips"></span></label>
            <div class="controls">
                <label class="textarea">
                    <textarea name="description"><?php echo ((isset($info["description"]) && ($info["description"] !== ""))?($info["description"]):''); ?></textarea>
                    <?php echo hook('adminArticleEdit', array('name'=>'description','value'=>$info['description']));?>
                </label>
            </div>
        </div>

        <div class="form-item">
            <input type="hidden" name="id" value="<?php echo ((isset($info["id"]) && ($info["id"] !== ""))?($info["id"]):''); ?>">
            <button class="btn submit-btn ajax-post" id="submit" type="submit" target-form="form-horizontal">确 定</button>
            <button class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
        </div>
    </form>

        </div>
        <div class="cont-ft">
            <div class="copyright">
                <div class="fl">感谢使用<a href="http://www.onethink.cn" target="_blank">OneThink</a>管理平台</div>
                <div class="fr">V<?php echo (ONETHINK_VERSION); ?></div>
            </div>
        </div>
    </div>
    <!-- /内容区 -->
    <script type="text/javascript">
    (function(){
        var ThinkPHP = window.Think = {
            "ROOT"   : "/cao", //当前网站地址
            "APP"    : "/cao/index.php?s=", //当前项目地址
            "PUBLIC" : "/cao/Public", //项目公共目录地址
            "DEEP"   : "<?php echo C('URL_PATHINFO_DEPR');?>", //PATHINFO分割符
            "MODEL"  : ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
            "VAR"    : ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"]
        }
    })();
    </script>
    <script type="text/javascript" src="/cao/Public/static/think.js"></script>
    <script type="text/javascript" src="/cao/Public/Admin/js/common.js"></script>
    <script type="text/javascript">
        +function(){
            var $window = $(window), $subnav = $("#subnav"), url;
            $window.resize(function(){
                $("#main").css("min-height", $window.height() - 130);
            }).resize();

            /* 左边菜单高亮 */
            url = window.location.pathname + window.location.search;
            url = url.replace(/(\/(p)\/\d+)|(&p=\d+)|(\/(id)\/\d+)|(&id=\d+)|(\/(group)\/\d+)|(&group=\d+)/, "");
            $subnav.find("a[href='" + url + "']").parent().addClass("current");

            /* 左边菜单显示收起 */
            $("#subnav").on("click", "h3", function(){
                var $this = $(this);
                $this.find(".icon").toggleClass("icon-fold");
                $this.next().slideToggle("fast").siblings(".side-sub-menu:visible").
                      prev("h3").find("i").addClass("icon-fold").end().end().hide();
            });

            $("#subnav h3 a").click(function(e){e.stopPropagation()});

            /* 头部管理员菜单 */
            $(".user-bar").mouseenter(function(){
                var userMenu = $(this).children(".user-menu ");
                userMenu.removeClass("hidden");
                clearTimeout(userMenu.data("timeout"));
            }).mouseleave(function(){
                var userMenu = $(this).children(".user-menu");
                userMenu.data("timeout") && clearTimeout(userMenu.data("timeout"));
                userMenu.data("timeout", setTimeout(function(){userMenu.addClass("hidden")}, 100));
            });

	        /* 表单获取焦点变色 */
	        $("form").on("focus", "input", function(){
		        $(this).addClass('focus');
	        }).on("blur","input",function(){
				        $(this).removeClass('focus');
			        });
		    $("form").on("focus", "textarea", function(){
			    $(this).closest('label').addClass('focus');
		    }).on("blur","textarea",function(){
			    $(this).closest('label').removeClass('focus');
		    });

            // 导航栏超出窗口高度后的模拟滚动条
            var sHeight = $(".sidebar").height();
            var subHeight  = $(".subnav").height();
            var diff = subHeight - sHeight; //250
            var sub = $(".subnav");
            if(diff > 0){
                $(window).mousewheel(function(event, delta){
                    if(delta>0){
                        if(parseInt(sub.css('marginTop'))>-10){
                            sub.css('marginTop','0px');
                        }else{
                            sub.css('marginTop','+='+10);
                        }
                    }else{
                        if(parseInt(sub.css('marginTop'))<'-'+(diff-10)){
                            sub.css('marginTop','-'+(diff-10));
                        }else{
                            sub.css('marginTop','-='+10);
                        }
                    }
                });
            }
        }();
    </script>
    
    <script type="text/javascript">
        Think.setValue("style_id", <?php echo ((isset($info["style_id"]) && ($info["style_id"] !== ""))?($info["style_id"]): 0); ?>);
        Think.setValue("material", <?php echo ((isset($info["material"]) && ($info["material"] !== ""))?($info["material"]): 0); ?>);
        Think.setValue("purity", <?php echo ((isset($info["purity"]) && ($info["purity"] !== ""))?($info["purity"]): 0); ?>);
        Think.setValue("crowd", <?php echo ((isset($info["crowd"]) && ($info["crowd"] !== ""))?($info["crowd"]): 0); ?>);
        Think.setValue("pid", <?php echo ((isset($info["pid"]) && ($info["pid"] !== ""))?($info["pid"]): 0); ?>);
        Think.setValue("hide", <?php echo ((isset($info["hide"]) && ($info["hide"] !== ""))?($info["hide"]): 0); ?>);
        Think.setValue("is_dev", <?php echo ((isset($info["is_dev"]) && ($info["is_dev"] !== ""))?($info["is_dev"]): 0); ?>);
        //导航高亮
        highlight_subnav('<?php echo U('index');?>');
    </script>

</body>
</html>