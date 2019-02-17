


@extends('layout.principal')	

@section('title', 'JUCAAD | Início')

@section('conteudo')

		
		


    <header class="masthead text-center text-white d-flex">
			<!-- <img src="img/header.png" style="min-width:100%;min-height:100%;"/> -->
			<div class="container my-auto ">
        <div class="row " style="margin-top: 100px;">
          <div class="col-lg-10 mx-auto">
            <h1 class="text-uppercase" style="/*font-size:3.5rem*/">
							<span style="background:#504379;color:#FFF">&nbsp;CON </span><span style="background:#FFF;color:#504379">&nbsp;JUCAAD </span><br><span style="letter-spacing: 1.4em;">&nbsp;2019</span>
            </h1>
            <hr>
					</div>
					<div class="col-lg-8 mx-auto">
						@if(!empty($msgHome))
							@component('components.alert')
								@slot('type',$msgHome['type'])
								@slot('text',$msgHome['text'])
							@endcomponent	
						@endif
					</div>	
          <div class="col-lg-12 mx-auto">
						<p class="text-faded mb-12" style="font-size:3.2rem">JOÃO 15:5</p>
						<p class="text-faded mb-12" style="font-size:2.1rem">"EU SOU A VIDEIRA VERDADEIRA; VOCÊS SÃO OS RAMOS. SE ALGUÉM PERMANECER EM MIM E EU NELE, ESSE DARÁ MUITO FRUTO; POIS SEM MIM VOCÊS NÃO PODEM FAZER COISA ALGUMA."</p>
						<p class="text-faded mb-12"><img src="{{url('/img/conectadosComCristo.jpg')}}" style="width:100%;/*heigth:600px*/" alt="Conectados com Cristo"></p>
          </div>
        </div>
      </div>
    </header>

    <section class="cg_bg-dark-blue" id="sobre">
      <div class="container" style="font-size:1.5rem;">
        <div class="row">
          <div class="col-lg-8 mx-auto text-center text-white">
            <h2 class="section-heading">Fique por dentro dos detalhes.</h2>
            <hr class="light my-4">            
						<ul class="text-left">
							<li><strong style="color:yellow">Local:</strong> Assembleia de Deus - Campo de Santa Maria</li>
							<li><strong style="color:yellow">Data:</strong> 2 a 5 de março de 2019</li>				
							<li><strong style="color:yellow">Endereço:</strong> CL 117, Área Especial, Lote C, Santa Maria - DF</li>
							<li><strong style="color:yellow">Facebook:</strong> <a target="_blank" href="http://fb.com/jucaad" style="color:#FFF">fb.com/jucaad</a></li>				
							<li><strong style="color:yellow">Instagram:</strong> <a target="_blank" href="http://instragam.com/jucaadadsam" style="color:#FFF">instragam.com/jucaadadsam</a></li>
						</ul>            
          </div>
        </div>
      </div>
    </section>

    <section id="services-old" style="display:none">
      <div class="container">
        <div class="row">
          <div class="col-lg-12 text-center">
            <h2 class="section-heading">At Your Service</h2>
            <hr class="my-4">
          </div>
        </div>
      </div>
      <div class="container">
        <div class="row">
          <div class="col-lg-3 col-md-6 text-center">
            <div class="service-box mt-5 mx-auto">
              <i class="fa fa-4x fa-diamond text-primary mb-3 sr-icons"></i>
              <h3 class="mb-3">Sturdy Templates</h3>
              <p class="text-muted mb-0">Our templates are updated regularly so they don't break.</p>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 text-center">
            <div class="service-box mt-5 mx-auto">
              <i class="fa fa-4x fa-paper-plane text-primary mb-3 sr-icons"></i>
              <h3 class="mb-3">Ready to Ship</h3>
              <p class="text-muted mb-0">You can use this theme as is, or you can make changes!</p>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 text-center">
            <div class="service-box mt-5 mx-auto">
              <i class="fa fa-4x fa-newspaper-o text-primary mb-3 sr-icons"></i>
              <h3 class="mb-3">Up to Date</h3>
              <p class="text-muted mb-0">We update dependencies to keep things fresh.</p>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 text-center">
            <div class="service-box mt-5 mx-auto">
              <i class="fa fa-4x fa-heart text-primary mb-3 sr-icons"></i>
              <h3 class="mb-3">Made with Love</h3>
              <p class="text-muted mb-0">You have to make your websites with love these days!</p>
            </div>
          </div>
        </div>
      </div>
    </section>
	
		<!-- Inscricoes -->
		<section id="inscricao" class="text-white">
			<div class="container">

				<div class="row">
						<div class="col-lg-12">

							<form name="f_inscricao" id="f_inscricao" role="form" method="post" action="{{url('/inscricao')}}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}" />

								<div class="tabs-container">
										<ul class="nav nav-tabs" role="tablist">
												<li><a class="nav-link active" data-toggle="tab" href="#tab-1" id="a1GoToTab1">Inscrição</a></li>
												<!--<li><a class="nav-link" data-toggle="tab" href="#tab-2" id="a1GoToTab2">Camisas</a></li>-->
										</ul>
										<div class="tab-content">
												<div role="tabpanel" id="tab-1" class="tab-pane active">
														<div class="panel-body">
															<div class="container ">
																<div class="row">
																	<div class="col-lg-12 text-center">
																		<br /><h2 class="section-heading">Faça sua inscrição</h2>
																		<hr class="my-4">
																	</div>
																</div>
															</div>
															<div class="container">
																<div class="row">
																	<div class="col-lg-12">
																		
																		@if(count($errors) > 0)					
																			<div class="alert alert-danger">
																				<ul>
																					@foreach ($errors->all() as $error)
																						<li>{!! $error !!}</li>
																					@endforeach
																				</ul>
																			</div>
																		@endif
																		
																		@if(!empty($msgCadastro))
																			@component('components.alert')
																				@slot('type',$msgCadastro['type'])
																				@slot('text',$msgCadastro['text'])
																			@endcomponent	
																		@endif
																		
																		
																	
																			<div class="row">
																			
																				<div class="col-md-12">
																					<div class="form-group">
																						<label>Nome Completo</label>
																						<input type="text" class="form-control" name="NO_USUARIO" required>					
																					</div>
																				</div>
																				<!--
																				<div class="col-md-4">
																					<div class="form-group">
																						<label>Senha (para acesso a este portal)</label>
																						<input type="password" class="form-control" name="TX_SENHA">					
																					</div>
																				</div>

																				<div class="col-md-4">
																					<div class="form-group">
																						<label>Repita a Senha para confirmar</label>
																						<input type="password" class="form-control" name="TX_SENHA_confirmation">					
																					</div>
																				</div>	
																				-->
																			</div>
																			
																			<br/>
																			
																			

																			<div class="row">
																			
																				<div class="col-md-6">
																					<div class="form-group">
																						<label>E-mail <span style="color: #f05f40; font-size: 12px;">(Não repetir e-mail já utilizado em outra inscrição deste congresso)</span></label>
																						<input type="email" class="form-control" name="NO_MAIL" required>
																					</div>
																				</div>
																			
																				<div class="col-md-3">
																					<div class="form-group">
																						<label>Sexo</label>
																						<select class="form-control" name="NR_SEXO">
																							<option>Selecione</option>
																							<option value="1">Masculino</option>
																							<option value="2">Feminino</option>						
																						</select>
																					</div>
																				</div>
																				
																				<div class="col-md-3">
																					<div class="form-group">
																						<label>Data de Nascimento</label>
																						<input type="text" class="form-control mDate" name="DT_NASCIMENTO">					
																					</div>
																				</div>	
																				
																			</div>
																			
																			<div class="row">
																			
																				<div class="col-md-4">
																					<div class="form-group">
																						<label>Telefone Celular/WhatsApp</label>
																						<input type="text" class="form-control mFone" name="NR_CELULAR">					
																					</div>
																				</div>
																				
																				<div class="col-md-4">
																					<div class="form-group">
																						<label>Telefone Residencial</label>
																						<input type="text" class="form-control mFone" name="NR_TELEFONE">					
																					</div>
																				</div>
																				
																				<div class="col-md-4">
																					<div class="form-group">
																						<label>Endereço</label>
																						<input type="text" class="form-control" name="DS_ENDERECO">					
																					</div>
																				</div>
																				
																			</div>
																			
																			<div class="row">
																			
																				<div class="col-md-6">
																					<div class="form-group">
																						<label>Cidade</label>
																						<input type="text" class="form-control" name="NO_CIDADE">					
																					</div>
																				</div>
																				
																				<div class="col-md-6">
																					<div class="form-group">
																						<label>Bairro</label>
																						<input type="text" class="form-control" name="NO_BAIRRO">					
																					</div>
																				</div>
																				
																			</div>
																			
																			<br/>
																			
																			<div class="row">
																				
																				<div class="col-md-6">
																					<div class="form-group">
																						<label>Igreja/Congregação</label>
																						<select class="form-control" name="CD_CONGREGACAO">
																							<option value="">Selecione</option>
																							@foreach($congregacao as $c)
																								<option value="{{$c->CD_CONGREGACAO}}">{{$c->NO_CONGREGACAO}}</option>
																							@endforeach
																						</select>
																					</div>							
																				</div>
																				
																				<div class="col-md-6">
																					<div class="form-group">
																						<label>Nome do líder</label>
																						<select class="form-control" name="CD_USUARIO_LIDER">
																							<option value="">Selecione a congregação primeiro...</option>
																						</select>
																					</div>							
																				</div>
																				
																			</div>
																			
																			<div class="row" style="margin-top:20px">
																				<div class="col-lg-12">
																					<div class="form-group">																					
																						<input value="Não" type="checkbox" class="" name="ST_ALIMENTACAO" id="ST_ALIMENTACAO">	
																						<label for="ST_ALIMENTACAO" style="display: contents;"> Não irei participar da alimentação que será oferecida durante o congresso. (A taxa de inscrição não será cobrada nesse caso)</label>				
																					</div>
																				</div>	
																			</div>

																			<div class="row">
																				<p class="text-primary">* A inscrição corresponde a participação no evento mais os custeios com alimentação, por isso será cobrada uma taxa de <span style="color:#FFF000;">R$ 40,00</span> que deve ser repassada ao seu lider.</p>
																			</div>
																			
																			<div class="row">
																				
																				<div class="col-md-2">
																					<!--<a class="btn btn-light btn-xl text-primary" id="a2GoToTab2">Avançar</a>-->
																					<button type="submit" class="btn btn-primary btn-xl" >Enviar</button>
																				</div>
																				
																			</div>	
																	
																		
																	</div>          
																</div>
															</div>																
														</div>
												</div> <!-- #tab-1 -->
												@if(1==2)
												<div role="tabpanel" id="tab-2" class="tab-pane">
														<div class="panel-body">
															<div class="container">
																<div class="row">
																	<div class="col-lg-12 text-center">
																		<br /><h2 class="section-heading">Adquira camisas do evento</h2>
																		<hr class="light my-4">
																	</div>
																</div>
															</div>
															<div class="container">	
																<!-- <div class="row"> -->
																			<div id="grupoPedido">

																				<div class="row">
																					<div class="col-lg-6">
																						<div class="form-group">
																							<img src="img/camisa_jucaad_azul.jpg" style="width:90%;"/><br/>
																							<input type="radio" class="" name="NO_COR_aux" id="camisaAzul" value="Azul">	
																							<label for="camisaAzul" class="lbCamisa"> Camisa Azul - R$ 35,00</label>				
																						</div>
																					</div>
																					<div class="col-lg-6">
																						<div class="form-group">
																							<img src="img/camisa_jucaad_vermelho.jpg" style="width:90%;"/><br/>
																							<input type="radio" class="" name="NO_COR_aux" id="camisaVermelha" value="Vermelha">	
																							<label for="camisaVermelha" class="lbCamisa"> Camisa Vermelha - R$ 35,00</label>
																						</div>
																					</div>	
																				</div>

																				<div class="row">
																					<div class="col-lg-3">
																						<div class="form-group">
																							<label>Gênero</label>
																							<select class="form-control" name="NO_GENERO_aux" id="NO_GENERO_aux">
																								<option>Selecione</option>
																								<option value="Masculina">Masculina</option>
																								<option value="Feminina">Feminina</option>						
																							</select>
																						</div>							
																					</div>
																					<div class="col-lg-3">
																						<div class="form-group">
																							<label>Tamanho</label>
																							<select class="form-control" name="NO_TAMANHO_aux" id="NO_TAMANHO_aux">
																								<option>Selecione</option>
																								<option value="P">P</option>
																								<option value="M">M</option>
																								<option value="G">G</option>
																								<option value="GG">GG</option>
																								<option value="XG">XG</option>						
																							</select>
																						</div>							
																					</div>
																					<div class="col-lg-3">
																						<div class="form-group">
																							<label>Quantidade</label>
																							<input type="number" class="form-control" name="NR_QUANTIDADE_aux" id="NR_QUANTIDADE_aux" min="1">
																						</div>							
																					</div>
																					<div class="col-lg-3">
																						<div class="form-group">
																							<label style="visibility:hidden">Adicionar ao pedido</label>
																							<br><span ><a href="javascrit:void(0)" class="btn btn-primary btn-xl" name="addProduto" id="addProduto">Adicionar ao pedido</a></span>
																						</div>	
																					</div>
																				</div>

																				<div class="row" >
																					<div class="table-responsive">
																						<table class="table table-striped table-bordered table-hover" style="margin-top:30px" id="tbPedido">
																							<thead>
																								<tr>
																									<th colspan="8" style="text-align:center;font-size: 30px;">PEDIDO</th>
																								</tr>	
																								<tr>
																									<th>#</th>
																									<th>Produto</th>																									
																									<th>Cor</th>
																									<th>Gênero</th>
																									<th>Tamanho</th>
																									<th>Quantidade</th>
																									<th>Valor</th>
																									<th>Ações</th>
																								</tr>
																							</thead>
																							<tbody>																								
																									<!--
																									<tr>
																										<td class="n"></td>
																										<td></td>
																										<td></td>
																										<td></td>
																										<td></td>
																										<td></td>
																										<td></td>						
																										<td>
																											<a title="Excluir" href="javascript:void()" style="color:#FFF000"><i class="fa fa-times"></i></a>&nbsp;&nbsp;
																										</td>
																									</tr>	
																									-->																							
																							</tbody>
																							<tfoot>
																								<tr style="color:#f05f40">																								
																									<th colspan="6" style="text-align: center;">Total</th>
																									<th colspan="2" id="valorTotal"><span class="moeda">R$ </span><span class="valor">0,00</span> </th>
																								</tr>																							
																							</tfoot>
																						</table>
																					</div>
																				</div>

																			</div> <!-- #grupoPedido -->
																			
																			<div class="row" style="margin-top:20px">
																				<div class="col-lg-12">
																					<div class="form-group">																					
																						<input type="checkbox" class="" name="naoFazerPedido" id="naoFazerPedido">	
																						<label for="naoFazerPedido" style="display: contents;"> Obrigado, mas não desejo adquirir camisas do evento.</label>				
																					</div>
																				</div>	
																			</div>
																			
																			<div class="row">
																				<p class="text-primary">* A inscrição corresponde a participação no evento mais os custeios com alimentação, por isso será cobrada uma taxa de <span style="color:#FFF000;">R$ 40,00</span> que deve ser repassada ao seu lider.</p>
																			</div>

																			<div class="row">
																				<br/>
																				<div class="col-md-2">
																					<a class="btn btn-light btn-xl text-primary" id="a2GoToTab1">Voltar</a>
																				</div>
																				<div class="col-md-2">
																					<button type="submit" class="btn btn-primary btn-xl" >Enviar</button>
																				</div>
																			</div>
																<!-- </div> -->
															</div> <!-- .container -->	
														</div> <!-- .panel-body -->
												</div> <!-- #tab-2 -->
												@endif
										</div> <!-- .tab-content -->


								</div>
							</form>	
						</div>
				</div>
			</div>	
		</section>

    <section id="contato" class="cg_bg-dark-blue text-white">
      <div class="container">
        <div class="row">
          <div class="col-lg-8 mx-auto text-center">
            <h2 class="section-heading">Contato</h2>
            <hr class="my-4">
            <p class="mb-5">Você pode entrar em contato através dos telefones abaixo ou por e-mail.</p>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-4 ml-auto text-center">
            <i class="fa fa-phone fa-3x mb-3 sr-contact"></i>
            <p>(61)9851-48433</p><p>(61)9810-39213</p>
          </div>
          <div class="col-lg-4 mr-auto text-center">
            <i class="fa fa-envelope-o fa-3x mb-3 sr-contact"></i>
            <p>
              <a href="mailto:contato@conjucaad.com.br">contato@conjucaad.com.br</a>
            </p>						
          </div>
        </div>
      </div>
    </section>

		

	@if(count($errors) > 0)
	<script>		
		window.location.href='#inscricao';					
	</script>
	@endif
	
	@if(!empty($msgCadastro))
	<script>		
		window.location.href='#inscricao';					
	</script>
	@endif
	
@endsection    


