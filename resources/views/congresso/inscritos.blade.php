<?php 
//use App\Classes\URLify;
use App\Classes\MyUtil; 
?>


@extends('layout.principal')	

@section('title', 'JUCAAD | Inscritos')

@section('conteudo')

	<section class="cg_bg-new-blue" id="usuarios">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 text-center text-white">
					<h2 class="section-heading">Inscritos</h2>
					<hr class="my-4">
				</div>
			</div>
		</div>
	  <div class="container">
			<div class="row">
				<div class="col-lg-12 text-left text-white">
				<h3 class="section-heading"><span class="cg_blue2">{{$evento->NO_EVENTO}}</span></h3>
				
				</div>
			</div>
		</div>
		
		
			<div class="container text-white" style="margin-top:20px">
				<div class="row">
					<form name="buscarInscricao" role="form" style="min-width: 100%;" method="post" action="{{Request::url()}}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}" />
						<input type="hidden" name="CD_EVENTO" value="{{$evento->CD_EVENTO}}" />
						<div class="col-lg-4" style="display: inline-block;">
							<div class="form-group">
								<label>Nome</label>
								<input type="text" class="form-control" name="NO_USUARIO" id="NO_USUARIO" value="{{@$request['NO_USUARIO']}}">
							</div>							
						</div>
						<div class="col-lg-4" style="display: inline-block;">
							<div class="form-group">
								<label>Igreja/Congregação</label>
								<select class="form-control" name="CD_CONGREGACAO" id="CD_CONGREGACAO">
									@if(count($congregacao)>1)
										<option value="">Selecione</option>
									@endif
									@foreach($congregacao as $c)
										<option value="{{$c->CD_CONGREGACAO}}" {{@$request['CD_CONGREGACAO']==$c->CD_CONGREGACAO?'selected':''}} >{{$c->NO_CONGREGACAO}}</option>
									@endforeach					
								</select>
							</div>							
						</div>
						<div class="col-lg-3" style="display: inline-block;">
							<div class="form-group">
								<label>Status</label>
								<select class="form-control" name="CD_STATUS" id="CD_STATUS">
									<option value="">Selecione</option>
									@foreach($status as $s)
										<option value="{{$s->CD_STATUS}}" {{@$request['CD_STATUS']==$s->CD_STATUS?'selected':''}} >{{$s->NO_STATUS}}</option>
									@endforeach					
								</select>
							</div>							
						</div>
				</div>
				<div class="row">																				
					<div class="col-md-2">
						<button type="submit" class="btn btn-primary btn-xl">Buscar</button> <br/><br/>
					</div>					
				</div>
			</div>
	

    <div class="container text-white">
      @if(!empty($inscricaoList))
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th>#</th>
							<th>Nome</th>
							<!--<th>E-mail</th> -->
							<!--<th>Sexo</th>
							<th>Nascimento</th> -->
							<th>Celular</th>
							<!--<th>Telefone</th>
							<th>Endereço</th>
							<th>Cidade</th>
							<th>Bairro</th> -->
							<th>Congregação</th>
							<th>Alimentação</th>
							<!--<th>Lider</th> -->
							<th>Status</th>
							<th>Ações</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($inscricaoList as $inscricao) 
							<tr>
								<td>{{$loop->iteration}}</td>
								<td>{{@$inscricao->usuarioPerfil[0]->usuario[0]->NO_USUARIO}}</td>
								<!--<td>{{@$usuario->NO_MAIL}}</td> -->
								<!--<td>{{MyUtil::convSexo(@$usuario->NR_SEXO)}}</td>
								<td>{{MyUtil::convDt(@$usuario->DT_NASCIMENTO)}}</td> -->
								<td>{{MyUtil::mask(@$inscricao->usuarioPerfil[0]->usuario[0]->NR_CELULAR,'mFone')}}</td>
								<!--<td>{{MyUtil::mask(@$usuario->NR_TELEFONE,'mFone')}}</td>
								<td>{{@$usuario->DS_ENDERECO}}</td>
								<td>{{@$usuario->NO_CIDADE}}</td>
								<td>{{@$usuario->NO_BAIRRO}}</td> -->
								<td>{{@$inscricao->usuarioPerfil[0]->congregacao[0]->NO_CONGREGACAO}}</td>
								<td>{{@$inscricao->ST_ALIMENTACAO}}</td>
								<!--<td>{{@$usuario->NO_LIDER}}</td> -->
								<td style="color:#FFF000;">{{@$inscricao->status[0]->NO_STATUS}}</td>
								<td><a href="{{url('/inscricao-detalhes/'.MyUtil::b64UrlEncode(@$inscricao->CD_INSCRICAO+10000))}}" style="" title="Ver detalhes" target="_blank"><i class="fa fa-eye"></i></a></td>
							</tr>
						@endforeach	
					</tbody>
					<tfoot>
						<tr style="color:#f05f40">
							<th colspan="7" style="text-align: center;">Total de registros:&nbsp;&nbsp; {{$inscricaoList->total()}}</th>							
						</tr>																							
					</tfoot>
				</table>
			</div>
			<!-- /.table-responsive -->

			{{$inscricaoList->links()}}
			@else
				<p>Não foram encontradas inscrições para este evento.</p>
			@endif
		</div>      
    </section>
	
	
    

	@if(count($errors) > 0)
	<script>		
		window.location.href='#cadastro';					
	</script>
	@endif
	
	@if(!empty($msgCadastro))
	<script>		
		window.location.href='#cadastro';					
	</script>
	@endif
	
@endsection    


