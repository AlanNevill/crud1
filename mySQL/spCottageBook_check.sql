# spCottageBook_check.sql

delimiter //

drop procedure if exists spCottageBook_check //

CREATE PROCEDURE spCottageBook_check(
	_dateSat		date,
	_cottageNum		smallint,
	_firstNight		date,
    _lastNight		date
	)
    READS SQL DATA
	begin
    
		select count(*) as clashCount from CottageBook
        where 	DateSat = _dateSat
        and		CottageNum = _cottageNum
        and		((_firstNight between FirstNight and LastNight)
        or 		(_lastNight  between FirstNight and LastNight)
        or		(FirstNight between _firstNight and _lastNight) );
        
	end
//

delimiter ;