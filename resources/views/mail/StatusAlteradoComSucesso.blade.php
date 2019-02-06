<?php 
//use App\Classes\URLify;
use App\Classes\MyUtil; 
?>

<html>
    <body>
		<h1>CONJUCAAD</h1>
        <p>Olá {{ $inscricao->usuarioPerfil[0]->usuario[0]->NO_USUARIO }}!</p>
        
        <p>O status da sua inscrição foi alterado para <strong>{{$inscricao->status[0]->NO_STATUS}}</strong>.</p>
        
		<p>Você pode acompanhar o status da sua inscrição <a target="_blank" href="{{url('/inscricao-detalhes/'.MyUtil::b64UrlEncode($inscricao->CD_INSCRICAO+10000))}}">clicando aqui</a></p>
        
		<p>Att, <br>
        Equipe Conjucaad!</p>
    </body>
</html>