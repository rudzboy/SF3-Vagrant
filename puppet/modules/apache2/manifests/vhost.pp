define apache2::vhost() {
    file {
        "/etc/apache2/sites-available/${name}":
            source  => "puppet:///modules/apache2/${fresh_environment}/${name}",
            require => Package['apache2'],
            notify  => Service['apache2'];

        "/etc/apache2/sites-enabled/${name}":
            ensure => link,
            target => "/etc/apache2/sites-available/${name}",
            notify => Service['apache2'];
    }
}