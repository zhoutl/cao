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
        <h2><?php if(isset($data)): ?>订单管理<?php endif; ?> </h2>
    </div>

    <div class="cf">
        <a class="btn" href="<?php echo U('add');?>">新 增</a>
        <button class="btn ajax-post confirm" url="<?php echo U('del');?>" target-form="ids">删 除</button>
        <!-- 高级搜索 -->
        <div class="search-form fr cf">
            <form action="<?php echo U('index');?>" method="post"  >
                <label>下单时间:</label>
                <input type="text"  name="start_time" value="<?php echo I('start_time');?>" class="time-start text input-2x" placeholder="起始时间">-
                <input type="text"   name="end_time"  value="<?php echo I('end_time');?>" class="time-end text input-2x" placeholder="结束时间">
                <input type="text" name="order_no"  class="text input-4x" value="<?php echo I('order_no');?>" placeholder="请输入订单号">
                <input type="text" name="consignee"  class="text input-3x" value="<?php echo I('consignee');?>" placeholder="请输入收货人">
                <input type="text" name="mobile"  class="text input-4x" value="<?php echo I('mobile');?>" placeholder="请输入收货人号码">
                <button type="submit" class="btn"  >搜索</button>
            </form>
            <!--<div class="sleft">-->
            <!--<input type="text" name="name" class="search-input" value="<?php echo I('name');?>" placeholder="请输入商品名称">-->
            <!--<a class="sch-btn" href="javascript:;" id="search" url="/cao/index.php?s=/admin/order/index.html"><i class="btn-search"></i></a>-->
            <!--</div>-->
        </div>
    </div>

    <div class="data-table table-striped">
        <form class="ids">
            <table align="">
                <thead>
                <tr>
                    <th class="row-selected">
                        <input class="checkbox check-all" type="checkbox">
                    </th>
                    <th>订单号</th>
                    <th>下单时间</th>
                    <th>收货人</th>
                    <th>收货地址</th>
                    <th>总金额</th>
                    <th>物流单号</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php if(!empty($list)): if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$order): $mod = ($i % 2 );++$i;?><tr>
                            <td><input class="ids row-selected" type="checkbox" name="id[]" value="<?php echo ($order["order_id"]); ?>"></td>
                            <td><?php echo ($order["order_no"]); ?></td>
                            <td><?php echo date('Y-m-d H:i',$order['create_time']); ?></td>
                            <td>
                                <?php echo ($order["consignee"]); ?><br>
                                <?php echo ($order["mobile"]); ?>
                            </td>
                            <td><?php echo ($order["address"]); ?></td>
                            <td><?php echo ((isset($order["amount"]) && ($order["amount"] !== ""))?($order["amount"]):0); ?></td>
                            <td><?php echo ($order["shipping_sn"]); ?></td>

                            <td>
                                <a title="编辑" href="<?php echo U('edit?id='.$item['id']);?>">编辑</a>
                                <a class="confirm ajax-get" title="删除" href="<?php echo U('del?id='.$order['order_id']);?>">删除</a>
                            </td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    <?php else: ?>
                    <td colspan="10" class="text-center"> aOh! 暂时还没有内容! </td><?php endif; ?>
                </tbody>
            </table>
        </form>
        <!-- 分页 -->
        <div class="page">
            <?php echo ($_page); ?>
        </div>
    </div>

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
    
    <link href="/cao/Public/static/datetimepicker/css/datetimepicker.css" rel="stylesheet" type="text/css">
    <?php if(C('COLOR_STYLE')=='blue_color') echo '<link href="/cao/Public/static/datetimepicker/css/datetimepicker_blue.css" rel="stylesheet" type="text/css">'; ?>
    <link href="/cao/Public/static/datetimepicker/css/dropdown.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="/cao/Public/static/datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="/cao/Public/static/datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js" charset="UTF-8"></script>
    <script type="text/javascript">
        Think.setValue("category_id", <?php echo ((isset($info["category_id"]) && ($info["category_id"] !== ""))?($info["category_id"]): 0); ?>);
        Think.setValue("material", <?php echo ((isset($info["material"]) && ($info["material"] !== ""))?($info["material"]): 0); ?>);
        Think.setValue("purity", <?php echo ((isset($info["purity"]) && ($info["purity"] !== ""))?($info["purity"]): 0); ?>);
        Think.setValue("status", <?php echo ((isset($info["status"]) && ($info["status"] !== ""))?($info["status"]): 0); ?>);
        Think.setValue("color", <?php echo ((isset($info["color"]) && ($info["color"] !== ""))?($info["color"]): 0); ?>);
        $(function() {
            $('.time-start').datetimepicker({
                format: 'yyyy-mm-dd',
                language:"zh-CN",
                minView:2,
                autoclose:true
            });

            $('.time-end').datetimepicker({
                format: 'yyyy-mm-dd',
                language:"zh-CN",
                minView:2,
                autoclose:true
            });
//
            //导航高亮
            highlight_subnav('<?php echo U('index');?>');

        });
    </script>

</body>
</html>