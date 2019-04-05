<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'CongressoController@index');

Route::post('/login','UsuarioController@login');

Route::get('/logout','UsuarioController@logout');

Route::get('/alterar-senha','UsuarioController@getAlterarSenha');

Route::post('/alterar-senha','UsuarioController@postAlterarSenha');

Route::post('/inscricao','UsuarioController@postFormInscricao');

Route::get('/usuarios','UsuarioController@index');

Route::get('/congregacao/{cdCongregacao}/coordenadores','UsuarioController@congregacaoCoordenadores');

Route::get('/inscritos/{cdEevento?}','CongressoController@getFiltroEventosAtivos');

Route::post('/inscritos/{cdEevento?}','CongressoController@buscarInscricao');

Route::get('/pedidos','CongressoController@getFiltroEventosAtivos');

Route::post('/pedidos','CongressoController@buscarPedidos');

Route::get('/inscricao-detalhes/{cdInscricaoHash}','CongressoController@getInscricaoDetalhes');

Route::post('/alterar-status-inscricao','CongressoController@postAlterarStatusInscricao');

Route::post('/alterar-inscricao','UsuarioController@postFormInscricao');

Route::get('/eventos','CongressoController@eventos');

Route::get('/mail-confirmacao-inscricao/{mail}', function ($mail) { 
    $inscricao = App\Inscricao::with(['usuarioPerfil','status','evento','inscricaoPedido'])->request(["NO_MAIL"=>$mail])->first(); 
    if(!empty($inscricao)){
        Illuminate\Support\Facades\Mail::to($mail)->send(new App\Mail\InscricaoRealizadaComSucesso($inscricao));
        return new App\Mail\InscricaoRealizadaComSucesso($inscricao);
    }else 
        return "Não foi localizada inscrição por parte do e-mail <strong>{$mail}</strong>";   
});