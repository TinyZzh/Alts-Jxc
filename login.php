<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="title" content="奥蕾泰森 - 管理登录">
    <title>奥蕾泰森 - 管理登录</title>
    <link href="css/semantic.css" type="text/css" rel="stylesheet">
    <script src="js/jquery.min.js" type="text/javascript"></script>
    <script src="js/semantic.js" type="text/javascript"></script>
    <script src="js/jxc-1.0.0.js" type="text/javascript"></script>
</head>
<body id="body">
<div style="display: flex; height: 100%;">
    <div class="ui two column middle aligned very relaxed stackable grid" style="margin: auto;">
        <div class="column">
            <div class="ui form">
                <div class="field">
                    <label>Username</label>
                    <div class="ui left icon input">
                        <input id="inJxcAccount" type="text" placeholder="Username">
                        <i class="user icon"></i>
                    </div>
                </div>
                <div class="field">
                    <label>Password</label>
                    <div class="ui left icon input">
                        <input id="inJxcPsw" type="password">
                        <i class="lock icon"></i>
                    </div>
                </div>
                <div id="btnJxcLogin" class="ui blue large fluid submit button">Login</div>
            </div>
        </div>
        <div class="ui vertical divider">Or</div>
        <div class="center aligned column">
            <div id="btnJxcSignUp" class="ui big green labeled icon button"><i class="signup icon"></i> Sign Up</div>
        </div>
    </div>
</div>

<div id="alertDiv" class="ui small modal">
    <i class="close icon"></i>
    <div class="header">
        Error
    </div>
    <div class="small content">
        <!--
        <div class="image">    image
            An image can appear on left or an icon
        </div>-->
        <div id="divContent" class="description">
            A description can appear on the right
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#btnJxcLogin').click(function () {
            var url = 'Jxc/do.php?api=public&c=login&account=' + $('#inJxcAccount').val() + '&psw=' + $('#inJxcPsw').val();
            W2Util.request(url, undefined, function (data) {
                if (data.status == 'success') {
                    window.location.href = "index.php";
                } else {
//                    w2alert('[' + data.message + ']', "Error");
                    $('#divContent').text(data.message);
                    $('.ui .input').css('error');
                    $("#alertDiv").modal('show');
                }
            });
        });

//        $('#btnJxcSignUp').api({
//            //        url: 'Jxc/do.php?api=public&c=login&account=' + $('#inJxcAccount').val() + '&psw=' + $('#inJxcPsw').val()
////            url: 'Jxc/do.php?api=public&c=login&account={inJxcAccount}&psw={value}',
//            on: function() {
//                console.log('xxx');
//            },
//        });
    });





</script>
</body>
</html>