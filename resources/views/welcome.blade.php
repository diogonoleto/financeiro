<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <title>Diretório Digital</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <!-- Favicon -->
  <link href="img/favicon.ico" rel="icon">
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Raleway:100,200,400,500,700|Roboto:100,200,400,900" rel="stylesheet">

  <!-- Bootstrap CSS File -->
  <link href="{{ asset('lib/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  
  <!-- Libraries CSS Files -->
  <link href="{{ asset('lib/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('plugins/perfectScrollbar/perfect-scrollbar.min.css') }}"/>

  <!-- Main Stylesheet File -->
  <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>

<body style="position: relative; overflow: hidden;">
  <section class="hero">
    <div class="container text-center">
      <div class="row">
        <div class="col-md-12">
          <a class="hero-brand" href="/" title="Home">
            <img src="{{ asset('img/logo.png') }}"></a>
        </div>
      </div>

      <div class="col-md-12">
        <h1 style="font-weight: 400; color: #ffffff;">Solução para seus negócios.‎</h1>
        <p style="color: #ffffff;" class="tagline">
          Gestão financeira simples e eficiente.
        </p>
        <!-- <a class="btn btn-primary btn-lg" href="#about">Saiba Mais</a> -->
        <a href="{{ url('/login') }}" class="btn btn-outline-primary btn-login btn-lg">Login</a>
      </div>
    </div>
  </section>
  <header id="header">
    <div class="container">
      <div id="logo" class="pull-left">
        <a href="index.html">
          <img src="{{ asset('img/logo.png') }}" alt="" title="" />
        </a>
      </div>
      <nav id="nav-menu-container">
        <ul class="nav-menu">
          <li><a href="#about">Produto</a></li>
          <li><a href="#precos">Planos e Preços</a></li>
          <li><a href="#contact">Contate-nos</a></li>
        </ul>
      </nav>
      @if (Route::has('login'))
        @if (Auth::check())
        <!-- <a href="{{ url('/home') }}" class="btn btn-outline-primary pull-right btn-login">Home</a> -->
        @else
        <a href="{{ url('/login') }}" class="btn btn-outline-primary pull-right btn-login">Login</a>
        @endif
      @endif
    </div>
  </header>
  <section class="about" id="about">
    <div class="container text-center">
      <h2>Gestão financeira sob controle</h2>
      <div class="row stats-row">
        <div class="stats-col text-center col-md-4 col-sm-6">
          <img style="width: 40%" src="https://www.quickbooks.com.br/content/dam/intuit/quickbooks/i18n/pt/br/homepage/QB_BR_feature_icons/icn-BR-QBZP-fluxocaixa-sucessogarantido.svg">
          <h3>Sucesso garantido</h3>
          <p>Mantenha o fluxo de caixa em dia e aumente as chances de sucesso de seu negócio</p>
        </div>
        <div class="stats-col text-center col-md-4 col-sm-6">
          <img style="width: 40%" src="https://www.quickbooks.com.br/content/dam/intuit/quickbooks/i18n/pt/br/homepage/QB_BR_feature_icons/icn-BR-QBZP-fluxocaixa-despesas.svg">
          <h3>Despesas sob controle</h3>
          <p>Planeje despesas com antecedência e evite imprevistos financeiros</p>
        </div>
        <div class="stats-col text-center col-md-4 col-sm-6">
          <img style="width: 40%" src="https://www.quickbooks.com.br/content/dam/intuit/quickbooks/i18n/pt/br/homepage/QB_BR_feature_icons/icn-BR-QBZP-fluxocaixa-receitas.svg">
          <h3>Receitas monitoradas</h3>
          <p>Tenha o controle preciso de quanto seu negócio está faturando</p>
        </div>
      </div>
      <div class="row stats-row">
        <div class="stats-col text-center col-md-4 col-sm-6">
          <img style="width: 40%" src="https://www.quickbooks.com.br/content/dam/intuit/quickbooks/i18n/pt/br/homepage/QB_BR_feature_icons/icn-BR-QBZP-fluxocaixa-lancamentosparcelados.svg">
          <h3>Lançamentos parcelados</h3>
          <p>Cadastre rapidamente receitas e despesas de forma parcelada</p>
        </div>
        <div class="stats-col text-center col-md-4 col-sm-6">
          <img style="width: 40%" src="https://www.quickbooks.com.br/content/dam/intuit/quickbooks/i18n/pt/br/homepage/QB_BR_feature_icons/icn-BR-QBZP-fluxocaixa-controleimpostos.svg">
          <h3>Controle de impostos</h3>
          <p>A Diretorio traz uma área específica para separar impostos de outras despesas</p>
        </div>
        <div class="stats-col text-center col-md-4 col-sm-6">
          <img style="width: 40%" src="https://www.quickbooks.com.br/content/dam/intuit/quickbooks/i18n/pt/br/homepage/QB_BR_feature_icons/icn-BR-QBZP-fluxocaixa-boletos.svg">
          <h3>Contabilidade em dia</h3>
          <p>Com o fluxo de caixa da Diretorio fica mais fácil emitir relatórios contábeis</p>
        </div>
      </div>
    </div>
  </section>
  <div class="block block-pd-lg block-bg-overlay text-center" data-bg-img="img/parallax-bg.jpg" style="background-color: #8BC34A">
    <h2>Controle sua empresa sem complicação</h2>
    <p>Sistema de gestão online feito para organizar sua empresa e dar mais tempo para você</p>
    <img alt="" class="gadgets-img hidden-md-down" src="{{ asset('img/gadgets.png') }}" style="width: 500px;">
  </div>
  <section class="precos" id="precos">
    <div class="container">
      <div class="row justify-content-center">
        <div class="text-center col-lg-10 col-md-12 col-xs-12">
          <h2 class="text-center">Escolha o plano ideal para o seu negócio.</h2>
          <div class="row no-gutters stats-row">
            <div class="col-md-4 pr-1">
              <div class="list-group text-center">
                <div class="list-group-item">
                  <div style="width: 100%; font-size: 1.4rem; font-weight: 500; color: #4CAF50;">
                    FREE
                  </div>
                </div>
                <div class="list-group-item text-uppercase font-weight-bold">
                  Free
                </div>
                <a href="#" class="list-group-item">
                  1 usuários
                </a>
                <a href="#" class="list-group-item">
                  1 Contas bancárias
                </a>
                <a href="#" class="list-group-item">
                  Monitore receitas, despesas e lucro
                </a>
                <a href="#" class="list-group-item">
                  Monitore receitas, despesas e lucro
                </a>
                <div class="list-group-item">
                  <button class="btn btn-block text-truncate btn-outline-primary">Inscrever-se</button>
                </div>
              </div>
            </div>
            <div class="col-md-4 pr-1">
              <div class="list-group">
                <div class="list-group-item">
                  <div style="width: 100%; font-size: 1.4rem; font-weight: 500; color: #4CAF50;">
                    AUTÔNOMO
                  </div>
                </div>
                <div class="list-group-item text-uppercase font-weight-bold">
                  R$29/mes.
                </div>
                <a href="#" class="list-group-item">
                  1 usuários
                </a>
                <a href="#" class="list-group-item">
                  3 Contas bancárias
                </a>
                <a href="#" class="list-group-item">
                  Monitore receitas, despesas e lucro
                </a>
                <a href="#" class="list-group-item">
                  Monitore receitas, despesas e lucro
                </a>
                <div class="list-group-item">
                  <button class="btn btn-block text-truncate btn-outline-primary">Compre</button>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="list-group">
                <div href="#" class="list-group-item">
                  <div style="width: 100%; font-size: 1.4rem; font-weight: 500; color: #4CAF50;">
                    MICRO
                  </div>
                </div>
                <div class="list-group-item text-uppercase font-weight-bold">
                  R$59/mes.
                </div>
                <a href="#" class="list-group-item">
                  3 usuários
                </a>
                <a href="#" class="list-group-item">
                  Contas bancárias ilimitadas
                </a>
                <a href="#" class="list-group-item">
                  Monitore receitas, despesas e lucro
                </a>
                <a href="#" class="list-group-item">
                  Monitore receitas, despesas e lucro
                </a>
                <div class="list-group-item">
                  <button class="btn btn-block text-truncate btn-outline-primary">Compre</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="cta">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-9 col-sm-12 text-lg-left text-center">
          <h2>Em qualquer lugar a qualquer momento</h2>
          <p>
            É um aplicativo de controle financeiro pessoal que permite que você cadastre suas despesas e receitas, podendo ser acessado pelo computador, tablet ou smartphone.
          </p>
        </div>
        <div class="col-lg-3 col-sm-12 text-lg-right text-center">
          <a class="btn btn-outline-white btn-lg" href="#">Inscrever-se</a>
        </div>
      </div>
    </div>
  </section>
  <section id="contact">
    <div class="container">
      <div class="row">
        <div class="col-md-12 text-center">
          <h2 class="section-title">Contate-nos</h2>
        </div>
      </div>
      <div class="row stats-row">
        <div class="col-lg-4 offset-lg-2">
          <div class="info">
            <div>
              <i class="fa fa-map-marker"></i>
              <p>
                Rua Herman Lima, 10<br>
                Q/18, Cj. Aruanã - Compensa<br>
                Manaus - AM<br>
                Cep: 69036-400
              </p>
            </div>
            <div>
              <i class="fa fa-envelope"></i>
              <p>contato@diretoriodigital.com.br</p>
            </div>
            <div>
              <i class="fa fa-phone"></i>
              <p>+55 92 3343-6255</p>
            </div>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="form">
            <div id="sendmessage">Sua mensagem foi enviada. Obrigado!</div>
            <div id="errormessage"></div>
            <form action="" method="post" role="form" class="contactForm">
              <div class="form-group">
                <input type="text" name="name" class="form-control" id="name" placeholder="Seu Nome" data-rule="minlen:4" data-msg="Please enter at least 4 chars" />
                <div class="validation"></div>
              </div>
              <div class="form-group">
                <input type="email" class="form-control" name="email" id="email" placeholder="Seu E-mail" data-rule="email" data-msg="Please enter a valid email" />
                <div class="validation"></div>
              </div>
              <div class="form-group">
                <input type="text" class="form-control" name="subject" id="subject" placeholder="Assunto" data-rule="minlen:4" data-msg="Please enter at least 8 chars of subject" />
                <div class="validation"></div>
              </div>
              <div class="form-group">
                <textarea class="form-control" name="message" rows="5" data-rule="required" data-msg="Escreva algo para nós" placeholder="Message"></textarea>
                <div class="validation"></div>
              </div>
              <div class="text-center"><button type="submit">Enviar</button></div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  <footer class="site-footer">
    <div class="bottom">
      <div class="container">
        <div class="row">
          <div class="col-lg-4 col-xs-12 text-lg-left text-center">
            <p class="copyright-text">
              © Ditetório Digital
            </p>

          </div>
          <div class="col-lg-4 col-xs-12 text-lg-left text-center">
            <nav class="nav social-nav hidden-sm-down">
              <a href="#"><i class="fa fa-twitter"></i></a>
              <a href="#"><i class="fa fa-facebook"></i></a>
              <a href="#"><i class="fa fa-linkedin"></i></a>
            </nav>
          </div>
          <div class="col-lg-4 col-xs-12 text-lg-right text-center">
            <ul class="list-inline">
              <li class="list-inline-item"><a href="index.html">Produto</a></li>
              <li class="list-inline-item"><a href="#precos">Planos e Preços</a></li>
              <li class="list-inline-item"><a href="#contact">Contate-nos</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </footer>
  <!-- <a class="scrolltop" href="#"><span class="fa fa-angle-up"></span></a>  -->

  <!-- Required JavaScript Libraries -->
  <script src="{{ asset('lib/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('lib/superfish/hoverIntent.js') }}"></script>
  <script src="{{ asset('lib/superfish/superfish.min.js') }}"></script>
  <script src="{{ asset('lib/tether/js/tether.min.js') }}"></script>
  <script src="{{ asset('lib/stellar/stellar.min.js') }}"></script>
  <script src="{{ asset('lib/bootstrap/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('lib/counterup/counterup.min.js') }}"></script>
  <script src="{{ asset('lib/waypoints/waypoints.min.js') }}"></script>
  <script src="{{ asset('lib/easing/easing.js') }}"></script>
  <script src="{{ asset('lib/stickyjs/sticky.js') }}"></script>
  <script src="{{ asset('lib/parallax/parallax.js') }}"></script>
  <script src="{{ asset('lib/lockfixed/lockfixed.min.js') }}"></script>

  <!-- Template Specisifc Custom Javascript File -->
  <script src="{{ asset('js/custom.js') }}"></script>
  <script type="text/javascript" src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>
  <script type="text/javascript" src="{{ asset('plugins/perfectScrollbar/perfect-scrollbar.min.js') }}"></script>

  <script type="text/javascript">
    // window.onload = function () {
    //   Ps.initialize(document.querySelector('body'), {
    //     suppressScrollX:true,
    //   });
    // };


  </script>

</body>
</html>


