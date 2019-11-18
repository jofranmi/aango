@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card text-white bg-dark">
                <div class="card-header">View orders</div>

                <div class="card-body">
                    @if (Gate::forUser(Auth::user())->allows('customer'))
                        <orders-filter :items="{{ $items->toJson() }}" :status="{{ $status->toJson() }}" :authorized="false"></orders-filter>
                        <orders-table :data="{{ $orders->toJson() }}" :authorized="false"></orders-table>
                    @else
                        <orders-filter :customers="{{ $customers->toJson() }}" :items="{{ $items->toJson() }}" :status="{{ $status->toJson() }}" :authorized="true"></orders-filter>
                        <orders-table :data="{{ $orders->toJson() }}" :authorized="true"></orders-table>
                    @endif
                    {{--<search-element :filters="{
                    'Name': {'user': 'name'},
                    'Id': 'id'
                    }"></search-element>

                    <search-wrapper :resources="{{ $orders->toJson() }}">
                        <template v-slot:default="slotProps">
                            <orders-table :data="slotProps.results"></orders-table>
                        </template>
                    </search-wrapper>--}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
