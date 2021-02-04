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

SELECT * FROM datesat order BY 1;

INSERT INTO CottageBook SELECT * FROM CottageBook_bck;

UPDATE CottageWeek SET RentWeek=99 WHERE DateSat='2021-01-02';

SELECT * FROM CottageWeek WHERE YEAR(DateSat) =2021 ORDER BY 1,2;

INSERT INTO BandYear(BandYear, CottageNum, PriceBand, RentWeek) VALUES 
	(	2021, 1, 1, 595),
	(	2021, 1, 2, 850),
	(	2021, 1, 3, 995),
	(	2021, 2, 1, 665),
	(	2021, 2, 2, 945),
	(	2021, 2, 3, 1120),
	(	2021, 3, 1, 805),
	(	2021, 3, 2, 1120),
	(	2021, 3, 3, 1470);
	
	
SELECT * FROM BandYear;

ALTER table datesat ADD COLUMN PriceBand TINYINT DEFAULT 0;

update DateSat set PriceBand=1 where DateSat='2021-1-2';
update DateSat set PriceBand=1 where DateSat='2021-1-9';
update DateSat set PriceBand=1 where DateSat='2021-1-16';
update DateSat set PriceBand=1 where DateSat='2021-1-23';
update DateSat set PriceBand=1 where DateSat='2021-1-30';
update DateSat set PriceBand=1 where DateSat='2021-2-6';
update DateSat set PriceBand=2 where DateSat='2021-2-13';
update DateSat set PriceBand=1 where DateSat='2021-2-20';
update DateSat set PriceBand=1 where DateSat='2021-2-27';
update DateSat set PriceBand=1 where DateSat='2021-3-6';
update DateSat set PriceBand=1 where DateSat='2021-3-13';
update DateSat set PriceBand=1 where DateSat='2021-3-20';
update DateSat set PriceBand=2 where DateSat='2021-3-27';
update DateSat set PriceBand=3 where DateSat='2021-4-3';
update DateSat set PriceBand=3 where DateSat='2021-4-10';
update DateSat set PriceBand=2 where DateSat='2021-4-17';
update DateSat set PriceBand=1 where DateSat='2021-4-24';
update DateSat set PriceBand=1 where DateSat='2021-5-1';
update DateSat set PriceBand=1 where DateSat='2021-5-8';
update DateSat set PriceBand=1 where DateSat='2021-5-15';
update DateSat set PriceBand=1 where DateSat='2021-5-22';
update DateSat set PriceBand=1 where DateSat='2021-5-29';
update DateSat set PriceBand=1 where DateSat='2021-6-5';
update DateSat set PriceBand=1 where DateSat='2021-6-12';
update DateSat set PriceBand=1 where DateSat='2021-6-19';
update DateSat set PriceBand=1 where DateSat='2021-6-26';
update DateSat set PriceBand=1 where DateSat='2021-7-3';
update DateSat set PriceBand=2 where DateSat='2021-7-10';
update DateSat set PriceBand=2 where DateSat='2021-7-17';
update DateSat set PriceBand=3 where DateSat='2021-7-24';
update DateSat set PriceBand=3 where DateSat='2021-7-31';
update DateSat set PriceBand=3 where DateSat='2021-8-7';
update DateSat set PriceBand=3 where DateSat='2021-8-14';
update DateSat set PriceBand=3 where DateSat='2021-8-21';
update DateSat set PriceBand=2 where DateSat='2021-8-28';
update DateSat set PriceBand=1 where DateSat='2021-9-4';
update DateSat set PriceBand=1 where DateSat='2021-9-11';
update DateSat set PriceBand=1 where DateSat='2021-9-18';
update DateSat set PriceBand=1 where DateSat='2021-9-25';
update DateSat set PriceBand=1 where DateSat='2021-10-2';
update DateSat set PriceBand=1 where DateSat='2021-10-9';
update DateSat set PriceBand=1 where DateSat='2021-10-16';
update DateSat set PriceBand=1 where DateSat='2021-10-23';
update DateSat set PriceBand=1 where DateSat='2021-10-30';
update DateSat set PriceBand=1 where DateSat='2021-11-6';
update DateSat set PriceBand=1 where DateSat='2021-11-13';
update DateSat set PriceBand=1 where DateSat='2021-11-20';
update DateSat set PriceBand=1 where DateSat='2021-11-27';
update DateSat set PriceBand=1 where DateSat='2021-12-4';
update DateSat set PriceBand=2 where DateSat='2021-12-11';
update DateSat set PriceBand=3 where DateSat='2021-12-18';
update DateSat set PriceBand=3 where DateSat='2021-12-25';

SELECT * 
FROM CottageWeek CW
LEFT JOIN DateSat DS 	ON DS.DateSat = CW.DateSat
LEFT JOIN BandYear YB 	ON YB.BandYear = YEAR(DS.DateSat)
								AND YB.CottageNum = CW.CottageNum
								and YB.PriceBand = DS.PriceBand
WHERE YEAR(CW.DateSat) = 2021;

Update CottageWeek CW
LEFT JOIN DateSat DS 	ON DS.DateSat = CW.DateSat
LEFT JOIN BandYear YB 	ON YB.BandYear = YEAR(DS.DateSat)
								AND YB.CottageNum = CW.CottageNum
								and YB.PriceBand  = DS.PriceBand
SET CW.RentWeek = YB.RentWeek, CW.RentDay = YB.RentWeek / 5								
WHERE YEAR(CW.DateSat) = 2021;