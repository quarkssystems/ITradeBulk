@php $sep = "?"; @endphp
@if (strpos(Request::fullUrl(), '?') !== false)
	@php
		$sep = "&";
	@endphp
@else
	@php
		$sep = "?";
	@endphp
@endif		
 @foreach ($list as $key=>$val)
	@if ($param == 'supplier')
		<li class='nav-link'> <a href="{{route('products')}}{{$sep}}{{$param}}={{$val->slug}}"> <span>{{$val->companyname}}</span></a></li>
	@else 
		<li class='nav-link'> <a href="{{route('products')}}{{$sep}}{{$param}}={{$val->slug}}"> <span>{{$val->name}}</span></a></li>
	@endif
    
 @endforeach