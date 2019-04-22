# -*- mode: ruby -*-
# vi: set ft=ruby :

require 'yaml'

VAGRANTFILE_API_VERSION ||= "2"
confDir = $confDir ||= File.expand_path(File.dirname(__FILE__))

homesteadYamlPath = confDir + "/Homestead.yaml"
customizationScriptPath = confDir + "/user-customizations.sh"
aliasesPath = confDir + "/.homestead/resources/aliases"
require File.expand_path(File.dirname(__FILE__) + '/.homestead/scripts/homestead.rb')

Vagrant.require_version '>= 2.2.0'

recommendation_to_install = []

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

	if Vagrant.has_plugin?('vagrant-notify')
	    config.vm.provision :shell, inline: <<-EOM
        	sudo DEBIAN_FRONTEND=noninteractive apt-get update -q -y
        	sudo DEBIAN_FRONTEND=noninteractive apt-get install -q -y ruby-full
        	tr -d '\015' </tmp/bash_aliases > /home/vagrant/.bash_aliases
        EOM
    else
    	recommendation_to_install.push 'vagrant-notify'
	end

    if File.exist? aliasesPath then
        config.vm.provision "file", source: aliasesPath, destination: "/tmp/bash_aliases"
        config.vm.provision "shell" do |s|
            s.inline = "awk '{ sub(\"\r$\", \"\"); print }' /tmp/bash_aliases > /home/vagrant/.bash_aliases && chown vagrant:vagrant /home/vagrant/.bash_aliases"
        end
    end

    if File.exist? homesteadYamlPath then
        settings = YAML::load(File.read(homesteadYamlPath))
    else
        abort "Homestead settings file not found in #{confDir}"
    end

    Homestead.configure(config, settings)

    if File.exist? customizationScriptPath then
        config.vm.provision "shell", path: customizationScriptPath, privileged: false, keep_color: true
    end

    if Vagrant.has_plugin?('vagrant-hostsupdater')
        config.hostsupdater.aliases = settings['sites'].map { |site| site['map'] }
    elsif Vagrant.has_plugin?('vagrant-hostmanager')
        config.hostmanager.enabled = true
        config.hostmanager.manage_host = true
        config.hostmanager.aliases = settings['sites'].map { |site| site['map'] }
    else
    	recommendation_to_install.push 'vagrant-hostmanager'
    end

    config.trigger.after [:provision, :up] do |trigger|
    	exists = File.exist?('.env')
    	trigger.name = "Checking if .env file exist (if not creating)"
    	trigger.info = exists ? ".env already exists" : ".env was created"
    	trigger.ruby do |env,machine|
			unless exists
				File.write ".env", <<~CONTENT
					# This file was automatic generated from Vagrantfile
					# If you need to regenerate it, delete and run vagrant provision
					DB_TYPE=pdo.mysql
					DB_HOST=localhost
					DB_USER=homestead
					DB_PASS=secret
					DB_PCONNECT=false
					DB_NAME=impresscms
					DB_CHARSET=utf8
					DB_COLLATION=utf8_general_ci
					DB_PREFIX=icms
					DB_SALT=homestead
					URL=http://impresscms.test
				CONTENT
			end
    	end
    end

	unless recommendation_to_install.empty?
		config.trigger.after [:provision, :up],
			name: "Collecting what vagrant plugins you should install",
			warn: "We recommend to install these vagrant plugins to have full experience and rerun provision: #{recommendation_to_install.join(' ')}"
	end

end