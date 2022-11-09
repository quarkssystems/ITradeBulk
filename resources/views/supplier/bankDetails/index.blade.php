@extends('supplier.layouts.main')
@section('page-header')
    <div class="container-fluid">
        <ol class="breadcrumb breadcrumb-style1">
            <li class="breadcrumb-item"><a href="/">{{__('Home')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{$pageTitle}}</li>
        </ol>
        <div class="page-header">
            <div class="page-title">
                <h4>{{$pageTitle}}</h4>
            </div>
        </div>
    </div>
@endsection
@section('content')

    <div class="row">
        <div class="col-md-12">
            @include('frontend.helpers.globalMessage.message')
        </div>
    </div>
        @include('supplier.bankDetails.edit')
@endsection
@section('footerScript')

    <script type="text/javascript">
      
           $(document).ready(function(){
        


           

         /*   $('#bankbranchform').submit(function(e){
                     e.preventDefault();
                
               let ajaxUrl = "{{route('frontend.ajax.new-bankbranch')}}";
                $.ajax({
                    type: 'POST',
                    data: {_token: TOKEN, $(this).serialize()},
                    url: ajaxUrl,
                    success: function (data) {
                        console.log(data)
                        console.log("ata")
                       
                    },
                   
                });
               
            });*/
        });
    </script>
@endsection
