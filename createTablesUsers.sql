DROP TABLE IF EXISTS users;
CREATE TABLE users
(
  id              smallint unsigned NOT NULL auto_increment,
  name            varchar(20) NOT NULL,
  pass            varchar(255) NOT NULL, 
  groupId        TINYINT(1) NOT NULL DEFAULT '1' COMMENT 'Идентификатор группы',   
  
  PRIMARY KEY     (id)
);