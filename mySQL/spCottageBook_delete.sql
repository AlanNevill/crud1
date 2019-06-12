# spCottageBook_delete.sql

delimiter //


drop procedure if exists spCottageBook_delete //

CREATE PROCEDURE spCottageBook_delete (
					_IdNum			int unsigned
	)
    MODIFIES SQL DATA
	begin
		delete from CottageBook	where IdNum = _IdNum;
	end
//

delimiter ;