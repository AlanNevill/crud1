# DeviceId.TAB.sql
# For mySQL

delimiter //
drop table if exists DeviceId //

CREATE TABLE DeviceId(
	DeviceId			varchar(20)		NOT NULL	primary key,
	DeviceDesc			varchar(50)		null,
	UserAgentString		varchar(250)	NOT NULL
    )
//


delimiter ;
