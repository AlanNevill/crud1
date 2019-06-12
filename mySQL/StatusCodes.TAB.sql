# StatusCodes.TAB.sql
# For mySQL

delimiter //
drop table if exists StatusCodes //

CREATE TABLE StatusCodes(
	IdNum				int unsigned	NOT NULL	AUTO_INCREMENT primary key,
	Category			varchar(20)		not null,
	StatusCode			char(1)			NOT NULL,
	ShortDescription	varchar(15)		not null,
    LongDescription		varchar(100)	not null,
	CONSTRAINT UC_StatusCodes 	UNIQUE (Category,  StatusCode)
    )
//

insert into StatusCodes(Category, StatusCode, ShortDescription, LongDescription) values 
('booking','P','Provisional','Booking has not been confirmed'),	
('booking','C','Confirmed','Booking is confirmed')
//

delimiter ;
