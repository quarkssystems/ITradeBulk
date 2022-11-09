<div class="row">

    <div class="col-xs-12 col-md-12 col-sm-12 col-xs-12 text-center">

        <div class="btn-group mb-3" role="group" aria-label="Basic example">

            <a class="btn {{ request()->route()->named('admin.manage-logistic.*')? 'btn-primary': 'btn-secondary' }} "
                href="{{ route('admin.manage-logistic.edit', $user->uuid) }}">{{ __('Basic Detail') }} </a>

            @if ($user->role != 'COMPANY')
                <a class="btn {{ request()->route()->named('admin.logistic-detail.*')? 'btn-primary': 'btn-secondary' }} "
                    href="{{ route('admin.logistic-detail.index', $user->uuid) }}">{{ __('Vehicle details') }}</a>

                {{-- <a class="btn {{request()->route()->named('admin.logistic-detail.*') ? 'btn-primary' : 'btn-secondary'}} " href="{{

            $user->logisticDetails()->exists() ? route('admin.logistic-detail.edit', ['user_uuid' => $user->uuid, 'logistic_detail' => $user->logisticDetails()->first()->uuid]) : route('admin.logistic-detail.create', $user->uuid)

            }}">{{__('Vehicle details')}}</a> --}}
            @endif





            @if ($user->role == 'COMPANY')
                <a class="btn {{ request()->route()->named('admin.company-document.*')? 'btn-primary': 'btn-secondary' }}"
                    href="{{ route('admin.company-document.create', $user->uuid) }}">{{ __('Documents') }}</a>
            @endif



            @if ($user->role == 'DRIVER')
                <a class="btn {{ request()->route()->named('admin.logistic-document.*')? 'btn-primary': 'btn-secondary' }}"
                    href="{{ route('admin.logistic-document.create', $user->uuid) }}">{{ __('Documents') }}</a>
            @endif





            @if ($user->logistic_company_id == null)
                <a class="btn {{ request()->route()->named('admin.logistic-bank-details.*')? 'btn-primary': 'btn-secondary' }}"
                    href="{{ $user->bankDetails()->exists() ? route('admin.logistic-bank-details.edit', ['user_uuid' => $user->uuid, 'logistic_bank_detail' => $user->bankDetails()->first()->uuid]) : route('admin.logistic-bank-details.create', $user->uuid) }}">{{ __('Bank details') }}</a>



                <a class="btn {{ request()->route()->named('admin.logistic-tax-details.*')? 'btn-primary': 'btn-secondary' }}"
                    href="{{ $user->taxDetails()->exists() ? route('admin.logistic-tax-details.edit', [$user->uuid, $user->taxDetails()->first()->uuid]) : route('admin.logistic-tax-details.create', $user->uuid) }}">{{ __('Tax details') }}</a>



                <a class="btn {{ request()->route()->named('admin.logistic-wallet.*')? 'btn-primary': 'btn-secondary' }}"
                    href="{{ route('admin.logistic-wallet.create', $user->uuid) }}">{{ __('Wallet Transactions') }}</a>
            @endif



        </div>

    </div>

</div>
