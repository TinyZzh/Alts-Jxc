<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="title" content="奥蕾泰森 - 管理登录">
    <title>奥蕾泰森 - 管理登录</title>
    <link href="css/jxc-1.0.0.css" type="text/css" rel="stylesheet">
    <link href="css/w2ui-1.4.3.min.css" type="text/css" rel="stylesheet">
    <script src="js/jquery.min.js" type="text/javascript"></script>
    <script src="js/w2ui-1.4.3.js" type="text/javascript"></script>
    <script src="js/jxc-1.0.0.js" type="text/javascript"></script>
</head>
<body id="body">
<div id="layout">
    <div id="div_jxc_login" style="width: 300px; height: 180px"></div>
</div>
<script type="text/javascript">
    //  locale
    w2utils.settings.locale = 'zh-cn';
    w2utils.settings.currencyPrefix = '￥';
    w2utils.settings.date_format = "yyyy-mm-dd";
    w2utils.settings.time_format = "hh24:mm:ss";

    //
    var login = $().w2form({
        name: 'jxc_login_form',
        fields: [
            {field: 'account', type: 'text', required: true, html: {caption: '登录名', attr: 'style="width: 140px"'}},
            {field: 'psw', type: 'password', required: true, html: {caption: '密 码', attr: 'style="width: 140px"'}},
            {field: 'is_save_psw', type: 'checkbox', html: {caption: '记住密码'}}
        ],
        actions: {
            'Login': function (event) {
                console.log('save', event);
                W2Util.request('Jxc/do.php?api=public&c=login', {record: this.record}, function (data) {
                    if (data.status == 'success') {
                        window.location.href = "index.php";
                    } else {
                        w2alert('[' + data.message + ']', "Error");
                    }
                });
            }
        }
    });
    $('#div_jxc_login').w2render('jxc_login_form');


</script>
</body>
</html>