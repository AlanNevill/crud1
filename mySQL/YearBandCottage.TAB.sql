# YearBandCottage.TAB.sql
# For mySQL

delimiter //
drop table if exists YearBandCottage //

CREATE TABLE YearBandCottage(
	DateYear		smallint		NOT NULL,
	Band			smallint		not null,
    CottageNum		smallint		not null,
    RentDay			decimal(8,2)	not null	default 0,
    RentWeek		decimal(8,2)	not null	default 0,
    constraint PK_YearBandCottage Primary key (DateYear, Band, CottageNum)
    )
//

insert into YearBandCottage(DateYear, Band, CottageNum, RentDay, RentWeek) values 
(2019,1,1,95,665),		(2019,1,2,95,665),		(2019,1,3,115,805),
(2019,2,1,135,945),		(2019,2,2,135,945),		(2019,2,3,160,1120),
(2019,3,1,160,1120),	(2019,3,2,160,1120),	(2019,3,3,210,1470)

 //

