<html>
    <head>
        <style>

            body{
                font-size: 14px;
            }

            #school_information{
                margin-top: 10%;
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
                            <td><span class="school_data"> {{$invoice->order->school->name}}</span></td>
                        </tr>
                        <tr>
                            <td><span class="school_data"> {{$invoice->order->school->direction}}</span></td>
                        </tr>
                        <tr>
                            <td><span class="school_data"> {{$invoice->order->school->nit}}</span></td>
                        </tr>
                        
                    </tbody>
                </table>
            </section><br /><br /><br />
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
                            <td>{{$p->progress->purchased_amount}}</td>
                            @if($p->progress->product->camouflage)
                                <td>{{$p->invoiced_as}}</td>
                            @else
                                <td>{{$p->progress->product->name}}</td>
                            @endif

                            <td>Q {{number_format($p->progress->detail->sale_price,2)}}</td>
                            <td>Q {{number_format(($p->progress->purchased_amount * $p->progress->detail->sale_price),2)}}</td>
                        </tr>
                         @endforeach
                        
                    </tbody>
                </table>
            </section><br /><br />
            <section style="margin-left: 80%">
                <span>Q {{number_format($invoice->total,2)}}</span><br />
                <span>Q {{number_format($invoice->total_iva,2)}}</span><br />
                <span>Q {{number_format(($invoice->total - $invoice->total_iva),2)}}</span><br />
            </section>
        </div>
        </div>
    </body>
</html>