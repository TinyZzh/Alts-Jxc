<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="title" content="奥蕾泰森 - 管理登录">
    <title>奥蕾泰森 - 管理登录</title>
    <link href="css/w2ui-1.4.3.min.css" type="text/css" rel="stylesheet">
    <script src="js/jquery.min.js" type="text/javascript"></script>
    <script src="js/w2ui-1.4.3-zh-cn.js" type="text/javascript"></script>
</head>
<body id="body">
<div id="layout">
    <div id="div_main" style="width: 300px"></div>


    <div class="login-screen">
        <div class="login-icon">
            <img src="img/login/icon.png" alt="Welcome to Mail App">
            <h4>Welcome to <small>Mail App</small></h4>
        </div>

        <div class="login-form">
            <div class="form-group">
                <input type="text" class="form-control login-field" value="" placeholder="Enter your name" id="login-name">
                <label class="login-field-icon fui-user" for="login-name"></label>
            </div>

            <div class="form-group">
                <input type="password" class="form-control login-field" value="" placeholder="Password" id="login-pass">
                <label class="login-field-icon fui-lock" for="login-pass"></label>
            </div>

            <a class="btn btn-primary btn-lg btn-block" href="#">Log in</a>
            <a class="login-link" href="#">Lost your password?</a>
        </div>
    </div>


</div>
<script type="text/javascript">
    //  locale
    w2utils.settings.locale = 'zh-cn';
    w2utils.settings.currencyPrefix = '￥';
    w2utils.settings.date_format = "yyyy-mm-dd";
    w2utils.settings.time_format = "hh24:mm:ss";

    var login = $().w2form({
        name: 'jxc_login_form',
        fields: [
            {field: 'login', type: 'text', required: true, html: {caption: '登录名', attr: 'style="width: 140px"'}},
            {field: 'password', type: 'password', required: true, html: {caption: '密 码', attr: 'style="width: 140px"'}},
            {field: 'save_psw', type: 'checkbox', html: {caption: '记住密码'}},
        ],
        actions: {
            'Save': function (event) {
                console.log('save', event);
                this.save();
            }
        }
    });
    $('#div_main').w2render('jxc_login_form');
    //    w2ui.layout.content('main', login);

//    w2popup.open({
//        title: '登 录',
//        body: '<div id="pop_layout" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px;"></div>',
//        width: 400,
//        height: 200,
//        showClose: false,
//        modal: true,
//        onOpen: function (event) {
//            console.log(event);
//            event.onComplete = function (event) {
//                console.log(event);
//                // if (obj.options.max) {
//                //     onPopupMax();
//                // }
//                $('#pop_layout').w2render('jxc_login_form');
//            };
//        }
//    });


</script>
</body>
</html>