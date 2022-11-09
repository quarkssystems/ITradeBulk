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

use Session;



class AdminBrandController extends Controller

{

    use DataGrid;



    public $dataUrl = '/admin/brands';

    public $userRole = 'ADMIN';

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

        $filters[] = [

            'title' => 'switch'

        ];

        $tableName = $brandModel->getTable();

        $url = $this->dataUrl;

        $this->setGridModel($brandModel);

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

            $fileName = 'BRAND_DATA';

            return $excel->download(new DataGridExport('admin.brand.export', $data), "$fileName.xlsx");

        }





        $route = $this->route;



        $pageTitle = "MANAGE Brand";
        // $pageTitle = "MANAGE Manufacturer";

        if(Session::has('BrandPage')){
            Session::forget('BrandPage');
        }
        Session::put('BrandPage', $request->input('page') ?? 1);
        
        // added
        $data = tap($data,function($query){
            return $query->getCollection()->transform(function ($value) {
            $check = '';
            $cval = 1;

            $fileExist = file_exists( public_path() . $value->icon_file) ? 1 : 0;
            $onOff = '';
            if($value->on_off == 1){
                $onOff = 'checked';
                $cval = 0;
            }
            
            if($value->on_off == null){
                if($value->icon_file == null){
                    $check =  '<label class="switchNew">
                    <input type="checkbox" '. $onOff .' class="onoff" data-id="'.$value->uuid.'" data-onoff="'.$value->on_off.'" data-conoff="'.$cval.'" data-fileExist="'.$fileExist.'">
                    <span class="slider round"></span>
                    </label>';
                    // $check = '<input type="checkbox" name="switch" value=""/>';
                } else {
                    $check =  '<label class="switchNew">
                    <input type="checkbox" '. $onOff .' class="onoff" data-id="'.$value->uuid.'" data-onoff="'.$value->on_off.'" data-conoff="'.$cval.'" data-fileExist="'.$fileExist.'">
                    <span class="slider round"></span>
                    </label>';
                    // $check = '<input type="checkbox" name="switch" value=""/>';

                }
            } else {
                $check =  '<label class="switchNew">
                <input type="checkbox" '. $onOff .' class="onoff" data-id="'.$value->uuid.'" data-onoff="'.$value->on_off.'" data-conoff="'.$cval.'" data-fileExist="'.$fileExist.'">
                <span class="slider round"></span>
                </label>';
                // $check = '<input type="checkbox" name="switch" value="'.$value->on_off.'"/>';
            }
            $value->switch = $check;
            // Your code here
            return $value;
        });
    });

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

        $pageTitle = "CREATE Brand";
        // $pageTitle = "CREATE Manufacturer";

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
        // $pageTitle = $copy ? "COPY Manufacturer" : "EDIT Manufacturer";

        $route = $this->route;

        $role = $this->userRole;

        return view('admin.brand.form', compact('brand', 'pageTitle', 'route', 'role', 'statuses', 'copy'));

    }



    /**

     * @param AdminCategoryRequest $request

     * @param Category $category

     * @return \Illuminate\Http\RedirectResponse

     */

    public function update(AdminBrandRequest $request, Brand $brand)

    {
        // echo '<pre>';print_r($request->all());die;
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

        elseif(Session::has('BrandPage')){
            // echo 11;die;
            $page = session()->get("BrandPage");
            // $redirectRoute = ;
            return redirect()->route("$route.index", ['page'=>$page])->with(['status' => 'success', 'message' => trans('success.admin|brand|updated')]);
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
        $pageTitle = "IMPORT BRAND";
        // $pageTitle = "IMPORT Manufacturer";

        

        return view("$route.import",compact('pageTitle', 'route'));

    }



     /**

     * @param Category submit $category

     * @return \Illuminate\Http\RedirectResponse

     */

    public function import_parse(AdminImportCsvRequest $request,Excel $excel)

    {

    



        if($request->hasFile('file_import')) {

            $import = new AdminManufacturerImport();   

            $excel->import($import,$request->file('file_import'));

            $message = $import->getManufacturerAddCount() ." Brand Add successfully And  ".$import->getManufacturerUpdateCount() ." Brand Update successfully ";
            // $message = $import->getManufacturerAddCount() ." Manufacturer Add successfully And  ".$import->getManufacturerUpdateCount() ." Manufacturer Update successfully ";



            return redirect(route("admin.manufacturerimport"))->with(['status' => 'success' , 'message' =>$message ]);

        }

        

    }

    public function storeBrandPhoto(Request $request){

        function rmdir_recursive($dir) {
            foreach(scandir($dir) as $file) {
               if ('.' === $file || '..' === $file) continue;
               if (is_dir("$dir/$file")) rmdir_recursive("$dir/$file");
               else unlink("$dir/$file");
           }
        
           rmdir($dir);
        }
        
        $arr = [];

        $fileRealName = $request->brand_id;

        if(isset($_FILES["icon"]["name"])) {
            $filename = $_FILES["icon"]["name"];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
    
            $source = $_FILES["icon"]["tmp_name"];
            $type = $_FILES["icon"]["type"];
        
            $name = explode(".", $filename);
        
          /* PHP current path */
          $path = '/uploads/media/manufacturer/'.$fileRealName.'/';
        //   $path = public_path().'/uploads/media/manufacturer/'.$fileRealName.'/';
          if (!is_dir(public_path().''. $path)) {
                //Create our directory if it does not exist
                mkdir(public_path().''.$path, 0777, true);
                // echo "Directory created";
            }
            // absolute path to the directory where zipper.php is in
       
          $target = $path . $filename.'.'.$ext; // target zip file
     
            if(move_uploaded_file($source, public_path().'/uploads/media/manufacturer/'.$fileRealName.'/'. $filename .'.'.$ext)) {
              
                Brand::where('uuid',$request->brand_id)->update([
                    'icon_file' => $target,
                    'on_off' => '1',
                    'status' => 'ACTIVE'
                ]);

                return redirect()->back()->with(['message' => 'Your file was uploaded.']);
            } else {
                return redirect()->back()->with(['message' => 'There was a problem with the upload. Please try again.']);
               
            }
        }
        return $arr;
    }

    public function statusBrandPhoto(Request $request,$brand_id){

       $brandData = Brand::where('uuid',$brand_id)->select('on_off')->first();
        if($brandData->on_off == 1){
            Brand::where('uuid',$brand_id)->update([
                'on_off' => '0',
                'status' => 'INACTIVE'
            ]);
        }
        if($brandData->on_off == 0){
            Brand::where('uuid',$brand_id)->update([
                'on_off' => '1',
                'status' => 'ACTIVE'
            ]);
        }
       
        return redirect()->back()->with(['message' => 'Status Changed.']);
    }
    
}