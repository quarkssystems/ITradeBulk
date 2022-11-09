<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Promotion;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Auth\Guard;
use App\Models\SupplierItemInventory;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class ProductImport implements ToModel, WithHeadingRow
{


	public function transformDate($value, $format = 'Y-m-d')
	{
		try {
			return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value))->format($format);
		} catch (\ErrorException $e) {
			if (is_int($value)) {
				return \Carbon\Carbon::createFromFormat($format, $value);
			} else {
				return \Carbon\Carbon::parse($value)->format($format);
			}
		}
	}
	/**
	 * @param array $row
	 *
	 * @return \Illuminate\Database\Eloquent\Model|null
	 */
	public function modelOld(array $row)
	{

		// Define varible of  csv colonm

		$barcode      = trim($row['stoc_bcode']);
		$qty      = $row['stoc_qty'];
		$price      = $row['stoc_price'];
		$remarks      = $row['remarks'];


		// Barcode is exist in db if not than insert product 
		// DB::enableQueryLog();
		$checkproduct = Product::withoutGlobalScopes()->where('barcode', $barcode)->first();
		//dd($checkproduct);
		//dd(DB::getQueryLog());
		if ($checkproduct === null) {
		} else {
			$productid = $checkproduct->uuid;
			$supplier_id = auth()->user()->uuid;
			$inventoryModel = new SupplierItemInventory;
			$data_inventory = $inventoryModel->where('product_id', $productid)->where('user_id', $supplier_id)->select('uuid')->first();
			if ($data_inventory) {

				$inventoryModel->where('uuid', $data_inventory->uuid)->update(['single' => $qty, 'single_price' => $price]);
			} else {

				$inventoryModel->create(['user_id' => $supplier_id, 'product_id' => $productid, 'single' => $qty, 'single_price' => $price, 'remarks' => $remarks]);
			}
		}
	}

	public function model(array $row)
	{
		//New Fields
		$barcode = trim($row['Barcode']);
		$storeID = $row['Store_ID'];
		$storeItemCode = $row['Store_Item_Code'];
		$vat = $row['Vat'];
		$cost = $row['Cost'];
		$markup = $row['Markup'];
		$autoprice = $row['Autoprice'];
		$price = $row['Price'] ? $row['Price'] : 0;
		$quantity = $row['Quantity'] ? $row['Quantity'] : 0;
		$minOrderQuantity = $row['Min_Order_Quantity'];
		// dd('hi', $row['Stock_Expiry_Date']);

		// if (isset($row['Stock_Expiry_Date']) && trim($row['Stock_Expiry_Date']) != "") {
		// 	$stockExpiryDate =  \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(trim($row['Stock_Expiry_Date']))->format('Y-m-d');
		// } else {
		// 	$stockExpiryDate = null;
		// }
		$stockExpiryDate = $row['Stock_Expiry_Date'] ? $this->transformDate($row['Stock_Expiry_Date']) : null;
		// dd($stockExpiryDate);
		// 
		// $audited = $row['Audited'];
		// $published = $row['Published'] ? 'ACTIVE' : 'INACTIVE';
		// $promotionID = $row['Promotion_ID'];
		// $promotionType = $row['Promotion_Type'];
		// $periodFrom = $row['Period_From'] ? Carbon::parse($row['Period_From'])->format('Y-m-d') : null;
		// $periodTo = $row['Period_To'] ? Carbon::parse($row['Period_To'])->format('Y-m-d') : null;
		// $promotionPrice = $row['Promotion_Price'];
		// 

		//if stock/price less than zero then set item to unpublished(status = inactive)
		if ($price <= 0 || $quantity <= 0) {
			$published = 'INACTIVE';
		}

		// Barcode is exist in db if not than insert product 
		// DB::enableQueryLog();
		$checkproduct = Product::withoutGlobalScopes()->where('store_id', $storeID)->where('barcode', $barcode)->first();
		//dd(DB::getQueryLog());
		if ($checkproduct !== null) {
			//Set item to unpublished(status = inactive) before import
			$checkproduct['status'] = 'INACTIVE';
			$checkproduct->save();

			//check for promotions
			// $promotionuuId = null;
			// if($promotionID && $promotionType && $periodFrom && $periodTo && $promotionPrice) {
			// 	$promotionReqData = [
			// 		'promotion_id' => $promotionID,
			// 		'promotion_type' => $promotionType,
			// 		'period_from' => $periodFrom,
			// 		'period_to' => $periodTo,
			// 		'promotion_price' => $promotionPrice,
			// 	];

			// 	$checkPromotion = Promotion::where('promotion_id', $promotionID)->first();

			// 	if($checkPromotion) {
			// 		$promotionuuId = $checkPromotion->uuid;
			// 	} else {
			// 		$promotionData = Promotion::create($promotionReqData);
			// 		$promotionuuId = $promotionData->uuid;
			// 	}
			// }

			$inventoryReqData = [
				'stoc_vat' => $vat,
				'cost' => $cost,
				'markup' => $markup,
				'autoprice' => $autoprice,
				'single_price' => $price,
				'single' => $quantity,
				'min_order_quantity' => $minOrderQuantity,
				'stock_expiry_date' => $stockExpiryDate,
				// 'stock_expiry_date' => date('Y-m-d', strtotime($stockExpiryDate)),
				// 'stock_expiry_date' => $stockExpiryDate,
				// 'audited' => $audited,
				// 'promotion_id' => $promotionuuId
			];

			$productid = $checkproduct->uuid;
			$supplier_id = auth()->user()->uuid;
			$inventoryModel = new SupplierItemInventory;
			$data_inventory = $inventoryModel->where('product_id', $productid)->where('store_id', $storeID)->where('user_id', $supplier_id)->select('uuid')->first();
			if ($data_inventory) {
				$inventoryModel->where('uuid', $data_inventory->uuid)->update($inventoryReqData);
			} else {
				$inventoryReqData['store_id'] = $storeID;
				$inventoryReqData['user_id'] = $supplier_id;
				$inventoryReqData['product_id'] = $productid;
				$inventoryModel->create($inventoryReqData);
			}

			//set item status as per status from sheet 
			// $checkproduct['status'] = $published;
			$checkproduct->save();
		}
	}
}
