@servers(['localhost' => '127.0.0.1'])

@setup
$repo              = 'git@github.com:lionslair/urlhub.git';
$defaultBranch     = 'master';
$env_file          = getcwd() . '/.env';
$release_directory = getcwd() . '/releases';
$current           = getcwd() . '/current';
$release           = date('YmdHis');
$current_release   = $release_directory . '/' . $release;
$storage           = getcwd(). '/storage';
$cwd               = getcwd();
$chmods            = ['storage', 'public'];
$user              = 'nathanr';
$group             = 'nathanr';
$keep              = 3;
$pushover_user     = 'uiZzjkVgRD8zzuUDHnWVAPLnVgusyu';
$pushover_token    = 'adcjmiis4t9buh3uq95zcqx8nnokz1';

@endsetup

@macro('deploy', ['on' => 'localhost'])
git
run_composer
compile_assets
{{--download_assets--}}
{{--browsershot_fonts--}}
symlinks
permissions
extra_permissions
database_updates
cache
cleanup
daemons
@endmacro

@task('git')
echo "Release Directory will be {{ $current_release }}";
chown -R {{ $user }}:{{ $group }} {{ $release_directory }}
[ -d {{ $release_directory }} ] || mkdir {{ $release_directory }};
cd {{ $release_directory }};

@if($branch)
    git clone --depth 1 {{ $repo }} -b {{ $branch }} {{ $release }} || exit 1;
@else
    git clone --depth 1 {{ $repo }} -b {{ $defaultBranch }} {{ $release }} || exit 1;
@endif
echo "Repository cloned {{ $current_release }}";
@endtask

@task('run_composer')
echo "Start composer dependencies install";
cd {{ $current_release }}
php /usr/local/bin/composer install --no-interaction --no-progress --prefer-dist
echo "Finish composer dependencies install";
@endtask

@task('compile_assets')
cd {{ $current_release }};

pnpm install
echo "Node dependencies installed.";

pnpm run build
echo "Ran pnpm run build tasks.";

rm -rf node_modules;
echo "Removed the node modules directory";
@endtask

@task('download_assets')
cd {{ $current_release }}

@if($frontendUrl)
    echo "Download zip"
    wget -q {{ $frontendUrl }}/public.zip

    rm -rf public;

    echo "Extract the zip file"
    unzip public.zip

    rm public.zip;
@endif
@endtask

@task('browsershot_fonts')
cd {{ $current_release }}

echo "Populate the fonts for browsershot"
php artisan app:populate-browsershot-fonts

@endtask

@task('symlinks')
cd {{ $current_release }}

echo "Copy the release storage to storage";
cp -r {{ $current_release }}/storage {{ $cwd }}
echo "Remove the repo version of the storage folder";
rm -rf {{ $current_release }}/storage
ln -nfs {{ $storage }} storage;
echo "Storage now symlinked to current release";

echo "Symlink environment file.";
ln -nfs {{ $env_file }} .env

rm {{ $current }};
ln -nfs {{ $current_release }} {{ $current }};
echo "Symlink the current release.";

echo "Make Storage public";
php artisan storage:link
@endtask

@task('permissions')
@foreach ($chmods as $directory)
    chmod -R 755 {{ $current_release }}/{{ $directory }};
    chmod -R g+s {{ $current_release }}/{{ $directory }};
    chown -R {{ $user }}:{{ $group }} {{ $current_release }}/{{ $directory }}
    echo "Permissions updated for {{ $directory }}";
@endforeach
@endtask

@task('extra_permissions')
cd {{ $storage }}
chmod -R 777 logs
echo "Extra Permissions updated";
@endtask

@task('database_updates')
cd {{ $current_release }}
php artisan migrate --force
echo "Ran the database migrations";

php artisan migrate --path=database/migrations/restructure --force
echo "Ran the RESTRUCTURE database migrations";
@endtask

@task('cache')
cd {{ $current_release }}
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan event:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
@endtask

@task('cleanup')
purging=$(ls -dt {{ $release_directory }}/* | tail -n +{{ $keep + 1 }});
echo "pruging {{ $purging }}"
if [ "$purging" != "" ]; then
echo Purging old releases: $purging;
rm -rf $purging;
else
echo "No releases found for purging at this time";
fi
@endtask

@task('daemons')
cd {{ $current_release }}
{{--    php artisan websockets:restart--}}
{{--php artisan horizon:terminate--}}
{{--php artisan queue:restart--}}
@endtask

@error
echo "TASK FAILED: ". $task;
curl --data "user={{ $pushover_user }}" --data "token={{ $pushover_token }}" --data "title=Envoy deploy FAILED --data "message=Envoy deployment FAILED for urlhub. Died at task $task  Release folder was: {{ $current_release }}" "https://api.pushover.net/1/messages.json";
{{--@slack('https://hooks.slack.com/services/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', '#devops', "Envoy deployment FAILED for Project Starter. Died at task $task | Release folder was: " . $current_release);--}}
exit;
@enderror

@finished
curl --data "user={{ $pushover_user }}" --data "token={{ $pushover_token }}" --data "title=Wedding Website Deployment" --data "message=Envoy task {{ $task }} ran on rzepeckisrvr.com  Release folder was: {{ $current_release }}" "https://api.pushover.net/1/messages.json";
{{--@slack('https://hooks.slack.com/services/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', '#devops', "Envoy deployed Project Starter. Release folder was: " . $current_release);--}}
echo "Deployment Complete";
@endfinished
