{{-- /**
 * Created by PhpStorm.
 * User: mayank
 * Date: 22/11/18
 * Time: 10:24 AM
 */ --}}

@extends('supplier.layouts.main')
@section('page-header')
    <div class="container-fluid">
        <ol class="breadcrumb breadcrumb-style1">
            <li class="breadcrumb-item"><a href="/">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
        </ol>
        <div class="page-header">
            <div class="page-title">
                <h4>{{ $pageTitle }}</h4>
            </div>
        </div>
    </div>
@endsection


@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    <div class="data-grid">

                        <?php
                        $admin_fieldschecked = '';
                        $product_codeschecked = '';
                        $product_linkschecked = '';
                        $product_descriptionchecked = '';
                        $data_hierarchychecked = '';
                        $variantschecked = '';
                        $attributeschecked = '';
                        $image_managementchecked = '';
                        $promotionschecked = '';
                        $invoice_splittingchecked = '';
                        $pallet_configurationchecked = '';
                        $factchecked = '';
                        $barcodechecked = '';
                        $descriptionchecked = '';
                        $front_imagechecked = '';
                        
                        if ($quickView != null) {
                            if ($quickView->admin_fields == 1) {
                                $admin_fieldschecked = 'checked';
                            }
                            if ($quickView->product_codes == 1) {
                                $product_codeschecked = 'checked';
                            }
                            if ($quickView->product_links == 1) {
                                $product_linkschecked = 'checked';
                            }
                            if ($quickView->product_description == 1) {
                                $product_descriptionchecked = 'checked';
                            }
                            if ($quickView->data_hierarchy == 1) {
                                $data_hierarchychecked = 'checked';
                            }
                            if ($quickView->variants == 1) {
                                $variantschecked = 'checked';
                            }
                            if ($quickView->attributes == 1) {
                                $attributeschecked = 'checked';
                            }
                            if ($quickView->image_management == 1) {
                                $image_managementchecked = 'checked';
                            }
                            if ($quickView->promotions == 1) {
                                $promotionschecked = 'checked';
                            }
                            if ($quickView->invoice_splitting == 1) {
                                $invoice_splittingchecked = 'checked';
                            }
                            if ($quickView->pallet_configuration == 1) {
                                $pallet_configurationchecked = 'checked';
                            }
                            if ($quickView->fact == 1) {
                                $factchecked = 'checked';
                            }
                            if ($quickView->barcode == 1) {
                                $barcodechecked = 'checked';
                            }
                            if ($quickView->description == 1) {
                                $descriptionchecked = 'checked';
                            }
                            if ($quickView->front_image == 1) {
                                $front_imagechecked = 'checked';
                            }
                        }
                        
                        ?>

                        <table>

                             <tr>
                                <td>Product Codes</td>
                                <td>
                                    <label class="switchNew">
                                        <input type="checkbox" class="onoff" name="product_codes"
                                            {{ $product_codeschecked }}>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                            </tr>

                          {{--  <tr>
                                <td>Product Links</td>
                                <td>
                                    <label class="switchNew">
                                        <input type="checkbox" class="onoff" name="product_links"
                                            {{ $product_linkschecked }}>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>

                            <tr>
                                <td>Product Description</td>
                                <td>
                                    <label class="switchNew">
                                        <input type="checkbox" class="onoff" name="product_description"
                                            {{ $product_descriptionchecked }}>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr> --}}

                            <tr>
                                <td>Data Hierarchy</td>
                                <td>
                                    <label class="switchNew">
                                        <input type="checkbox" class="onoff" name="data_hierarchy"
                                            {{ $data_hierarchychecked }}>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>

                            {{-- <tr>
                                <td>Variants</td>
                                <td>
                                    <label class="switchNew">
                                        <input type="checkbox" class="onoff" name="variants" {{ $variantschecked }}>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>

                            <tr>
                                <td>Attributes</td>
                                <td>
                                    <label class="switchNew">
                                        <input type="checkbox" class="onoff" name="attributes" {{ $attributeschecked }}>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>

                            <tr>
                                <td>Image Management</td>
                                <td>
                                    <label class="switchNew">
                                        <input type="checkbox" class="onoff" name="image_management"
                                            {{ $image_managementchecked }}>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>Promotions</td>
                                <td>
                                    <label class="switchNew">
                                        <input type="checkbox" class="onoff" name="promotions" {{ $promotionschecked }}>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>Invoice Splitting</td>
                                <td>
                                    <label class="switchNew">
                                        <input type="checkbox" class="onoff" name="invoice_splitting"
                                            {{ $invoice_splittingchecked }}>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>Pallet Configuration</td>
                                <td>
                                    <label class="switchNew">
                                        <input type="checkbox" class="onoff" name="pallet_configuration"
                                            {{ $pallet_configurationchecked }}>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr> --}}

                            <tr>
                                <td>Facts</td>
                                <td>
                                    <label class="switchNew">
                                        <input type="checkbox" class="onoff" name="fact" {{ $factchecked }}>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            {{-- <tr>
                                <td>Barcode</td>
                                <td>
                                    <label class="switchNew">
                                        <input type="checkbox" class="onoff" name="barcode" {{ $barcodechecked }}>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>Description</td>
                                <td>
                                    <label class="switchNew">
                                        <input type="checkbox" class="onoff" name="description" {{ $descriptionchecked }}>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>Front (Image)</td>
                                <td>
                                    <label class="switchNew">
                                        <input type="checkbox" class="onoff" name="front_image" {{ $front_imagechecked }}>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
 --}}



                        </table>
                        {{-- @include('admin.productUnit.grid') --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footerScript')
    <script>
        let user_id = '{{ Auth::user()->uuid }}';
        $(document).on('click', '.onoff', function() {
            console.log(this, user_id);
            let name = $(this).attr('name');
            $.ajax({
                type: 'POST',
                data: {
                    _token: TOKEN,
                    user_id: user_id,
                    name: name
                },
                url: '/user/quick-view',
                success: function(data) {


                },
                error: function(xhr, status, error) {

                    alert(xhr.responseText);

                }
            });
        });
    </script>
@endsection
