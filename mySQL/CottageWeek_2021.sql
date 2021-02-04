

INSERT INTO CottageWeek (datesat, CottageNum, bShortBreaksAllowed)
SELECT DateSat,CottageNum,bShortBreaksAllowed FROM DateSat join Cottage 
WHERE YEAR(DateSat)=2021;