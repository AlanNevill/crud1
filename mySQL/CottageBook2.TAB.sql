# CottageBook2.TAB.sql
# For MariaDb

delimiter //
drop table if exists CottageBook2 //

CREATE TABLE CottageBook2(
	IdNum				int UNSIGNED		NOT NULL	AUTO_INCREMENT primary key,
	`DateSat`			DATE				NOT NULL,
	CottageNum			SMALLINT			NOT NULL,
	FirstNight			DATE				not null,
    LastNight			DATE				not null,
    NumNights 			tinyint UNSIGNED 	as (datediff(LastNight, FirstNight)+1) VIRTUAL,
    BookingName 		VARCHAR(50) 		NOT NULL 	DEFAULT '',
	BookingStatus		char(1)				not null	default 'P',
    BookingRef			VARCHAR(5)			not NULL	DEFAULT '*****',
    Rental				decimal(8,2)		not null	default 0,
    Notes				varchar(100)		not NULL	DEFAULT '',
    BookingSource 		CHAR(1) 			NOT NULL 	DEFAULT 'O',
	ExternalReference	VARCHAR(20) 		not NULL 	DEFAULT '',
	ContactEmail 		varchar(50) 		not NULL 	DEFAULT '' ,
	NumAdults 			tinyint UNSIGNED	not NULL 	DEFAULT 0,
	NumChildren 		tinyint UNSIGNED 	not NULL 	DEFAULT 0 ,
	Children 			varchar(50) 		not NULL 	DEFAULT '',
	NumDogs 			tinyint UNSIGNED 	not NULL 	DEFAULT 0,
 
    CONSTRAINT UC_CottageFirstNight UNIQUE (CottageNum,  FirstNight),
	CONSTRAINT UC_CottageLastNight 	UNIQUE (CottageNum,  LastNight)
    )
//

insert into CottageBook2(DateSat, CottageNum, FirstNight, LastNight, BookingStatus) values 
(2019-08-03,1,2019-08-03,2019-08-06,'C'),
(2019-08-03,2,2019-08-03,2019-08-09,'C'),
(2019-08-03,3,2019-08-03,2019-08-09,'P'),
(2019-08-10,1,2019-08-10,2019-08-16,'P'),
(2019-08-10,2,2019-08-10,2019-08-16,'P'),
(2019-08-10,3,2019-08-10,2019-08-16,'P'),
(2019-08-17,1,2019-08-17,2019-08-23,'P'),
(2019-08-03,1,2019-08-07,2019-08-09,'P'),
(2019-08-24,2,2019-08-24,2019-08-30,'P'),
(2019-01-05,1,2019-01-11,2019-01-11,'P'),
(2019-01-05,1,2019-01-07,2019-01-07,'P'),
(2019-01-05,1,2019-01-08,2019-01-08,'P'),
(2019-01-05,1,2019-01-09,2019-01-09,'P'),
(2019-01-05,1,2019-01-10,2019-01-10,'P'),
(2019-01-05,1,2019-01-05,2019-01-05,'P'),
(2019-01-12,3,2019-01-12,2019-01-12,'P'),
(2019-01-12,1,2019-01-13,2019-01-13,'P'),
(2019-01-12,1,2019-01-14,2019-01-14,'P'),
(2019-01-12,1,2019-01-15,2019-01-15,'P'),
(2019-01-12,1,2019-01-16,2019-01-18,'P'),
(2019-01-12,1,2019-01-12,2019-01-12,'P'),
(2019-01-05,1,2019-01-06,2019-01-06,'P'),
(2019-01-12,3,2019-01-13,2019-01-13,'P'),
(2019-01-12,3,2019-01-14,2019-01-14,'P'),
(2019-01-19,3,2019-01-19,2019-01-20,'P'),
(2019-01-12,3,2019-01-15,2019-01-15,'P'),
(2019-01-19,3,2019-01-21,2019-01-22,'P'),
(2019-01-19,3,2019-01-23,2019-01-23,'P'),
(2019-02-16,3,2019-02-21,2019-02-22,'P'),
(2019-02-16,3,2019-02-16,2019-02-16,'P'),
(2019-02-23,3,2019-02-25,2019-02-26,'P'),
(2019-02-23,3,2019-02-27,2019-03-01,'P'),
(2019-02-23,3,2019-02-23,2019-02-23,'P'),
(2019-01-26,2,2019-01-31,2019-02-01,'P'),
(2019-01-19,3,2019-01-24,2019-01-24,'P'),
(2019-01-19,3,2019-01-25,2019-01-25,'P'),
(2019-03-30,2,2019-03-30,2019-04-05,'P'),
(2019-03-30,3,2019-03-30,2019-03-30,'P'),
(2019-03-16,3,2019-03-16,2019-03-16,'P'),
(2019-04-27,3,2019-04-27,2019-04-27,'P'),
(2019-04-06,3,2019-04-06,2019-04-06,'P'),
(2019-03-30,3,2019-03-31,2019-03-31,'P'),
(2019-03-30,3,2019-04-01,2019-04-01,'P'),
(2019-01-12,2,2019-01-12,2019-01-12,'P'),
(2019-01-05,2,2019-01-05,2019-01-06,'P'),
(2019-01-05,2,2019-01-07,2019-01-08,'P')
 //

delimiter ;
