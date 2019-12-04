@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card text-white bg-dark">
                <div class="card-header">
                    <nav id="navigation" class="nav nav-pills flex-column flex-sm-row" role="tablist">
                        <a class="flex-sm-fill text-sm-center nav-link active" href="#items" id="items-tab" aria-controls="customers" data-toggle="pill" role="tab" aria-selected="true">Items</a>
                        <a class="flex-sm-fill text-sm-center nav-link" href="#create" id="create-tab" aria-controls="create" data-toggle="pill" role="tab">Create</a>
                        <a class="flex-sm-fill text-sm-center nav-link" href="#viewEdit" aria-controls="viewEdit" data-toggle="pill" role="tab">View/Edit</a>
                    </nav>
                </div>

                <div class="card-body tab-content">
                    <div class="tab-pane fade show active" id="items" role="tabpanel" aria-labelledby="items-tab">
                        <div class="row">
                            <div class="col-sm-3">
                                <make-select :makes="{{ $makes->toJson() }}"></make-select>
                            </div>
                            <div class="col-sm-9">
                                <search-element :filters="{
                                'Model': 'model',
                                'Key Type': {'item': 'name'}
                                }"></search-element>
                                <search-wrapper :resources="{{ $items->toJson() }}" :filter="'model'">
                                    <template v-slot:default="slotProps">
                                        <div class="card-columns">
                                            <item-card v-for="item in slotProps.results" :item="item" :key="item.id"></item-card>
                                        </div>
                                    </template>
                                </search-wrapper>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="create" role="tabpanel" aria-labelledby="create-tab">
                        <item-create-table :itemtypes="{{ $itemTypes->toJson() }}" :makeslist="{{ $makesVehicles->toJson() }}"></item-create-table>
                    </div>

                    <div class="tab-pane fade" id="viewEdit" role="tabpanel" aria-labelledby="view-tab">
                        <item-view-edit-table :itemtypes="{{ $itemTypes->toJson() }}" :makeslist="{{ $makesVehicles->toJson() }}"></item-view-edit-table>
                    </div>
                </div>
            </div>
            <customer-remove-modal></customer-remove-modal>
        </div>
    </div>
</div>
@endsection
