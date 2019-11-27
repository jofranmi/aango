@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card text-white bg-dark">
                <div class="card-header">
                    <nav id="navigation" class="nav nav-pills flex-column flex-sm-row" role="tablist">
                        <a class="flex-sm-fill text-sm-center nav-link active" href="#customers" id="customers-tab" aria-controls="customers" data-toggle="pill" role="tab" aria-selected="true">Users</a>
                        <a class="flex-sm-fill text-sm-center nav-link" href="#create" id="create-tab" aria-controls="create" data-toggle="pill" role="tab">Create</a>
                        <a class="flex-sm-fill text-sm-center nav-link" href="#viewEdit" aria-controls="viewEdit" data-toggle="pill" role="tab">View/Edit</a>
                    </nav>
                </div>

                <div class="card-body tab-content">
                    <div class="tab-pane fade show active" id="customers" role="tabpanel" aria-labelledby="customers-tab">
                        <search-element :filters="{'Name': 'name'}"></search-element>
                        <search-wrapper :resources="{{ $users->toJson() }}" :filter="'name'">
                            <template v-slot:default="slotProps">
                                <div class="card-columns">
                                    <user-card v-for="user in slotProps.results" :user="user" :key="user.id"></user-card>
                                </div>
                            </template>
                        </search-wrapper>
                    </div>

                    <div class="tab-pane fade" id="create" role="tabpanel" aria-labelledby="create-tab">
                        <user-create-table :customers="{{ $customers->toJson() }}" :usertypes="{{ $userTypes->toJson() }}"></user-create-table>
                    </div>

                    <div class="tab-pane fade" id="viewEdit" role="tabpanel" aria-labelledby="view-tab">
                        <user-view-edit-table></user-view-edit-table>
                    </div>
                </div>
            </div>
            <customer-remove-modal></customer-remove-modal>
        </div>
    </div>
</div>
@endsection
