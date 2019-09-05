@extends('user-management.master')

@section('header')
    @parent

@endsection

@section('breadcrumb')
    @include('mekaeils-package.layouts.breadcrumb',[
        'pageTitle' => 'Permissions',
        'lists' => [
            [
                'link'  => '#',
                'name'  => 'User Management',
            ],
            [
                'link'  => '#',
                'name'  => 'Permissions',
            ]
        ]
    ])
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('admin.user_management.permission.create') }}" class="btn btn-outline-primary btn-icon-text float-right btn-newInList">
                        <i class="mdi mdi-settings btn-icon-prepend"></i>
                        new permission   
                    </a>            
                    <h4 class="card-title">List of the permissions</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>
                                    #
                                </th>
                                <th>
                                    Permission Name
                                </th>
                                <th>
                                    Title
                                </th>
                                <th>
                                    Guard name
                                </th>
                                <th>
                                    description
                                </th>
                                <th>
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permissions as $item)
                                <tr>
                                    <td>
                                        {{ $item->id }}
                                    </td>
                                    <td>
                                        {{ $item->name }}
                                    </td>
                                    <td>
                                        {{ $item->title ??'--'  }}
                                    </td>
                                    <td>
                                        {{ $item->guard_name }}
                                    </td>
                                    <td>
                                        {{ $item->description }}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.user_management.permission.edit', $item->id) }}" class="btn btn-outline-dark btn-sm">Edit</a>

                                        <form action="{{ route('admin.user_management.permission.delete', $item->id) }}" method="post" class="inline-block">
                                            @method('DELETE')
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>

                    <div class="pagination">
                        {{ $permissions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('footer')
    @parent
    
@endsection