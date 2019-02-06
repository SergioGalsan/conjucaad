<?php 
//use App\Classes\URLify;
use App\Classes\MyUtil; 
?>


@extends('layout.principal')	

@section('title', 'JUCAAD | Pedidos')

@section('conteudo')

	<section class="cg_bg-new-blue" id="usuarios">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 text-center text-white">
					<h2 class="section-heading">Pedidos</h2>
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
      @if(!empty($pedidoList))
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th>#</th>
							<!--<th>Produto</th>-->
							<th>Nome</th>
							<th>Congregação</th>
							<th>Cor</th>
							<th>Gênero</th>
							<th>Tamanho</th>
							<th>Quantidade</th>
							<th>Valor</th>
							<th>Status da Inscrição</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($pedidoList as $pedido) 
							<tr>
								<td>{{$loop->iteration}}</td>
								<!--<td>{{@$pedido->NO_PRODUTO}}</td>-->
								<td>{{@$pedido->NO_USUARIO}}</td>
								<td>{{@$pedido->NO_CONGREGACAO}}</td>
								<td>{{@$pedido->NO_COR}}</td>
								<td>{{@$pedido->NO_GENERO}}</td>
								<td>{{@$pedido->NO_TAMANHO}}</td>
								<td>{{@$pedido->NR_QUANTIDADE}}</td>
								<td>{{number_format(@$pedido->VL_PRODUTO*@$pedido->NR_QUANTIDADE, 2, ',' , '.')}}</td>
								<td style="color:#FFF000;">{{@$pedido->NO_STATUS}}</td>								
							</tr>
						@endforeach	
					</tbody>
					<tfoot>
						<tr style="color:#f05f40">
							<th colspan="9" style="text-align: center;">Total de registros:&nbsp;&nbsp; {{$pedidoList->total()}}</th>							
						</tr>																							
					</tfoot>
				</table>
			</div>
			<!-- /.table-responsive -->

			{{$pedidoList->links()}}
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


