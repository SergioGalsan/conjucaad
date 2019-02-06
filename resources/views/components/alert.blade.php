
@switch($type)
	@case('danger')		
		@php($icone = '<i class="fa fa-exclamation-circle append-icon"></i>')
		@break	
	@case('success')
		@php($icone = '<i class="fa fa-check-circle append-icon"></i>')
		@break
	@case('info')	
		@php($icone = '<i class="fa fa-info-circle append-icon"></i>')
		@break
	@case('warning')
		@php($icone = '<i class="fa fa-exclamation-triangle append-icon"></i>')
		@break
	@default
		@php($type = "warning")
		@php($icone = '<i class="fa fa-exclamation-triangle append-icon"></i>')
@endswitch

<div class="alert alert-{{@$type}} alert-dismissable alert-condensed">                            
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="icon-cross"></i></button>
	{!!@$icone!!} {!!@$text!!} 
</div>