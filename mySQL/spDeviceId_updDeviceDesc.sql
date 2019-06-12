# spDeviceId_updDeviceDesc.sql

delimiter //

drop procedure if exists spDeviceId_updDeviceDesc //

CREATE PROCEDURE spDeviceId_updDeviceDesc (
		_DeviceId	varchar(20),
		_DeviceDesc	varchar(50)
	)
    MODIFIES SQL DATA
	begin
		update DeviceId set DeviceDesc = _DeviceDesc
        where DeviceId = _DeviceId;
	end
//

delimiter ;