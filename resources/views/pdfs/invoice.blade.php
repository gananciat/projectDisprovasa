<html>
    <head>
        <style>

            body{
                font-size: 14px;
                line-height: 25px;
            }

            #school_information{
                margin-top: 14%;
            }

            .school_data {
                margin-left: 15%;
            }

            #anulado {
                color: rgba(255, 0, 0, 0.5);
                top: 40%;
                left: 40%;
                font-size: 50px;
                -webkit-transform: rotate(-45deg); 
                -moz-transform: rotate(-45deg);    
                width:100px; text-align: center; position: absolute;
            }
        </style>
    </head>
    <body>
        <div id="invoice">
            <section id="school_information">
                <table style="width: 100%; font-size: 13px; margin-left: 75px;">
                    <tbody>
                        <tr>
                            <td><span> {{Carbon\Carbon::parse($invoice->date)->format('d-m-Y')}}</span></td>
                        </tr>
                        <tr>
                            <td><span style="font-size: 12px;"> {{$invoice->order->school->bill}}</span></td>
                        </tr>
                        <tr>
                            <td><span> {{$invoice->order->school->direction}}</span></td>
                        </tr>
                        <tr>
                            <td>
                                <span> {{$invoice->order->school->nit}}</span>
                                <span style="margin-left: 30%">
                                @if(count($invoice->order->school->phons) > 0)
                                    {{$invoice->order->school->phons[0]->number}}

                                @endif
                                 </span>
                                <span style="margin-left: 30%"> {{$invoice->order->school->email}}</span>
                            </td>
                        </tr>
                        
                    </tbody>
                </table>
            </section>
            <section style="position: fixed; top: 29%">
                <table style="width: 100%" >
                    @if($invoice->cancel)
                    <h1 id="anulado">
                        ANULADA
                    </h1>
                    @endif
                    <tbody>
                        @foreach($invoice->products as $p)
                        <tr>
                            <td width="15%">{{$p->progress->purchased_amount}}</td>
                            @if($p->progress->product->camouflage)
                                <td width="75%">{{$p->invoiced_as}}</td>
                            @else
                                <td width="75%">{{$p->progress->product->name}}</td>
                            @endif
                            <td width="10%">
                                <span style="padding-left: 20%;">
                                    Q {{number_format(($p->progress->purchased_amount * $p->progress->detail->sale_price),2)}}
                                </span> 
                            </td>
                        </tr>
                         @endforeach
                        
                    </tbody>
                </table>
            </section><br /><br />
            <section style="position: fixed; top: 85%; left: 0; font-size: 14px;">
                <table style="width: 100%">
                    <tbody>
                        <tr>
                            <td width="12%"></td>
                            <td width="78%">{{$total}}</td>
                            <td width="10%">
                                <span>
                                    Q {{number_format($invoice->total,2)}}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </section>
        </div>
        </div>
    </body>
</html>