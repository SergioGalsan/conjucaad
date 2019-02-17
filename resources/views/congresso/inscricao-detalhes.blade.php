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
				<div class="col-md-8 mx-auto">
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
				<div class="col-md-12 text-left text-white">
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
									<td>R$ {{number_format($inscricaoPedido->NR_QUANTIDADE * $inscricaoPedido->produto[0]->VL_PRODUTO, 2, ',' , '.')}}</td>
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

		@if(@$perfilLogado->CD_PERFIL == 1 || (@$perfilLogado->CD_PERFIL == 2 && @$perfilLogado->CD_CONGREGACAO == @$inscricao->usuarioPerfil[0]->congregacao[0]->CD_CONGREGACAO))
			<div class="container text-white">
				<div class="row">									
					<div class="col-md-4">
						<a class="btn btn-light btn-xl text-primary" data-toggle="modal" data-target="#editarDadosInscricao">Editar dados desta inscrição</a>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
						<!-- Modal -->
						<div class="modal fade" id="editarDadosInscricao" tabindex="-1" role="dialog" >
							<div class="modal-dialog text-white" style="min-width:60%;">
								<div class="modal-content cg_bg-dark-blue" >
									<div class="modal-header">
										<h4 class="modal-title" >Editar dados da inscrição</h4>
									</div>
									<div class="modal-body"> 
										<form id="f_editar_inscricao" method="post" name="f_editar_inscricao" class="" action="{{url('/alterar-inscricao')}}">
											<input type="hidden" name="_token" value="{{ csrf_token() }}" />
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<label>Nome</label>												
														<input class="form-control" type="text" name="NO_USUARIO" value="{{@$inscricao->usuarioPerfil[0]->usuario[0]->NO_USUARIO}}">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<label>E-mail <span style="color: #f05f40; font-size: 12px;">(Se for necessário alterar o e-mail, entrar em contato com o administrador do site)</span></label>												
														<input class="form-control" type="text" name="NO_MAIL" value="{{@$inscricao->usuarioPerfil[0]->usuario[0]->NO_MAIL}}" readonly>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label>Sexo</label>
														<select class="form-control" name="NR_SEXO">
															<option>Selecione</option>
															<option value="1">Masculino</option>
															<option value="2">Feminino</option>						
														</select>
														<script>$('[name=NR_SEXO]').val('{{@$inscricao->usuarioPerfil[0]->usuario[0]->NR_SEXO}}')</script>	
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>Data de Nascimento</label>												
														<input class="form-control" type="text" name="DT_NASCIMENTO" value="{{MyUtil::convDt(@$inscricao->usuarioPerfil[0]->usuario[0]->DT_NASCIMENTO)}}">
													</div>
												</div>
											</div>
											<div class="row">	
												<div class="col-md-6">
													<div class="form-group">
														<label>Telefone Celular/WhatsApp</label>
														<input type="text" class="form-control mFone" name="NR_CELULAR" value="{{@$inscricao->usuarioPerfil[0]->usuario[0]->NR_CELULAR}}">					
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>Telefone Residencial</label>
														<input type="text" class="form-control mFone" name="NR_TELEFONE" value="{{@$inscricao->usuarioPerfil[0]->usuario[0]->NR_TELEFONE}}">					
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label>Endereço</label>
														<input type="text" class="form-control" name="DS_ENDERECO" value="{{@$inscricao->usuarioPerfil[0]->usuario[0]->DS_ENDERECO}}">					
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>Cidade</label>
														<input type="text" class="form-control" name="NO_CIDADE" value="{{@$inscricao->usuarioPerfil[0]->usuario[0]->NO_CIDADE}}">					
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label>Bairro</label>
														<input type="text" class="form-control" name="NO_BAIRRO" value="{{@$inscricao->usuarioPerfil[0]->usuario[0]->NO_BAIRRO}}">					
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>Igreja/Congregação</label>
														<select class="form-control" name="CD_CONGREGACAO">
															<option value="">Selecione</option>
															@foreach($congregacao as $c)
																<option value="{{$c->CD_CONGREGACAO}}">{{$c->NO_CONGREGACAO}}</option>
															@endforeach
														</select>
														<script>$('[name=CD_CONGREGACAO]').val('{{@$inscricao->usuarioPerfil[0]->congregacao[0]->CD_CONGREGACAO}}')</script>
													</div>							
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label>Nome do líder</label>
														<select class="form-control" name="CD_USUARIO_LIDER">
															<option value="{{@$inscricao->usuarioPerfil[0]->usuario[0]->usuarioLider[0]->CD_USUARIO}}">{{@$inscricao->usuarioPerfil[0]->usuario[0]->usuarioLider[0]->NO_USUARIO}}</option>
														</select>
													</div>							
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<input value="Não" type="checkbox" class="" name="ST_ALIMENTACAO" id="ST_ALIMENTACAO" {{@$inscricao->ST_ALIMENTACAO=='Não'?'checked':''}}>	
														<label for="ST_ALIMENTACAO" class="text-primary" style="display: contents;"> Não irei participar da alimentação que será oferecida durante o congresso. (A taxa de inscrição não será cobrada nesse caso)</label>
													</div>							
												</div>
											</div>
											
											<br/>
											<h2 class="text-center">Pedidos</h2>
											<hr/>

											
											<div id="grupoPedido">

													<div class="row">
														<div class="col-md-6">
															<div class="form-group">
																<img src="{{url('/img/camisa_jucaad_azul.jpg')}}" style="width:90%;"/><br/>
																<input type="radio" class="" name="NO_COR_aux" id="camisaAzul" value="Azul">	
																<label for="camisaAzul" class="lbCamisa"> Camisa Azul - R$ 35,00</label>				
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">
																<img src="{{url('/img/camisa_jucaad_vermelho.jpg')}}" style="width:90%;"/><br/>
																<input type="radio" class="" name="NO_COR_aux" id="camisaVermelha" value="Vermelha">	
																<label for="camisaVermelha" class="lbCamisa"> Camisa Vermelha - R$ 35,00</label>
															</div>
														</div>	
													</div>

													<div class="row">
														<div class="col-md-3">
															<div class="form-group">
																<label>Gênero</label>
																<select class="form-control" name="NO_GENERO_aux" id="NO_GENERO_aux">
																	<option>Selecione</option>
																	<option value="Masculina">Masculina</option>
																	<option value="Feminina">Feminina</option>						
																</select>
															</div>							
														</div>
														<div class="col-md-3">
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
														<div class="col-md-3">
															<div class="form-group">
																<label>Quantidade</label>
																<input type="number" class="form-control" name="NR_QUANTIDADE_aux" id="NR_QUANTIDADE_aux" min="1">
															</div>							
														</div>
														<div class="col-md-3">
															<div class="form-group">
																<label style="visibility:hidden">Adicionar ao pedido</label>
																<br><span ><a href="javascrit:void(0)" class="btn btn-primary btn-xl" name="addProduto" id="addProduto">Adicionar ao pedido</a></span>
															</div>	
														</div>
													</div>

													<div class="row" >
														<div class="col-md-12">
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
																		@php($totalValor=0)
																		@foreach ($inscricao->inscricaoPedido as $inscricaoPedido)
																			<tr>
																				<input type="hidden" name="CD_PRODUTO[]" value="1" >
																				<input type="hidden" name="NO_COR[]" value="{{$inscricaoPedido->NO_COR}}" >
																				<input type="hidden" name="NO_GENERO[]" value="{{$inscricaoPedido->NO_GENERO}}" >
																				<input type="hidden" name="NO_TAMANHO[]" value="{{$inscricaoPedido->NO_TAMANHO}}" >
																				<input type="hidden" name="NR_QUANTIDADE[]" value="{{$inscricaoPedido->NR_QUANTIDADE}}" >
																				<td class="n">{{$loop->iteration}}</td>
																				<td>{{$inscricaoPedido->produto[0]->NO_PRODUTO}}</td>
																				<td>{{$inscricaoPedido->NO_COR}}</td>
																				<td>{{$inscricaoPedido->NO_GENERO}}</td>
																				<td>{{$inscricaoPedido->NO_TAMANHO}}</td>
																				<td>{{$inscricaoPedido->NR_QUANTIDADE}}</td>
																				<td class="valorProduto" value="{{$inscricaoPedido->NR_QUANTIDADE * $inscricaoPedido->produto[0]->VL_PRODUTO}}">R$ {{number_format($inscricaoPedido->NR_QUANTIDADE * $inscricaoPedido->produto[0]->VL_PRODUTO, 2, ',' , '.')}}</td>
																				<td><a title="Remover este item" href="javascript:void(0)" style="" class="delProduto"><i class="fa fa-times"></i></a>&nbsp;&nbsp;</td>
																			</tr>
																			@php($totalValor += $inscricaoPedido->NR_QUANTIDADE * $inscricaoPedido->produto[0]->VL_PRODUTO)
																		@endforeach																								
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
																			<th colspan="2" id="valorTotal"><span class="moeda">R$ </span><span class="valor">{{number_format($totalValor, 2, ',' , '.')}}</span> </th>
																		</tr>																							
																	</tfoot>
																</table>
															</div>
														</div>
													</div>

											</div> <!-- #grupoPedido -->
											


										</form>
									
									</div>
									<div class="modal-footer background-login">
										<button type="button" class="btn" data-dismiss="modal">Fechar</button>
										<button type="button" class="btn btn-primary" onclick="f_editar_inscricao.submit()">Salvar Alterações</button>
									</div>
								</div>
								<!-- /.modal-content --> 
							</div>
							<!-- /.modal-dialog --> 
						</div>
						<!-- /.modal -->
				
				</div>
			</div>
		@endif
  </section>
	
@endsection    


