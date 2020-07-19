# CottageBook.TAB.sql
# For MariaDb

###############################################################################
# Remember to recreate the history triggers afer dropping the CottageBook table
###############################################################################

delimiter //
drop table if exists CottageBook //

CREATE TABLE CottageBook(
	IdNum				int UNSIGNED		NOT NULL	AUTO_INCREMENT primary key,
	`DateSat`			DATE				NOT NULL,
	CottageNum			SMALLINT			NOT NULL,
	FirstNight			DATE				not null,
    LastNight			DATE				not null,
	BookingStatus		char(1)				not null	default 'P',
    BookingRef			VARCHAR(5)			not NULL	DEFAULT '*****',
    Rental				decimal(8,2)		not null	default 0,
    Notes				varchar(100)		not NULL	DEFAULT '',
 
   CONSTRAINT CHK_BookingStatus CHECK (BookingStatus IN ('C','P')),
	CONSTRAINT UC_CottageFirstNight UNIQUE (CottageNum,  FirstNight),
	CONSTRAINT UC_CottageLastNight 	UNIQUE (CottageNum,  LastNight)
    )
//

delimiter ;
