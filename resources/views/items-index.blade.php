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
                                <td><button class="btn btn-primary">Buy Item</button></td>
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
