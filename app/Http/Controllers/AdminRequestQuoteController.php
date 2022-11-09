<?php



namespace App\Http\Controllers;



use App\Exports\DataGridExport;

use App\Http\Controllers\Helpers\DataGrid;

use App\Http\Requests\AdminRequestQuoteRequest;

use App\User;

use App\Models\RequestQuote;

use Illuminate\Http\Request;

use Illuminate\View\View;

use Maatwebsite\Excel\Excel;

use Session;

class AdminRequestQuoteController extends Controller

{

    use DataGrid;



    public $dataUrl = '/admin/requestQuote';



    public $route = 'admin.requestQuote';



    /**

     * @param Request $request

     * @param Team $team

     * @param Excel $excel

     * @return \Illuminate\Contracts\View\Factory|View|Excel|\Symfony\Component\HttpFoundation\BinaryFileResponse

     * @throws \PhpOffice\PhpSpreadsheet\Exception

     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception

     */

    public function index(Request $request, RequestQuote $request_quote, Excel $excel)

    {

        

        $filters = [];

        $filters[] = ['title' => 'No'];



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

            'title' => 'Email',

            'column' => 'email',

            'operator' => 'LIKE',

            'sorting' => true,

            'search' => [

                'type' => 'text',

                'placeholder' => 'Search email'

            ]

        ];



        $filters[] = [

            'title' => 'Message',

            'column' => 'message',

            'operator' => 'LIKE',

            'sorting' => true,

            'search' => [

                'type' => 'text',

                'placeholder' => 'Search message'

            ]

        ];

        // $filters[] = [

        //     'title' => 'Slug',

        //     'column' => 'slug',

        //     'operator' => 'LIKE',

        //     'sorting' => true,

        //     'search' => [

        //         'type' => 'text',

        //         'placeholder' => 'Search slug'

        //     ]

        // ];



        $filters[] = [

            'title' => 'Status',

            'column' => 'status',

            'operator' => '=',

            'sorting' => true,

            'search' => [

                'type' => 'select',

                'placeholder' => 'Show all',

                'data' => $request_quote->getStatusesDropDown()

            ]

        ];

        $filters[] = [

            'title' => 'Uploaded file',

        ];



//        $filters[] = [

//            'title' => 'Action'

//        ];



        $tableName = $request_quote->getTable();

        // echo $tableName;die;

        $url = $this->dataUrl;

        $this->setGridModel($request_quote);

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

            $fileName = 'RequestQuote';

            return $excel->download(new DataGridExport('admin.requestQuote.export', $data), "$fileName.xlsx");

        }



        $route = $this->route;

        $pageTitle = "Request Quote";

        if(Session::has('RequestQuotePage')){
            Session::forget('RequestQuotePage');
        }
        Session::put('RequestQuotePage', $request->input('page') ?? 1);

        if ($request->ajax()) {

            return view('admin.requestQuote.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route'));

        } else {

            return view('admin.requestQuote.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route'));

        }

    }



    /**

     * @param Team $team

     * @return View

     */

    public function create(RequestQuote $request_quote) : View

    {

        $statuses = $request_quote->getStatusesDropDown();

        $pageTitle = "Request Quote";

        $route = $this->route;

        return view('admin.requestQuote.form', compact('request_quote', 'pageTitle', 'route', 'statuses'));

    }



    /**

     * @param AdminTeamRequest $request

     * @param Team $team

     * @return \Illuminate\Http\RedirectResponse

     */

    public function store(AdminRequestQuoteRequest $request, RequestQuote $request_quote)

    {

        $requestQuote = $request_quote->create($request->all());

        $route = $this->route;

        $redirectRoute = route("$route.index");

        if($request->has('save_continuei'))

        {

            $redirectRoute = route("$route.edit", $requestQuote->uuid);

        }

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|requestQuote|created')]);

    }



    /**

     */

    // public function show(Team $team)

    // {

    //     //

    // }



    /**

     * @return View

     */

    // public function edit(Team $team) : View

    // {

    //     $statuses = $team->getStatusesDropDown();

    //     $copy = request()->has('copy') ? true : false;

    //     $pageTitle = $copy ? "COPY Team" : "EDIT Team";

    //     $route = $this->route;

    //     return view('admin.team.form', compact('team', 'pageTitle', 'route', 'statuses', 'copy'));

    // }



    /**

     * @param AdminCategoryRequest $request

     * @param Category $category

     * @return \Illuminate\Http\RedirectResponse

     */

    // public function update(AdminTeamRequest $request, Team $team)

    // {

    //     $arr = [];

    //     if($request->hasFile('coloured_image') && $request->file('coloured_image')->isValid() && $request->hasFile('black_white_image') && $request->file('black_white_image')->isValid())

    //     {

    //         $arr = $request->all();

    //         $coloured_image = $team->uploadMedia($request->file('coloured_image'));

    //         $black_white_image = $team->uploadMedia($request->file('black_white_image'));

    //         unset($arr['coloured_image']);

    //         unset($arr['black_white_image']);

    //         $arr['coloured_image'] = $coloured_image['path'].$coloured_image['name'];

    //         $arr['black_white_image'] = $black_white_image['path'].$black_white_image['name'];

    //     }

    //     $team->update($arr);

    //     $route = $this->route;

    //     $redirectRoute = route("$route.index");

    //     if($request->has('save_continue'))

    //     {

    //         $redirectRoute = route("$route.edit", $team->id);

    //     }

        // elseif(Session::has('RequestQuotePage')){
        //     // echo 11;die;
        //     $page = session()->get("RequestQuotePage");
        //     // $redirectRoute = ;
        //     return redirect()->route("$route.index", ['page'=>$page])->with(['status' => 'success', 'message' => trans('success.admin|brand|updated')]);
        // }

    //     return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|team|updated')]);

    // }



    /**

     * @param Category $category

     * @return \Illuminate\Http\RedirectResponse

     */

    // public function destroy(Team $team)

    // {

    //     $route = $this->route;

    //     if($team->canDelete())

    //     {

    //         try{

    //             $team->delete();

    //             return redirect(route("$route.index"))->with(['status' => 'success', 'message' => trans('success.admin|team|deleted')]);



    //         }catch (\Exception $exception)

    //         {

    //             return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|team|deleteNotPossible')]);

    //         }

    //     }

    //     else

    //     {

    //         return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|team|deleteNotPossible')]);

    //     }

    // }

    public function status(Request $request){

        $input = $request->all();

        if($input['uuid']){

            RequestQuote::where('uuid',$input['uuid'])->update(['status' => $input['val']]);

            return 'Status Changed.';

        }

    }

     

}

