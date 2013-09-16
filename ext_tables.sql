#
# Table structure for table 'tx_imagetooltips_tooltip'
#
CREATE TABLE tx_imagetooltips_tooltip (
  uid int(11) NOT NULL auto_increment,
  pid int(11) DEFAULT '0' NOT NULL,
  tstamp int(11) unsigned DEFAULT '0' NOT NULL,
  crdate int(11) unsigned DEFAULT '0' NOT NULL,
  cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
  deleted tinyint(3) unsigned DEFAULT '0' NOT NULL,
  hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
  description varchar(255) DEFAULT '' NOT NULL,
  related_content_element int(11) unsigned DEFAULT '0' NOT NULL,
  related_image_position int(11) unsigned DEFAULT '0' NOT NULL,
  tooltip_text text,
  PRIMARY KEY (uid),
  KEY parent (pid)
);