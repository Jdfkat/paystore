@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Items</div>
                <table>
                    <tr>
                        <th>Item #</th>
                        <th>Item Name</th>
                        <th>Item Description</th>
                        <th>Item Price</th>
                        <th>Buy</th>                    
                    </tr>
                    @if(count($items) > 0)
                        @foreach($items as $item)
                            <tr>
                                <td>{{$item->id}}</td>
                                <td>{{$item->name}}</td>
                                <td>{{$item->description}}</td>
                                <td>{{$item->price}}</td>
                                <td>
                                    <!-- <form method="post" action="https://www.sandbox.paypal.com/cgi-bin/webscr" > -->
                                    <form method="post" action="https://www.sandbox.paypal.com/cgi-bin/webscr" >
                                        <input type="hidden" name="charset" value="utf-8">
                                        <input type="hidden" name="cmd" value="_xclick" />
                                        <input type="hidden" name="business" value="{{ config('paypalipn.sanbox.ipnbusiness') }}" />
                                        <input type="hidden" name="cbt" value="PG Business" />
                                        <input type="hidden" name="currency_code" value="USD" />

                                        <!-- Allow the customer to enter the desired quantity -->
                                        <input type="hidden" name="quantity" value="1" />
                                        <input type="hidden" name="item_name" value="{{$item->name}}" />
                                        <input type="hidden" name="amount" value="{{$item->price}}" />
                                        <input type="hidden" name="shipping" value="0" />
                                        <input type="hidden" name="no_note" value="1" />

                                        <input type="hidden" name="return" value="{{url('/')}}" />
                                        <input type="hidden" name="cancel" value="{{url('/')}}" />
                                        <input type="hidden" name="notify_url" value="{{ config('paypalipn.sanbox.ipnnotifyurl') }}" />
                                        
                                        <!-- Custom value you want to send and process back in the IPN -->
                                        <!-- <input type="hidden" name="custom" value="{{$item->id}}" /> -->

                                        <input type="hidden" name="custom" value="{{ json_encode(['reference_id' => $item->id, 'reference_type' => 'item']) }}" />
                                        <button type="submit" class="btn btn-primary">Buy Item</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <h3>No items found</h3>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
