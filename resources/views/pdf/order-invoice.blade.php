<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Aqua Spa Supplies</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-size:14px;
            font-family:'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .lines {
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .lines-heading {
            text-align: left;
            background-color: #ededed;
        }

        .lines-heading th {
            padding: 5px 10px;
            border: 1px solid #ccc;
        }

        .lines-body td {
            padding: 5px 10px;
            border: 1px solid #ededed;
        }

        .summary {
            margin-bottom: 40px;
        }

        .summary td {
            padding: 5px 10px;
        }

        .summary .total td {
            border-top: 1px solid #ccc;
        }


    </style>
</head>

<body>
    <div class="content">
        <div class="invoice-box">

            <table cellpadding="0" cellspacing="0" width="100%">
                <tr class="top">
                    <td>
                        <table width="100%">
                            <tr>
                                <td class="title" align="left" width="50%">
                                    <h3>Order Invoice</h3>
                                </td>
                                <td align="right" width="50%">
                                    Invoice #: ORD-{{ @$order->id }} <br>
                                    Created: {{ $order->created_at }}<br>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td>
                        <table width="100%">
                            <tr>
                                <td align="left" width="50%">
                                    <h3>Billing</h3>
                                    {{ $order->billing_firstname }} {{ @$order->billing_lastname }}<br>
                                    {{ $order->billing_address }}
                                    @if ($order->billing_address_two)
                                        {{ $order->billing_address_two }} <br>
                                    @endif
                                    @if ($order->billing_address_three)
                                        {{ $order->billing_address_three }} <br>
                                    @endif
                                    {{ $order->billing_city }}<br>
                                    {{ $order->billing_county }}<br>
                                    {{ $order->billing_state }}<br>
                                    {{ $order->billing_zip }}<br>
                                    {{ $order->billing_country }}
                                    @if($order->vat_no)
                                        <p>VAT No.: {{ $order->vat_no }}</p>
                                    @endif
                                </td>

                                <td align="right" width="50%">
                                    <strong>Aqua Warehouse LTD</strong><br>
                                    Unit 2 Rignals Lane<br>
                                    Chelmsford<br>
                                    Essex<br>
                                    CM2 8RF<br>
                                    United Kingdom
                                    <p>VAT No.: GB820301490</p>
                                    <p>Tel No: 01245 477400</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <table cellpadding="0" cellspacing="0" width="100%" class="lines">
                <thead class="lines-heading">
                    <tr width="100%">
                        <th width="35%">
                            Product
                        </th>
                        <th width="28%">
                            SKU
                        </th>
                        <th width="15%">
                            Price
                        </th>
                        <th width="10%">
                            Qty
                        </th>
                        <th width="12%">
                            Line Total
                        </th>
                    </tr>
                </thead>
                <tbody class="lines-body">
                    @foreach ($lines as $item)
                    <tr>
                        <td>
                            {{ $item->product }} <br>
                            @if ($item->product != $item->variant)
                                <small>{{ $item->variant }}</small>
                            @endif
                        </td>
                        <td>
                            {{ $item->sku }}
                        </td>
                        <td>
                            &pound;{{ $item->total }}
                        </td>
                        <td>
                            {{ $item->quantity }}
                        </td>
                        <td>
                            &pound;{{ $item->total }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <table cellpadding="0" cellspacing="0" width="100%" class="summary">
                @foreach ($order->discounts() as $discount)
                    <tr>
                        <td width="60%"></td>
                        <td align="right">{{ $discount->coupon }}</td>
                        <td align="right">{{ $discount->amount }}%</td>
                    </tr>
                @endforeach
                <tr>
                    <td width="60%"></td>
                    <td align="right"><strong>Tax</strong></td>
                    <td align="right">&pound;{{ $order->vat }}</td>
                </tr>
                <tr>
                    <td width="60%"></td>
                    <td align="right"><strong>Shipping</strong></td>
                    <td align="right">&pound;{{ $order->shipping_total }}<br> <small>{{ $order->shipping_method }}</small></td>
                </tr>
                <tr>
                    <td width="60%"></td>
                    <td align="right" style="border-top: 2px solid #ccc;"><strong>Total</strong></td>
                    <td align="right" style="border-top: 2px solid #ccc;">&pound;{{ $order->total }}</td>
                </tr>
            </table>
            
            <p><strong>Order Notes</strong><br>
            {{ $order->notes }}</p>
            <br>
        </div>
    </div>
</body>
</html>
