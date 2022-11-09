{{-- /**
 * Created by PhpStorm.
 * User: mayank
 * Date: 22/11/18
 * Time: 10:24 AM
 */ --}}
<table class="table ">
    <thead class="thead-light">
        <tr>

            <th>{{ __('Audited') }} </th>
            <th>{{ __('Published') }} </th>
            <th>{{ __('Has_UPC') }} </th>
            <th>{{ __('Barcode') }} </th>
            <th>{{ __('Product_Code') }} </th>
            <th>{{ __('Store_Item_Code') }} </th>
            <th>{{ __('Parent_ID') }} </th>
            <th>{{ __('Variant_ID') }} </th>
            <th>{{ __('Unit_Barcode_Link') }} </th>
            <th>{{ __('Description') }} </th>
            <th>{{ __('Brand') }} </th>
            <th>{{ __('Manufacturer') }} </th>
            <th>{{ __('Category_Group') }} </th>
            <th>{{ __('Department') }} </th>
            <th>{{ __('Category') }} </th>
            <th>{{ __('SubCategory') }} </th>
            <th>{{ __('Segment') }} </th>
            <th>{{ __('SubSegment') }} </th>
            <th>{{ __('Vat') }} </th>
            <th>{{ __('Cost') }} </th>
            <th>{{ __('Markup') }} </th>
            <th>{{ __('Autoprice') }} </th>
            <th>{{ __('Price') }} </th>
            <th>{{ __('Quantity') }} </th>
            <th>{{ __('Min_Order_Quantity') }} </th>
            <th>{{ __('Stock_Expiry_Date') }} </th>
            <th>{{ __('Packing') }} </th>
            <th>{{ __('Units_Per_Packing') }} </th>
            <th>{{ __('Size') }} </th>
            <th>{{ __('Unit_Of_Measure') }} </th>
            <th>{{ __('Size_Description') }} </th>
            <th>{{ __('Height') }} </th>
            <th>{{ __('Width') }} </th>
            <th>{{ __('Depth') }} </th>
            <th>{{ __('Weight') }} </th>
            <th>{{ __('Colour') }} </th>
            <th>{{ __('Colour_Variants') }} </th>
            <th>{{ __('Size_Variants') }} </th>
            <th>{{ __('Spec_Sheet_Url') }} </th>
            <th>{{ __('Product_Specification') }} </th>
            <th>{{ __('Warranty') }} </th>
            <th>{{ __('Attributes') }} </th>
            <th>{{ __('Image_File_Name') }} </th>
            <th>{{ __('Alternate_Image_1') }} </th>
            <th>{{ __('Alternate_Image_2') }} </th>
            <th>{{ __('Promotion_Type') }} </th>
            <th>{{ __('Promotion_ID') }} </th>
            <th>{{ __('Period_From') }} </th>
            <th>{{ __('Period_To') }} </th>
            <th>{{ __('Promotion_Price') }} </th>
            <th>{{ __('Courier_safe') }} </th>
            <th>{{ __('Out_Of_Stock_Lead_Time') }} </th>
            <th>{{ __('Is_Permanent_Lead_Product') }} </th>
            <th>{{ __('Product_Delivery_Type') }} </th>



            {{-- <th>{{ __('Store_ID') }}</th>
            <th>{{ __('Has_UPC') }}</th>
            <th>{{ __('Barcode') }}</th>
            <th>{{ __('Product_Code') }}</th>
            <th>{{ __('Store_Item_Code') }}</th>
            <th>{{ __('Parent_ID') }}</th>
            <th>{{ __('Variant_ID') }}</th>
            <th>{{ __('Unit_Barcode_Link') }}</th>
            <th>{{ __('Packing') }}</th>
            <th>{{ __('Units_Per_Packing') }}</th>
            <th>{{ __('Size') }}</th>
            <th>{{ __('Unit_Of_Measure') }}</th>
            <th>{{ __('Size_Description') }}</th>
            <th>{{ __('Height') }}</th>
            <th>{{ __('Width') }}</th>
            <th>{{ __('Depth') }}</th>
            <th>{{ __('Weight') }}</th>
            <th>{{ __('Description') }}</th>
            <th>{{ __('Brand') }}</th>
            <th>{{ __('Manufacturer') }}</th>
            <th>{{ __('Colour') }}</th>
            <th>{{ __('Colour_Variants') }}</th>
            <th>{{ __('Size_Variants') }}</th>
            <th>{{ __('Department') }}</th>
            <th>{{ __('SubDepartment') }}</th>
            <th>{{ __('Category') }}</th>
            <th>{{ __('SubCategory') }}</th>
            <th>{{ __('Segment') }}</th>
            <th>{{ __('SubSegment') }}</th>
            <th>{{ __('Category_Group') }}</th>
            <th>{{ __('Spec_Sheet_Url') }}</th>
            <th>{{ __('Product_Specification') }}</th>
            <th>{{ __('Warranty') }}</th>
            <th>{{ __('Attributes') }}</th>
            <th>{{ __('Image_File_Name') }}</th> --}}
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $datum)
            @php
                $defaultValue = '';
            @endphp
            <tr>
                <td>{{ $datum->audited ? $datum->audited : $defaultValue }}</td>
                <td>{{ $datum->published ? $datum->published : $defaultValue }}</td>
                <td>{{ $datum->has_upc ? $datum->has_upc : $defaultValue }}</td>
                <td>{{ $datum->barcode ? $datum->barcode : $defaultValue }}</td>
                <td>{{ $datum->product_code ? $datum->product_code : $defaultValue }}</td>
                <td>{{ $datum->store_item_code ? $datum->store_item_code : $defaultValue }}</td>
                <td>{{ $datum->parent_id ? $datum->parent_id : $defaultValue }}</td>
                <td>{{ $datum->variant_id ? $datum->variant_id : $defaultValue }}</td>
                <td>{{ $datum->unit_barcode_link ? $datum->unit_barcode_link : $defaultValue }}</td>
                <td>{{ $datum->description ? $datum->description : $defaultValue }}</td>
                <td>{{ $datum->brand ? $datum->brand->name : $defaultValue }}</td>
                <td>{{ $datum->manufacturer ? $datum->manufacturer : $defaultValue }}</td>
                <td>{{ $datum->category_group ? $datum->category_group : $defaultValue }}</td>
                <td>{{ $datum->department ? $datum->department : $defaultValue }}</td>
                <td>{{ $datum->category ? $datum->category : $defaultValue }}</td>
                <td>{{ $datum->subcategory ? $datum->subcategory : $defaultValue }}</td>
                <td>{{ $datum->segment ? $datum->segment : $defaultValue }}</td>
                <td>{{ $datum->subsegment ? $datum->subsegment : $defaultValue }}</td>
                <td>{{ $datum->vat ? $datum->vat : $defaultValue }}</td>
                <td>{{ $datum->cost ? $datum->cost : $defaultValue }}</td>
                <td>{{ $datum->markup ? $datum->markup : $defaultValue }}</td>
                <td>{{ $datum->autoprice ? $datum->autoprice : $defaultValue }}</td>
                <td>{{ $datum->price ? $datum->price : $defaultValue }}</td>
                <td>{{ $datum->quantity ? $datum->quantity : $defaultValue }}</td>
                <td>{{ $datum->min_order_quantity ? $datum->min_order_quantity : $defaultValue }}</td>
                <td>{{ $datum->stock_expiry_date ? $datum->stock_expiry_date : $defaultValue }}</td>
                <td>{{ $datum->packing ? $datum->packing : $defaultValue }}</td>
                <td>{{ $datum->units_per_packing ? $datum->units_per_packing : $defaultValue }}</td>
                <td>{{ $datum->size ? $datum->size : $defaultValue }}</td>
                <td>{{ $datum->unit_of_measure ? $datum->unit_of_measure : $defaultValue }}</td>
                <td>{{ $datum->size_description ? $datum->size_description : $defaultValue }}</td>
                <td>{{ $datum->height ? $datum->height : $defaultValue }}</td>
                <td>{{ $datum->width ? $datum->width : $defaultValue }}</td>
                <td>{{ $datum->depth ? $datum->depth : $defaultValue }}</td>
                <td>{{ $datum->weight ? $datum->weight : $defaultValue }}</td>
                <td>{{ $datum->colour ? $datum->colour : $defaultValue }}</td>
                <td>{{ $datum->colour_variants ? $datum->colour_variants : $defaultValue }}</td>
                <td>{{ $datum->size_variants ? $datum->size_variants : $defaultValue }}</td>
                <td>{{ $datum->spec_sheet_url ? $datum->spec_sheet_url : $defaultValue }}</td>
                <td>{{ $datum->product_specification ? $datum->product_specification : $defaultValue }}</td>
                <td>{{ $datum->warranty ? $datum->warranty : $defaultValue }}</td>
                <td>{{ $datum->attributes ? $datum->attributes : $defaultValue }}</td>
                <td>{{ $datum->base_image ? url($datum->base_image) : $defaultValue }}</td>
                <td>{{ $datum->alternate_image_1 ? $datum->alternate_image_1 : $defaultValue }}</td>
                <td>{{ $datum->alternate_image_2 ? $datum->alternate_image_2 : $defaultValue }}</td>
                <td>{{ $datum->promotion_type ? $datum->promotion_type : $defaultValue }}</td>
                <td>{{ $datum->promotion_id ? $datum->promotion_id : $defaultValue }}</td>
                <td>{{ $datum->period_from ? $datum->period_from : $defaultValue }}</td>
                <td>{{ $datum->period_to ? $datum->period_to : $defaultValue }}</td>
                <td>{{ $datum->promotion_price ? $datum->promotion_price : $defaultValue }}</td>
                <td>{{ $datum->courier_safe ? $datum->courier_safe : $defaultValue }}</td>
                <td>{{ $datum->out_of_stock_lead_time ? $datum->out_of_stock_lead_time : $defaultValue }}</td>
                <td>{{ $datum->is_permanent_lead_product ? $datum->is_permanent_lead_product : $defaultValue }}</td>
                <td>{{ $datum->product_delivery_type ? $datum->product_delivery_type : $defaultValue }}</td>

                {{-- <td>{{ $datum->store_id ? $datum->store_id : $defaultValue }}</td>
                <td>{{ $datum->has_upc ? $datum->has_upc : $defaultValue }}</td>
                <td>{{ $datum->barcode ? $datum->barcode : $defaultValue }}</td>
                <td>{{ $datum->product_code ? $datum->product_code : $defaultValue }}</td>
                <td>{{ $datum->store_item_code ? $datum->store_item_code : $defaultValue }}</td>
                <td>{{ $datum->parent_id ? $datum->parent_id : $defaultValue }}</td>
                <td>{{ $datum->variant_id ? $datum->variant_id : $defaultValue }}</td>
                <td>{{ $datum->unit_barcode_link ? $datum->unit_barcode_link : $defaultValue }}</td>
                <td>{{ $datum->stock_type ? $datum->stock_type : $defaultValue }}</td>
                <td>{{ $datum->stock_of ? $datum->stock_of : $defaultValue }}</td>
                <td>{{ $datum->size ? $datum->size : $defaultValue }}</td>
                <td>{{ $datum->unit_name ? $datum->unit_name : $defaultValue }}</td>
                <td>{{ $datum->size_description ? $datum->size_description : $defaultValue }}</td>
                <td>{{ $datum->height ? $datum->height : $defaultValue }}</td>
                <td>{{ $datum->width ? $datum->width : $defaultValue }}</td>
                <td>{{ $datum->depth ? $datum->depth : $defaultValue }}</td>
                <td>{{ $datum->stoc_wt ? $datum->stoc_wt : $defaultValue }}</td>
                <td>{{ $datum->name ? $datum->name : $defaultValue }}</td>
                <td>{{ $datum->product_brand ? $datum->product_brand : $defaultValue }}</td>
                <td>{{ $datum->brand_id ? $datum->brand_name : $defaultValue }}</td>
                <td>{{ $datum->colour ? $datum->colour : $defaultValue }}</td>
                <td>{{ $datum->colour_variants ? $datum->colour_variants : $defaultValue }}</td>
                <td>{{ $datum->size_variants ? $datum->size_variants : $defaultValue }}</td>
                <td>{{ $datum->department ? $datum->department : $defaultValue }}</td>
                <td>{{ $datum->subdepartment ? $datum->subdepartment : $defaultValue }}</td>
                <td>{{ $datum->category ? $datum->category : $defaultValue }}</td>
                <td>{{ $datum->subcategory ? $datum->subcategory : $defaultValue }}</td>
                <td>{{ $datum->segment ? $datum->segment : $defaultValue }}</td>
                <td>{{ $datum->subsegment ? $datum->subsegment : $defaultValue }}</td>
                <td>{{ $datum->category ? $datum->category : $defaultValue }}</td>
                <td>{{ $datum->spec_sheet_url ? $datum->spec_sheet_url : $defaultValue }}</td>
                <td>{{ $datum->description ? $datum->description : $defaultValue }}</td>
                <td>{{ $datum->warranty ? $datum->warranty : $defaultValue }}</td>
                <td>{{ $datum->search_keyword ? $datum->search_keyword : $defaultValue }}</td>
                <td>{{ $datum->base_image ? url($datum->base_image) : $defaultValue }}</td> --}}
            </tr>
        @endforeach
        @if ($data->count() == 0)
            <tr>
                <td colspan="35">
                    <div class="alert alert-primary">{{ __('No data found') }}</div>
                </td>
            </tr>
        @endif
    </tbody>
</table>
