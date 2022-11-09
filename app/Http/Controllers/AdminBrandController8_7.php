<?php

namespace App\Http\Controllers;

use App\Exports\DataGridExport;
use App\Http\Controllers\Helpers\DataGrid;
use App\Http\Requests\AdminBrandRequest;
use App\Http\Requests\AdminImportCsvRequest;
use App\Http\Requests\AdminCategoryRequest;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Excel;
use App\Imports\AdminManufacturerImport;


class AdminBrandController8_7 extends Controller
{
    use DataGrid;

    public $dataUrl = '/admin/brands';

    public $route = 'admin.brands';

    /**
     * @param Request $request
     * @param Brand $brandModel
     * @param Excel $excel
     * @return \Illuminate\Contracts\View\Factory|View|Excel|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function index(Request $request, Brand $brandModel, Excel $excel)
    {
        $filters = [];
        $filters[] = ['title' => 'No'];

        $filters[] = [
            'title' => 'Thumbnail',
        ];
        $filters[] = [
            'title' => 'Name',
            'column' => 'name',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search name'
            ]
        ];
        $filters[] = [
            'title' => 'Slug',
            'column' => 'slug',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search slug'
            ]
        ];

        $filters[] = [
            'title' => 'Status',
            'column' => 'status',
            'operator' => '=',
            'sorting' => true,
            'search' => [
                'type' => 'select',
                'placeholder' => 'Show all',
                'data' => $brandModel->getStatusesDropDown()
            ]
        ];

        $filters[] = [
            'title' => 'Action'
        ];

        $tableName = $brandModel->getTable();
        $url = $this->dataUrl;
        $this->setGridModel($brandModel);
        $this->setGridRequest($request);
        $this->setFilters($filters);

        $this->setSorting(['sorting_field' => $tableName.'_sorting_field', 'sort' => $tableName.'_sort', 'default_field' => 'created_at', 'default_sort' => 'DESC']);

        $this->setGridUrl($url);

        $this->setGridVariables();

        if($request->has('export_data'))
        {
            $this->setPaginationEnable(false);
            $data = $this->getGridData();
        }
        else
        {
            $data = $this->getGridData();
            $dataGridTitle = $this->gridTitles();
            $dataGridSearch = $this->gridSearch();
            $dataGridPagination = $this->gridPagination($data);
        }

        if($request->has('export_data'))
        {
            $fileName = 'BRAND_DATA';
            return $excel->download(new DataGridExport('admin.brand.export', $data), "$fileName.xlsx");
        }


        $route = $this->route;

        $pageTitle = "MANAGE BRANDS";

        if ($request->ajax()) {
            return view('admin.brand.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route'));
        } else {
            return view('admin.brand.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route'));
        }
    }

    /**
     * @param Brand $brand
     * @return View
     */
    public function create(Brand $brand) : View
    {
        $statuses = $brand->getStatusesDropDown();
        $pageTitle = "CREATE BRAND";
        $route = $this->route;
        return view('admin.brand.form', compact('brand', 'pageTitle', 'route', 'statuses'));
    }

    /**
     * @param AdminBrandRequest $request
     * @param Brand $brand
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AdminBrandRequest $request, Brand $brand)
    {

        if($request->hasFile('icon') && $request->file('icon')->isValid())
        {
            $documentFile = $brand->uploadMedia($request->file('icon'));
            $document = $documentFile['path'].$documentFile['name'];
            $request->merge(['icon_file' => $document]);
        }

        $brandModel = $brand->create($request->all());
        $route = $this->route;
        $redirectRoute = route("$route.index");
        if($request->has('save_continue'))
        {
            $redirectRoute = route("$route.edit", $brandModel->uuid);
        }
        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|brand|created')]);
    }

    /**
     * @param Brand $brand
     */
    public function show(Brand $brand)
    {
        //
    }

    /**
     * @param Brand $brand
     * @return View
     */
    public function edit(Brand $brand) : View
    {
        $statuses = $brand->getStatusesDropDown();
        $copy = request()->has('copy') ? true : false;
        $pageTitle = $copy ? "COPY BRAND" : "EDIT BRAND";
        $route = $this->route;
        return view('admin.brand.form', compact('brand', 'pageTitle', 'route', 'role', 'statuses', 'copy'));
    }

    /**
     * @param AdminCategoryRequest $request
     * @param Category $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AdminBrandRequest $request, Brand $brand)
    {
        if($request->hasFile('icon') && $request->file('icon')->isValid())
        {
            $documentFile = $brand->uploadMedia($request->file('icon'));
            $document = $documentFile['path'].$documentFile['name'];
            $request->merge(['icon_file' => $document]);
        }
        $brand->update($request->all());
        $route = $this->route;
        $redirectRoute = route("$route.index");
        if($request->has('save_continue'))
        {
            $redirectRoute = route("$route.edit", $brand->uuid);
        }
        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|brand|updated')]);
    }

    /**
     * @param Category $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Brand $brand)
    {
        $route = $this->route;
        if($brand->canDelete())
        {
            try{
                $brand->delete();
                return redirect(route("$route.index"))->with(['status' => 'success', 'message' => trans('success.admin|brand|deleted')]);

            }catch (\Exception $exception)
            {
                return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|brand|deleteNotPossible')]);
            }
        }
        else
        {
            return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|brand|deleteNotPossible')]);
        }
    }
      /**
     * @param Brand INPORT $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getImport()
    {
        $route = "admin.brand";
        $pageTitle = "IMPORT Manufacturer";
        
        return view("$route.import",compact('pageTitle', 'route'));
    }

     /**
     * @param Category submit $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import_parse(AdminImportCsvRequest $request,Excel $excel)
    {
    

        if($request->hasFile('csv_import')) {
            $import = new AdminManufacturerImport();   
            $excel->import($import,$request->file('csv_import'));
            $message = $import->getManufacturerAddCount() ." Manufacturer Add successfully And  ".$import->getManufacturerUpdateCount() ." Manufacturer Update successfully ";

            return redirect(route("admin.manufacturerimport"))->with(['status' => 'success' , 'message' =>$message ]);
        }
        
    }
}