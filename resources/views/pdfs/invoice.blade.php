<html>
    <head>
        <style>

            body{
                font-size: 14px;
            }

            #school_information{
                margin-top: 15%;
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
                <table style="width: 100%">
                    <tbody>
                        <tr>
                            <td><span class="school_data"> {{Carbon\Carbon::parse($invoice->date)->format('d-m-Y')}}</span></td>
                        </tr>
                        <tr>
                            <td><span class="school_data"> {{$invoice->order->school->name}}</span></td>
                        </tr>
                        <tr>
                            <td><span class="school_data"> {{$invoice->order->school->direction}}</span></td>
                        </tr>
                        <tr>
                            <td>
                                <span class="school_data"> {{$invoice->order->school->nit}}</span>
                                <span class="school_data" style="margin-left: 30%"> </span>
                                <span class="school_data" style="margin-left: 30%"> {{$invoice->order->school->email}}</span>
                            </td>
                        </tr>
                        
                    </tbody>
                </table>
            </section><br /><br /><br /><br />
            <section id="table_data">
                <table style="width: 100%">
                    @if($invoice->cancel)
                    <h1 id="anulado">
                        ANULADA
                    </h1>
                    @endif
                    <tbody>
                        @foreach($invoice->products as $p)
                        <tr>
                            <td width="13%">{{$p->progress->purchased_amount}}</td>
                            @if($p->progress->product->camouflage)
                                <td width="50%">{{$p->invoiced_as}}</td>
                            @else
                                <td width="50%">{{$p->progress->product->name}}</td>
                            @endif
                            <td width="15%">Q {{number_format(($p->progress->purchased_amount * $p->progress->detail->sale_price),2)}}</td>
                        </tr>
                         @endforeach
                        
                    </tbody>
                </table>
            </section><br /><br />
            <section style="position: fixed; top: 80%; left: 0">
                <table style="width: 100%">
                    <tbody>
                        <tr>
                            <td width="13%"></td>
                            <td width="50%">{{$total}}</td>
                            <td width="15%">Q {{number_format($invoice->total,2)}}</td>
                        </tr>
                    </tbody>
                </table>
            </section>
        </div>
        </div>
    </body>
</html>