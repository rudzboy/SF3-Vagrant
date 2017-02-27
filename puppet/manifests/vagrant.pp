stage { 'pre':
    before => Stage['main']
}

class { 'baseconfig':
    stage => 'pre'
}

$vegas_environment = 'vagrant'
$mysql_user='root'
$mysql_password='vegas'
$cron_user='vagrant'
$logrotate_dir = '/etc/logrotate.d'
$ssl_cert_dir = '/etc/ssl/localcerts'
$ssl_cert_name = 'ssl-cert-vegas.crt'

include baseconfig, apache2, php5, mariadb, elkdev
