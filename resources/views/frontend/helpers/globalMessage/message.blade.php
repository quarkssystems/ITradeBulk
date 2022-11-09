@if(request()->session()->exists('message'))
    <section class="global-message">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="alert alert-{{request()->session()->exists('status') ? request()->session()->get('status') : 'primary'}} alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">
                            <i class="fa fa-times"></i>
                        </button>
                      {!! request()->session()->get('message') !!}
                    </div>

                </div>
            </div>
        </div>
    </section>
@endif
@if ($errors->any())
<section class="global-message">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">
                        <i class="fa fa-times"></i>
                    </button>
                    @php(  $msg = $errors->all())
                    {!!  implode('<br>', $msg) !!}
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{--@if(request()->route()->named('supplier.inventory.*'))
    @if(!empty($errors->all()))
        <section class="global-message">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">
                                <i class="fa fa-times"></i>
                            </button>
                            @php(  $msg = $errors->all())
                            {!!  implode('<br>', $msg) !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endif--}}