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
		Notes			,
        BookingSource	,
        ExternalReference,
        ContactEmail	,
        NumAdults		,
		NumChildren		,
		Children		,
		NumDogs	
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
		OLD.Notes			,
        OLD.BookingSource	,
        OLD.ExternalReference,
        OLD.ContactEmail	,
        OLD.NumAdults		,
		OLD.NumChildren		,
		OLD.Children		,
		OLD.NumDogs	
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
		Notes			,
        BookingSource	,
        ExternalReference,
        ContactEmail	,
        NumAdults		,
		NumChildren		,
		Children		,
		NumDogs	
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
		NEW.Notes 			,
        NEW.BookingSource	,
        NEW.ExternalReference,
        NEW.ContactEmail	,
        NEW.NumAdults		,
		NEW.NumChildren		,
		NEW.Children		,
		NEW.NumDogs	
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
		Notes			,
        BookingSource	,
        ExternalReference,
        ContactEmail	,
        NumAdults		,
		NumChildren		,
		Children		,
		NumDogs	
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
		NEW.Notes 			,
        NEW.BookingSource	,
        NEW.ExternalReference,
        NEW.ContactEmail	,
        NEW.NumAdults		,
		NEW.NumChildren		,
		NEW.Children		,
		NEW.NumDogs	
		)
	;
END;

//


