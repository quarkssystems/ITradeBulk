<?php



namespace App\Http\Controllers;



use App\Exports\DataGridExport;

use App\Http\Controllers\Helpers\DataGrid;

use App\Http\Requests\AdminTeamRequest;

use App\User;

use App\Models\Category;

use App\Models\Team;

use Illuminate\Http\Request;

use Illuminate\View\View;

use Maatwebsite\Excel\Excel;

use Session;

class AdminTeamsController extends Controller

{

    use DataGrid;



    public $dataUrl = '/admin/team';



    public $route = 'admin.team';



    /**

     * @param Request $request

     * @param Team $team

     * @param Excel $excel

     * @return \Illuminate\Contracts\View\Factory|View|Excel|\Symfony\Component\HttpFoundation\BinaryFileResponse

     * @throws \PhpOffice\PhpSpreadsheet\Exception

     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception

     */

    public function index(Request $request, Team $team, Excel $excel)

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

            'title' => 'Designation',

            'column' => 'designation',

            'operator' => 'LIKE',

            'sorting' => true,

            'search' => [

                'type' => 'text',

                'placeholder' => 'Search designation'

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

                'data' => $team->getStatusesDropDown()

            ]

        ];



        $filters[] = [

            'title' => 'Action'

        ];



        $tableName = $team->getTable();

        // echo $tableName;die;

        $url = $this->dataUrl;

        $this->setGridModel($team);

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

            $fileName = 'Team';

            return $excel->download(new DataGridExport('admin.team.export', $data), "$fileName.xlsx");

        }





        $route = $this->route;



        $pageTitle = "Team";

        if(Session::has('TeamPage')){
            Session::forget('TeamPage');
        }
        Session::put('TeamPage', $request->input('page') ?? 1);

        if ($request->ajax()) {

            return view('admin.team.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route'));

        } else {

            return view('admin.team.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route'));

        }

    }



    /**

     * @param Team $team

     * @return View

     */

    public function create(Team $team) : View

    {

        $statuses = $team->getStatusesDropDown();

        $pageTitle = "CREATE Team";

        $route = $this->route;

        return view('admin.team.form', compact('team', 'pageTitle', 'route', 'statuses'));

    }



    /**

     * @param AdminTeamRequest $request

     * @param Team $team

     * @return \Illuminate\Http\RedirectResponse

     */

    public function store(AdminTeamRequest $request, Team $team)

    {

        $arr = [];

        if($request->hasFile('coloured_image') && $request->file('coloured_image')->isValid() && $request->hasFile('black_white_image') && $request->file('black_white_image')->isValid())

        {

            $arr = $request->all();

            $coloured_image = $team->uploadMedia($request->file('coloured_image'));

            $black_white_image = $team->uploadMedia($request->file('black_white_image'));

            unset($arr['coloured_image']);

            unset($arr['black_white_image']);

            $arr['coloured_image'] = $coloured_image['path'].$coloured_image['name'];

            $arr['black_white_image'] = $black_white_image['path'].$black_white_image['name'];

        }

        $team = $team->create($arr);

        $route = $this->route;

        $redirectRoute = route("$route.index");

        if($request->has('save_continuei'))

        {

            $redirectRoute = route("$route.edit", $team->id);

        }

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|team|created')]);

    }



    /**

     */

    public function show(Team $team)

    {

        //

    }



    /**

     * @return View

     */

    public function edit(Team $team) : View

    {

        $statuses = $team->getStatusesDropDown();

        $copy = request()->has('copy') ? true : false;

        $pageTitle = $copy ? "COPY Team" : "EDIT Team";

        $route = $this->route;

        return view('admin.team.form', compact('team', 'pageTitle', 'route', 'statuses', 'copy'));

    }



    /**

     * @param AdminCategoryRequest $request

     * @param Category $category

     * @return \Illuminate\Http\RedirectResponse

     */

    public function update(AdminTeamRequest $request, Team $team)

    {

        $arr = [];

        

        // if($request->hasFile('coloured_image') && $request->file('coloured_image')->isValid() && $request->hasFile('black_white_image') && $request->file('black_white_image')->isValid())

        // {

        //     $arr = $request->all();

        //     $coloured_image = $team->uploadMedia($request->file('coloured_image'));

        //     $black_white_image = $team->uploadMedia($request->file('black_white_image'));

        //     unset($arr['coloured_image']);

        //     unset($arr['black_white_image']);

        //     $arr['coloured_image'] = $coloured_image['path'].$coloured_image['name'];

        //     $arr['black_white_image'] = $black_white_image['path'].$black_white_image['name'];

        // } 

            $arr = $request->all();

        if($request->hasFile('coloured_image') && $request->file('coloured_image')->isValid()){
            $coloured_image = $team->uploadMedia($request->file('coloured_image'));
            unset($arr['coloured_image']);
            $arr['coloured_image'] = $coloured_image['path'].$coloured_image['name'];
        } else {
            unset($arr['coloured_image']);
        }
         if($request->hasFile('black_white_image') && $request->file('black_white_image')->isValid()){
            $black_white_image = $team->uploadMedia($request->file('black_white_image'));
            unset($arr['black_white_image']);
            $arr['black_white_image'] = $black_white_image['path'].$black_white_image['name'];
        } else {
            unset($arr['black_white_image']);
        }
      
        $team->update($arr);

        $route = $this->route;

        $redirectRoute = route("$route.index");

        if($request->has('save_continue'))

        {

            $redirectRoute = route("$route.edit", $team->id);

        }

        elseif(Session::has('TeamPage')){
            // echo 11;die;
            $page = session()->get("TeamPage");
            // $redirectRoute = ;
            return redirect()->route("$route.index", ['page'=>$page])->with(['status' => 'success', 'message' => trans('success.admin|team|updated')]);
        }

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|team|updated')]);

    }



    /**

     * @param Category $category

     * @return \Illuminate\Http\RedirectResponse

     */

    public function destroy(Team $team)

    {

        $route = $this->route;

        if($team->canDelete())

        {

            try{

                $team->delete();

                return redirect(route("$route.index"))->with(['status' => 'success', 'message' => trans('success.admin|team|deleted')]);



            }catch (\Exception $exception)

            {

                return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|team|deleteNotPossible')]);

            }

        }

        else

        {

            return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|team|deleteNotPossible')]);

        }

    }

     

}

