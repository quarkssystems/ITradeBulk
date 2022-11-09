@if(request()->session()->exists('message'))
    <br>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="alert alert-{{request()->session()->exists('status') ? request()->session()->get('status') : 'primary'}} alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">
                    <i class="fa fa-close"></i>
                </button>
                {{request()->session()->get('message')}}
            </div>
        </div>
    </div>

@endif

@if(request()->session()->exists('errmessage'))
    <br>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="alert alert-{{request()->session()->exists('errstatus') ? request()->session()->get('errstatus') : 'danger'}} alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">
                    <i class="fa fa-close"></i>
                </button>
                {{request()->session()->get('errmessage')}}
            </div>
        </div>
    </div>

@endif