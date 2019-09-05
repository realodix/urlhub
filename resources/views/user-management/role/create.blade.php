@extends('user-management.master')

@section('header')
    @parent

@endsection

@section('breadcrumb')
    @include('mekaeils-package.layouts.breadcrumb',[
        'pageTitle' => 'Create Role',
        'lists' => [
            [
                'link'  => '#',
                'name'  => 'User Management',
            ],
            [
                'link'  => 'admin.user_management.role.index',
                'name'  => 'Roles',
            ],
            [
                'link'  => '#',
                'name'  => 'New role', 
            ]
        ]
    ])
@endsection

@section('content')

    <form class="forms-sample" method="POST" action="{{ route('admin.user_management.role.store') }}">
        {!! csrf_field() !!}

        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Name like: Admin">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" name="title" class="form-control" id="title" placeholder="Title like: Admin Manager">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="guard_name">guard name</label>
                                <select class="form-control" name="guard_name" id="guard_name">
                                    <option value="web" selected>web</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" name="description" id="description" rows="4"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Permissions</h4>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                @forelse ($permissions as $item)                                
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="checkbox" name="permissions[]" value="{{ $item->name }}" class="form-check-input">
                                            {{ $item->title . ($item->description ? "  [ " . $item->description . " ]" : "")}}
                                            <i class="input-helper"></i>
                                        </label>
                                    </div>
                                @empty
                                    ----
                                @endforelse                          
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 grid-margin stretch-card">
            <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
            <a href="{{ route('admin.user_management.role.index') }}" class="btn btn-light">Cancel</a>
        </div>
    </form>

@endsection


@section('footer')
    @parent
    
@endsection