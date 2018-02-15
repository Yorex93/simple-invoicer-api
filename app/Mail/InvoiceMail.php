<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Barryvdh\DomPDF\Facade as PDF;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;

    private $file;

    public $toMail;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Invoice $invoice,  $file=null)
    {
        $this->invoice = $invoice;

        $this->file = $file;

        $this->toMail =  true;

	    if(!is_null($file)){
		    $pdf = PDF::loadView('mails.invoice', compact('invoice'))->save($this->file);
	    }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
	    if(!is_null($this->file)){
		    return $this->view('mails.invoice')->attach($this->file, [
			    'as' => str_slug($this->invoice->client).'_invoice.pdf',
			    'mime' => 'application/pdf',
		    ])->subject('Invoice #'.$this->invoice->invoice_no.' from '.$this->invoice->user->company->company_name.'');
	    }else{
		    return $this->view('mails.invoice')->subject('Invoice #'.$this->invoice->invoice_no.' from '.$this->invoice->user->company->company_name.'');
	    }
    }
}
