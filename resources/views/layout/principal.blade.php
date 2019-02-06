<?php //dd($perfilLogado);
if(!empty($perfilLogado))
	$userName = explode(' ',$perfilLogado->usuario[0]->NO_USUARIO);

if(strpos(Request::url(),'inscritos')===false){
	$inscricaoHref = "#inscricao";
	$inicioHref = "#page-top";
	$sobreHref = "#sobre";
	$contatoHref = "#contato";	
}else{
	$inscricaoHref = url("/#inscricao");
	$inicioHref = url("/");
	$sobreHref = url("/#sobre");
	$contatoHref = url("/#contato");	
}	

//dd($perfilLogado);
?>

<!DOCTYPE html>
<html lang="pt-br">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Portal do congresso JUCAAD">
		<meta name="author" content="Sérgio Paulo">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<meta name="base-url" content="{{ url('/') }}">

    <title>@yield('title')</title>

    <!-- Bootstrap core CSS -->
    <link href="{{url('/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="{{url('/vendor/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>

    <!-- Plugin CSS -->
    <link href="{{url('/vendor/magnific-popup/magnific-popup.css')}}" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{url('/css/creative.min.css')}}" rel="stylesheet">
		<link href="{{url('/css/congresso.css')}}" rel="stylesheet">

		<!-- JS includes -->
		<script src="{{url('vendor/jquery/jquery.min.js')}}"></script>

		<!-- For specific page top includes -->
		@yield('topSpecificIncludes') 
		<!-- For specific page top includes /-->
	
  </head>

  <body id="page-top" class="cg_bg-new-blue">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
      <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="{{$inscricaoHref}}" style="color:#f05f40;">Inscrições</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
						<li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="{{$inicioHref}}">Inicio</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="{{$sobreHref}}">Sobre</a>
            </li>    
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="{{$contatoHref}}">Contato</a>
            </li>
			@if(in_array(@$perfilLogado->CD_PERFIL,[1,2]))
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="dropdownAdm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Administrativo</a>
					<div class="dropdown-menu" aria-labelledby="dropdownAdm">
						<ul>
							@if($perfilLogado->CD_PERFIL == 1 && 1==2)
								<li class="nav-item">
									<a class="nav-link" href="{{url('/eventos')}}">Eventos</a>
								</li>
							@endif	
							<li class="nav-item">
							  <a class="nav-link" href="{{url('/inscritos')}}">Inscritos</a>
							</li>
							<li class="nav-item">
							  <a class="nav-link" href="{{url('/pedidos')}}">Pedidos</a>
							</li>							
							@if($perfilLogado->CD_PERFIL == 1 && 1==2)
								<li class="nav-item">
									<a class="nav-link" href="{{url('/usuarios')}}">Usuarios</a>
								</li>
							@endif
						</ul>	
					</div>
				</li>	
			@endif
			@if(empty($perfilLogado))
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="dropdownLogin" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Login</a>
					<div class="dropdown-menu" aria-labelledby="dropdownLogin">
						<form method="post" action="{{url('/login')}}">
							<input type="hidden" name="_token" value="{{ csrf_token() }}" />
							<div class="col-md-12">
								<div class="form-group">
									<label>E-mail</label>
									<input type="email" class="form-control" name="NO_MAIL">					
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Senha</label>
									<input type="password" class="form-control" name="TX_SENHA">					
								</div>
							</div>
							<div class="col-md-12">
								<button type="submit" class="btn btn-primary btn-xl js-scroll-trigger" href="#services">Entrar</button>
							</div>
						</form>	
					</div>
				</li>
			@else
				<li class="nav-item">
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="dropdownUser" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"> {{@$userName[0]}} </i></a>
						<div class="dropdown-menu" aria-labelledby="dropdownUser">
							<span class="dropdown-item" >
							<i class="fa fa-user"></i>
							Perfil: <span style="color:#f05f40">{{@$perfilLogado->perfil[0]->NO_PERFIL}}</span> </span>
							@if(@$perfilLogado->CD_PERFIL == 2)
								<span class="dropdown-item" >
								<i class="fa fa-institution"></i>
								Congregação: <span style="color:#f05f40">{{@$perfilLogado->congregacao[0]->NO_CONGREGACAO}}</span> </span>
							@endif

							<a class="dropdown-item" href="{{url('/alterar-senha')}}">
							<i class="fa fa-lock"></i>
							Alterar Senha</a>												
							<a class="dropdown-item" href="{{url('/logout')}}">
							<i class="fa fa-sign-out"></i>
							Sair</a>						
						</div>
					</li>
				</li>
			@endif	
          </ul>
        </div>
      </div>
    </nav>

    @yield('conteudo')

    <!-- Bootstrap core JavaScript -->    
    <!--<script src="{{url('vendor/jquery/jquery.min.js')}}"></script>-->
	<script src="{{url('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <!-- Plugin JavaScript -->
    <script src="{{url('vendor/jquery-easing/jquery.easing.min.js')}}"></script>
    <script src="{{url('vendor/scrollreveal/scrollreveal.min.js')}}"></script>
    <script src="{{url('vendor/magnific-popup/jquery.magnific-popup.min.js')}}"></script>
	
    <!-- Custom scripts for this template -->
    <script src="{{url('js/creative.min.js')}}"></script>
		<script type="text/javascript" src="{{url('js/jquery.mask.js')}}"></script>
		<script type="text/javascript" src="{{url('js/mascara2.js')}}"></script>
		<script type="text/javascript" src="{{url('js/congresso.js')}}"></script>

  </body>

</html>
