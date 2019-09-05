<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Authentication | {{ env('APP_NAME') }}</title>
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('mekaeils-package/css/style.css') }}">
    <!-- //end inject -->
    <link rel="shortcut icon" href="../../images/favicon.png" />

    @yield('header')
    
</head>

<body>
    
  <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth">
                <div class="row w-100">
                <div class="col-lg-4 mx-auto">
                    
                    @include('mekaeils-package.layouts.alert')

                    <div class="auth-form-light text-left p-5">
                    <a href="{{ url('/') }}" class="brand-logo">
                        <img src="{{ config('laravel_user_management.logo_url') }}">
                    </a>
                    <br>
                    <br>

