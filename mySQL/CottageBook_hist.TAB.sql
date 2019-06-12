# CottageBook_hist.TAB.sql
# For mySQL

delimiter //
drop table if exists CottageBook_hist //

CREATE TABLE CottageBook_hist(
	SeqNum			int unsigned	not null 	AUTO_INCREMENT primary key,
    histDate		datetime		not null	DEFAULT CURRENT_TIMESTAMP,
    crud			char(1)			NOT NULL	check (crud in ('I','U','D')),
	IdNum			int unsigned	NOT NULL,	
	DateSat			date			NOT NULL,
	CottageNum		smallint		NOT NULL,
	FirstNight		date			not null,
    LastNight		date			not null,
    BookingStatus	char(1)			null,
    BookingRef		char(4)			null,
    Rental			decimal(8,2)	not null	default 0,
    Notes			varchar(100)	null
    
    )
//

drop trigger if exists CottageBook_after_delete//

CREATE TRIGGER CottageBook_after_delete
AFTER DELETE
   ON CottageBook FOR EACH ROW
BEGIN
   -- Insert record into history table
   INSERT INTO CottageBook_hist
	   (crud			,
		IdNum			,	
		DateSat			,
		CottageNum		,
		FirstNight		,
		LastNight		,
        BookingStatus	,
        BookingRef		,
        Rental			,
		Notes)
   VALUES
	   ( 'D',
		OLD.IdNum			,	
		OLD.DateSat			,
		OLD.CottageNum		,
		OLD.FirstNight		,
		OLD.LastNight		,
        OLD.BookingStatus	,
        OLD.BookingRef		,
        OLD.Rental			,
		OLD.Notes );
END;
 //


drop trigger if exists CottageBook_after_insert//

CREATE TRIGGER CottageBook_after_insert
AFTER INSERT
   ON CottageBook FOR EACH ROW
BEGIN
   -- Insert record into history table
   INSERT INTO CottageBook_hist
	   (crud			,
		IdNum			,	
		DateSat			,
		CottageNum		,
		FirstNight		,
		LastNight		,
        BookingStatus	,
        BookingRef		,
        Rental			,
		Notes)
   VALUES
	   ( 'I',
		NEW.IdNum			,	
		NEW.DateSat			,
		NEW.CottageNum		,
		NEW.FirstNight		,
		NEW.LastNight		,
        NEW.BookingStatus	,
        NEW.BookingRef		,
        NEW.Rental			,
		NEW.Notes );
END;
//

drop trigger if exists CottageBook_after_update//

CREATE TRIGGER CottageBook_after_update
AFTER UPDATE
   ON CottageBook FOR EACH ROW
BEGIN
   -- Insert record into history table
   INSERT INTO CottageBook_hist
	   (crud			,
		IdNum			,	
		DateSat			,
		CottageNum		,
		FirstNight		,
		LastNight		,
        BookingStatus	,
        BookingRef		,
        Rental			,
		Notes)
   VALUES
	   ( 'U',
		NEW.IdNum			,	
		NEW.DateSat			,
		NEW.CottageNum		,
		NEW.FirstNight		,
		NEW.LastNight		,
        NEW.BookingStatus	,
        NEW.BookingRef		,
        NEW.Rental			,
		NEW.Notes );
END;

//


