@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>
                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <h2>Order</h2>
                                <div class="table-responsive">
                                    <table class="table table-striped table-sm">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>total</th>
                                            <th>status</th>
                                            <th>Customer</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($order as $objOrder)
                                            <tr>
                                                <td>{{ $objOrder->id }}</td>
                                                <td>{{ $objOrder->total }}</td>
                                                <td>{{ $objOrder->status }}</td>
                                                <td>{{ $objOrder->customers->name }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-6 offset-md-3">
                                    <a href="{{route('payment')}}" class="btn btn-primary btn-block ">Pay with PayMob</a>
{{--                                    <a  href="{{url('order-status') }}/{{ $objOrder->id }}/1" class="btn btn-info btn-block">Cach On Delivery</a>--}}
                                    <a  href="{{route('edit.status', ['order_id' => $objOrder->id, 'status' => 1 ])}}" class="btn btn-info btn-block">Cach On Delivery</a>

                                </div>
                            </div>
                        </div>
                        @endforeach

                        <div class="card">
                            <div class="card-body">
                                <h2>Order Details</h2>
                                <div class="table-responsive">
                                    <table class="table table-striped table-sm">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($order as $orderProducts)
{{--                                            {{ $orderProducts}}--}}
                                        @foreach ($orderProducts->products as $orderProduct)

{{--                                            {{$orderProduct['pivot']['quantity']}}--}}
                                            <tr>
                                                <td>{{$orderProduct['id'] }}</td>
                                                <td>{{ $orderProduct['title']}}</td>
                                                <td>{{ $orderProduct['price'] }}</td>
                                                <td>{{ $orderProduct['pivot']['quantity'] }}</td>
                                            </tr>
                                        @endforeach
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

        </div>
    </div>
    </div>



@endsection
