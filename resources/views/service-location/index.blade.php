@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card text-white bg-dark">
                <div class="card-header">
                    <nav id="navigation" class="nav nav-pills flex-column flex-sm-row" role="tablist">
                        <a class="flex-sm-fill text-sm-center nav-link active" href="#service-locations" id="service-locations-tab" aria-controls="service-locations" data-toggle="pill" role="tab" aria-selected="true">Service Locations</a>
                        <a class="flex-sm-fill text-sm-center nav-link" href="#create" id="create-tab" aria-controls="create" data-toggle="pill" role="tab">Create</a>
                        <a class="flex-sm-fill text-sm-center nav-link" href="#viewEdit" aria-controls="viewEdit" data-toggle="pill" role="tab">View/Edit</a>
                    </nav>
                </div>

                <div class="card-body tab-content">
                    <div class="tab-pane fade show active" id="service-locations" role="tabpanel" aria-labelledby="service-locations-tab">
                        <search-element :filters="{
                                'Name': 'name',
                                'Address': 'address'
                                }"></search-element>
                        <search-wrapper :resources="{{ $locations->toJson() }}" :filter="'name'">
                            <template v-slot:default="slotProps">
                                <div class="card-columns">
                                    <service-location-card v-for="serviceLocation in slotProps.results" :service_location="serviceLocation" :key="serviceLocation.id"></service-location-card>
                                </div>
                            </template>
                        </search-wrapper>
                    </div>

                    <div class="tab-pane fade" id="create" role="tabpanel" aria-labelledby="create-tab">
                        <service-location-create-table :states="{{ $states }}"></service-location-create-table>
                    </div>

                    <div class="tab-pane fade" id="viewEdit" role="tabpanel" aria-labelledby="view-tab">
                        <service-location-view-edit-table :states="{{ $states }}"></service-location-view-edit-table>
                    </div>
                </div>
            </div>
            <service-location-remove-modal></service-location-remove-modal>
        </div>
    </div>
</div>
@endsection
