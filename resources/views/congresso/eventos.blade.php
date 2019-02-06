<?php 
//use App\Classes\URLify;
use App\Classes\MyUtil; 
?>


@extends('layout.principal')	

@section('title', 'JUCAAD | Eventos')

@section('conteudo')

	<section class="cg_bg-new-blue" id="usuarios">
      <div class="container">
        <div class="row">
          <div class="col-lg-12 text-center text-white">
            <h2 class="section-heading">Eventos</h2>
            <hr class="my-4">
          </div>
        </div>
      </div>
	  <div class="container">        
		<div class="row">
		  <div class="col-lg-2 ">			
			<button type="button" class="btn btn-light btn-xl " >Novo evento</button><br /><br />
		  </div>
		</div>		
      </div>
      <div class="container text-white">
        @if(!empty($eventoList))
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>Nome</th>
									<!--<th>Texto de apresentação</th>						
									<th>Detalhes</th>-->
									<th>Dt. Inicio inscrições</th>
									<th>Dt. Fim inscrições</th>
									<th>Valor Inscrição</th>
									<th>Status</th> 
									<th>Ações</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($eventoList as $evento)
									<tr>
										<td>{{$loop->iteration}}</td>
										<td>{{@$evento->NO_EVENTO}}</td>
										<!--<td>{{@$evento->TX_APRESENTACAO}}</td>
										<td>{{@$evento->DS_EVENTO}}</td>-->
										<td>{{MyUtil::convDt(@$evento->DT_INICIO_INSCRICAO)}}</td>
										<td>{{MyUtil::convDt(@$evento->DT_FIM_INSCRICAO)}}</td>
										<td>{{MyUtil::convMoeda(@$evento->VL_INSCRICAO)}}</td>							
										<td>{{@$evento->status[0]->NO_STATUS}}</td>							
										<td>
											<a title="Ver detalhes" href="javascript:void()" style="color:#FFF"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;
											<a title="Editar" href="javascript:void()" style="color:#FFF"><i class="fa fa-pencil"></i></a>
										</td>
									</tr>
								@endforeach	
							</tbody>
						</table>
					</div>
					<!-- /.table-responsive -->
				@else
					<p>Não foram feitas inscrições para este evento.</p>
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


