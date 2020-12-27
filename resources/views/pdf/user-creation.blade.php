<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ __('User creation pdf') }}</title>
</head>
<body>
    <h1 style="text-align: center">
        {{ __('Welcome ') }}
        {{ $user->first_name }}
        {{ $user->last_name }}
    </h1>
    <span>
        <p>{{ __('A new account has been created for you in our bank.') }}</p>
        <br>
        <p>{{ __('Your login information is:') }}</p>
        <p>
            {{ __('Login: ') }}
            <span style="font-weight: bold">
                {{ $user->email }}
            </span>
        </p>
        <p>
            {{ __('Password: ') }}
            <span style="font-weight: bold">
                {{ $password }}
            </span>
        </p>
    </span>
</body>
</html>
