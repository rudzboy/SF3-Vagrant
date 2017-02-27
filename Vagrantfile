VAGRANTFILE_API_VERSION = "2"
Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
    config.vm.box = "debian/contrib-jessie64"
    config.vm.synced_folder ".", "/vagrant", disabled: true
    config.vm.synced_folder ".", "/var/www/sf3fresh/current"
    #config.winnfsd.logging="on"
    
    config.hostmanager.enabled = true
    config.hostmanager.manage_host = true
    
    config.vm.define "site" do |site|
        site.vm.hostname = "sf3fresh.local"
        site.vm.network "private_network", ip: "10.0.0.60"
        site.vm.provider "virtualbox" do |v|
            v.name = "sf3fresh"
            v.cpus = 2
            v.memory = 4092
        end
        
        site.vm.provision :shell, path: "vagrant_bootstrap.sh"
        
        site.vm.provision :puppet do |puppet|
           puppet.manifests_path = 'puppet/manifests'
           puppet.manifest_file = 'vagrant.pp'
           puppet.module_path = 'puppet/modules'
        end
    end
end
