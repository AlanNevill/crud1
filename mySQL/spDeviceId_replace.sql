# spDeviceId_replace.sql

delimiter //

drop procedure if exists spDeviceId_replace //

CREATE PROCEDURE spDeviceId_replace (
		DeviceId			varchar(20),
		UserAgentString		varchar(250)
	)
    MODIFIES SQL DATA
	begin
    
		replace INTO DeviceId(	DeviceId,
                                UserAgentString)
						values(	DeviceId,
								UserAgentString);
	end
//

delimiter ;