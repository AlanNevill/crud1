# RentWeek.TAB.sql
# For mySQL

delimiter //
drop table if exists RentWeek //

CREATE TABLE RentWeek(
	IdNum			int unsigned	NOT NULL	AUTO_INCREMENT primary key,
	DateSat			date			NOT NULL,
	WeekNum			int				NOT NULL, 	
	CottageNum		int				NOT NULL,
	Rent			decimal(8,2)	not null	default 0,
    CONSTRAINT UC_RentWeek UNIQUE (DateSat,WeekNum,CottageNum)
    )
//

insert into RentWeek(DateSat, WeekNum, CottageNum, Rent) values 
	(20180721,29,1,101.11),
    (20180721,29,2,201.22),
    (20180721,29,3,303.33),
	(20180728,30,1,102.22),
    (20180728,30,2,202.22),
    (20180728,30,3,302.22)

 //

