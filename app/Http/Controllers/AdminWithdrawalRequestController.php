<?php

namespace App\Http\Controllers;

use App\Exports\DataGridExport;
use App\Http\Controllers\Helpers\DataGrid;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Excel;
use App\Models\Withdrawal;
use App\Models\WalletTransactions;

class AdminWithdrawalRequestController extends Controller
{
    use DataGrid;

    public $dataUrl = '/admin/withdrawalrequest';

    public $route = 'admin.withdrawalrequest';

    /**
     * @param Request $request
     * @param Brand $brandModel
     * @param Excel $excel
     * @return \Illuminate\Contracts\View\Factory|View|Excel|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function index(Request $request, Withdrawal $Withdrawal, Excel $excel)
    {
        $filters = [];
        $filters[] = ['title' => 'No'];
        
        $filters[] = [
            'title' => 'Wallet Reference',
            'column' => 'uuid',
            'operator' => 'LIKE',
            'sorting' => true,
            'search' => [
                'type' => 'text',
                'placeholder' => 'Search name'
            ]
        ];
        $filters[] = [
            'title' => 'Name',
            'column' => 'user_id',
            'operator' => 'LIKE',
            'sorting' => true
            
        ];
        $filters[] = [
            'title' => 'Amount',
            'column' => 'amount',
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
                'data' => $Withdrawal->getStatusDropDown()
            ]
        ];

        $filters[] = [
            'title' => 'Date'
        ];

        $tableName = $Withdrawal->getTable();
        // echo $tableName;die;
        $url = $this->dataUrl;
        $this->setGridModel($Withdrawal);
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
            $fileName = 'Settle Request';
            return $excel->download(new DataGridExport('admin.withdrawalRequest.export', $data), "$fileName.xlsx");
        }


        $route = $this->route;

        $pageTitle = "Settle Request";

        if ($request->ajax()) {
            return view('admin.withdrawalRequest.grid', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'route'));
        } else {
            return view('admin.withdrawalRequest.index', compact('data', 'dataGridTitle', 'dataGridSearch', 'dataGridPagination', 'pageTitle', 'route'));
        }
    }

    public function approveWithdrawalTransaction($transaction_id,  Withdrawal $Withdrawal, WalletTransactions $walletTransactionsModel)
    {
        $Withdrawal->where('uuid', $transaction_id)->update(["status" => "APPROVED"]);
        $withdrawal = $Withdrawal->where('uuid', $transaction_id)->first();

        $walletTransactionsModel->create([

                "credit_amount" => 0,
                "debit_amount" => $withdrawal->amount,
                "user_id" => $withdrawal->user_id,
                "remarks" => "WITHDRAWAL REQUEST",
                "status" => "APPROVED"

        ]);

        return redirect()->back()->with(['status' => 'success', 'message' => "Withdrawal Request approved successfully"]);
    }

    public function cancelWithdrawalTransaction($transaction_id, Withdrawal $Withdrawal, WalletTransactions $walletTransactionsModel)
    {
        $Withdrawal->where('uuid', $transaction_id)->update(["status" => "CANCELED"]);
        $withdrawal = $Withdrawal->where('uuid', $transaction_id)->first();

        $walletTransactionsModel->create([

                "credit_amount" => 0,
                "debit_amount" => $withdrawal->amount,
                "user_id" => $withdrawal->user_id,
                "remarks" => "WITHDRAWAL REQUEST",
                "status" => "CANCELED"

        ]);

        return redirect()->back()->with(['status' => 'success', 'message' => "Withdrawal Request canceled successfully"]);
    }
 
   
}
