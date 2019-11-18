@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card text-white bg-dark">
                <div class="card-header">Create a new order</div>

                <div class="card-body">
                    <search-vin></search-vin>
                    <vin-decoder-result></vin-decoder-result>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
