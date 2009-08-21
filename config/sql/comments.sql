create table comments (
	id		integer not null auto_increment primary key,
	title		varchar(128) not null,
	comment    	text not null,
        user_id         integer not null,
        status          int(4) default 0, 
	created		datetime default null,
	modified	datetime default null,

        parent_id       integer default null,
        parent_model    varchar(32) default null,

        index(parent_id),
        index(parent_model)
);

alter table users add column comment_count integer default 0;
alter table links add column comment_count integer default 0;