class elkdev {
    file { "/etc/rsyslog.d/50-default.conf":
        source => "puppet:///modules/elkdev/50-default.conf",
        notify => Service['rsyslog']
    }

    service { "rsyslog":
        ensure => "running"
    }
}