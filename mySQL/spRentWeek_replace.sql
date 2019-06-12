# spRentWeek_replace.sql

delimiter //


drop procedure if exists spRentWeek_replace //

CREATE PROCEDURE spRentWeek_replace (
		DateSat		date,
		WeekNum		integer,
		CottageNum	integer,
		Rent		decimal(8,2)
	)
    MODIFIES SQL DATA
	begin
    
		replace INTO RentWeek(	DateSat,
								WeekNum,
                                CottageNum,
                                Rent)
						values(	DateSat,
								WeekNum, 
								CottageNum, 
								Rent);
	end
//

