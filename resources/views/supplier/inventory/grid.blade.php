<div class="alert alert-warning alert-dismissible hidden" role="alert" id="supplier-stock-msg">
    <strong>Warning!</strong> <span class="dot"></span> : Please verify price of the product highlight in this color.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="table-responsive">
    <table class="table table-dashboard table-add-stock" id="stock-add-table">
        <thead class="table-light">
            {!! $dataGridTitle !!}
        </thead>
        <tbody>
            <tr>
                {!! $dataGridSearch !!}
            </tr>
            @php($countStart = ($data->currentPage() - 1) * $data->perPage())
            @php($amtMargin = 10)
            @foreach ($data as $datum)
                @php($stockData = $datum->supplier_stock)

                @php($cmpClass = '')
                {{-- @if ($stockData['single_price'] > $datum->base_price + $amtMargin || $stockData['single_price'] < $datum->base_price - $amtMargin)
                    @php($cmpClass = 'supplier-stock-bg-color')
                @endif --}}
                @if ($stockData['single_price'] < $datum->base_price - $amtMargin)
                    @php($cmpClass = 'supplier-stock-bg-color')
                @endif
                <tr class="{{ $cmpClass }}">
                    <td>{{ $countStart + $loop->iteration }}</td>
                    {{-- <td>{!! $datum->base_image_data !!}</td> --}}
                    <td>
                        {{-- <div class="avatar"> --}}
                        {!! $datum->base_image_data !!}
                        {{-- </div> --}}
                        <span class="pl-2">{{ $datum->name }}</span>{!! $datum->published == 0 ? "<br><label class='badge badge-error'>Unpublish</label>" : '' !!}
                    </td>
                    <td>{{ $datum->brand_name }}</td>
                    <td>{{ $datum->packing }}</td>
                    {{-- <td>{{ $datum->stock_type }}</td> --}}

                    <td> Quantity: {{ $stockData['single'] }} {!! $stockData['single_price'] != 'NA'
                        ? "<br><label class='badge badge-success'>Price: " . $stockData['single_price'] . '</label>'
                        : '' !!}</td>
                    {{-- <td>
                        @if ($doc_approve == 1)
                            <button data-url="{{ route('supplier.inventory.create') }}"
                                data-product="{{ $datum->uuid }}"
                                class="ajax-modal btn-small btn btn-primary btn-xs my-2 my-sm-0"><i
                                    class="fa fa-plus"></i> {{ __('Stock') }}</button>
                        @endif
                    </td> --}}
                    @if (auth()->user()->fact_access == '1')
                        <td>
                            <a href="/supplier/updateFact/{{ $datum->uuid }}"
                                class="btn-small btn btn-primary btn-xs my-2 my-sm-0">Edit</a>
                            {{-- {!! $datum->edit !!} --}}
                        </td>
                    @endif
                </tr>
            @endforeach
            @if ($data->count() == 0)
                <tr>
                    <td colspan="11">
                        <div class="alert alert-primary">{{ __('No data found') }}</div>
                    </td>
                </tr>
            @endif
        </tbody>

    </table>

    {!! $dataGridPagination !!}

</div>
@section('footerScript')
    <script>
        $(document).ready(function() {
            $("#stock-add-table tr").each(function() {
                var className = $(this).attr('class');
                if (typeof(className) !== 'undefined' && className == 'supplier-stock-bg-color') {
                    $("#stock-add-table").removeClass("hidden");
                }
            });
        });
    </script>
@endsection
