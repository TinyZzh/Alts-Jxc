<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="title" content="奥蕾泰森 - 进销存管理系统">
    <title>奥蕾泰森 - 进销存管理系统</title>
    <link href="css/w2ui-1.4.3.min.css" type="text/css" rel="stylesheet">
    <script src="js/jquery.min.js" type="text/javascript"></script>
    <script src="js/w2ui-1.4.3-zh-cn.js" type="text/javascript"></script>
</head>
<body id="body">
<div id="layout">
    <div id="div_main"></div>
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
    //    w2ui.layout.content('main', login);

    w2popup.open({
        title: '登 录',
        body: '<div id="pop_layout" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px;"></div>',
        width: 400,
        height: 200,
        showClose: false,
        modal: true,
        onOpen: function (event) {
            console.log(event);
            event.onComplete = function (event) {
                console.log(event);
                // if (obj.options.max) {
                //     onPopupMax();
                // }
                $('#pop_layout').w2render('jxc_login_form');
            };
        }
    });


</script>
</body>
</html>