# CottageBook_hist.TRG.sql

delimiter //

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
		Notes	
		)
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
		OLD.Notes
		)
	;
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
		Notes
		)
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
		NEW.Notes
		)
	;
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
		Notes
		)
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
		NEW.Notes
		)
	;
END;

//


