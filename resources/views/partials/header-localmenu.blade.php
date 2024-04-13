<nav class="bg-uh-bg-1 pt-1">
    <div class="hidden sm:flex max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 croll-smooth hover:scroll-auto">
        <a href="{{route('dashboard')}}"
            class="mr-8 py-3 font-semibold hover:text-uh-blue-2 transition duration-100 ease-in-out border-b-2 border-transparent
                {{(request()->route()->getName() === 'dashboard') ?
                'text-uh-blue-2 !border-orange-500' :
                'text-slate-600 hover:border-slate-300'}}"
        >
            @svg('icon-dashboard', 'mr-1')
            <span class="">{{__('Dashboard')}}</span>
        </a>

        @role('admin')
            <a href="{{route('dashboard.allurl')}}"
                class="mr-8 py-3 font-semibold hover:text-uh-blue-2 transition duration-100 ease-in-out border-b-2 border-transparent
                    {{(request()->route()->getName() === 'dashboard.allurl') ?
                    'text-uh-blue-2 !border-orange-500' :
                    'text-slate-600 hover:border-slate-300'}}"
            >
                @svg('icon-link', 'mr-1')
                <span class="">{{__('URL List')}}</span>
            </a>
            <a href="{{route('user.index')}}"
                class="mr-8 py-3 font-semibold hover:text-uh-blue-2 transition duration-100 ease-in-out border-b-2 border-transparent
                    {{(request()->route()->getName() === 'user.index') ?
                    'text-uh-blue-2 !border-orange-500' :
                    'text-slate-600 hover:border-slate-300'}}"
            >
                @svg('icon-users', 'mr-1')
                <span class="">{{__('User List')}}</span>
            </a>
            <a href="{{route('dashboard.about')}}"
                class="mr-8 py-3 font-semibold hover:text-uh-blue-2 transition duration-100 ease-in-out border-b-2 border-transparent
                    {{(request()->route()->getName() === 'dashboard.about') ?
                    'text-uh-blue-2 !border-orange-500' :
                    'text-slate-600 hover:border-slate-300'}}"
            >
                @svg('icon-about-system', 'mr-1')
                <span class="">{{__('About')}}</span>
            </a>
        @endrole
    </div>
</nav>
