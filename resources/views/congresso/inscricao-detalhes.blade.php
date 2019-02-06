<?php 
//use App\Classes\URLify;
use App\Classes\MyUtil;
if(@$inscricao->ST_ALIMENTACAO=='Não')
	@$inscricao->evento[0]->VL_INSCRICAO = 0;
?>


@extends('layout.principal')	

@section('title', 'JUCAAD | Detalhes da Inscrição')

@section('conteudo')

	<section class="cg_bg-new-blue" id="usuarios">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 text-center text-white">
					<h2 class="section-heading">Detalhes da Inscrição</h2>
					<hr class="my-4">
				</div>
			</div>
			<div class="row">
				<div class="col-lg-4 mx-auto">
					@if(!empty($msg))
						@component('components.alert')
							@slot('type',$msg['type'])
							@slot('text',$msg['text'])
						@endcomponent	
					@endif
				</div>	
			</div>
		</div>
	  	<div class="container">
			<div class="row">
				<div class="col-lg-12 text-left text-white">
				<h3 class="section-heading"><span class="cg_blue2">{{$inscricao->evento[0]->NO_EVENTO}}</span></h3>
				
				</div>
			</div>
		</div>
		

		<div class="container text-white">
			@if(!empty($inscricao))
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover">
						<tbody>
							<tr>
								<th>Nome</th>
								<td>{{@$inscricao->usuarioPerfil[0]->usuario[0]->NO_USUARIO}}</td>
							</tr>
							<tr>
								<th>E-mail</th>
								<td>{{@$inscricao->usuarioPerfil[0]->usuario[0]->NO_MAIL}}</td>
							</tr>
							<tr>
								<th>Sexo</th>
								<td>{{MyUtil::convSexo(@$inscricao->usuarioPerfil[0]->usuario[0]->NR_SEXO)}}</td>
							</tr>
							<tr>
								<th>Nascimento</th>
								<td>{{MyUtil::convDt(@$inscricao->usuarioPerfil[0]->usuario[0]->DT_NASCIMENTO)}}</td>
							</tr>
							<tr>
								<th>Celular</th>
								<td>{{MyUtil::mask(@$inscricao->usuarioPerfil[0]->usuario[0]->NR_CELULAR,"mFone")}}</td>
							</tr>
							<tr>
								<th>Telefone</th>
								<td>{{MyUtil::mask(@$inscricao->usuarioPerfil[0]->usuario[0]->NR_TELEFONE,"mFone")}}</td>
							</tr>
							<tr>
								<th>Endereço</th>
								<td>{{@$inscricao->usuarioPerfil[0]->usuario[0]->DS_ENDERECO}}</td>
							</tr>
							<tr>
								<th>Cidade</th>
								<td>{{@$inscricao->usuarioPerfil[0]->usuario[0]->NO_CIDADE}}</td>
							</tr>
							<tr>
								<th>Bairro</th>
								<td>{{@$inscricao->usuarioPerfil[0]->usuario[0]->NO_BAIRRO}}</td>
							</tr>							
							<tr>
								<th>Congregação</th>
								<td>{{@$inscricao->usuarioPerfil[0]->congregacao[0]->NO_CONGREGACAO}}</td>
							</tr>
							<tr>
								<th>Líder</th>
								<td>{{@$inscricao->usuarioPerfil[0]->usuario[0]->usuarioLider[0]->NO_USUARIO}}</td>
							</tr>
							<tr>
								<th>Alimentação</th>
								<td>{{@$inscricao->ST_ALIMENTACAO}}</td>
							</tr>
							<tr style="color:#FFF000;">
								<th>Taxa de Inscrição</th>
								<td>R$ {{number_format(@$inscricao->evento[0]->VL_INSCRICAO, 2, ',' , '.')}}</td>
							</tr>
							<tr style="color:#FFF000;" >
								<th>Status</th>
								
								<td id="showStatus" style="">
									{{@$inscricao->status[0]->NO_STATUS}} 
									@if(@$perfilLogado->CD_PERFIL == 1 || (@$perfilLogado->CD_PERFIL == 2 && @$perfilLogado->CD_CONGREGACAO == @$inscricao->usuarioPerfil[0]->congregacao[0]->CD_CONGREGACAO))
										<a id="editarStatus" style="margin-left:15px" href="javascript:void(0)">[Editar status]</a>
									@endif	
								</td>
								
								@if(@$perfilLogado->CD_PERFIL == 1 || (@$perfilLogado->CD_PERFIL == 2 && @$perfilLogado->CD_CONGREGACAO == @$inscricao->usuarioPerfil[0]->congregacao[0]->CD_CONGREGACAO))
								<td id="editaStatus" style="display:none">
									<form name="f_edita_status" id="f_edita_status" method="post" action="{{url('/alterar-status-inscricao')}}">
										<input type="hidden" name="_token" value="{{ csrf_token() }}" />
										<input type="hidden" name="CD_INSCRICAO" value="{{ $inscricao->CD_INSCRICAO }}" />
										<div class="col-lg-4" style="display: inline-block;">
											<div class="form-group">												
												<select class="form-control" name="CD_STATUS" id="CD_STATUS">
													@foreach($status as $s)
														<option value="{{$s->CD_STATUS}}" {{@$request['CD_STATUS']==$s->CD_STATUS?'selected':''}} >{{$s->NO_STATUS}}</option>
													@endforeach					
												</select>
												<script>$('#CD_STATUS').val('{{@$inscricao->status[0]->CD_STATUS}}')</script>		
											</div>				
										</div>
										<a id="salvarEdicaoStatus" onClick="f_edita_status.submit();" style="margin-left:15px" href="javascript:void(0)">[Salvar status]</a>
										<a id="cancelarEdicaoStatus" style="margin-left:15px" href="javascript:void(0)">[Cancelar alteração]</a>
									</form>
								</td>
								@endif
							</tr>
						</tbody>
					</table>	
				</div> <!-- /.table-responsive -->		
			@endif	
		</div>

		<div class="container text-white">
			@if(!empty($inscricao->inscricaoPedido))
				<div class="table-responsive">					
					<table class="table table-striped table-bordered table-hover">						
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
							</tr>	
						</thead>
						<tbody>
							@php($totalValor=0)
							@foreach ($inscricao->inscricaoPedido as $inscricaoPedido)
								<tr>
									<td>{{$loop->iteration}}</td>
									<td>{{$inscricaoPedido->produto[0]->NO_PRODUTO}}</td>
									<td>{{$inscricaoPedido->NO_COR}}</td>
									<td>{{$inscricaoPedido->NO_GENERO}}</td>
									<td>{{$inscricaoPedido->NO_TAMANHO}}</td>
									<td>{{$inscricaoPedido->NR_QUANTIDADE}}</td>
									<td>R$ {{number_format($inscricaoPedido->produto[0]->VL_PRODUTO, 2, ',' , '.')}}</td>
								</tr>
								@php($totalValor += $inscricaoPedido->NR_QUANTIDADE * $inscricaoPedido->produto[0]->VL_PRODUTO)
							@endforeach	
						</tbody>
						<tfoot>
							<tr style="color:#FFF000">	
								<th colspan="6" style="text-align: center;">Total do Pedido</th>
								<th colspan="1">R$ {{number_format($totalValor, 2, ',' , '.')}} </th>
							</tr>
							<tr style="color:#f05f40">	
								<th colspan="6" style="text-align: center;">Total do Pedido (R$ {{number_format($totalValor, 2, ',' , '.')}}) + Taxa de Inscrição (R$ {{number_format(@$inscricao->evento[0]->VL_INSCRICAO, 2, ',' , '.')}})</th>
								<th colspan="1">R$ {{number_format($totalValor+@$inscricao->evento[0]->VL_INSCRICAO, 2, ',' , '.')}} </th>
							</tr>																							
						</tfoot>
					</table>
				</div>
			@endif
		</div>			      
    </section>
	
@endsection    


