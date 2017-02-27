class app {
    package {'cifs-utils':
        ensure => present
    }
    
    file { 'rrd_dir':
        path => $vegas_rrd_dir,
        ensure => "directory"
    }
    
    mount {'vegas_rrd':
        name => $vegas_rrd_dir,
        ensure => "mounted",
        atboot => "true",
        fstype => "cifs",
        device => $vegas_rrd_device,
        options => "username=nevada,password=nfsen",
        require => [File["rrd_dir"], Package["cifs-utils"]]
    }
}
