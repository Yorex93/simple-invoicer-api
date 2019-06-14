<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\InvoiceResource;
use App\Mail\InvoiceMail;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{

		public function index(){
			$invoices = new InvoiceResource(Auth::user()->load('invoices', 'invoices.items')->invoices);
			return response()->json(['success' => ['invoices'=>$invoices]], 200);
		}


		public function getSingle($id){

		}


		public function store(Request $request){
			$validator = Validator::make($request->all(), [
				"invoiceNumber"=> 'required|string',
				"clientName"=> 'required|string',
				"billTo"=> 'required|string',
				"clientAddress"=> 'required|string',
				"clientCity"=> 'required|string',
				"clientCountry"=> 'required|string',
				"invoiceDate"=> 'required|string',
				"invoiceDueDate"=> 'required|string',
				"invoiceNotes"=> 'required|string',
				"invoiceItems" => 'required|array',
				"clientEmail" => 'required|email',
				"companyName" => 'required|string',
				"companyAddress" => 'required|string',
				"companyLogo"=>'nullable|string',
				"userFullName" => 'required|string',
				'vat' => 'nullable|integer'
			], [
				'companyName.required' => 'You have not set up your company name',
				'companyAddress.required' => 'You have not set up your companyAddress',
				'userFullName.required' => 'You have not set up your full name',
			]);

			if($validator->fails()){
				return response()->json(['error' => $validator->errors()], 401);
			}

			Profile::where('user_id', Auth::user()->id)->update([
				'full_name' => $request->userFullName
			]);

			Company::where('user_id', Auth::user()->id)->update([
				'company_name' => $request->companyName,
				'company_address' => $request->companyAddress,
				'company_logo' => $request->companyLogo
			]);

			$invoice = Invoice::create([
				"user_id"=> Auth::user()->id,
                "invoice_no"=> $request->invoiceNumber,
                "client"=> $request->clientName,
                "bill_to"=> $request->billTo,
                "client_address"=> $request->clientAddress,
                "client_city"=> $request->clientCity,
                "client_country"=> $request->clientCountry,
                "invoice_date"=> date('Y-m-d H:i:s', strtotime($request->invoiceDate)),
                "invoice_due_date"=> date('Y-m-d H:i:s', strtotime($request->invoiceDueDate)),
                "invoice_notes"=> $request->invoiceNotes,
				"client_email" => $request->clientEmail,
				"vat" => $request->vat
			]);

			foreach ($request->invoiceItems AS $item){
				$invoiceItem = InvoiceItem::create([
					'invoice_id' => $invoice->id,
					'item_description' => $item['itemDescription'],
					'item_unit_cost' => $item['itemUnitCost'],
					'units' => $item['itemQuantity'],
					'total_cost' => $item['itemUnitCost'] * $item['itemQuantity'],
				]);
			}

			$invoice = Invoice::findOrFail($invoice->id)->load('items', 'user.profile', 'user.company');

			$file = 'invoices/'.str_slug($invoice->client)."~".str_random(5).'.pdf';

			$mail = Mail::to($invoice->client_email);

			$mail->send(new InvoiceMail($invoice, $file));

			//File::delete($file);



			return response()->json(['success' => $invoice], 201);
		}
}
