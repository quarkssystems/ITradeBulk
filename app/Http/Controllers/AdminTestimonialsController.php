<?php



namespace App\Http\Controllers;



use App\Exports\DataGridExport;

use App\Http\Controllers\Helpers\DataGrid;

use App\Http\Requests\AdminTestimonialsRequest;

use App\User;

use App\Models\Category;

use App\Models\Testimonials;

use Illuminate\Http\Request;

use Illuminate\View\View;

use Maatwebsite\Excel\Excel;

use Session;

class AdminTestimonialsController extends Controller

{

    use DataGrid;



    public $dataUrl = '/admin/testimonials';



    public $route = 'admin.testimonials';



    /**

     * @param Request $request

     * @param Testimonials $testimonials

     * @param Excel $excel

     * @return \Illuminate\Contracts\View\Factory|View|Excel|\Symfony\Component\HttpFoundation\BinaryFileResponse

     * @throws \PhpOffice\PhpSpreadsheet\Exception

     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception

     */

    public function index(Request $request, Testimonials $testimonial, Excel $excel)

    {



        $filters = [];

        $filters[] = ['title' => 'No'];



        

       

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

                'data' => $testimonial->getStatusesDropDown()

            ]

        ];



        $filters[] = [

            'title' => 'Action'

        ];



        $tableName = $testimonial->getTable();

        // echo $tableName;die;

        $url = $this->dataUrl;

        $this->setGridModel($testimonial);

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

            $fileName = 'Testimonials';

            return $excel->download(new DataGridExport('admin.testimonials.export', $data), "$fileName.xlsx");

        }

        



        $route = $this->route;



        $pageTitle = "Testimonials";

        if(Session::has('TestimonialsPage')){
            Session::forget('TestimonialsPage');
        }
        Session::put('TestimonialsPage', $request->input('page') ?? 1);

        if ($request->ajax()) {

            return view('admin.testimonials.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route'));

        } else {

            return view('admin.testimonials.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route'));

        }

    }



    /**

     * @param testimonials $testimonials

     * @return View

     */

    public function create(Testimonials $testimonial) : View

    {

        $statuses = $testimonial->getStatusesDropDown();

        $pageTitle = "CREATE Testimonials";

        $route = $this->route;

        return view('admin.testimonials.form', compact('testimonial', 'pageTitle', 'route', 'statuses'));

    }



    /**

     * @param AdminTestimonialsRequest $request

     * @param testimonials $testimonials

     * @return \Illuminate\Http\RedirectResponse

     */

    public function store(AdminTestimonialsRequest $request, Testimonials $testimonial)

    {



        $testimonial = $testimonial->create($request->all());

        $route = $this->route;

        $redirectRoute = route("$route.index");

        if($request->has('save_continuei'))

        {

            $redirectRoute = route("$route.edit", $testimonial->id);

        }

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|testimonials|created')]);

    }



    /**

     */

    public function show(Testimonials $testimonial)

    {

        //

    }



    /**

     * @return View

     */

    public function edit(Testimonials $testimonial) : View

    {

        // echo '<pre>';

        // print_r($testimonial->toArray());

        // die;

        $statuses = $testimonial->getStatusesDropDown();

        $copy = request()->has('copy') ? true : false;

        $pageTitle = $copy ? "COPY Testimonials" : "EDIT Testimonials";

        $route = $this->route;

        $users = User::where([['status','Active'],['role',$testimonial->type]])->get();

        $html = "<select name='client_id' id='client_id' class='form-control'>";

        if($users){

            foreach($users as $user){

                $sel = '';

                if($user->id == $testimonial->client_id){

                    $sel = 'selected';

                }

                $html .="<option value=".$user->id." $sel>".$user->first_name .' '. $user->last_name ."</option>";

            }

            $html .='</select>';

        }

        $testimonial['client_id'] = $html;

        // echo '<pre>';

        // echo '<hr>';

        // print_r($testimonial->toArray());

        // die;

        return view('admin.testimonials.form', compact('testimonial', 'pageTitle', 'route', 'statuses', 'copy'));

    }



    /**

     * @param AdminCategoryRequest $request

     * @param Category $category

     * @return \Illuminate\Http\RedirectResponse

     */

    public function update(AdminTestimonialsRequest $request, Testimonials $testimonial)

    {

        // if($request->hasFile('icon') && $request->file('icon')->isValid())

        // {

        //     $documentFile = $brand->uploadMedia($request->file('icon'));

        //     $document = $documentFile['path'].$documentFile['name'];

        //     $request->merge(['icon_file' => $document]);

        // }

        $testimonial->update($request->all());

        $route = $this->route;

        $redirectRoute = route("$route.index");

        if($request->has('save_continue'))

        {

            $redirectRoute = route("$route.edit", $testimonial->id);

        }

        elseif(Session::has('TestimonialsPage')){
            // echo 11;die;
            $page = session()->get("TestimonialsPage");
            // $redirectRoute = ;
            return redirect()->route("$route.index", ['page'=>$page])->with(['status' => 'success', 'message' => trans('success.admin|testimonials|updated')]);
        }

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|testimonials|updated')]);

    }



    /**

     * @param Category $category

     * @return \Illuminate\Http\RedirectResponse

     */

    public function destroy(Testimonials $testimonial)

    {

        $route = $this->route;

        if($testimonial->canDelete())

        {

            try{

                $testimonial->delete();

                return redirect(route("$route.index"))->with(['status' => 'success', 'message' => trans('success.admin|testimonials|deleted')]);



            }catch (\Exception $exception)

            {

                return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|testimonials|deleteNotPossible')]);

            }

        }

        else

        {

            return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|testimonials|deleteNotPossible')]);

        }

    }



    public function getClients(Request $request){

        $type = $request->type;

        if($type =='supplier'|| $type =='vendor'){

            $users = User::where([['status','Active'],['role',$type]])->get();

            $html = "";

            if($users){

                foreach($users as $user){

                    $html .="<option value=".$user->id.">".$user->first_name .' '. $user->last_name ."</option>";

                }

            }

            return $html;

        }

    }

}

