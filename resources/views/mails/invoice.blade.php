<!doctype html>
<html><head>
    <meta charset="utf-8">
    @php
        $company = $invoice->user->company;
        $profile = $invoice->user->profile;
    @endphp
    <title>{{ $company->company_name }} Invoice #{{ $invoice->invoice_no }}</title>
    <style>

        body{
            font-family:Arial !important;
        }
        .row{
            height: 200px;
            background-color: transparent;
            width: 100%;
            clear: both;
            position: relative;
        }

        .address-box h3{
            font-weight: bold;
        }

        .items{
            font-size: 15px;
        }



        .header,
        .footer {
            width: 100%;
            text-align: center;
            position: absolute;
        }
        .header {
            top: 0px;
        }
        .footer {
            bottom: 100px;
        }
        .description p{
            margin: 0;
            margin-bottom: 5px;
        }

        .div-loader-wrapper{
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1000;
        }

        .watermark{
            display: block;
            position: relative;
            left: 20%;
            top: 30%;

            opacity: 0.2;
        }

    </style>
    @if(isset($toMail))
        <style>
            body{
                width: 850px !important;
                margin: 0 auto;
                display: block;
                border: 1px solid lightgray;
                padding: 15px;
            }

            .watermark{
                display: none;
                position: relative;
                left: 30%;
                top: 30%;
                opacity: 0.2;
            }

            .items{
                width: 100%
            }
        </style>
    @endif
</head><body style="width: 720px; overflow: hidden; position: relative">
<div class="div-loader-wrapper">
    @if(isset($watermark))
    <div class="watermark">
        <img src="{{ asset('watermark.png') }}" style="width: 600px;">
    </div>
    @endif
</div>
<table width="100%" style="margin-bottom: 30px;">
    <tr>
        <td width="33%"></td>
        <td width="33%"></td>
        <td width="33%"></td>
    </tr>
    <tr>
        <td colspan="2">
            <h3>To</h3>
            <p style="font-weight: bold; margin: 0">{{ $invoice->bill_to }},</p>
            <p style="font-weight: 400; margin: 0;margin-top: 8px">{{ $invoice->client }}</p>
            <p style="font-weight: 400; margin: 0;margin-top: 8px">{{ $invoice->client_address }},</p>
            <p style="font-weight: 400; margin-top: 8px">{{ $invoice->client_city }}, {{ $invoice->client_country }}</p>
        </td>
        <td>
            <table width="98%">
                <tbody>
                <tr>
                    <td align="right">
                        @if(!is_null($invoice->logo))
                        <img src="{{ asset($invoice->logo) }}" width="180">
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>
                        <h1 style="text-align: right; font-size: 32px; margin-bottom: 0; color: #999999; ">INVOICE</h1>
                        <p style="text-align: right; font-size: 14px; font-weight: 600; margin: 0">INVOICE #{{$invoice->invoice_no}}</p>
                        <p style="text-align: right; font-size: 13px; font-weight: 500; margin-top: 0">DATE: {{ strtoupper(\Carbon\Carbon::parse()->format('F d, Y')) }}</p>
                        <p style="text-align: right; font-size: 13px; font-weight: 500; margin-top: 5px">DUE DATE: {{ strtoupper(\Carbon\Carbon::parse($invoice->invoice_due_date)->format('F d, Y')) }}</p>
                    </td>
                </tr>
                <tr></tr>
                </tbody>
            </table>

        </td>
    </tr>
    <tr>
        <td colspan="3">
            <table width="100%" style="margin-top: 25px; border-collapse: collapse; table-layout: fixed" border="1" cellpadding="5" class="items">
                <tr>
                    <td  align="center" style="width: 10%"><b>NO</b></td>
                    <td  align="center" style="width: 50%"><b>DESCRIPTION</b></td>
                    <td align="center" style="width: 20%"><b>RATE</b></td>
                    <td align="center" style="width: 20%"><b>TOTAL (=N=)</b></td>
                </tr>
                @foreach($invoice->items AS $item)
                <tr>
                    <td align="center"><b>{{ $loop->iteration }}</b></td>
                    <td align="left" class="description">
                        <p>{{ $item->item_description }}</p>
                    </td>
                    <td align="right">{{ number_format($item->item_unit_cost, 2) }} X {{ $item->units }}</td>
                    <td align="right">{{ number_format($item->total_cost, 2) }}</td>
                </tr>
                @endforeach

                @php


                @endphp
                <tr>
                    <td align="left" class="description" colspan="2">

                    </td>
                    <td align="right" class="description">
                        <p >SUBTOTAL</p>
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                        @if(!is_null($invoice->vat))
                            <p>VAT ({{ $invoice->vat }}%)</p>
                        @else
                            <p>&nbsp;</p>
                        @endif
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                        <p><b>TOTAL</b></p>
                    </td>
                    <td align="right" class="description">
                        <p>{{ number_format($invoice->items->sum('total_cost'), 2) }}</p>
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                        @if(!is_null($invoice->vat))
                            <p>{{ number_format(($invoice->items->sum('total_cost') * $invoice->vat * 0.01), 2) }}</p>
                        @else
                            <p>&nbsp;</p>
                        @endif
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                        @if(!is_null($invoice->vat))
                            <p>{{ number_format(($invoice->items->sum('total_cost') + $invoice->vat * 0.01 * $invoice->items->sum('total_cost')), 2) }}</p>
                        @else
                            <p><b>{{ number_format($invoice->items->sum('total_cost'), 2) }}</b></p>
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    @if(!is_null($invoice->invoice_notes))
        <tr>
            <td colspan="3">
                <p>{{ $invoice->invoice_notes }}</p>
            </td>
        </tr>
    @endif
    <tr>
        <td></td>
        <td></td>
        <td align="right">
            <div class="" style="width: 95%;">
                <p ><i>For</i>: <b>{{ $company->company_name }}</b></p>
                <p>{{ $invoice->user->profile->full_name }}</p>
            </div>
        </td>
    </tr>


</table>



</body></html>
