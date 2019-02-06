<?php 
//use App\Classes\URLify;
use App\Classes\MyUtil; 
?>


@extends('layout.principal')	

@section('title', 'Congresso | Usuários')

@section('conteudo')

	<section class="bg-primary" id="usuarios">
      <div class="container">
        <div class="row">
          <div class="col-lg-12 text-center text-white">
            <h2 class="section-heading">Usuários</h2>
            <hr class="my-4">
          </div>
        </div>
      </div>
      <div class="container text-white">
        @if(!empty($usuarioList))
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>Nome</th>
						<th>E-mail</th>
						<!--<th>Sexo</th>
						<th>Nascimento</th> -->
						<th>Celular</th>
						<!--<th>Telefone</th>
						<th>Endereço</th>
						<th>Cidade</th>
						<th>Bairro</th> -->
						<th>Congregação</th>
						<!--<th>Lider</th> -->
						<th>Ações</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($usuarioList as $usuario)
						<tr>
							<td>{{$loop->iteration}}</td>
							<td>{{@$usuario->NO_USUARIO}}</td>
							<td>{{@$usuario->NO_MAIL}}</td>
							<!--<td>{{MyUtil::convSexo(@$usuario->NR_SEXO)}}</td>
							<td>{{MyUtil::convDt(@$usuario->DT_NASCIMENTO)}}</td> -->
							<td>{{MyUtil::mask(@$usuario->NR_CELULAR,'mFone')}}</td>
							<!--<td>{{MyUtil::mask(@$usuario->NR_TELEFONE,'mFone')}}</td>
							<td>{{@$usuario->DS_ENDERECO}}</td>
							<td>{{@$usuario->NO_CIDADE}}</td>
							<td>{{@$usuario->NO_BAIRRO}}</td> -->
							<td>{{@$usuario->congregacao[0]->NO_CONGREGACAO}}</td>
							<!--<td>{{@$usuario->NO_LIDER}}</td> -->
							<td><a title="Ver detalhes" href="javascript:void()" style="color:#FFF"><i class="fa fa-eye"></i></a></td>
						</tr>
					@endforeach	
				</tbody>
			</table>
		</div>
		<!-- /.table-responsive -->
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


