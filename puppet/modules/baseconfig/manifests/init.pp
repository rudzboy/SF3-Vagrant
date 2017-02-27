class baseconfig {
    file { "/etc/apt/sources.list":
        source  => "puppet:///modules/baseconfig/sources.list"
    }
    
    exec { 'apt-get update':
        command => "/usr/bin/apt-get update",
        require => File['/etc/apt/sources.list']

    }
}