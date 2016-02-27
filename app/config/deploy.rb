role :web, "51.255.49.2"
role :app, "51.255.49.2", :primary => true

set :application, "test"
set :deploy_to, "/web/citope_vet"
set :user, "fodaveg"
set :ssh_options, {:forward_agent => true}
set :shared_children, %w(app/logs)

default_run_options[:shell] = '/bin/bash'

set :scm, :git
set :repository, "git@bitbucket.org:fodaveg/citope_vet.git"
set :deploy_via, :remote_cache
set :branch, "develop"

set :model_manager, "doctrine"

logger.level = Logger::MAX_LEVEL

set :use_sudo, false
set :keep_releases, 5

set :dump_assetic_assets, false
set :normalize_asset_timestamps, false

set :use_composer, true
set :composer_options, "--verbose"

task :do_deploy do
	symfony.composer.install
end

task :quick_deploy do
	capifony_pretty_print "--> Doing quick deploy (updating)"

	if !dry_run
		run "cd #{current_path} ; git fetch 2>&1 ; git checkout origin/master 2>&1 ; php app/console cache:clear --env=#{symfony_env_prod}"
	end
	capifony_puts_ok
end

namespace :composer do
  task :copy_vendors, :except => { :no_release => true } do
    capifony_pretty_print "--> Copy vendor file from previous release"

    run "vendorDir=#{current_path}/vendor; if [ -d $vendorDir ] || [ -h $vendorDir ]; then cp -a $vendorDir #{latest_release}/vendor; fi;"
    capifony_puts_ok
  end
end

after "deploy", "deploy:cleanup"
before 'symfony:composer:install', 'composer:copy_vendors'
before 'symfony:composer:update', 'composer:copy_vendors'