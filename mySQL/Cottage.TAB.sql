# Cottage.TAB.sql
# For mySQL

delimiter //
drop table if exists Cottage //

CREATE TABLE Cottage(
	CottageNum            INT 		    	nOT NULL		primary key,
	CottageName		      varchar(50) 	not NULL  	DEFAULT '',
	BookingSource			char(1)		not null	default '',
	BookingSourceRef	varchar(50)		not null	default ''
    )
//

INSERT INTO Cottage (CottageNum, CottageName)
VALUES 
	(1, "Cornflower"),
	(2, "Cowslip"),
	(3, "Meadowsweet")

delimiter ;
