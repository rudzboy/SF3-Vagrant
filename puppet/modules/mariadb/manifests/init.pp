class mariadb {
    package { ['mariadb-server']:
        ensure => present
    }
    
    service { 'mysql':
        ensure  => running,
        require => Package['mariadb-server']
    }
    
    exec { 'set-mysql-password':
        unless  => "mysqladmin -u${mysql_user} -p${mysql_password} status",
        command => "mysqladmin -u${mysql_user} password ${mysql_password}",
        path    => ['/bin', '/usr/bin'],
        require => Service['mysql'];
    }
}