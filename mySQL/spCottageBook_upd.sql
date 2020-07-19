# spCottageBook_upd.sql

delimiter //

drop procedure if exists spCottageBook_upd //

CREATE PROCEDURE spCottageBook_upd (
	 _IdNum				int unsigned,
    _BookingStatus	char(1),
    _Notes				VARCHAR(100)
    )

   MODIFIES SQL DATA
	begin
		update CottageBook
			set 				BookingStatus 	= _BookingStatus,
								Notes				= _Notes
		where idNum = _idNum;
	end
//

delimiter ;