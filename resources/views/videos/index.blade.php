@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Videos</div>
                <table>
                    <tr>
                        <th>Video #</th>
                        <th>Video Title</th>
                        <th>Video Description</th>
                        <th>Video Actor</th>
                        <th>Video Price</th>
                        <th>Puchase</th>                    
                    </tr>
                    @if(count($videos) > 0)
                        @foreach($videos as $video)
                            <tr>
                                <td>{{$video->id}}</td>
                                <td>{{$video->title}}</td>
                                <td>{{$video->description}}</td>
                                <td>{{$video->author}}</td>
                                <td>{{$video->price}}</td>
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
                                        <input type="hidden" name="item_name" value="{{$video->title}}" />
                                        <input type="hidden" name="amount" value="{{$video->price}}" />
                                        <input type="hidden" name="shipping" value="0" />
                                        <input type="hidden" name="no_note" value="1" />

                                        <input type="hidden" name="return" value="{{url('/')}}" />
                                        <input type="hidden" name="cancel" value="{{url('/')}}" />
                                        <input type="hidden" name="notify_url" value="{{ config('paypalipn.sanbox.ipnnotifyurl') }}" />
                                        
                                        <!-- Custom value you want to send and process back in the IPN -->
                                        <!-- <input type="hidden" name="custom" value="{{$video->id}}" /> -->

                                        <input type="hidden" name="custom" value="{{ json_encode(['reference_id' => $video->id, 'reference_type' => 'video']) }}" />

                                        <button type="submit" class="btn btn-primary">Purchase Video</button>
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
