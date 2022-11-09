<?php



namespace App\Http\Controllers;



use App\Exports\DataGridExport;

use App\Http\Controllers\Helpers\BaseController;

use App\Http\Controllers\Helpers\DataGrid;

use App\Http\Requests\AdminCategoryRequest;

use App\Http\Requests\AdminImportCsvRequest;

use App\Models\Category;

use Illuminate\Http\Request;

use Illuminate\View\View;

use Maatwebsite\Excel\Excel;

use App\Imports\AdminCategoryImport;

use Session;


class AdminCategoryController extends Controller

{

    use DataGrid;

    use BaseController;



    public $dataUrl = '/admin/categories';

    public $route = 'admin.categories';



    public function getQuickActionModel()

    {

        return new Category();

    }

    /**

     * @param Request $request

     * @param Category $categoryModel

     * @param Excel $excel

     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|Excel|\Symfony\Component\HttpFoundation\BinaryFileResponse

     * @throws \PhpOffice\PhpSpreadsheet\Exception

     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception

     */

    public function index(Request $request, Category $categoryModel, Excel $excel)

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

                'data' => $categoryModel->getStatusesDropDown()

            ]

        ];



        $filters[] = [

            'title' => 'Action'

        ];



        $tableName = $categoryModel->getTable();

        $url = $this->dataUrl;

        $this->setGridModel($categoryModel);

        $this->setGridRequest($request);

        $this->setFilters($filters);



        $this->setSorting(['sorting_field' => $tableName.'_sorting_field', 'sort' => $tableName.'_sort', 'default_field' => 'name', 'default_sort' => 'ASC']);



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

            $fileName = 'CATEGORY_DATA';

            return $excel->download(new DataGridExport('admin.category.export', $data), "$fileName.xlsx");

        }





        $route = $this->route;



        $pageTitle = "MANAGE CATEGORIES";


        if(Session::has('CategoryPage')){
            Session::forget('CategoryPage');
        }
        Session::put('CategoryPage', $request->input('page') ?? 1);


        if ($request->ajax()) {

            return view('admin.category.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route'));

        } else {

            return view('admin.category.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route'));

        }

    }



    /**

     * @param Category $category

     * @return View

     */

    public function create(Category $category) : View

    {

        $categories = $category->getDropDown();

        $statuses = $category->getStatusesDropDown();

        $pageTitle = "CREATE CATEGORY";

        $route = $this->route;

        $role = auth()->user()->role;



        return view('admin.category.form', compact('category', 'pageTitle', 'route', 'role', 'categories', 'statuses'));

    }



    /**

     * @param AdminCategoryRequest $request

     * @param Category $category

     * @return \Illuminate\Http\RedirectResponse

     */

    public function store(AdminCategoryRequest $request, Category $category)

    {

        if($request->hasFile('banner_image') && $request->file('banner_image')->isValid())

        {

            $documentFile = $category->uploadMedia($request->file('banner_image'),'category');

            $document = $documentFile['path'].$documentFile['name'];

            $request->merge(['banner_image_file' => $document]);

        }

        if($request->hasFile('thumb_image') && $request->file('thumb_image')->isValid())

        {

            $documentFile = $category->uploadMedia($request->file('thumb_image'),'category' );

            $document = $documentFile['path'].$documentFile['name'];

            $request->merge(['thumb_image_file' => $document]);

        }



        $categoryModel = $category->create($request->all());

        $route = $this->route;



        $redirectRoute = route("$route.index");

        if($request->has('save_continue'))

        {

            $redirectRoute = route("$route.edit", $categoryModel->uuid);

        }

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|category|created')]);

    }



    /**

     * @param Category $category

     */

    public function show(Category $category)

    {

        //

    }



    /**

     * @param Category $category

     * @return View

     */

    public function edit(Category $category) : View

    {

        // $categories = $category->getDropDown($category->uuid);

        $categories = $category->getParentCategoriesForCategory();

        $statuses = $category->getStatusesDropDown();

        $copy = request()->has('copy') ? true : false;

        $pageTitle = $copy ? "COPY CATEGORY" : "EDIT CATEGORY";

        $route = $this->route;

        $role = auth()->user()->role;

        return view('admin.category.form', compact('category', 'pageTitle', 'route', 'role', 'categories', 'statuses', 'copy'));

    }



    /**

     * @param AdminCategoryRequest $request

     * @param Category $category

     * @return \Illuminate\Http\RedirectResponse

     */

    public function update(AdminCategoryRequest $request, Category $category)

    {

        if($request->hasFile('banner_image') && $request->file('banner_image')->isValid())

        {

            $documentFile = $category->uploadMedia($request->file('banner_image'),'category');

            $document = $documentFile['path'].$documentFile['name'];

            $request->merge(['banner_image_file' => $document]);

        }

        if($request->hasFile('thumb_image') && $request->file('thumb_image')->isValid())

        {

            $documentFile = $category->uploadMedia($request->file('thumb_image'),'category');

            $document = $documentFile['path'].$documentFile['name'];

            $request->merge(['thumb_image_file' => $document]);

        }



        $category->update($request->all());

        $route = $this->route;



        $redirectRoute = route("$route.index");

        if($request->has('save_continue'))

        {

            $redirectRoute = route("$route.edit", $category->uuid);

        }

        elseif(Session::has('CategoryPage')){
            $page = session()->get("CategoryPage");
            return redirect()->route("$route.index", ['page'=>$page])->with(['status' => 'success', 'message' => trans('success.admin|category|updated')]);
        }

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|category|updated')]);

    }



    /**

     * @param Category $category

     * @return \Illuminate\Http\RedirectResponse

     */

    public function destroy(Category $category)

    {

        $route = $this->route;

        if($category->canDelete())

        {

            try{

                $category->delete();

                return redirect(route("$route.index"))->with(['status' => 'success', 'message' => trans('success.admin|category|deleted')]);



            }catch (\Exception $exception)

            {

                return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|category|deleteNotPossible')]);

            }

        }

        else

        {

            return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|category|deleteNotPossible')]);

        }

    }

     /**

     * @param Product INPORT $product

     * @return \Illuminate\Http\RedirectResponse

     */

    public function getImport()

    {

        $route = "admin.category";
        $pageTitle = "IMPORT Category";

        

        return view("$route.import",compact('pageTitle', 'route'));

    }



     /**

     * @param Category submit $category

     * @return \Illuminate\Http\RedirectResponse

     */

    public function import_parse(AdminImportCsvRequest $request,Excel $excel)

    {

        if($request->hasFile('file_import')) {

            $import = new AdminCategoryImport();   

            $excel->import($import,$request->file('file_import'));

            $message = $import->getCategoryAddCount() ." Category Add successfully And  ".$import->getCategoryUpdateCount() ." Category Update successfully ";



            return redirect(route("admin.categoryimport"))->with(['status' => 'success' , 'message' =>$message ]);

        }

        

    }

}

