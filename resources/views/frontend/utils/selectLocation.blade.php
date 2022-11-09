<!-- The Modal -->
<div class="modal" id="selectLocationModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">{{__('Select Your Current Location')}}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                {!! Form::open(['route' => "checkout.store-location", 'id'=>'checkoutStoreLocationForm', 'autocomplete' => 'off', 'name' => 'checkoutStoreLocationForm']) !!}
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group required">
{{--                        {!! Form::label('location', 'Select Location', ['class' => 'form-label label-required']) !!}--}}
                        {!! Form::select('location', getLocationsDropdown(), session()->get('checkout_location'), ['class' => 'form-control location-selector', 'placeholder' => 'Select your location']) !!}
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group required">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>