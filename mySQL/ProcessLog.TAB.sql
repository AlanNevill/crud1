# ProcessLog.TAB.sql
# For mySQL

delimiter //
drop table if exists ProcessLog//

CREATE TABLE ProcessLog(
	IdNum			int unsigned	NOT NULL	AUTO_INCREMENT primary key,
	MessDateTime	datetime		NOT NULL	DEFAULT CURRENT_TIMESTAMP,
	MessType		char(1)			NOT NULL	check (MessType in ('I','E','W')), 	
	Application		varchar(100)	NOT NULL,
	Routine			varchar(100)	NULL,
	UserId			varchar(100)	NOT NULL,
	ErrorMess		varchar(2000) 	NULL,
	Remarks			varchar(2000) 	NULL,
	AlarmRaised		char(1)			NOT NULL	DEFAULT 'N'
)
//

# check constraint not supported mySQL so define a before insert trigger
drop trigger if exists trg_ProcessLog_insert //

create trigger trg_ProcessLog_insert before insert on ProcessLog
for each row
begin
	declare msg varchar(128);
	if new.MessType not in ('I','E','W') then
		set msg = concat('ProcessLog.MessType not in (I,E,W), value supplied=', new.MessType);
        signal  sqlstate '45000' set message_text = msg;
	end if;
end

//

