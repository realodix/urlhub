@extends('user-management.master')

@section('header')
    @parent

@endsection

@section('breadcrumb')
    @include('mekaeils-package.layouts.breadcrumb',[
        'pageTitle' => 'Departments',
        'lists' => [
            [
                'link'  => '#',
                'name'  => 'User Management',
            ],
            [
                'link'  => '#',
                'name'  => 'Departments',
            ]
        ]
    ])
@endsection

@section('content')
<div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('admin.user_management.department.create') }}" class="btn btn-outline-primary btn-icon-text float-right btn-newInList">
                        <i class="mdi mdi-shape-rectangle-plus btn-icon-prepend"></i>
                        new department   
                    </a>
                    <h4 class="card-title">List of the departments</h4>
                    
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>
                                    #
                                </th>
                                <th>
                                    Title
                                </th>
                                <th>
                                    Parent
                                </th>
                                <th>
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($departments as $item)
                                <tr>
                                    <td>
                                    {{ $item->id }}
                                    </td>
                                    <td>
                                        {{ $item->title }}
                                    </td>
                                    <td>
                                        {{  $item->parent ? $item->parent->title : '----' }}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.user_management.department.edit', $item->id) }}" class="btn btn-outline-dark btn-sm">Edit</a>

                                        <form action="{{ route('admin.user_management.department.delete', $item->id) }}" method="post" class="inline-block">
                                            @method('DELETE')
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('footer')
    @parent
    
@endsection