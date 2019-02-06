$(document).on('click','#a2GoToTab2', function(){
    $('#a1GoToTab2').click();
})
$(document).on('click','#a2GoToTab1', function(){
    $('#a1GoToTab1').click();
})

$(document).on('change','[name=CD_CONGREGACAO]', function(){ 
    var selectbox = $('[name=CD_USUARIO_LIDER]');
    var cdCongregacao = $(this).val();
    if(cdCongregacao==""){
        selectbox.find('option').remove();               
        $('<option>').val("").text("Selecione a congregação primeiro...").appendTo(selectbox);
        return false;
    }	
	var url = $('meta[name=base-url]').attr('content') + '/congregacao/'+cdCongregacao+'/coordenadores';	
	$.ajax({
		 method: "GET",
		 url: url,
		 dataType: "json",		 
		 success: function(data) {            
            if(data.length>0){                
                selectbox.find('option').remove();
                $.each(data, function (i, d) {
                    $('<option>').val(d.CD_USUARIO).text(d.usuario[0].NO_USUARIO).appendTo(selectbox);
                });
            }  	
		 }
	  });
});

$(document).on('click','#naoFazerPedido', function(){    
    if($(this).prop('checked')){
        $("#tbPedido > tbody").children().remove();        
        $('#valorTotal .valor').html("0,00");
        $('#grupoPedido').hide(500);
    }else    
        $('#grupoPedido').show(500);
})


$(document).on('click','#addProduto', function(){
    
    var checked = $("[name=NO_COR_aux]:checked").length;				
    if(!checked) {        
        alert("Selecione ao menos uma opção de camisa.");
        return false;
    }
    if($("#NO_GENERO_aux").val()=="" || $("#NO_TAMANHO_aux").val()=="" || $("#NR_QUANTIDADE_aux").val()==""){
        alert("Existem campos obrigatórios sobre o pedido não preenchidos, verifique e tente adicionar novamente.")
        return false;
    }

    $('#tbPedido').find('tbody').append(
          '<tr>'
            + '<input type="hidden" name="CD_PRODUTO[]" value="1" >'
            + '<input type="hidden" name="NO_COR[]" value="'+$("[name=NO_COR_aux]:checked").val()+'" >'
            + '<input type="hidden" name="NO_GENERO[]" value="'+$("#NO_GENERO_aux").val()+'" >'
            + '<input type="hidden" name="NO_TAMANHO[]" value="'+$("#NO_TAMANHO_aux").val()+'" >'
            + '<input type="hidden" name="NR_QUANTIDADE[]" value="'+$("#NR_QUANTIDADE_aux").val()+'" >'
            + '<td class="n"></td>'
            + '<td>Camisa CONJUCAAD 2019</td>'
            + '<td>'+$("[name=NO_COR_aux]:checked").val()+'</td>'
            + '<td>'+$("#NO_GENERO_aux").val()+'</td>'
            + '<td>'+$("#NO_TAMANHO_aux").val()+'</td>' 
            + '<td>'+$("#NR_QUANTIDADE_aux").val()+'</td>'
            + '<td class="valorProduto" value="'+($("#NR_QUANTIDADE_aux").val()*35)+'">R$ '+($("#NR_QUANTIDADE_aux").val()*35)+',00</td>'
            + '<td>'						
            +       '<a title="Remover este item" href="javascript:void(0)" style="" class="delProduto"><i class="fa fa-times"></i></a>&nbsp;&nbsp;'          
            + '</td>'
        + '</tr>');

    // Ajustar os numeros das linhas das linhas de header
	$('.n').each(function(index, element) {                            
        $(this).html(index+1);
        if((index+1) & 1)
            var color = "#FFF000";
        else
            var color = "#FFF";
        $(this).parent('tr').css('color',color);
    });

    var valorTotal = parseFloat(0);
    $('.valorProduto').each(function(index, element) {         
        valorTotal += parseFloat($(this).attr('value'));
    });
    $('#valorTotal .valor').html(String(valorTotal) + ",00");   
})


$(document).on('click','.delProduto', function(){ console.log('delProduto...');
    $(this).parent('td').parent('tr').remove();
    // Ajustar os numeros das linhas das linhas de header
	$('.n').each(function(index, element) {                            
        $(this).html(index+1);
        if((index+1) & 1)
            var color = "#FFF000";
        else
            var color = "#FFF";
        $(this).parent('tr').css('color',color);
    });
    
    var valorTotal = 0;
    $('.valorProduto').each(function(index, element) { 
        valorTotal += parseFloat($(this).attr('value'));
    });
    $('#valorTotal .valor').html(String(valorTotal) + ",00");   
})

$(document).on('click','#editarStatus', function(){    
   $('#showStatus').css("display","none");
   $('#editaStatus').show();
})

$(document).on('click','#cancelarEdicaoStatus', function(){    
   $('#showStatus').show();
   $('#editaStatus').css("display","none");
})


// PARA TESTES 
$(document).ready(function(){
    $("[name=NO_USUARIO]").val("Fulano de Teste");
    $("[name=NO_MAIL]").val("fulano@teste.com");
    $("[name=NR_SEXO]").val("1");
    $("[name=DT_NASCIMENTO]").val("01/01/2001");
    $("[name=NR_CELULAR]").val("(61) 98742-5613");
    $("[name=DS_ENDERECO]").val("Brasília");
    $("[name=NO_CIDADE]").val("Brasília");
    $("[name=NO_BAIRRO]").val("Brasília");
    //$("[name=CD_CONGREGACAO]").val("1");
    //$("[name=CD_USUARIO_LIDER]").val("2");
})
