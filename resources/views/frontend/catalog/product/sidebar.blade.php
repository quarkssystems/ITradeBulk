<div class="cate_title">Categories</div>
<div class="filter-search-filterSearchBox">
    <input type="text"  id='searchcategory'  data-ajax-url="{{ route('frontend.ajax.postGetCategories') }}"  data-cat-holder="cat_suggestions"   placeholder="Category" class="filter-search-inputBox filter-search-hidden" >
    <button class="btn btn-primary my-2 my-sm-0 inner-search-btn" type="submit"><i class="fa fa-search"></i></button>
</div>
<ul class="user-sidebar nav flex-column cat_suggestions">
    @foreach ($parentCategories as $category)
        <li class="nav-link">
            <a href="{{ route('products') }}?category={{ $category->slug }}"> <span style="font-size: 14px">{{ $category->name }}</span></a>
        </li>
    @endforeach

    <div class="br_more_cate">
    <a href="{{ url('/products/detail/category') }}" class="btn btn-sm btn-theme product_details_btn" style="font-size: 13px;margin-left:16px;font-weight: bolder;">See more</a>
        <!-- <span id="seemore" style="font-size: 16px;">See more</span>
        <div class="allcategory" style="display: none;">
            <button id="close-btn">X</button>

            <ul class="FilterDirectory-list">
                @foreach($groupedCategories as $key => $categories)
                    <li class="FilterDirectory-listTitle">{{ $key }}</li>

                    @foreach($categories as $category)
                        <li>
                            <label><a href="{{ route('products') }}?category={{ $category->slug }}">{{ $category->name }}</a></label>
                        </li>
                    @endforeach
                @endforeach
            </ul>
        </div> -->
    </div>
    <!-- <div class="br_more_cate">
        <span id="seemore" style="font-size: 16px;">See more</span>
        <div class="allcategory" style="display: none;">
            <button id="close-btn">X</button>

            <ul class="FilterDirectory-list">
                @foreach($groupedCategories as $key => $categories)
                    <li class="FilterDirectory-listTitle">{{ $key }}</li>

                    @foreach($categories as $category)
                        <li>
                            <label><a href="{{ route('products') }}?category={{ $category->slug }}">{{ $category->name }}</a></label>
                        </li>
                    @endforeach
                @endforeach
            </ul>
        </div>
    </div> -->
</ul>

<div class="cate_title">Manufacturer</div>
<div class="filter-search-filterSearchBox">
    <input type="text"  id = 'searchbrand'  data-ajax-url="{{ route('frontend.ajax.postGetBrand') }}"  data-brand-holder="brand_suggestions"   placeholder="Manufacturer"   class="filter-search-inputBox filter-search-hidden" >
    <button class="btn btn-primary my-2 my-sm-0 inner-search-btn" type="submit" ><i class="fa fa-search"></i></button>
</div>
<ul class="user-sidebar nav flex-column brand_suggestions">
    @foreach ($brands as $brand) 
    <li class='nav-link'> 
        <a href="#" class="buildURL" bid="brand" aval="{{ $brand->slug }}">
            <span>{{ $brand->name }}</span>
        </a>
    </li>
    @endforeach
    <div class="br_more_cate">
        <a href="{{ url('/products/detail/manufacturer') }}@if(isset($_SERVER['QUERY_STRING']))?{{$_SERVER['QUERY_STRING']}}@endif" class="btn btn-sm btn-theme product_details_btn" style="font-size: 13px;margin-left:16px;font-weight: bolder;">See more</a>
    </div>
</ul>

{{--<div class="cate_title">Supplier</div>
<div class="filter-search-filterSearchBox">
    <input type="text"  id = 'searchsupplier'  data-ajax-url="{{ route('frontend.ajax.postGetSupplier') }}"  data-supplier-holder="supplier_suggestions"   placeholder="Supplier"   class="filter-search-inputBox filter-search-hidden" >
    <button class="btn btn-primary my-2 my-sm-0 inner-search-btn" type="submit"><i class="fa fa-search"></i></button>
</div>
<ul class="user-sidebar nav flex-column supplier_suggestions">
    @foreach ($suppliers as $supplier)
        @if (isset($supplier->company) && !empty($supplier->company)) 
            <li class='nav-link'> 
                <a href="#" class="buildURL" bid="supplier" aval="{{ $supplier->uuid }}">
                    <span>{{ $supplier->company->legal_name }}</span>
                </a>
            </li>
        @endif
    @endforeach
    <div class="br_more_cate">
        <a href="{{ url('/products/detail/supplier') }}@if(isset($_SERVER['QUERY_STRING']))?{{$_SERVER['QUERY_STRING']}}@endif" class="btn btn-sm btn-theme product_details_btn" style="font-size: 13px;margin-left:16px;font-weight: bolder;">See more</a>
    </div>
</ul>--}}