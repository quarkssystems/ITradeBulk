<?php



namespace App\Http\Controllers;



use App\Exports\DataGridExport;

use App\Http\Controllers\Helpers\DataGrid;

use App\Http\Requests\AdminBannerRequest;

use App\Http\Requests\AdminCategoryRequest;

use App\Models\Page;

use App\Models\Banner;

use Illuminate\Http\Request;

use Illuminate\View\View;

use Maatwebsite\Excel\Excel;

use Session;

class AdminBannerController extends Controller

{

    use DataGrid;



    public $dataUrl = '/admin/banner';



    public $route = 'admin.banner';



    /**

     * @param Request $request

     * @param Brand $brandModel

     * @param Excel $excel

     * @return \Illuminate\Contracts\View\Factory|View|Excel|\Symfony\Component\HttpFoundation\BinaryFileResponse

     * @throws \PhpOffice\PhpSpreadsheet\Exception

     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception

     */

    public function index(Request $request, Banner $banner, Excel $excel)

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

                'data' => $banner->getStatusesDropDown()

            ]

        ];



        $filters[] = [

            'title' => 'Action'

        ];



        $tableName = $banner->getTable();

        // echo $tableName;die;

        $url = $this->dataUrl;

        $this->setGridModel($banner);

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

            $fileName = 'Banner';

            return $excel->download(new DataGridExport('admin.banner.export', $data), "$fileName.xlsx");

        }





        $route = $this->route;



        $pageTitle = "Banner";

        if(Session::has('BannerPage')){
            Session::forget('BannerPage');
        }
        Session::put('BannerPage', $request->input('page') ?? 1);

        if ($request->ajax()) {

            return view('admin.banner.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route'));

        } else {

            return view('admin.banner.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route'));

        }

    }



    /**

     * @param Brand $brand

     * @return View

     */

    public function create(Banner $banner) : View

    {

        $statuses = $banner->getStatusesDropDown();

        $pageTitle = "CREATE Banner";

        $route = $this->route;

        $page = Page::where('id', '!=', '')->orderBy('name')->pluck('name', 'id');

        return view('admin.banner.form', compact('banner', 'pageTitle', 'route', 'statuses','page'));

    }



    /**

     * @param AdminBannerRequest $request

     * @param Brand $brand

     * @return \Illuminate\Http\RedirectResponse

     */

    public function store(AdminBannerRequest $request, Banner $banner)

    {

        $arr = [];

        if($request->hasFile('image') && $request->file('image')->isValid())

        {

            $documentFile = $banner->uploadMedia($request->file('image'));

            $arr = $request->all();

            unset($arr['image']);

            $arr['image'] = $documentFile['path'].$documentFile['name'];

        }



        if($request->hasFile('video') && $request->file('video')->isValid())

        {

            $documentFile = $banner->uploadMedia($request->file('video'));

            $arr = $request->all();

            unset($arr['video']);

            $arr['video'] = $documentFile['path'].$documentFile['name'];

        }



        // echo '<pre>';

        // print_r($arr);

        // die;

        $banner = $banner->create($arr);

        $route = $this->route;

        $redirectRoute = route("$route.index");

        if($request->has('save_continue'))

        {

            $redirectRoute = route("$route.edit", $banner->uuid);

        }

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|banner|created')]);

    }



    /**

     */

    public function show(Banner $BannerModel)

    {

        //

    }



    /**

     * @return View

     */

    public function edit(Banner $banner) : View

    {

        $statuses = $banner->getStatusesDropDown();

        $copy = request()->has('copy') ? true : false;

        $pageTitle = $copy ? "COPY Banner" : "EDIT Banner";

        $route = $this->route;

        $page = Page::where('id', '!=', '')->orderBy('name')->pluck('name', 'id');

        return view('admin.banner.form', compact('banner', 'pageTitle', 'route', 'statuses', 'copy','page'));

    }



    /**

     * @param AdminCategoryRequest $request

     * @param Category $category

     * @return \Illuminate\Http\RedirectResponse

     */

    public function update(AdminBannerRequest $request, Banner $banner)

    {


        $arr = [];

        $arr['in_slider'] = $request->in_slider;

        if($request->hasFile('image') && $request->file('image')->isValid())

        {

            $documentFile = $banner->uploadMedia($request->file('image'));

            $arr = $request->all();

            unset($arr['image']);

            $arr['image'] = $documentFile['path'].$documentFile['name'];

        }



        if($request->hasFile('video') && $request->file('video')->isValid())

        {

            $documentFile = $banner->uploadMedia($request->file('video'));

            $arr = $request->all();

            unset($arr['video']);

            $arr['video'] = $documentFile['path'].$documentFile['video'];

        }


        $banner->update($arr);

        $route = $this->route;

        $redirectRoute = route("$route.index");

        if($request->has('save_continue'))

        {

            $redirectRoute = route("$route.edit", $banner->uuid);

        }

        elseif(Session::has('BannerPage')){
            // echo 11;die;
            $page = session()->get("BannerPage");
            // $redirectRoute = ;
            return redirect()->route("$route.index", ['page'=>$page])->with(['status' => 'success', 'message' => trans('success.admin|banner|updated')]);
        }

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|banner|updated')]);

    }



    /**

     * @param Category $category

     * @return \Illuminate\Http\RedirectResponse

     */

    public function destroy(Banner $banner)

    {

        $route = $this->route;

        if($banner->canDelete())

        {

            try{

                $banner->delete();

                return redirect(route("$route.index"))->with(['status' => 'success', 'message' => trans('success.admin|banner|deleted')]);



            }catch (\Exception $exception)

            {

                return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|banner|deleteNotPossible')]);

            }

        }

        else

        {

            return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|banner|deleteNotPossible')]);

        }

    }

}

