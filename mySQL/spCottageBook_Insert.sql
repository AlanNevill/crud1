# spCottageBook_Insert.sql

delimiter //


drop procedure if exists spCottageBook_Insert //

CREATE PROCEDURE spCottageBook_Insert (
	_DateSat			date,
	_CottageNum			smallint,
	_FirstNight			date,
    _LastNight			date,
    _BookingName		VARCHAR(50),
    _BookingStatus		char(1),
    _BookingRef			char(4),
    _Rental				decimal(8,2),
    _Notes				varchar(100),
    _BookingSource		CHAR(1),
	_ExternalReference	VARCHAR(20),
	_ContactEmail		varchar(50),
	_NumAdults			TINYINT UNSIGNED,
	_NumChildren		TINYINT UNSIGNED,
	_Children			VARCHAR(50),
	_NumDogs			TINYINT UNSIGNED	
	)
	
    MODIFIES SQL DATA
	begin
		insert into CottageBook	(DateSat, 
								CottageNum, 
                                FirstNight, 
                                LastNight,
                                BookingName,
                                BookingStatus,
                                BookingRef,
                                Rental,
                                Notes,
								BookingSource,
								ExternalReference,
								ContactEmail,
								NumAdults,
								NumChildren,
								Children,
								NumDogs
								) 
					values   	(_DateSat, 
								_CottageNum, 
                                _FirstNight, 
                                _LastNight, 
                                _BookingName,
                                _BookingStatus,
                                _BookingRef,
                                _Rental,
                                _Notes,
								_BookingSource,
								_ExternalReference,
								_ContactEmail,
								_NumAdults,
								_NumChildren,
								_Children,
								_NumDogs
								);
		
        select LAST_INSERT_ID() as insertedIdNum;
	end
//

delimiter ;