<div class="mb-3 col-md-7 col-lg-7 col-xs-12 col-sm-12">
    @php($quickActions = ['DELETE' => 'DELETE SELECTED'])
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <small><i class="quick_action_selection_data_count"></i></small>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 text-right">
            <div class="form-inline pull-right">
                <a href="javascript:;" class="select_all_quick_action_item" data-selected="all"><small><i>{{__('Select all')}}</i></small></a>&nbsp;&nbsp;
                {!! Form::select('quick_action', $quickActions, null, ['class' => 'form-control form-control-sm quick_action_event_select', 'placeholder' => 'Select action']) !!}&nbsp;
                {!! Form::button('Submit', ['type' => 'button', 'class' => 'btn btn-primary btn-sm submit-quick-action-event', 'data-url' => route($route.'.quick-action')]) !!}
            </div>

        </div>
    </div>

</div>