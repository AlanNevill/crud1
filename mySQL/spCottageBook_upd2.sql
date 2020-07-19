# spCottageBook_upd2.sql using new schema

delimiter //

drop procedure if exists spCottageBook_upd2 //

CREATE PROCEDURE spCottageBook_upd2 (
	_IdNum				int unsigned,
	_BookingName		VARCHAR(50),
    _BookingStatus		char(1),
    _Rental				DECIMAL(8,2),
    _Notes				VARCHAR(100),
	_BookingSource		CHAR(1),
	_ExternalReference	VARCHAR(20),
	_ContactEmail		varchar(50),
	_NumAdults			TINYINT UNSIGNED,
	_NumChildren		TINYINT UNSIGNED,
	_Children			VARCHAR(50),
	_NumDogs			TINYINT unsigned
    )

    MODIFIES SQL DATA
	begin
		update CottageBook
			set BookingName			= _BookingName,
				BookingStatus 		= _BookingStatus,
				Rental				= _Rental,
				Notes				= _Notes,
				BookingSource		= _BookingSource,
				ExternalReference	= _ExternalReference,
				ContactEmail		= _ContactEmail,
				NumAdults			= _NumAdults,
				NumChildren			= _NumChildren,
				Children			= _Children,
				NumDogs				= _NumDogs
		where idNum = _idNum;
	end
//

delimiter ;