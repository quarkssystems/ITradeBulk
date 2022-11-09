<?php
/**
 * Created by PhpStorm.
 * User: mayank
 * Date: 22/11/18
 * Time: 10:03 AM
 */

namespace App\Http\Controllers\Helpers;

use App\Models\BankBranch;
use App\Models\BankMaster;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Session;

trait BaseController
{
    public function bankBranchGrid(Request $request, BankBranch $bankBranchModel, BankMaster $bankMasterModel, $url)
    {
        $filters = [];
        $filters[] = ['title' => 'No'];

        $filters[] = [
            'title' => 'Bank',
            'column' => 'bank_master_id',
            'operator' => '=',
            'sorting' => true,
            'search' => [
                'type' => 'select',
                'placeholder' => 'Show all',
                'data' => $bankMasterModel->getBankDropDown()
            ]
        ];

        $filters[] = [
            'title' => 'Branch',
            'column' => 'branch_name',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search name'
            ]
        ];

        $filters[] = [
            'title' => 'Branch code',
            'column' => 'branch_code',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search code'
            ]
        ];

        $filters[] = [
            'title' => 'Swift code',
            'column' => 'swift_code',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search code'
            ]
        ];

        $filters[] = [
            'title' => 'Province',
            'column' => 'state_id',
            'operator' => 'SCOPE',
            'search' => [
                'type' => 'scope',
                'placeholder' => 'Search province',
                'scope' => 'ofState'
            ]
        ];

        $filters[] = [
            'title' => 'City',
            'column' => 'city_id',
            'operator' => 'SCOPE',
            'search' => [
                'type' => 'scope',
                'placeholder' => 'Search city',
                'scope' => 'ofCity'
            ]
        ];

        $filters[] = [
            'title' => 'Postal Code',
            'column' => 'zipcode_id',
            'operator' => 'SCOPE',
            'search' => [
                'type' => 'scope',
                'placeholder' => 'Search postal code',
                'scope' => 'ofZipcode'
            ]
        ];

        $tableName = $bankBranchModel->getTable();
        $this->setGridModel($bankBranchModel);
        $this->setGridRequest($request);
        $this->setFilters($filters);

        $this->setEager(['bank', 'zipcode', 'city', 'state']);

        $this->setSorting(['sorting_field' => $tableName.'_sorting_field', 'sort' => $tableName.'_sort', 'default_field' => 'created_at', 'default_sort' => 'DESC']);


        $this->setGridUrl($url);

        $this->setGridVariables();
        $this->setPagination(10);
        $data = $this->getGridData();

        $dataGridTitle = $this->gridTitles();
        $dataGridSearch = $this->gridSearch();
        $dataGridPagination = $this->gridPagination($data);

        return [
            'dataGridTitle' => $dataGridTitle,
            'dataGridSearch' => $dataGridSearch,
            'dataGridPagination' => $dataGridPagination,
            'data' => $data
        ];
    }

    public function quickAction(Request $request)
    {
        if($request->has('items') && !empty($request->get('items')))
        {
            $model = $this->getQuickActionModel();
            $items = $request->get('items');
            $model->whereIn('uuid', $items)->delete();

            Session::flash('message', trans('quickAction.deleted'));
            Session::flash('status', 'success');
        }
        return "Success";
    }
}