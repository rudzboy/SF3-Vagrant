class php5 {
    package { [ 'php5',
                'php5-cli',
                'php5-fpm',
                'php5-curl',
                'php5-mysql',
                'php5-gd',
                'php5-intl',
                'php5-xsl',
                'php5-rrd']:
            ensure => present
    }
    
    service { 'php5-fpm':
        ensure  => running,
        require => [Package['php5-fpm'], File['/etc/php5/fpm/pool.d/www.conf']]
    }
    
    file {
        "/etc/php5/fpm/pool.d/www.conf":
            source  => "puppet:///modules/php5/${fresh_environment}/www.conf",
            require => Package['php5-fpm'],
            notify  => Service['php5-fpm'];
    }

    file {
        "/etc/php5/fpm/php.ini":
            source  => "puppet:///modules/php5/${fresh_environment}/fpm/php.ini",
            require => Package['php5-fpm'],
            notify  => Service['php5-fpm'];
    }
}
