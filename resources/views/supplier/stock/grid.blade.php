{{-- /**
 * Created by PhpStorm.
 * User: mayank
 * Date: 22/11/18
 * Time: 10:24 AM
 */ --}}
<div class="alert alert-warning alert-dismissible hidden" role="alert" id="supplier-stock-msg">
    <strong>Warning!</strong> <span class="dot"></span> : Please verify price of the product highlight in this color.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="table-responsive">
    <table class="table table-dashboard table-add-stock">
        <thead class="table-light">
            {!! $dataGridTitle !!}
        </thead>
        <tbody>
            <tr>
                {!! $dataGridSearch !!}
            </tr>
            @php($countStart = ($data->currentPage() - 1) * $data->perPage())
            @php($amtMargin = 10)
            @php($cmpClass = '')
            @foreach ($data as $datum)
        
                {{-- {{dd($datum)}} --}}
                {{-- @if ($datum->single_price > $datum->base_price + $amtMargin || $datum->single_price < $datum->base_price - $amtMargin)
                    @php($cmpClass = 'supplier-stock-bg-color')
                @endif --}}
                @if ($datum->single_price < $datum->base_price - $amtMargin)
                    @php($cmpClass = 'supplier-stock-bg-color')
                @endif
                <tr class="{{ $cmpClass }}">
                    <td>{{ $countStart + $loop->iteration }}</td>
                    <td>{{ $datum->name }}</td>
                    {{-- <td>{{ $datum->product_name }}</td> --}}
                    <td>{{ $datum->brand->name }}</td>
                    {{-- <td>{{ $datum->product_brand_name }}</td> --}}
                    <td>{{ $datum->packing }}</td>
                    {{-- <td>{{ $datum->product_stock_type }}</td> --}}
                    <td>
                        Quantity: {{ ($datum->single != null) ? $datum->single : '0' }}
                        <hr class="mt-1 mb-1">
                        Selling Price: R {{ ($datum->single_price != null) ? $datum->single_price : '0' }} <br>
                        Cost Price: R {{ ($datum->base_price != null) ? $datum->base_price : '0' }}
                    </td>

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
