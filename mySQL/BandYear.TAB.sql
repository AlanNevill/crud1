# BandYear.TAB.sql
# For mySQL

delimiter //
drop table if exists BandYear
//

CREATE TABLE BandYear(
	BandYear		INT				NOT NULL	,
	CottageNum	INT				NOT NULL ,
	PriceBand	TINYINT			not NULL	,
	RentDay		DECIMAL(8,2) 	NOT NULL DEFAULT 0,
	RentWeek		DECIMAL(8,2) 	NOT NULL DEFAULT 0,
	PRIMARY KEY(BandYear, CottageNum, PriceBand)
    )
//


 //

delimiter ;
