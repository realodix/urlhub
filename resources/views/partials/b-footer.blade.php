<footer class="mt-4 sm:mt-16 py-4 text-slate-700">
  <hr class="mb-8">
  <div class="main max-w-7xl mx-auto px-6 lg:px-8">
    <div class="flex flex-wrap-reverse">
      <div class="w-full sm:w-1/2">
        <a class="" href="https://github.com/realodix/urlhub">{{appName()}}</a> <small>{{config('app.version')}}</small>
        <span>
          &copy; 2018-present
          <a href="https://github.com/realodix" class="text-decoration-none text-body">Budi Hermawan</a>
        </span>
      </div>
      <div class="w-full sm:w-1/2 sm:text-right">
        <span>@lang('Powered by')</span>
        <a href="https://github.com/laravel/framework/releases/tag/v{{ App::VERSION() }}" target="_blank" title="@lang('Laravel v'.App::VERSION().' (release notes)')"
          class="text-decoration-none text-body"
        >
          Laravel <small>v{{ App::VERSION() }}</small>
        </a>
      </div>
    </div>
  </div>
</footer>
