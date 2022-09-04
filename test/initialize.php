<?php
define("TEST",true);
define("DEVELOPMENT",false);
define("PRODUCTION",false);

define("ATK14_DOCUMENT_ROOT",__DIR__."/");
define("ATK14_USE_SMARTY3",true);
define("ATK14_SMARTY_DEFAULT_MODIFIER","h");
define("ATK14_SMARTY_DIR_PERMS",0771);
define("ATK14_SMARTY_FILE_PERMS",0644);
define("ATK14_SMARTY_FORCE_COMPILE",true);
define("TEMP",__DIR__ . "/tmp/");
define("ATK14_NON_SSL_PORT",80);
define("ATK14_SSL_PORT",443);

define("PUPIQ_API_KEY","101.DemoApiKeyForAccountWithLimitedFunctions");

require(__DIR__ . "/../vendor/smarty/smarty/libs/Smarty.class.php");
require(__DIR__ . "/../vendor/smarty/smarty/libs/SmartyBC.class.php");
require(__DIR__ . "/../vendor/atk14/core/src/atk14_smarty_base_v3.php");

require(__DIR__ . "/../vendor/autoload.php");

class_autoload(__DIR__ . "/app/models/");

require(__DIR__ . "/../vendor/atk14/core/src/atk14_smarty_utils.php");
require(__DIR__ . "/../src/app/models/editable_fragment.php");
require(__DIR__ . "/../src/app/models/editable_fragment_history.php");

$HTTP_REQUEST = new HTTPRequest();
$HTTP_RESPONSE = new HTTPResponse();

$dbmole = PgMole::GetInstance("default");

function &dbmole_connection(&$dbmole){
	$out = pg_connect("dbname=test host=localhost port=5432 user=test password=test");
	return $out;
}

// Creating testing structures
$dbmole = PgMole::GetInstance();
$dbmole->doQuery("
DROP TABLE IF EXISTS editable_fragment_history;
DROP SEQUENCE IF EXISTS seq_editable_fragment_history;
DROP TABLE IF EXISTS editable_fragments;
DROP SEQUENCE IF EXISTS seq_editable_fragments;
DROP TABLE IF EXISTS users;
DROP SEQUENCE IF EXISTS seq_users;
DROP TABLE IF EXISTS translations;
DROP SEQUENCE IF EXISTS seq_translations;
");
$dbmole->doQuery("
CREATE SEQUENCE seq_users START WITH 11;
CREATE TABLE users(
	id INT PRIMARY KEY DEFAULT NEXTVAL('seq_users'),
	login VARCHAR(255) NOT NULL UNIQUE,
	password VARCHAR(255),
	firstname VARCHAR(255),
	lastname VARCHAR(255),
	email VARCHAR(255),
	is_admin BOOLEAN NOT NULL DEFAULT 'f',
	active BOOLEAN NOT NULL DEFAULT 't',
	--
	registered_from_ip_addr VARCHAR(255),
	--
	updated_by_user_id INT,
	--
	created_at TIMESTAMP NOT NULL DEFAULT NOW(),
	updated_at TIMESTAMP,
	--
	CONSTRAINT fk_users_upd_users FOREIGN KEY (updated_by_user_id) REFERENCES users
);

INSERT INTO users (id,login,password,firstname,lastname,is_admin) VALUES(1,'admin','!to_be_replaced_by_a_hashed_password!','Charlie','Root','t');
");
$dbmole->doQuery("
CREATE SEQUENCE seq_translations;
CREATE TABLE translations (
	id INT PRIMARY KEY DEFAULT NEXTVAL('seq_translations'),
	table_name VARCHAR(255) NOT NULL, -- products, cards, articles...
	record_id INT NOT NULL,
	key VARCHAR(255) NOT NULL, -- title, body....
	lang CHAR(2) NOT NULL, -- en, cs, sk...
	body TEXT,
	--
	created_at TIMESTAMP NOT NULL DEFAULT NOW(),
	updated_at TIMESTAMP,
	--
	CONSTRAINT unq_translations UNIQUE(table_name,record_id,key,lang)
);
");

$dbmole->doQuery(file_get_contents(__DIR__."/../src/db/migrations/0144_editable_fragments.sql"));

$ATK14_GLOBAL = new Atk14Global();
