<!DOCTYPE html>
<html lang="ja">

<head>
    <base href="http://192.168.10.10/">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>テスト</title>
</head>

<body class="">
    <div>
        <p>hogehoge</p>
        <div id="hoge"></div>
    </div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    $(function() {
        $('#hoge').text('fuga');
        console.log('hogesssssssssssssssssssssssssss');
        $.ajax('/api/users', {dataType: "json"}).done(function(data) {
            $('#hoge').text(JSON.stringify(data));
            window.status = 'imdone';
        });
    })
</script>

</html>