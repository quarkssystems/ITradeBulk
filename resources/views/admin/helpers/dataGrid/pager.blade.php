<div class="mb-3 col-md-5 col-lg-5 col-xs-12 col-sm-12">
    <strong>{{$data->perPage() <= $data->total() ? $data->perPage() : $data->total()}}</strong> {{__('of')}} <strong>{{$data->total()}}</strong> {{__('records')}}
</div>