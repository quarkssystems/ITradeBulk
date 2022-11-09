<div class="row">
    <div class="col-xs-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <div class="btn-group mb-3" role="group" aria-label="Basic example">
            <a class="btn {{request()->route()->named('admin.manage-vendor.*') ? 'btn-primary' : 'btn-secondary'}} " href="{{route('admin.manage-vendor.edit', $user->uuid)}}">{{__('Basic Detail')}}</a>

            <a class="btn {{request()->route()->named('admin.vendor-company.*') ? 'btn-primary' : 'btn-secondary'}} " href="{{
            $user->company()->exists() ? route('admin.vendor-company.edit', ['user_uuid' => $user->uuid, 'vendor_company' => $user->company()->first()->uuid]) : route('admin.vendor-company.create', $user->uuid)
            }}">{{__('Company')}}</a>

            <a class="btn {{request()->route()->named('admin.vendor-document.*') ? 'btn-primary' : 'btn-secondary'}}" href="{{
            route('admin.vendor-document.create', $user->uuid)
            }}">{{__('Documents')}}</a>

            <a class="btn {{request()->route()->named('admin.vendor-bank-details.*') ? 'btn-primary' : 'btn-secondary'}}" href="{{
            $user->bankDetails()->exists() ? route('admin.vendor-bank-details.edit', ['user_uuid' => $user->uuid, 'vendor_bank_detail' => $user->bankDetails()->first()->uuid]) : route('admin.vendor-bank-details.create', $user->uuid)
            }}">{{__('Bank details')}}</a>

            <a class="btn {{request()->route()->named('admin.vendor-tax-details.*') ? 'btn-primary' : 'btn-secondary'}}" href="{{
            $user->taxDetails()->exists() ? route('admin.vendor-tax-details.edit', [ $user->uuid, $user->taxDetails()->first()->uuid]) : route('admin.vendor-tax-details.create', $user->uuid)
            }}">{{__('Tax details')}}</a>

            <a class="btn {{request()->route()->named('admin.vendor-wallet.*') ? 'btn-primary' : 'btn-secondary'}}" href="{{
            route('admin.vendor-wallet.create', $user->uuid)
            }}">{{__('Wallet Transactions')}}</a>

        </div>
    </div>
</div>
