<?php

/**

 * Created by PhpStorm.

 * User: mayank

 * Date: 22/11/18

 * Time: 10:03 AM

 */



namespace App\Http\Controllers\Helpers;



use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;

use Illuminate\Pagination\Paginator;



trait DataGrid

{

    public $filters = [];

    public $sorting = ['sorting_field' => '', 'sort' => '', 'default_field' => 'created_at', 'default_sort' => 'asc'];



    /**

     * @var Model

     */

    public $model;



    /**

     * @var string|array

     */

    public $eager;



    /**

     * @var string

     */

    public $url;



    /**

     * @var Request

     */

    public $request;



    /**

     * @var integer

     */

    public $pagination = 20;



    /**

     * @var array

     */

    public $scopes = [];



    /**

     * @var array

     */

    public $scopesWithValue = [];



    /**

     * @var string

     */

    public $containerClass = '.data-grid';



    /**

     * @var array

     */

    public $defaultQueryParams = [];



    /**

     * @var string

     */

    public $sessionPrefix = '';



    /**

     * @var bool

     */

    public $paginationEnable = true;



    /**

     * @param array $scopes

     */

    public function setScopes(array $scopes): void

    {



        $this->scopes = $scopes;
    }



    /**

     * @param array $scopesWithValue

     */

    public function setScopesWithValue(array $scopesWithValue): void

    {

        $this->scopesWithValue = $scopesWithValue;
    }



    /**

     * @param array $sorting

     */

    public function setSorting(array $sorting): void

    {

        $this->sorting = $sorting;
    }



    /**

     * @param array $filters

     */

    public function setFilters(array $filters): void

    {

        $this->filters = $filters;
    }



    /**

     * @param Model $model

     */

    public function setGridModel($model): void

    {

        $this->model = $model;
    }



    /**

     * @param array|string $eager

     */

    public function setEager($eager): void

    {

        $this->eager = $eager;
    }



    /**

     * @param string $url

     */

    public function setGridUrl($url): void

    {

        $this->url = $url;
    }



    /**

     * @param Request $request

     */

    public function setGridRequest($request): void

    {

        $this->request = $request;
    }



    /**

     * @param Model $model

     */

    public function setModel(Model $model): void

    {

        $this->model = $model;
    }



    /**

     * @param string $url

     */

    public function setUrl(string $url): void

    {

        $this->url = $url;
    }



    /**

     * @param Request $request

     */

    public function setRequest(Request $request): void

    {

        $this->request = $request;
    }



    /**

     * @param int $pagination

     */

    public function setPagination(int $pagination): void

    {

        $this->pagination = $pagination;
    }



    /**

     * @param string $containerClass

     */

    public function setContainerClass(string $containerClass): void

    {

        $this->containerClass = $containerClass;
    }



    /**

     * @param array $defaultQueryParams

     */

    public function setDefaultQueryParams(array $defaultQueryParams): void

    {

        $this->defaultQueryParams = $defaultQueryParams;
    }



    /**

     * @param string $sessionPrefix

     */

    public function setSessionPrefix(string $sessionPrefix): void

    {

        $this->sessionPrefix = $sessionPrefix;
    }



    /**

     * @return bool

     */

    public function isPaginationEnable(): bool

    {

        return $this->paginationEnable;
    }



    /**

     * @param bool $paginationEnable

     */

    public function setPaginationEnable(bool $paginationEnable): void

    {

        $this->paginationEnable = $paginationEnable;
    }



    /**

     * @return array

     */

    public function getFilters(): array

    {

        return $this->filters;
    }



    /**

     * @return array

     */

    public function getSorting(): array

    {

        return $this->sorting;
    }



    /**

     * @return Model

     */

    public function getModel(): Model

    {

        return $this->model;
    }



    /**

     * @return array|string

     */

    public function getEager()

    {

        return $this->eager;
    }



    /**

     * @return string

     */

    public function getUrl(): string

    {

        return $this->url;
    }



    /**

     * @return Request

     */

    public function getRequest(): Request

    {

        return $this->request;
    }



    /**

     * @return int

     */

    public function getPagination(): int

    {

        return $this->pagination;
    }



    /**

     * @return string

     */

    public function getContainerClass(): string

    {

        return $this->containerClass;
    }



    /**

     * @return array

     */

    public function getDefaultQueryParams(): array

    {

        return $this->defaultQueryParams;
    }





    /**

     * @return string

     */

    public function getSessionPrefix(): string

    {

        return $this->sessionPrefix;
    }



    public function setGridVariables()

    {

        $sessionPrefix = $this->getSessionPrefix();

        $tableName = $this->model->getTable() . $sessionPrefix;

        $request = $this->request;





        foreach ($this->filters as $filterData) {

            if (isset($filterData['column']) && !empty($filterData['column'])) {

                $columns = [];

                if (is_array($filterData['column'])) {

                    foreach ($filterData['column'] as $column) {

                        $columns[] = $column;
                    }
                } else {

                    $columns[] = $filterData['column'];
                }



                foreach ($columns as $column) {

                    $sessionName = $tableName . "_" . $column;

                    if (isset($filterData['operator']) && $filterData['operator'] == "BETWEEN") {

                        foreach (["_start", "_end"] as $item) {

                            $filter = $sessionName . $item;

                            $request->session()->put(
                                $filter,

                                $request->has($filter) ? $request->get($filter) : ($request->session()

                                    ->has($filter) ? $request->session()->get($filter) : '')
                            );
                        }
                    } else {

                        $filter = $sessionName;

                        $request->session()->put(
                            $filter,

                            $request->has($filter) ? $request->get($filter) : ($request->session()

                                ->has($filter) ? $request->session()->get($filter) : '')
                        );
                    }
                }
            }
        }



        $request->session()->put(
            $this->sorting['sorting_field'],

            $request->has($this->sorting['sorting_field']) ? $request->get($this->sorting['sorting_field']) : ($request->session()

                ->has($this->sorting['sorting_field']) ? $request->session()->get($this->sorting['sorting_field']) : $this->sorting['default_field'])
        );





        $request->session()->put(
            "per_page_count",

            $request->has("per_page_count") ? $request->get("per_page_count") : ($request->session()

                ->has("per_page_count") ? $request->session()->get("per_page_count") : $this->getPagination())
        );



        $request->session()->put(
            $this->sorting['sort'],

            $request->has($this->sorting['sort']) ? $request->get($this->sorting['sort']) : ($request->session()

                ->has($this->sorting['sort']) ? $request->session()->get($this->sorting['sort']) : $this->sorting['default_sort'])
        );
    }



    public function getGridData($paginate = 10)

    {

        $sessionPrefix = $this->getSessionPrefix();

        $tableName = $this->model->getTable() . $sessionPrefix;

        $paginate = $this->request->session()->has("per_page_count") ? $this->request->session()->get("per_page_count") : $this->getPagination();



        $request = $this->request;

        $data = $this->model;

        // dd( $this->model->getTable());

        $data = $this->applyScopesWithValue($data);

        $data = $this->applyScopes($data);





        if (!empty($this->eager)) {

            $data = $data->with($this->eager);
        }

        foreach ($this->filters as $filterData) {

            if (isset($filterData['operator'])) {

                $columns = [];





                if (isset($filterData['column']) && !empty($filterData['column'])) {

                    if (is_array($filterData['column'])) {

                        foreach ($filterData['column'] as $column) {

                            $columns[] = $column;
                        }
                    } else {

                        $columns[] = $filterData['column'];
                    }
                }



                $filterSessionName = $tableName . '_' . implode('_', $columns);



                $filterOperator = $filterData['operator'];
                switch ($filterOperator) {

                    case 'LIKE':

                        if (!empty($request->session()->get($filterSessionName))) {

                            foreach ($columns as $filterName) {

                                $data = $data->where($filterName, $filterOperator, '%' . $request->session()->get($filterSessionName) . '%');
                            }
                        }



                        break;



                    case 'SCOPE':

                        if (!empty($request->session()->get($filterSessionName))) {

                            $data = $data->{$filterData['search']['scope']}($request->session()->get($filterSessionName));
                        }



                        break;

                    case 'BETWEEN':

                        foreach ($columns as $filterName) {

                            $startDate = $request->session()->get($filterSessionName . '_start');

                            $endDate = $request->session()->get($filterSessionName . '_end');

                            if (!empty($startDate) && !empty($endDate)) {

                                $startDate = Carbon::createFromFormat('d/m/Y', $startDate)->format('Y-m-d');

                                $endDate = Carbon::createFromFormat('d/m/Y', $endDate)->format('Y-m-d');

                                $data = $data->whereBetween($filterName, [$startDate, $endDate]);
                            } elseif (!empty($startDate) && empty($endDate)) {

                                $startDate = Carbon::createFromFormat('d/m/Y', $startDate)->format('Y-m-d');

                                $data = $data->whereDate($filterName, '>=', $startDate);
                            } elseif (empty($startDate) && !empty($endDate)) {

                                $endDate = Carbon::createFromFormat('d/m/Y', $endDate)->format('Y-m-d');

                                $data = $data->whereDate($filterName, '<=', $endDate);
                            }
                        }

                        break;

                    case '=':

                    default:

                        if (!empty($request->session()->get($filterSessionName))) {

                            foreach ($columns as $filterName) {

                                $data = $data->where($filterName, $request->session()->get($filterSessionName));
                            }
                        }

                        break;
                }
            }
        }



        $sortingField = $this->sorting['sorting_field'];

        $sortType = $this->sorting['sort'];



        $currentPage = $request->has('page') ? $request->get('page') : 1;



        $data = $data->orderBy($request->session()->get($sortingField), $request->session()->get($sortType));



        if ($this->isPaginationEnable()) {

            $data = $data->paginate($paginate, ['*'], 'page', $currentPage);
        } else {

            $data = $data->get();
        }





        $this->setScopes([]);

        $this->setScopesWithValue([]);

        $this->setEager([]);

        return $data;
    }



    public function gridTitles()

    {

        $containerClass = $this->getContainerClass();

        $sessionPrefix = $this->getSessionPrefix();

        $url = $this->url;

        $filters = $this->filters;

        $sorting = $this->sorting;

        $tableName = $this->model->getTable() . $sessionPrefix;

        return view('admin.helpers.dataGrid.title', compact('url', 'filters', 'sorting', 'tableName', 'containerClass'));
    }



    public function gridSearch()

    {

        $sessionPrefix = $this->getSessionPrefix();

        $containerClass = $this->getContainerClass();

        $defaultQueryParams = $this->getDefaultQueryParams();

        $queryString = '';

        foreach ($defaultQueryParams as $defaultQueryParamKey => $defaultQueryParamValue) {

            $queryString .= "$defaultQueryParamKey=$defaultQueryParamValue&";
        }



        $url = $this->url;

        $filters = $this->filters;

        $sorting = $this->sorting;

        $tableName = $this->model->getTable() . $sessionPrefix;

        return view('admin.helpers.dataGrid.search', compact('url', 'filters', 'sorting', 'tableName', 'containerClass', 'queryString'));
    }



    public function gridPagination($records)

    {

        $containerClass = $this->getContainerClass();

        $url = $this->url;

        return view('admin.helpers.dataGrid.pagination', compact('url', 'records', 'containerClass'));
    }



    public function applyScopesWithValue($model)

    {

        if (!empty($this->scopesWithValue)) {

            foreach ($this->scopesWithValue as $scope => $value) {

                $model = $model->$scope($value);
            }
        }

        return $model;
    }



    public function applyScopes($model)

    {

        if (!empty($this->scopes)) {

            foreach ($this->scopes as $scope) {

                $model = $model->{$scope}();
            }
        }

        return $model;
    }
}
