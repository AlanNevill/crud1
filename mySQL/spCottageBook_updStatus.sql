# spCottageBook_updStatus.sql

delimiter //


drop procedure if exists spCottageBook_updStatus //

CREATE PROCEDURE spCottageBook_updStatus (
	_IdNum				int unsigned,
    _BookingStatus		char(1)
	)
    MODIFIES SQL DATA
	begin
		update CottageBook
			set BookingStatus = _BookingStatus
		where idNum = _idNum;
	end
//

delimiter ;