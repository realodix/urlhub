<footer class="mt-5 py-3">
  <div class="container">
    <div class="row">
      <div class="col align-self-start">
        <a class="text-decoration-none text-body" href="https://github.com/realodix/urlhub">{{appName()}}</a> <small>{{config('app.version')}}</small>
        <span>&copy; 2018-present <a class="text-decoration-none text-body" href="https://github.com/realodix">Budi Hermawan</a></span>
      </div>
      <div class="col align-self-end text-end">
        <span>@lang('Powered by')</span>
        <a class="text-decoration-none text-body" href="https://github.com/laravel/framework/releases/tag/v{{ App::VERSION() }}" target="_blank" title="@lang('Laravel v'.App::VERSION().' (release notes)')" data-toggle="tooltip">Laravel<small> v{{ App::VERSION() }}</small></a>
      </div>
    </div>
  </div>
</footer>
