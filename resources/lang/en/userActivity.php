<?php
return [
    /**
     * Model
     * @see \App\User
     *
     * Controller
     * @see \App\Http\Controllers\AdminUserController
     * @see \App\Http\Controllers\AdminManageLogisticsController
     * @see \App\Http\Controllers\AdminManageVendorController
     * @see \App\Http\Controllers\AdminManageSupplierController
     *
     * FormRequest
     * @see \App\Http\Requests\AdminUserRequest
     */
    'user|created' => 'User created',
    'user|updated' => 'User updated',
    'user|deleted' => 'User deleted',

    /**
     * Model
     * @see \App\Models\Permission
     *
     * Controller
     * @see \App\Http\Controllers\AdminPermissionController
     *
     * FormRequest
     * @see \App\Http\Requests\AdminPermissionRequest
     */
    'permission|created' => 'Permission created',
    'permission|updated' => 'Permission updated',
    'permission|deleted' => 'Permission deleted',

    /**
     * Model
     * @see \App\Models\Role
     *
     * Controller
     * @see \App\Http\Controllers\AdminRoleController
     *
     * FormRequest
     * @see \App\Http\Requests\AdminRoleRequest
     */
    'role|created' => 'Role created',
    'role|updated' => 'Role updated',
    'role|deleted' => 'Role deleted',

    /**
     * Model
     * @see \App\Models\LocationCountry
     *
     * Controller
     * @see \App\Http\Controllers\AdminLocationCountryController
     *
     * FormRequest
     * @see \App\Http\Requests\AdminLocationCountryRequest
     */
    'locationCountry|created' => 'Country created',
    'locationCountry|updated' => 'Country updated',
    'locationCountry|deleted' => 'Country deleted',

    /**
     * Model
     * @see \App\Models\LocationState
     *
     * Controller
     * @see \App\Http\Controllers\AdminLocationStateController
     *
     * FormRequest
     * @see \App\Http\Requests\AdminLocationStateRequest
     */
    'locationState|created' => 'State created',
    'locationState|updated' => 'State updated',
    'locationState|deleted' => 'State deleted',

    /**
     * Model
     * @see \App\Models\LocationCity
     *
     * Controller
     * @see \App\Http\Controllers\AdminLocationCityController
     *
     * FormRequest
     * @see \App\Http\Requests\AdminLocationCityRequest
     */
    'locationCity|created' => 'City created',
    'locationCity|updated' => 'City updated',
    'locationCity|deleted' => 'City deleted',

    /**
     * Model
     * @see \App\Models\LocationZipcode
     *
     * Controller
     * @see \App\Http\Controllers\AdminLocationZipcodeController
     *
     * FormRequest
     * @see \App\Http\Requests\AdminLocationZipcodeRequest
     */
    'locationZipcode|created' => 'Zipcode created',
    'locationZipcode|updated' => 'Zipcode updated',
    'locationZipcode|deleted' => 'Zipcode deleted',

    /**
     * Model
     * @see \App\Models\UserCompany
     *
     * Controller
     * @see \App\Http\Controllers\AdminUserCompanyController
     * @see \App\Http\Controllers\AdminVendorCompanyController
     * @see \App\Http\Controllers\AdminSupplierCompanyController
     *
     * FormRequest
     * @see \App\Http\Requests\AdminUserCompanyRequest
     */
    'userCompany|created' => 'Company created',
    'userCompany|updated' => 'Company updated',
    'userCompany|deleted' => 'Company deleted',

    /**
     * Model
     * @see \App\Models\UserDocument
     *
     * Controller
     * @see \App\Http\Controllers\AdminUserDocumentController
     * @see \App\Http\Controllers\AdminVendorDocumentController
     *
     * FormRequest
     * @see \App\Http\Requests\AdminUserDocumentRequest
     */
    'userDocument|created' => 'Document created',
    'userDocument|updated' => 'Document updated',
    'userDocument|deleted' => 'Document deleted',

    /**
     * Model
     * @see \App\Models\LogisticDetails
     *
     * Controller
     * @see \App\Http\Controllers\AdminLogisticDetailController
     *
     * FormRequest
     * @see \App\Http\Requests\AdminLogisticDetailsRequest
     */
    'logisticDetails|created' => 'Driver details created',
    'logisticDetails|updated' => 'Driver details updated',
    'logisticDetails|deleted' => 'Driver details deleted',

    /**
     * Model
     * @see \App\Models\UserTaxDetails
     *
     * Controller
     * @see \App\Http\Controllers\AdminUserTaxDetailsController
     * @see \App\Http\Controllers\AdminVendorTaxDetailsController
     * @see \App\Http\Controllers\AdminLogisticTaxDetailsController
     * @see \App\Http\Controllers\AdminSupplierTaxDetailsController
     *
     * FormRequest
     * @see \App\Http\Requests\AdminUserTaxDetailsRequest
     */
    'userTaxDetails|created' => 'Tax details created',
    'userTaxDetails|updated' => 'Tax details updated',
    'userTaxDetails|deleted' => 'Tax details deleted',

    /**
     * Model
     * @see \App\Models\BankMaster
     *
     * Controller
     * @see \App\Http\Controllers\AdminBankMasterController
     *
     * FormRequest
     * @see \App\Http\Requests\AdminBankMasterRequest
     */
    'bankMaster|created' => 'Bank created',
    'bankMaster|updated' => 'Bank updated',
    'bankMaster|deleted' => 'Bank deleted',

    /**
     * Model
     * @see \App\Models\BankBranch
     *
     * Controller
     * @see \App\Http\Controllers\AdminBankBranchController
     *
     * FormRequest
     * @see \App\Http\Requests\AdminBankBranchRequest
     */
    'bankBranch|created' => 'Bank branch created',
    'bankBranch|updated' => 'Bank branch updated',
    'bankBranch|deleted' => 'Bank branch deleted',

    /**
     * Model
     * @see \App\Models\UserBankDetails
     *
     * Controller
     * @see \App\Http\Controllers\AdminUserBankDetailsController
     *
     * FormRequest
     * @see \App\Http\Requests\AdminUserBankDetailsRequest
     */
    'userBankDetails|created' => 'Bank details created',
    'userBankDetails|updated' => 'Bank details updated',
    'userBankDetails|deleted' => 'Bank details deleted',

    'category|created' => 'Category created',
    'category|updated' => 'Category updated',
    'category|deleted' => 'Category deleted',

    'brand|created' => 'Brand created',
    'brand|updated' => 'Brand updated',
    'brand|deleted' => 'Brand deleted',

    'tax|created' => 'Tax created',
    'tax|updated' => 'Tax updated',
    'tax|deleted' => 'Tax deleted',

    'product|created' => 'Product created',
    'product|updated' => 'Product updated',
    'product|deleted' => 'Product deleted',

    'productUnit|created' => 'Product unit created',
    'productUnit|updated' => 'Product unit updated',
    'productUnit|deleted' => 'Product unit deleted',

    'productCategory|created' => 'Product category created',
    'productCategory|updated' => 'Product category updated',
    'productCategory|deleted' => 'Product category deleted',

    'basketProduct|created' => 'Product added to basket',
    'basketProduct|updated' => 'Product updated in basket',
    'basketProduct|deleted' => 'Product deleted from basket',

    'basket|created' => 'Basket created',
    'basket|updated' => 'Basket updated',
    'basket|deleted' => 'Basket deleted',

    'deliveryVehicleMaster|created' => 'Delivery vehicle master created',
    'deliveryVehicleMaster|updated' => 'Delivery vehicle master updated',
    'deliveryVehicleMaster|deleted' => 'Delivery vehicle master deleted',
];
