stage { 'pre':
    before => Stage['main']
}

class { 'baseconfig':
    stage => 'pre'
}

$fresh_environment = 'vagrant'
$mysql_user='root'
$mysql_password='sf3fresh'
$cron_user='vagrant'
$logrotate_dir = '/etc/logrotate.d'
$ssl_cert_dir = '/etc/ssl/localcerts'
$ssl_cert_name = 'ssl-cert-sf3fresh.crt'

include baseconfig, apache2, php5, mariadb, elkdev
