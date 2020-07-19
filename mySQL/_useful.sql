SELECT * FROM ProcessLog ORDER BY 1 DESC;

SELECT * FROM CottageBook WHERE bookingref= '7V6Z';

SELECT * FROM CottageBook_hist WHERE idnum= 171;

ALTER TABLE CottageBook ADD CONSTRAINT CHK_BookingStatus CHECK (BookingStatus IN ('C','P'));

DROP CONSTRAINT CHK_BookingStatus;

ALTER TABLE CottageBook modify BookingStatus CHAR(1) NOT NULL;

UPDATE CottageBook_Test SET BookingStatus = 'W' WHERE IdNum = 1;

SELECT * FROM CottageBook_Test;

SELECT VERSION();

CALL spCottageBook_upd(171, 'W', 'Test with W');


CALL spCottageWeek_upd('2020-07-04',1,TRUE,-100,9500);

select * from CottageWeek where DateSat >= '2020-07-03' and CottageNum = 1 order by 1 ASC;

select * from ProcessLog where MessType ='E' order by IdNum desc LIMIT 100;

select * from ProcessLog where 1=1 order by IdNum DESC;

DELETE from ProcessLog WHERE MessType='I' AND idNum<1000;

SELECT * FROM DeviceId;

SELECT * FROM CottageBook _bck;

INSERT INTO CottageBook SELECT * FROM CottageBook_bck;
