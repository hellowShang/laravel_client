<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="js/jquery.js"></script>

</head>
<body>
    <form action="/loginDo" method="post">
        @csrf
        <table>
            <tr>
                <td>邮箱</td>
                <td><input type="email" name="email" id="email"></td>
            </tr>
            <tr>
                <td>密码</td>
                <td><input type="password" name="pass" id="pass"></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" value='登录'></td>
            </tr>
        </table>
        <input type="button" id="btn" value="点击获取数据">
    </form>

    <script>
        $(function(){
            $('#btn').click(function(){
                var id = Math.floor(Math.random()*10);
                if(id == 0 || id > 5){
                    id = 1;
                }
                $.get(
                    "http://api.1809a.com/test",
                    {id:id},
                    function(res) {
                        $('#email').val(res.email);
                        $('#pass').val(res.pass1);
                    },
                    'json'
                );
            });
        });
    </script>
</body>
</html>