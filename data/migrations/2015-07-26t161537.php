<?php

class Migration20150726161537
{

    public function up(Zend\ServiceManager\ServiceManager $serviceManager)
    {
        $sql = "CREATE TABLE `calendar42_calendar` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `settings` text DEFAULT NULL,
  `updated` DATETIME NOT NULL,
  `created` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        $serviceManager->get('Db\Master')->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);

        $sql = "CREATE TABLE `calendar42_event` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `calendarId` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `start` DATETIME NOT NULL,
  `end` DATETIME DEFAULT NULL,
  `allDay` enum('true','false') DEFAULT 'false',
  `location` varchar(255),
  `info` text DEFAULT NULL,
  `linkId` int(10) unsigned DEFAULT NULL,
  `updated` DATETIME NOT NULL,
  `created` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        $serviceManager->get('Db\Master')->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }

    public function down(Zend\ServiceManager\ServiceManager $serviceManager)
    {
        $sql = "DROP TABLE `calendar42_calendar`";
        $serviceManager->get('Db\Master')->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);

        $sql = "DROP TABLE `calendar42_event`";
        $serviceManager->get('Db\Master')->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }


}
