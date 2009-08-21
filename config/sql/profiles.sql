drop table if exists profiles;
create table profiles (
	id		integer not null auto_increment primary key,
        user_id         integer not null,

        public_mail     varchar(128) default null,
        homepage        varchar(128) default null,

        description     text default null,
        signature       varchar(128) default null,

        image           varchar(256) default null,
        icon            varchar(256) default null,

        icq             varchar(12) default null,
        msn             varchar(128) default null,
        yahoo           varchar(128) default null,

        index(user_id)
);