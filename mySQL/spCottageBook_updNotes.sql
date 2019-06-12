# spCottageBook_updNotes.sql

delimiter //


drop procedure if exists spCottageBook_updNotes //

CREATE PROCEDURE spCottageBook_updNotes (
	_IdNum	int unsigned,
    _Notes	varchar(100)
	)
    MODIFIES SQL DATA
	begin
		update CottageBook
			set Notes = _Notes
		where idNum = _idNum;
	end
//

delimiter ;