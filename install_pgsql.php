<?php
		if($installer_include != "yes")
		{
		die("this file can only be included");
		}
      	 $conn = new PDO("pgsql:host=$db_host;dbname=$db_name;user=$db_user;password=$db_pass");
      	 print_r($conn);
        if (!($conn)) {
            $template->assign("errortext", "Database connection could not be established. <br>Please check if database exists and check if login credentials are correct.");
            $template->display("error.tpl");
            die();
        }
        // Create MySQL Tables
$table1 = $conn->query("CREATE TABLE chat (
  ID serial,
  time varchar(255) NOT NULL DEFAULT '',
  ufrom varchar(255) NOT NULL DEFAULT '',
  ufrom_id int,
  userto varchar(255) NOT NULL DEFAULT '',
  userto_id int,
  text varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (ID)
);");

$table2 = $conn->query("CREATE TABLE company (
  ID serial,
  company varchar(255) NOT NULL,
  contact varchar(255) NOT NULL,
  email varchar(255) NOT NULL,
  phone varchar(64) NOT NULL,
  mobile varchar(64) NOT NULL,
  url varchar(255) NOT NULL,
  address varchar(255) NOT NULL,
  zip varchar(16) NOT NULL,
  city varchar(255) NOT NULL,
  country varchar(255) NOT NULL,
  state varchar(255) NOT NULL,
  \"desc\" text NOT NULL,
  PRIMARY KEY (ID)
);");

$table3 = $conn->query("CREATE TABLE company_assigned (
  ID serial,
  \"user\" int,
  company int,
  PRIMARY KEY (ID)
);");

$table4 = $conn->query("CREATE TABLE customers_assigned (
  ID serial,
  customer int,
  project int,
  PRIMARY KEY (ID),
  UNIQUE (ID)
);");

$table5 = $conn->query("CREATE TABLE files (
  ID serial,
  name varchar(255) NOT NULL DEFAULT '',
  \"desc\" varchar(255) NOT NULL DEFAULT '',
  project int,
  milestone int,
  \"user\" int,
  added varchar(255) NOT NULL DEFAULT '',
  datei varchar(255) NOT NULL DEFAULT '',
  type varchar(255) NOT NULL DEFAULT '',
  title varchar(255) NOT NULL DEFAULT '',
  folder int,
  visible text NOT NULL,
  PRIMARY KEY (ID)
);");

$table6 = $conn->query("CREATE TABLE files_attached (
  ID serial,
  file int,
  message int,
  PRIMARY KEY (ID)
);");

$table7 = $conn->query("CREATE TABLE log (
  ID serial,
  \"user\" int,
  name varchar(255) NOT NULL DEFAULT '',
  type varchar(255) NOT NULL DEFAULT '',
  action int,
  project int,
  datum varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (ID)
);");

$table8 = $conn->query("CREATE TABLE messages (
  ID serial,
  project int,
  title varchar(255) NOT NULL DEFAULT '',
  text text NOT NULL,
  tags varchar(255) NOT NULL,
  posted varchar(255) NOT NULL DEFAULT '',
  \"user\" int,
  name varchar(255) NOT NULL DEFAULT '',
  replyto int,
  milestone int,
  PRIMARY KEY (ID)
);
");

$table9 = $conn->query("CREATE TABLE milestones (
  ID serial,
  project int,
  name varchar(255) NOT NULL DEFAULT '',
  \"desc\" text NOT NULL,
  start varchar(255) NOT NULL DEFAULT '',
  \"end\" varchar(255) NOT NULL DEFAULT '',
  status smallint,
  PRIMARY KEY (ID)
);");

$table10 = $conn->query("CREATE TABLE milestones_assigned (
  ID serial,
  \"user\" int,
  milestone int,
  PRIMARY KEY (ID)
);");

$table11 = $conn->query("CREATE TABLE projectfolders (
  ID serial,
  parent int,
  project int,
  name text NOT NULL,
  description varchar(255) NOT NULL,
  visible text NOT NULL,
  PRIMARY KEY (ID)
);");

$table12 = $conn->query("CREATE TABLE projekte (
  ID serial,
  name varchar(255) NOT NULL DEFAULT '',
  \"desc\" text NOT NULL,
  start varchar(255) NOT NULL DEFAULT '',
  \"end\" varchar(255) NOT NULL DEFAULT '',
  status smallint,
  budget float NOT NULL DEFAULT '0',
  PRIMARY KEY (ID)
);");

$table13 = $conn->query("CREATE TABLE roles (
  ID serial,
  name varchar(255) NOT NULL,
  projects text NOT NULL,
  tasks text NOT NULL,
  milestones text NOT NULL,
  messages text NOT NULL,
  files text NOT NULL,
  chat text NOT NULL,
  timetracker text NOT NULL,
  admin text NOT NULL,
  PRIMARY KEY (ID)
);");

$table14 = $conn->query("CREATE TABLE roles_assigned (
  ID serial,
  \"user\" int,
  role int,
  PRIMARY KEY (ID)
);");

$table15 = $conn->query("CREATE TABLE settings (
  ID serial,
  settingsKey varchar(50) NOT NULL,
  settingsValue varchar(50) NOT NULL,
  PRIMARY KEY (ID)
);");

$table16 = $conn->query("CREATE TABLE tasklist (
  ID serial,
  project int,
  name varchar(255) NOT NULL DEFAULT '',
  \"desc\" text NOT NULL,
  start varchar(255) NOT NULL DEFAULT '',
  status smallint,
  access smallint,
  milestone int,
  PRIMARY KEY (ID)
);");

$table17 = $conn->query("CREATE TABLE tasks (
  ID serial,
  start varchar(255) NOT NULL DEFAULT '',
  \"end\" varchar(255) NOT NULL DEFAULT '',
  title varchar(255) NOT NULL DEFAULT '',
  text text NOT NULL,
  liste int,
  status smallint,
  project int,
  PRIMARY KEY (ID)
);");

$table18 = $conn->query("CREATE TABLE tasks_assigned (
  ID serial,
  \"user\" int,
  task int,
  PRIMARY KEY (ID)
);");

$table19 = $conn->query("CREATE TABLE timetracker (
  ID serial,
  \"user\" int,
  project int,
  task int,
  comment text NOT NULL,
  started varchar(255) NOT NULL DEFAULT '',
  ended varchar(255) NOT NULL DEFAULT '',
  hours float NOT NULL DEFAULT '0',
  pstatus smallint,
  PRIMARY KEY (ID)
);");

$table20 = $conn->query("CREATE TABLE \"user\" (
  ID serial,
  name varchar(255) DEFAULT '',
  email varchar(255) DEFAULT '',
  tel1 varchar(255) DEFAULT NULL,
  tel2 varchar(255) DEFAULT NULL,
  pass varchar(255) DEFAULT '',
  company varchar(255) DEFAULT '',
  lastlogin varchar(255) DEFAULT '',
  zip varchar(10) DEFAULT NULL,
  gender char(1) DEFAULT '',
  url varchar(255) DEFAULT '',
  adress varchar(255) DEFAULT '',
  adress2 varchar(255) DEFAULT '',
  state varchar(255) DEFAULT '',
  country varchar(255) DEFAULT '',
  tags varchar(255) DEFAULT '',
  locale varchar(6) DEFAULT '',
  avatar varchar(255) DEFAULT '',
  rate varchar(10) DEFAULT NULL,
  PRIMARY KEY (ID)
);");

        // Checks if tables could be created
        if (!$table1 or !$table2 or !$table3 or !$table4 or !$table5 or !$table6 or !$table7 or !$table8 or !$table9 or !$table10 or !$table11 or !$table12 or !$table13 or !$table14 or !$table15 or !$table16 or !$table17 or !$table18 or !$table19 or !$table20) {
            $template->assign("errortext", "Error: Tables could not be created.");
            $template->display("error.tpl");
            die();
        }

?>
