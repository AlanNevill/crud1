# spProcessLog_Insert2.sql

delimiter //


drop procedure if exists spProcessLog_Insert2 //

CREATE PROCEDURE spProcessLog_Insert2 (
		_MessType		char(1),
		_Application	varchar(100),
		_Routine		varchar(100),
		_ErrorMess		varchar(2000),
		_Remarks		varchar(2000)
	)
    MODIFIES SQL DATA
	begin
    
		declare myuser varchar(100);
        select user() into myuser;
        
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
                                myuser,
								_ErrorMess,
								_Remarks
							);
	end
//

delimiter ;
