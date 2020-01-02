@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card text-white bg-dark">
                <div class="card-header">View orders</div>

                <div class="card-body">
                    @can ('customer')
                        <orders-filter :items="{{ $items->toJson() }}" :status="{{ $statuses->toJson() }}" :authorized="false"></orders-filter>
                        <orders-table :data="{{ $orders->toJson() }}" :authorized="false"></orders-table>
                    @else
                        <orders-filter :customers="{{ $customers->toJson() }}" :items="{{ $items->toJson() }}" :status="{{ $statuses->toJson() }}" :authorized="true"></orders-filter>
                        <orders-table :data="{{ $orders->toJson() }}" :statuses="{{ $statuses->toJson() }}" :authorized="true"></orders-table>
                    @endcan
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
            <order-view-modal></order-view-modal>
        </div>
    </div>
</div>
@endsection
