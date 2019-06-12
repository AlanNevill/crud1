# spProcessLog_Insert.sql

delimiter //


drop procedure if exists spProcessLog_Insert //

CREATE PROCEDURE spProcessLog_Insert (
		_MessType		char(1),
		_Application	varchar(100),
		_Routine		varchar(100),
		_UserId			varchar(100),
		_ErrorMess		varchar(2000),
		_Remarks		varchar(2000)
	)
    MODIFIES SQL DATA
	begin
		INSERT INTO ProcessLog  (MessType,
								Application, 
								Routine, 
								UserId,
								ErrorMess,
								Remarks
							) 
						VALUES (_MessType,
								_Application, 
								_Routine, 
								_UserId,
								_ErrorMess,
								_Remarks
							);
	end
//
