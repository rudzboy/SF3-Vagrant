class apache2 {
    package { ['apache2', 'libapache2-mod-fastcgi']:
        ensure => present
    }
    
    service { 'apache2':
        ensure  => running,
        require => [Package['apache2'], File['/etc/apache2/mods-enabled/proxy_fcgi.load']]
    }

    file { '/etc/apache2/sites-enabled/000-default.conf':
        ensure  => absent,
        require => [Package['apache2'], File['/etc/apache2/mods-enabled/proxy_fcgi.load']],
        notify => Service['apache2'];
    }
    
    file { $ssl_cert_dir:
        ensure => "directory",
        mode => '755',
        owner => 'root',
        group => 'root',
    }
    
    file { 'ssl_cert_file':
        ensure => "file",
        mode => '600',
        owner => 'root',
        group => 'root',
        path => "${ssl_cert_dir}/${ssl_cert_name}",
        source => "puppet:///modules/apache2/${vegas_environment}/${ssl_cert_name}",
    }
    
    if $ssl_key_name != undef {
        file { 'ssl_key_file':
            ensure => "file",
            mode => '440',
            owner => 'root',
            group => 'root',
            path => "${ssl_cert_dir}/${ssl_key_name}",
            source => "puppet:///modules/apache2/${vegas_environment}/${ssl_key_name}",
        }
    }
    
    exec { 'a2enmod ssl':
        path => '/sbin:/bin:/usr/sbin:/usr/bin',
        user => 'root',
        notify => Service[apache2],
    }

    apache2::module { ['rewrite.load']: }
    apache2::module { ['proxy.load']: }
    apache2::module { ['proxy_fcgi.load']: }
    apache2::vhost { ['sf3fresh.conf']: }
}
