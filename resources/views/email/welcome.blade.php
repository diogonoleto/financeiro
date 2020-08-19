<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
    </head>
    <body>

        <img src="http://diretoriodigital.com.br/wp-content/uploads/2017/04/LogoDiretorioDigital-p.png" alt="" title="" style="margin: 20px" />
        <h3>Olá {{ $user->nome }}, tudo bem? </h3>
        <p>Estamos enviando esse email para lhe dar boas vindas a Diretório Digital.</p>
        <p>Sua assinatura acaba de ser confirmada e já está ativa em nosso sistema.</p>
        <p>Acesse: http://financeiro.rmdservicosdigitais.com.br</p>
        <p>Senha: {{ $password }}</p>
        <p>Forte abraço,</p>
        <p>Equipe Diretório Digital</p>
    </body>
</html>