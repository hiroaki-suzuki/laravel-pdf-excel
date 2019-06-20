# -*- mode: ruby -*-
# vi: set ft=ruby :

require 'json'
require 'yaml'

VAGRANTFILE_API_VERSION ||= "2"
confDir = $confDir ||= File.expand_path("vendor/laravel/homestead", File.dirname(__FILE__))

homesteadYamlPath = File.expand_path("Homestead.yaml", File.dirname(__FILE__))
homesteadJsonPath = File.expand_path("Homestead.json", File.dirname(__FILE__))
afterScriptPath = "after.sh"
aliasesPath = "aliases"

require File.expand_path(confDir + '/scripts/homestead.rb')

Vagrant.require_version '>= 1.9.0'

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
    if File.exist? aliasesPath then
        config.vm.provision "file", source: aliasesPath, destination: "/tmp/bash_aliases"
        config.vm.provision "shell" do |s|
            s.inline = "awk '{ sub(\"\r$\", \"\"); print }' /tmp/bash_aliases > /home/vagrant/.bash_aliases"
        end
    end

    if File.exist? homesteadYamlPath then
        settings = YAML::load(File.read(homesteadYamlPath))
    elsif File.exist? homesteadJsonPath then
        settings = JSON.parse(File.read(homesteadJsonPath))
    else
        abort "Homestead settings file not found in #{confDir}"
    end

    Homestead.configure(config, settings)

    if File.exist? afterScriptPath then
        config.vm.provision "shell", path: afterScriptPath, privileged: false, keep_color: true
    end

    if defined? VagrantPlugins::HostsUpdater
        config.hostsupdater.aliases = settings['sites'].map { |site| site['map'] }
    end

    config.vm.provision "shell", inline: <<-SHELL
        # install wkhtmltopdf
        if ! builtin command -v wkhtmltopdf > /dev/null ; then
            echo "install wkhtmltopdf"
            cd /tmp
            wget https://downloads.wkhtmltopdf.org/0.12/0.12.5/wkhtmltox_0.12.5-1.bionic_amd64.deb
            dpkg -i wkhtmltox_0.12.5-1.bionic_amd64.deb
        fi

        # install japanese font
        if [ ! -e /usr/share/fonts/IPAexfont00301 ]; then
            echo "install japanese font"
            cd /usr/local/src/
            wget https://oscdl.ipa.go.jp/IPAexfont/IPAexfont00301.zip
            unzip IPAexfont00301.zip
            mv IPAexfont00301/ /usr/share/fonts
        fi

        # set xdebug
        if ! grep -q "xdebug.remote_autostart = 1" /etc/php/7.2/fpm/conf.d/20-xdebug.ini; then
            echo "set xdebug"
            echo "xdebug.remote_autostart = 1" >> /etc/php/7.2/fpm/conf.d/20-xdebug.ini

            /etc/init.d/php7.2-fpm restart
        fi
    SHELL
end
