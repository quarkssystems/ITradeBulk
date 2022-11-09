@if(isset($breadcrumb))
    <section class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            @foreach($breadcrumb as $breadcrumbRoute => $breadcrumbName)
                                @if(!$loop->last)
                                <li class="breadcrumb-item"><a href="{{route($breadcrumbRoute)}}">{{__($breadcrumbName)}}</a></li>
                                @else
                                    <li class="breadcrumb-item active" aria-current="page">{{__($breadcrumbName)}}</li>
                                @endif
                            @endforeach
                        </ol>
                    </nav>

                </div>
            </div>
        </div>
    </section>
@endif