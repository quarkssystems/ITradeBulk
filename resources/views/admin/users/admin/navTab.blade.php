<div class="row">
    <div class="col-xs-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <div class="btn-group mb-3" role="group" aria-label="Basic example">
            <a class="btn {{request()->route()->named('admin.manage-vendor.*') ? 'btn-primary' : 'btn-secondary'}} " href="{{route('admin.manage-vendor.edit', $user->uuid)}}">{{__('Basic Detail')}}</a>

            <a class="btn btn-secondary" href="{{
            $user->company()->exists() ? route('admin.vendor-company.edit', $user->uuid, $user->company()->first()->uuid) : route('admin.vendor-company.create', $user->uuid)
            }}">{{__('Company')}}</a>

            <a class="btn btn-secondary" href="{{
            route('admin.vendor-document.create', $user->uuid)
            }}">{{__('Documents')}}</a>

            <a class="btn btn-secondary" href="{{
            $user->bankDetails()->exists() ? route('admin.user-bank-details.edit', $user->uuid, $user->bankDetails()->first()->uuid) : route('admin.user-bank-details.create', $user->uuid)
            }}">{{__('Bank details')}}</a>

            <a class="btn btn-secondary" href="{{
            $user->taxDetails()->exists() ? route('admin.user-tax-details.edit', $user->uuid, $user->taxDetails()->first()->uuid) : route('admin.user-tax-details.create', $user->uuid)
            }}">{{__('Tax details')}}</a>


        </div>
    </div>
</div>
