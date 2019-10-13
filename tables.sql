create table 164011_users_v5(
username VARCHAR(30),
password VARCHAR(20),
email VARCHAR(30),
fullname VARCHAR(30),
PRIMARY KEY (username));

create table 164011_news_v5(
username VARCHAR(30),
content VARCHAR(500),
image VARCHAR(500),
ts timestamp default now(),
score integer default 0,
ranking integer default 0,
id integer AUTO_INCREMENT,
PRIMARY KEY (id));

create table 164011_comments_v5(
username VARCHAR(30),
newsid integer,
content VARCHAR(500),
ts timestamp default now(),
id integer AUTO_INCREMENT,
PRIMARY KEY (id));

create table 164011_score_v5(
username VARCHAR(30),
newsid integer,
score integer default 0,
ts timestamp default now(),
id integer AUTO_INCREMENT,
PRIMARY KEY (id));

