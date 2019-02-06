$(function() {
  $('.mDate').mask('00/00/0000');
  $('.mTime').mask('00:00');
  $('.mDateTime').mask('00/00/0000 00:00');
  $('.mCep').mask('00000-000');
  $('.mConta').mask('000.000000-0');
  $('.mCnpj').mask('00.000.000/0000-00', {reverse: false});
  $('.mCpf').mask('000.000.000-00', {reverse: false});
  $('.mMoney').mask('#.##0,00', {reverse: true});
  $('.mNum').mask('#', {reverse: true});
  $('.mCard').mask('0000 0000 0000 0000', {reverse: false});
  
  var FoneMaskBehavior = function (val) {
    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
  },
  FoneOptions = {
    onKeyPress: function(val, e, field, options) {
        field.mask(FoneMaskBehavior.apply({}, arguments), options);
      }
  };
  $('.mFone').mask(FoneMaskBehavior, FoneOptions);
  
  // Mascara de CPF e CNPJ
  var CpfCnpjMaskBehavior = function (val) {
			return val.replace(/\D/g, '').length <= 11 ? '000.000.000-009' : '00.000.000/0000-00';
		},
  cpfCnpjpOptions = {
    	onKeyPress: function(val, e, field, options) {
      	field.mask(CpfCnpjMaskBehavior.apply({}, arguments), options);
      }
    };

  $('.mCpfCnpj').mask(CpfCnpjMaskBehavior, cpfCnpjpOptions);
  
  
  
  
  
  $('.mixed').mask('AAA 000-S0S');
  $('.mIpAddress').mask('099.099.099.099');
  $('.mPercent').mask('##0,00%', {reverse: true});
  $('.clear-if-not-match').mask("00/00/0000", {clearIfNotMatch: true});
  $('.placeholder').mask("00/00/0000", {placeholder: "__/__/____"});
 
  $('.fallback').mask("00r00r0000", {
    translation: {
      'r': {
        pattern: /[\/]/,
        fallback: '/'
      },
      placeholder: "__/__/____"
    }
  });

  $('.selectonfocus').mask("00/00/0000", {selectOnFocus: true});

  $('.cep_with_callback').mask('00000-000', {onComplete: function(cep) {
      console.log('Mask is done!:', cep);
    },
     onKeyPress: function(cep, event, currentField, options){
      console.log('An key was pressed!:', cep, ' event: ', event, 'currentField: ', currentField.attr('class'), ' options: ', options);
    },
    onInvalid: function(val, e, field, invalid, options){
      var error = invalid[0];
      console.log ("Digit: ", error.v, " is invalid for the position: ", error.p, ". We expect something like: ", error.e);
    }
  });

  $('.crazy_cep').mask('00000-000', {onKeyPress: function(cep, e, field, options){
    var masks = ['00000-000', '0-00-00-00'];
      mask = (cep.length>7) ? masks[1] : masks[0];
    $('.crazy_cep').mask(mask, options);
  }});

  

  

  $(".bt-mask-it").click(function(){
    $(".mask-on-div").mask("000.000.000-00");
    $(".mask-on-div").fadeOut(500).fadeIn(500)
  })

  //$('pre').each(function(i, e) {hljs.highlightBlock(e)});
});