<div class="row">
    <div class="col-xs-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <div class="btn-group mb-3" role="group" aria-label="Basic example">
            <a class="btn {{request()->route()->named('admin.logistic-company.*') ? 'btn-primary' : 'btn-secondary'}} " href="{{route('admin.logistic-company.edit', $logisticCompany->uuid)}}">{{__('Basic Detail')}}</a>
            <a class="btn {{request()->route()->named('admin.logistic-company-bank-details.*') ? 'btn-primary' : 'btn-secondary'}}" href="{{
            $logisticCompany->bankDetails()->exists() ? route('admin.logistic-company-bank-details.edit', ['logistic_company_uuid' => $logisticCompany->uuid, 'logistic_bank_detail' => $logisticCompany->bankDetails()->first()->uuid]) : route('admin.logistic-company-bank-details.create', $logisticCompany->uuid)
            }}">{{__('Bank details')}}</a>

            <a class="btn {{request()->route()->named('admin.logistic-company-tax-details.*') ? 'btn-primary' : 'btn-secondary'}}" href="{{
            $logisticCompany->taxDetails()->exists() ? route('admin.logistic-company-tax-details.edit', [ $logisticCompany->uuid, $logisticCompany->taxDetails()->first()->uuid]) : route('admin.logistic-company-tax-details.create', $logisticCompany->uuid)
            }}">{{__('Tax details')}}</a>
        </div>
    </div>
</div>