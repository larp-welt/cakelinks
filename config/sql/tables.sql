drop table if exists links;
create table links (
	id		integer not null auto_increment primary key,
	title		varchar(128) not null,
	description	text not null,
	url		varchar(128) not null,
        lng             float default null,
        alt             float default null,
        start           date default null,
        end             date default null,
        user_id         integer not null,
        status          int(4) default 0, 
	created		datetime default null,
	modified	datetime default null,
        hit_count       integer default 0,
        slug            varchar(128) not null,
        parent_id       integer default null,

        index(slug),
        index(status),
        index(created),
        index(modified)
);

drop table if exists tags;
create table tags (
	id		integer not null auto_increment primary key,
	name		varchar(128) not null,
        slug            varchar(128) not null,
        
        index(name),
        index(slug)
);

drop table if exists links_tags;
create table links_tags (
	link_id		integer not null,
	tag_id		integer not null,
        
        index(link_id),
        index(tag_id)
);

drop table if exists hits;
create table hits (
    link_id             integer not null,
    created             datetime default null,
    ip                  int default 0,

    index(link_id),
    index(ip)
);

drop table if exists users;
create table users (
        id              integer not null auto_increment primary key,
        username        varchar(64) not null,
        password        varchar(64) not null,
        email           varchar(64) not null,
        lastlogin       datetime default null,
        group_id        integer,
        slug            varchar(64) not null,
        
        created         datetime default null,
        modified        datetime default null,
        
        disabled        int(1) default 0,

        token           varchar(64),

        index(username),
        index(group_id),
        index(slug),
        index(disabled)
);

drop table if exists groups;
create table groups (
        id              integer not null auto_increment primary key,
        name            varchar(64) not null,
        
        parent_id       integer,
        lft             integer default null,
        rght            integer default null
);

insert into tags (name, slug) values ('<Kein Tag>', 'null'); 
