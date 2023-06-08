<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.MAIL_FROM_ADDRESS', 'Sales@etc.uz') }}</title>
    <style>
        h4 {
            font-size: 16px;
            margin-top: 15px;
            margin-bottom: 10px;
        }
        p {
            font-size: 14px;
            margin-top: 3px;
            margin-bottom: 3px;
            color: #000;
        }
    </style>
</head>
<body>

<h4>Новый заказ</h4>

<p><b>Имя:</b> {{ $full_name }}</p>

<p><b>Адрес:</b> {{ $address }}</p>

<p><b>Номер телефона:</b> {{ $phone }}</p>


_______________________


<h4>New Order</h4>

<p><b>Full name:</b> {{ $full_name }}</p>

<p><b>Address:</b> {{ $address }}</p>

<p><b>Phone number:</b> {{ $phone }}</p>


</body>
</html>
