# spDeviceId_insert.sql

delimiter //

drop procedure if exists spDeviceId_insert //

CREATE PROCEDURE spDeviceId_insert (
		_DeviceId			varchar(20),
		_UserAgentString	varchar(250)
	)
    MODIFIES SQL DATA
	begin
		IF NOT EXISTS(SELECT DeviceId FROM DeviceId WHERE DeviceId = _DeviceId) 
		THEN
			INSERT INTO DeviceId  ( DeviceId,  UserAgentString) 
							VALUES(_DeviceId, _UserAgentString);
		END IF;
	end
//

delimiter ;