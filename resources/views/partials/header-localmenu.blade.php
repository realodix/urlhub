@php
    $routeName = request()->route()->getName();
@endphp

<nav class="bg-uh-bg-1 pt-1">
    <div class="hidden layout-container sm:flex px-4 sm:px-6 lg:px-8 croll-smooth hover:scroll-auto">
        <a href="{{route('dashboard')}}"
            class="mr-8 py-3 font-semibold hover:text-uh-blue-2 transition duration-100 ease-in-out border-b-2 border-transparent
                {{($routeName === 'dashboard') ?
                'text-uh-blue-2 !border-orange-500' :
                'text-slate-600 hover:border-slate-300'}}"
        >
            @svg('icon-dashboard', 'mr-1')
            <span class="@if ($routeName === 'dashboard') text-black @endif">{{__('Dashboard')}}</span>
        </a>

        @role('admin')
            <a href="{{route('dashboard.allurl')}}"
                class="mr-8 py-3 font-semibold hover:text-uh-blue-2 transition duration-100 ease-in-out border-b-2 border-transparent
                    {{($routeName === 'dashboard.allurl') ?
                    'text-uh-blue-2 !border-orange-500' :
                    'text-slate-600 hover:border-slate-300'}}"
            >
                @svg('icon-link', 'mr-1')
                <span class="@if ($routeName === 'dashboard.allurl') text-black @endif">{{__('URL List')}}</span>
            </a>
            <a href="{{route('user.index')}}"
                class="mr-8 py-3 font-semibold hover:text-uh-blue-2 transition duration-100 ease-in-out border-b-2 border-transparent
                    {{($routeName === 'user.index') ?
                    'text-uh-blue-2 !border-orange-500' :
                    'text-slate-600 hover:border-slate-300'}}"
            >
                @svg('icon-people', 'mr-1')
                <span class="@if ($routeName === 'user.index') text-black @endif">{{__('User List')}}</span>
            </a>
            <a href="{{route('dashboard.about')}}"
                class="mr-8 py-3 font-semibold hover:text-uh-blue-2 transition duration-100 ease-in-out border-b-2 border-transparent
                    {{($routeName === 'dashboard.about') ?
                    'text-uh-blue-2 !border-orange-500' :
                    'text-slate-600 hover:border-slate-300'}}"
            >
                @svg('icon-about-system', 'mr-1')
                <span class="@if ($routeName === 'dashboard.about') text-black @endif">{{__('About')}}</span>
            </a>
        @endrole
    </div>
</nav>
