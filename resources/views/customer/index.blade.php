@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <search-element></search-element>

                    <search-wrapper :resources="{{ $customers->toJson() }}" :filter="'name'">
                        <template v-slot:default="slotProps">
                            <div class="card-columns">
                                <customer-card v-for="customer in slotProps.results" :customer="customer" :key="customer.id"></customer-card>
                            </div>
                        </template>
                    </search-wrapper>
                    {{--@foreach($customers as $customer)
                            <customer-card :customer="{{ $customer->toJson() }}"></customer-card>
                    @endforeach--}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
