<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>GetCandy</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-size:12px;
            font-family:'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .lines {
            margin-top: 30px;
            margin-bottom: 30px;
        }
        .discount-seperator {
            color:#ccc;
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

        .lines-footer {
            border-top:5px solid #f5f5f5;
            text-align:right;
        }

        .lines-footer td {
            padding: 10px;
            border: 1px solid #ededed;
        }

        .summary {
            margin-bottom: 40px;
        }

        .summary td {
            padding: 5px 10px;
        }

        .info {
            color:#0099e5;
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
                                    <img src="{{ config('getcandy.invoicing.logo', config('app.name')) }}" width="100px">
                                    <h3>Order Invoice</h3>
                                </td>
                                <td align="right" width="50%">
                                    Invoice: {{ @$order->invoice_reference }} <br>
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
                                <td align="left" width="33%">
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

                                <td align="left" width="33%">
                                    <h3>Shipping</h3>
                                    {{ $order->shipping_firstname }} {{ @$order->shipping_lastname }}<br>
                                    {{ $order->shipping_address }}
                                    @if ($order->shipping_address_two)
                                        {{ $order->shipping_address_two }} <br>
                                    @endif
                                    @if ($order->shipping_address_three)
                                        {{ $order->shipping_address_three }} <br>
                                    @endif
                                    {{ $order->shipping_city }}<br>
                                    {{ $order->shipping_county }}<br>
                                    {{ $order->shipping_state }}<br>
                                    {{ $order->shipping_zip }}<br>
                                    {{ $order->shipping_country }}
                                </td>

                                <td align="right" width="33%">
                                    <strong>{{ $settings['address']['address'] }}</strong><br>
                                    {{ $settings['address']['address_two'] }}<br>
                                    {{ $settings['address']['city'] }}<br>
                                    {{ $settings['address']['state'] }}<br>
                                    {{ $settings['address']['zip'] }}<br>
                                    {{ $settings['address']['country'] }}<br>
                                    @if($settings['tax']['vat_number'])
                                    <p>VAT No.: {{ $settings['tax']['vat_number'] }}</p>
                                    @endif
                                    @if($settings['contact']['telephone'])
                                    <p>Tel No: {{ $settings['contact']['telephone'] }}</p>
                                    @endif
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
                        <th width="10%">
                            Qty
                        </th>
                        <th width="15%">
                            Unit Price
                        </th>
                        <th width="15%">
                            Discount
                        </th>
                        <th width="15%">
                            Tax Rate
                        </th>
                        <th width="15%">
                            Tax Amount
                        </th>
                        <th width="12%">
                            Line Total
                        </th>
                    </tr>
                </thead>
                <tbody class="lines-body">
                    @foreach ($order->basketLines as $item)
                    <tr>
                        <td>
                            {{ $item->description }} <br>
                            <small>{{ $item->option }}</small>

                            @if ($item->variant)
                                <small>
                                    @foreach($item->variant->options as $option)
                                        {{ $option }}@if(!$loop->last), @endif
                                    @endforeach
                                </small>
                            @endif
                        </td>
                        <td>
                            {{ $item->sku }}
                        </td>
                        <td>
                            {{ $item->quantity }}
                        </td>
                        <td>
                            {!! $order->currency == 'GBP' ? '&pound;' : '&euro;' !!}{{ number_format($item->unit_price / 100, 2) }}
                        </td>
                        <td>
                            {!! $order->currency == 'GBP' ? '&pound;' : '&euro;' !!}{{ number_format($item->discount_total / 100, 2) }}
                        </td>
                        <td>VAT @ {{ $item->tax_rate }}%</td>
                        <td>
                            {!! $order->currency == 'GBP' ? '&pound;' : '&euro;' !!}{{ number_format($item->tax_total / 100, 2) }}
                        </td>
                        <td align="right">
                            {!! $order->currency == 'GBP' ? '&pound;' : '&euro;' !!}{{ number_format($item->line_total / 100, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="lines-footer">
                    @if($order->discounts)
                        @foreach ($order->discounts as $discount)
                            <tr class="discount-row">
                                <td colspan="4">
                                    <strong>{{ $discount->name }}</strong> @if($discount->type == 'percentage') @ {{ $discount->amount }}%@endif Discount<br>
                                    @if ($discount->coupon)
                                    Code: <code>{{ $discount->coupon }}</code>
                                    @endif
                                </td>
                                <td>-{{ $order->currency == 'GBP' ? '&pound;' : '&euro;' }}{{ number_format($discount->total, 2) }}</td>
                            </tr>
                        @endforeach
                    @endif
                    <tr>
                        <td colspan="5"></td>
                        <td colspan="2">
                            <strong>Shipping</strong> <br>
                            <small>{{ $order->shipping_method }}</small>
                        </td>
                        <td>{!! $order->currency == 'GBP' ? '&pound;' : '&euro;' !!}{{ number_format($order->delivery_total / 100, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="5"></td>
                        <td colspan="2"><strong>Sub Total</strong></td>
                        <td>{!! $order->currency == 'GBP' ? '&pound;' : '&euro;' !!}{{ number_format(($order->sub_total + $order->delivery_total) / 100, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="5"></td>
                        <td colspan="2"><strong>VAT</strong></td>
                        <td>{!! $order->currency == 'GBP' ? '&pound;' : '&euro;' !!}{{ number_format($order->tax_total / 100, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="5"></td>
                        <td colspan="2"><strong>Total</strong></td>
                        <td>{!! $order->currency == 'GBP' ? '&pound;' : '&euro;' !!}{{ number_format(($order->order_total) / 100, 2) }}</td>
                    </tr>
                </tfoot>
            </table>

            @if($order->notes)
            <p><strong>Order Notes</strong><br>
            {{ $order->notes }}</p>
            <br>
            @endif
        </div>
    </div>
</body>
</html>
