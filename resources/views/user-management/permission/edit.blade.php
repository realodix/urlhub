@extends('user-management.master')

@section('header')
    @parent

@endsection

@section('breadcrumb')
    @include('mekaeils-package.layouts.breadcrumb',[
        'pageTitle' => 'Edit Permission',
        'lists' => [
            [
                'link'  => '#',
                'name'  => 'User Management',
            ],
            [
                'link'  => 'admin.user_management.permission.index',
                'name'  => 'Permissions',
            ],
            [
                'link'  => '#',
                'name'  => 'Edit permission', 
            ]
        ]
    ])
@endsection

@section('content')

<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            {{-- <h4 class="card-title">Create new permission</h4> --}}
            <form class="forms-sample" method="POST" action="{{ route('admin.user_management.permission.update', $permission->id) }}">
                {{ method_field('PUT') }}
                {!! csrf_field() !!}

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" id="name" value="{{ $permission->name }}">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" class="form-control" id="title" value="{{ $permission->title }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="module">Module name</label>
                            <input type="text" class="form-control" name="module" id="module" value="{{ $permission->module }}">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="guard_name">guard name</label>
                            <select class="form-control" name="guard_name" id="guard_name">
                                <option selected>web</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" name="description" id="description" rows="4">{{ $permission->description }}</textarea>
                </div>
                <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
                <a href="{{ route('admin.user_management.permission.index') }}" class="btn btn-light">Cancel</a>
            </form>
        </div>
    </div>
</div>

@endsection


@section('footer')
    @parent
    
@endsection