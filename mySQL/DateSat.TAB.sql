# DateSat.TAB.sql
# For mySQL

delimiter //
drop table if exists DateSat //

CREATE TABLE DateSat(
	DateSat					date		NOT NULL	primary key,
	bShortBreaksAllowed		bool		not null	default true
    )
//


 //

delimiter ;
