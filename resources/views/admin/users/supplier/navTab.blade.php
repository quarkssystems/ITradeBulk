<div class="row">
    <div class="col-xs-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <div class="btn-group mb-3" role="group" aria-label="Basic example">
            <a class="btn {{ request()->route()->named('admin.manage-supplier.*')? 'btn-primary': 'btn-secondary' }} "
                href="{{ route('admin.manage-supplier.edit', $user->uuid) }}">{{ __('Basic Detail') }}</a>
            <a class="btn {{ request()->route()->named('admin.supplier-company.*')? 'btn-primary': 'btn-secondary' }} "
                href="{{ $user->company()->exists() ? route('admin.supplier-company.edit', ['user_uuid' => $user->uuid, 'supplier_company' => $user->company()->first()->uuid]) : route('admin.supplier-company.create', $user->uuid) }}">{{ __('Company') }}</a>
            <a class="btn {{ request()->route()->named('admin.supplier-document.*')? 'btn-primary': 'btn-secondary' }}"
                href="{{ route('admin.supplier-document.create', $user->uuid) }}">{{ __('Documents') }}</a>
            <a class="btn {{ request()->route()->named('admin.supplier-bank-details.*')? 'btn-primary': 'btn-secondary' }}"
                href="{{ $user->bankDetails()->exists() ? route('admin.supplier-bank-details.edit', ['user_uuid' => $user->uuid, 'supplier_bank_detail' => $user->bankDetails()->first()->uuid]) : route('admin.supplier-bank-details.create', $user->uuid) }}">{{ __('Bank details') }}</a>

            <a class="btn {{ request()->route()->named('admin.supplier-tax-details.*')? 'btn-primary': 'btn-secondary' }}"
                href="{{ $user->taxDetails()->exists() ? route('admin.supplier-tax-details.edit', [$user->uuid, $user->taxDetails()->first()->uuid]) : route('admin.supplier-tax-details.create', $user->uuid) }}">{{ __('Tax details') }}</a>

            <a class="btn {{ request()->route()->named('admin.supplier-wallet.*')? 'btn-primary': 'btn-secondary' }}"
                href="{{ route('admin.supplier-wallet.create', $user->uuid) }}">{{ __('Wallet Transactions') }}</a>

            <a class="btn {{ request()->route()->named('admin.supplier-stock.*')? 'btn-primary': 'btn-secondary' }}"
                href="{{ route('admin.supplier-stock.index', $user->uuid) }}">{{ __('Stock') }}</a>

        </div>
    </div>
    <div class="col-xs-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: -17px;margin-left: 42px;">
        <a class="btn {{ request()->route()->named('admin.supplier-delivery')? 'btn-primary': 'btn-secondary' }}"
            href="{{ route('admin.supplier-delivery', [$user->uuid]) }}">{{ __('Supplier Delivery Option') }}</a>

    </div>

</div>
