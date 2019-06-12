# spProcessLog_updAlarmRaised.sql

delimiter //

drop procedure if exists spProcessLog_updAlarmRaised //

CREATE PROCEDURE spProcessLog_updAlarmRaised (
		_maxIdNum		integer
	)
    MODIFIES SQL DATA
	begin
		update ProcessLog
		set AlarmRaised = 'Y'
			where 	MessType	<>'I' 
			and 	AlarmRaised	='N'
			and 	IdNum		<=_maxIdNum;
	end
//
