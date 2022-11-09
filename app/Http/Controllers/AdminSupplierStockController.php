<?php

namespace App\Http\Controllers;

use App\Exports\DataGridExport;
use App\Http\Controllers\Helpers\DataGrid;
use App\Models\SupplierItemInventory;
use App\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Excel;

class AdminSupplierStockController extends Controller
{
    use DataGrid;

    public $userRole = 'SUPPLIER';

    public $route = 'admin.supplier-stock';

    public $navTab = 'admin.users.supplier.navTab';

    /**
     * @param Request $request
     * @param User $userModel
     * @param Excel $excel
     * @return View
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function index($user_uuid, Request $request, SupplierItemInventory $inventory, Excel $excel, User $userModel)
    {
        $filters = [];

        $filters[] = ['title' => 'No'];

        $filters[] = [
            'title' => 'Name',
            'column' => 'product_name',
            'operator' => 'SCOPE',
            'sorting' => true,
            'search' => [
                'type' => 'scope',
                'scope' => 'productNameFilter',
                'placeholder' => 'Search name'
            ]
        ];

        $filters[] = [
            'title' => 'Brand',
            'column' => 'brand',
            'operator' => 'SCOPE',
            'sorting' => true,
            'search' => [
                'type' => 'scope',
                'scope' => 'productBrandFilter',
                'placeholder' => 'Search brand'
            ]
        ];




        $filters[] = [
            'title' => 'Quantity/Price'
        ];

        //        $filters[] = [
        //            'title' => 'Action'
        //        ];

        $tableName = $inventory->getTable();
        $url = route($this->route . ".index", $user_uuid);
        $this->setGridModel($inventory);
        $this->setScopesWithValue(['ofSupplier' => $user_uuid]);
        $this->setScopes(['onlyActive']);
        $this->setGridRequest($request);
        $this->setFilters($filters);

        $this->setSorting(['sorting_field' => $tableName . '_sorting_field', 'sort' => $tableName . '_sort', 'default_field' => 'created_at', 'default_sort' => 'DESC']);

        $this->setGridUrl($url);

        $this->setGridVariables();


        if ($request->has('export_data')) {
            $this->setPaginationEnable(false);
            $data = $this->getGridData();
        } else {
            $data = $this->getGridData();
            $dataGridTitle = $this->gridTitles();
            $dataGridSearch = $this->gridSearch();
            $dataGridPagination = $this->gridPagination($data);
        }

        $route = $this->route;
        $role = $this->userRole;
        $navTab = $this->navTab;
        $user = $userModel->where('uuid', $user_uuid)->first();
        $pageTitle = "MANAGE $role";

        if ($request->has('export_data')) {
            $fileName = 'SUPPLIER_STOCK_DATA';
            return $excel->download(new DataGridExport('admin.supplierStock.export', $data), "$fileName.xlsx");
        }

        if ($request->ajax()) {
            return view('admin.supplierStock.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route', 'role', 'user'));
        } else {
            return view('admin.supplierStock.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route', 'role', 'navTab', 'user'));
        }
    }
}
