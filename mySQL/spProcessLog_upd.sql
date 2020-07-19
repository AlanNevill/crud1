# spProcessLog_upd.sql

delimiter //

drop procedure if exists spProcessLog_upd //

CREATE PROCEDURE spProcessLog_upd (
		_IdNum			INTEGER,
		_UserId			VARCHAR(100),
		_Application	VARCHAR(100),
		_Remarks			VARCHAR(2000),
		_AlarmRaised 	CHAR(1)
	)
   MODIFIES SQL DATA
	begin
		update ProcessLog
		SET	UserId		= _UserId,
				Application	= _Application,
				Remarks		= _Remarks,
				AlarmRaised	= _AlarmRaised
		where IdNum = _IdNum;
	end
//
delimiter ;