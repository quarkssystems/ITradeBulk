<?php



namespace App\Http\Controllers;



use App\Exports\DataGridExport;

use App\Http\Controllers\Helpers\DataGrid;

use App\Http\Requests\AdminEmailTemplateRequest;

// use App\Models\Brand;

use App\Models\EmailTemplate;

use App\Models\Shortcode;

use Illuminate\Http\Request;

use Illuminate\View\View;

use Maatwebsite\Excel\Excel;

use Session;

class AdminEmailTemplateController extends Controller

{

    use DataGrid;



    public $dataUrl = '/admin/EmailTemplate';



    public $route = 'admin.emailTemplate';



    /**

     * @param Request $request

     * @param emailTemplate $emailTemplate

     * @param Excel $excel

     * @return \Illuminate\Contracts\View\Factory|View|Excel|\Symfony\Component\HttpFoundation\BinaryFileResponse

     * @throws \PhpOffice\PhpSpreadsheet\Exception

     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception

     */

    public function index(Request $request, emailTemplate $emailTemplate, Excel $excel)

    {

        

        $filters = [];

        $filters[] = ['title' => 'No'];



       /* $filters[] = [

            'title' => 'Thumbnail',

        ];



        */



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



        // $filters[] = [

        //     'title' => 'Status',

        //     'column' => 'status',

        //     'operator' => '=',

        //     'sorting' => true,

        //     'search' => [

        //         'type' => 'select',

        //         'placeholder' => 'Show all',

        //         'data' => $emailTemplate->getStatusesDropDown()

        //     ]

        // ];



        $filters[] = [

            'title' => 'Action'

        ];



        $tableName = $emailTemplate->getTable();

       

        $url = $this->dataUrl;

        $this->setGridModel($emailTemplate);

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

            $fileName = 'emailTemplate';

            return $excel->download(new DataGridExport('admin.emailTemplate.export', $data), "$fileName.xlsx");

        }





        $route = $this->route;



        $pageTitle = "Email Template";

        if(Session::has('EmailTemplatePage')){
            Session::forget('EmailTemplatePage');
        }
        Session::put('EmailTemplatePage', $request->input('page') ?? 1);

        if ($request->ajax()) {



            return view('admin.emailTemplate.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route'));

        } else {



            return view('admin.emailTemplate.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route'));

        }

    }



    /**

     * @param emailTemplate $emailTemplate

     * @return View

     */

    public function create(EmailTemplate $emailTemplate) : View

    {

        $statuses = $emailTemplate->getStatusesDropDown();

        $pageTitle = "CREATE Email Template";

        $route = $this->route;

        $shortCodes = Shortcode::whereNull('deleted_at')->get();

        // echo '<pre>';

        // print_r($shortCodes->toArray());

        // die;

        return view('admin.emailTemplate.form', compact('emailTemplate', 'pageTitle', 'route', 'statuses','shortCodes'));

    }



    /**

     * @param emailTemplate $emailTemplate

     * @return \Illuminate\Http\RedirectResponse

     */

    public function store(AdminEmailTemplateRequest $request, EmailTemplate $emailTemplate)

    {

        $emailTemplate = $emailTemplate->create($request->all());

        $route = $this->route;

        $redirectRoute = route("$route.index");

        if($request->has('save_continue'))

        {

            $redirectRoute = route("$route.edit", $emailTemplate->id);

        }

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|emailTemplate|created')]);

    }



    /**

     */

    public function show(emailTemplate $emailTemplate)

    {

        //

    }



    /**

     * @return View

     */

    public function edit(emailTemplate $emailTemplate) : View

    {

        $statuses = $emailTemplate->getStatusesDropDown();

        $copy = request()->has('copy') ? true : false;

        $pageTitle = $copy ? "COPY Email Template" : "EDIT Email Template";

        $route = $this->route;

        $shortCodes = Shortcode::whereNull('deleted_at')->get();

        return view('admin.emailTemplate.form', compact('emailTemplate', 'pageTitle', 'route', 'statuses', 'copy','shortCodes'));

    }



    /**

     * @param AdminCategoryRequest $request

     * @param Category $category

     * @return \Illuminate\Http\RedirectResponse

     */

    public function update(AdminEmailTemplateRequest $request, emailTemplate $emailTemplate)

    {

        // if($request->hasFile('icon') && $request->file('icon')->isValid())

        // {

        //     $documentFile = $brand->uploadMedia($request->file('icon'));

        //     $document = $documentFile['path'].$documentFile['name'];

        //     $request->merge(['icon_file' => $document]);

        // }

        $emailTemplate->update($request->all());

        $route = $this->route;

        $redirectRoute = route("$route.index");

        if($request->has('save_continue'))

        {

            $redirectRoute = route("$route.edit", $emailTemplate->id);

        }

        elseif(Session::has('EmailTemplatePage')){
            // echo 11;die;
            $page = session()->get("EmailTemplatePage");
            // $redirectRoute = ;
            return redirect()->route("$route.index", ['page'=>$page])->with(['status' => 'success', 'message' => trans('success.admin|emailTemplate|updated')]);
        }

        return redirect($redirectRoute)->with(['status' => 'success', 'message' => trans('success.admin|emailTemplate|updated')]);

    }



    /**

     * @param Category $category

     * @return \Illuminate\Http\RedirectResponse

     */

    public function destroy(emailTemplate $emailTemplate)

    {

        $route = $this->route;

        if($emailTemplate->canDelete())

        {

            try{

                $emailTemplate->delete();

                return redirect(route("$route.index"))->with(['status' => 'success', 'message' => trans('success.admin|emailTemplate|deleted')]);



            }catch (\Exception $exception)

            {

                return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|emailTemplate|deleteNotPossible')]);

            }

        }

        else

        {

            return redirect(route("$route.index"))->with(['status' => 'warning', 'message' => trans('warning.admin|emailTemplate|deleteNotPossible')]);

        }

    }

}

