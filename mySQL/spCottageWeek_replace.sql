# spCottageWeek_replace.sql

delimiter //


drop procedure if exists spCottageWeek_replace //

CREATE PROCEDURE spCottageWeek_replace (
		DateSat		date,
		CottageNum	integer,
		RentDay		decimal(8,2),
        RentWeek	decimal(8,2)
	)
    MODIFIES SQL DATA
	begin
    
		replace INTO CottageWeek(	DateSat,
									CottageNum,
									RentDay,
									RentWeek)
							values(	DateSat,
									CottageNum, 
									RentDay,
									RentWeek);
	end
//

