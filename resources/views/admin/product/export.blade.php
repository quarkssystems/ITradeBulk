{{-- /**
 * Created by PhpStorm.
 * User: mayank
 * Date: 22/11/18
 * Time: 10:24 AM
 */ --}}
<table class="table ">
    <thead class="thead-light">
        <tr>














            {{-- {{ dd('hi', $data[1], $_REQUEST) }} --}}
            @if ($data[1]->admin_fields == '1')
                <th>{{ __('Audited') }} </th>
                <th>{{ __('Published') }} </th>
            @endif
            @if ($data[1]->product_codes == '1')
                <th>{{ __('Has_UPC') }} </th>
                <th>{{ __('Barcode') }} </th>
                <th>{{ __('Product_Code') }} </th>
                <th>{{ __('Store_Item_Code') }} </th>
            @endif
            @if ($data[1]->product_links == '1')
                <th>{{ __('Parent_ID') }} </th>
                <th>{{ __('Variant_ID') }} </th>
                <th>{{ __('Unit_Barcode_Link') }} </th>
            @endif
            @if ($data[1]->product_description == '1')
                <th>{{ __('Description') }} </th>
                <th>{{ __('Brand') }} </th>
                <th>{{ __('Manufacturer') }} </th>
            @endif
            @if ($data[1]->data_hierarchy == '1')
                <th>{{ __('Category_Group') }} </th>
                <th>{{ __('Department') }} </th>
                <th>{{ __('Category') }} </th>
                <th>{{ __('SubCategory') }} </th>
                <th>{{ __('Segment') }} </th>
                <th>{{ __('SubSegment') }} </th>
            @endif
            @if ($data[1]->fact == '1')
                <th>{{ __('Vat') }} </th>
                <th>{{ __('Cost') }} </th>
                <th>{{ __('Markup') }} </th>
                <th>{{ __('Autoprice') }} </th>
                <th>{{ __('Price') }} </th>
                <th>{{ __('Quantity') }} </th>
                <th>{{ __('Min_Order_Quantity') }} </th>
                <th>{{ __('Stock_Expiry_Date') }} </th>
            @endif
            @if ($data[1]->pallet_configuration == '1')
                <th>{{ __('Packing') }} </th>
                <th>{{ __('Units_Per_Packing') }} </th>
                <th>{{ __('Size') }} </th>
                <th>{{ __('Unit_Of_Measure') }} </th>
                <th>{{ __('Size_Description') }} </th>
                <th>{{ __('Height') }} </th>
                <th>{{ __('Width') }} </th>
                <th>{{ __('Depth') }} </th>
                <th>{{ __('Weight') }} </th>
            @endif
            @if ($data[1]->variants == '1')
                <th>{{ __('Colour') }} </th>
                <th>{{ __('Colour_Variants') }} </th>
                <th>{{ __('Size_Variants') }} </th>
            @endif

            @if ($data[1]->attributes == '1')
                <th>{{ __('Spec_Sheet_Url') }} </th>
                <th>{{ __('Product_Specification') }} </th>
                <th>{{ __('Warranty') }} </th>
                <th>{{ __('Attributes') }} </th>
            @endif
            @if ($data[1]->image_management == '1')
                <th>{{ __('Image_File_Name') }} </th>
                <th>{{ __('Alternate_Image_1') }} </th>
                <th>{{ __('Alternate_Image_2') }} </th>
            @endif
            @if ($data[1]->promotions == '1')
                <th>{{ __('Promotion_Type') }} </th>
                <th>{{ __('Promotion_ID') }} </th>
                <th>{{ __('Period_From') }} </th>
                <th>{{ __('Period_To') }} </th>
                <th>{{ __('Promotion_Price') }} </th>
            @endif
            @if ($data[1]->invoice_splitting == '1')
                <th>{{ __('Courier_safe') }} </th>
                <th>{{ __('Out_Of_Stock_Lead_Time') }} </th>
                <th>{{ __('Is_Permanent_Lead_Product') }} </th>
                <th>{{ __('Product_Delivery_Type') }} </th>
            @endif



            @if ($data[1]->barcode == '1' && $data[1]->product_codes == '0')
                <th>{{ __('Barcode') }} </th>
            @endif
            @if ($data[1]->description == '1' && $data[1]->product_description == '0')
                <th>{{ __('Description') }} </th>
            @endif
            @if ($data[1]->front_image == '1' && $data[1]->image_management == '0')
                <th>{{ __('Image_File_Name') }} </th>
            @endif


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
        @foreach ($data[0] as $datum)
            @php
                $defaultValue = '';
            @endphp
            <tr>
                @if ($data[1]->admin_fields == '1')
                    <td>{{ $datum->audited ? $datum->audited : $defaultValue }}</td>
                    <td>{{ $datum->published ? $datum->published : $defaultValue }}</td>
                @endif
                @if ($data[1]->product_codes == '1')
                    <td>{{ $datum->has_upc ? $datum->has_upc : $defaultValue }}</td>
                    <td>{{ $datum->barcode ? $datum->barcode : $defaultValue }}</td>
                    <td>{{ $datum->product_code ? $datum->product_code : $defaultValue }}</td>
                    <td>{{ $datum->store_item_code ? $datum->store_item_code : $defaultValue }}</td>
                @endif
                @if ($data[1]->product_links == '1')
                    <td>{{ $datum->parent_id ? $datum->parent_id : $defaultValue }}</td>
                    <td>{{ $datum->variant_id ? $datum->variant_id : $defaultValue }}</td>
                    <td>{{ $datum->unit_barcode_link ? $datum->unit_barcode_link : $defaultValue }}</td>
                @endif
                @if ($data[1]->product_description == '1')
                    <td>{{ $datum->description ? $datum->description : $defaultValue }}</td>
                    <td>{{ $datum->brand ? $datum->brand->name : $defaultValue }}</td>
                    <td>{{ $datum->manufacturer ? $datum->manufacturer : $defaultValue }}</td>
                @endif
                @if ($data[1]->data_hierarchy == '1')
                    <td>{{ $datum->category_group ? $datum->category_group : $defaultValue }}</td>
                    <td>{{ $datum->department ? $datum->department : $defaultValue }}</td>
                    <td>{{ $datum->category ? $datum->category : $defaultValue }}</td>
                    <td>{{ $datum->subcategory ? $datum->subcategory : $defaultValue }}</td>
                    <td>{{ $datum->segment ? $datum->segment : $defaultValue }}</td>
                    <td>{{ $datum->subsegment ? $datum->subsegment : $defaultValue }}</td>
                @endif
                @if ($data[1]->fact == '1')
                    <td>{{ $datum->vat ? $datum->vat : $defaultValue }}</td>
                    <td>{{ $datum->cost ? $datum->cost : $defaultValue }}</td>
                    <td>{{ $datum->markup ? $datum->markup : $defaultValue }}</td>
                    <td>{{ $datum->autoprice ? $datum->autoprice : $defaultValue }}</td>
                    <td>{{ $datum->price ? $datum->price : $defaultValue }}</td>
                    <td>{{ $datum->quantity ? $datum->quantity : $defaultValue }}</td>
                    <td>{{ $datum->min_order_quantity ? $datum->min_order_quantity : $defaultValue }}</td>
                    <td>{{ $datum->stock_expiry_date ? $datum->stock_expiry_date : $defaultValue }}</td>
                @endif
                @if ($data[1]->pallet_configuration == '1')
                    <td>{{ $datum->packing ? $datum->packing : $defaultValue }}</td>
                    <td>{{ $datum->units_per_packing ? $datum->units_per_packing : $defaultValue }}</td>
                    <td>{{ $datum->size ? $datum->size : $defaultValue }}</td>
                    <td>{{ $datum->unit_of_measure ? $datum->unit_of_measure : $defaultValue }}</td>
                    <td>{{ $datum->size_description ? $datum->size_description : $defaultValue }}</td>
                    <td>{{ $datum->height ? $datum->height : $defaultValue }}</td>
                    <td>{{ $datum->width ? $datum->width : $defaultValue }}</td>
                    <td>{{ $datum->depth ? $datum->depth : $defaultValue }}</td>
                    <td>{{ $datum->weight ? $datum->weight : $defaultValue }}</td>
                @endif
                @if ($data[1]->variants == '1')
                    <td>{{ $datum->colour ? $datum->colour : $defaultValue }}</td>
                    <td>{{ $datum->colour_variants ? $datum->colour_variants : $defaultValue }}</td>
                    <td>{{ $datum->size_variants ? $datum->size_variants : $defaultValue }}</td>
                @endif
                @if ($data[1]->attributes == '1')
                    <td>{{ $datum->spec_sheet_url ? $datum->spec_sheet_url : $defaultValue }}</td>
                    <td>{{ $datum->product_specification ? $datum->product_specification : $defaultValue }}</td>
                    <td>{{ $datum->warranty ? $datum->warranty : $defaultValue }}</td>
                    <td>{{ $datum->attributes ? $datum->attributes : $defaultValue }}</td>
                @endif
                @if ($data[1]->image_management == '1')
                    <td>{{ $datum->base_image ? url($datum->base_image) : $defaultValue }}</td>
                    <td>{{ $datum->alternate_image_1 ? $datum->alternate_image_1 : $defaultValue }}</td>
                    <td>{{ $datum->alternate_image_2 ? $datum->alternate_image_2 : $defaultValue }}</td>
                @endif
                @if ($data[1]->promotions == '1')
                    <td>{{ $datum->promotion_type ? $datum->promotion_type : $defaultValue }}</td>
                    <td>{{ $datum->promotion_id ? $datum->promotion_id : $defaultValue }}</td>
                    <td>{{ $datum->period_from ? $datum->period_from : $defaultValue }}</td>
                    <td>{{ $datum->period_to ? $datum->period_to : $defaultValue }}</td>
                    <td>{{ $datum->promotion_price ? $datum->promotion_price : $defaultValue }}</td>
                @endif
                @if ($data[1]->invoice_splitting == '1')
                    <td>{{ $datum->courier_safe ? $datum->courier_safe : $defaultValue }}</td>
                    <td>{{ $datum->out_of_stock_lead_time ? $datum->out_of_stock_lead_time : $defaultValue }}</td>
                    <td>{{ $datum->is_permanent_lead_product ? $datum->is_permanent_lead_product : $defaultValue }}
                    </td>
                    <td>{{ $datum->product_delivery_type ? $datum->product_delivery_type : $defaultValue }}</td>
                @endif

                @if ($data[1]->barcode == '1' && $data[1]->product_codes == '0')
                    <td>{{ $datum->barcode ? $datum->barcode : $defaultValue }}</td>
                @endif
                @if ($data[1]->description == '1' && $data[1]->product_description == '0')
                    <td>{{ $datum->description ? $datum->description : $defaultValue }}</td>
                @endif
                @if ($data[1]->front_image == '1' && $data[1]->image_management == '0')
                    <td>{{ $datum->base_image ? url($datum->base_image) : $defaultValue }}</td>
                @endif


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
        @if ($data[0]->count() == 0)
            <tr>
                <td colspan="35">
                    <div class="alert alert-primary">{{ __('No data found') }}</div>
                </td>
            </tr>
        @endif
    </tbody>
</table>
