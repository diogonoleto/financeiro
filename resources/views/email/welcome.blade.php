<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
    </head>
    <body>

        <img src="{{ asset('img/logo_c.png') }}" alt="" title="" style="margin: 20px" />
        <h3>Olá {{ $user->nome }}, tudo bem? </h3>
        <p>Estamos enviando esse email para lhe dar boas vindas a DNS ODontológica.</p>
        <p>Sua assinatura acaba de ser confirmada e já está ativa em nosso sistema.</p>
        <p>Acesse: {{ env('APP_URL') }}</p>
        <p>Senha: {{ $password }}</p>
        <p>Forte abraço,</p>
        <p>Equipe DNS ODontológica</p>
    </body>
</html>
