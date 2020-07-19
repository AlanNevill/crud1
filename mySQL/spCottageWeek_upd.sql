# spCottageWeek_upd.sql

delimiter //

drop procedure if exists spCottageWeek_upd //

CREATE PROCEDURE spCottageWeek_upd (
		_DateSat			VARCHAR(10),
		_CottageNum		int,
		_ShortBreaks	bool,
		_RentDay			DECIMAL(8,2),
		_RentWeek 		DECIMAL(8,2)
	)
   MODIFIES SQL DATA
	begin
		update CottageWeek
		SET	bShortBreaksAllowed	= _ShortBreaks,
				RentDay					= _RentDay,
				RentWeek					= _RentWeek
		WHERE `DateSat` 	= _DateSat
		AND	CottageNum	= _CottageNum;
	end
//
delimiter ;