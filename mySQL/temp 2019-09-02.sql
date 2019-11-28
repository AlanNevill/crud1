SELECT * FROM CottageWeek ORDER BY 1,2;

UPDATE CottageWeek SET RentDay = 0 WHERE bShortBreaksAllowed = 0 and DateSat >= '2019-08-31';

UPDATE CottageWeek SET RentDay = 161 
	WHERE bShortBreaksAllowed = 1 
	and CottageNum = 3
	and DateSat >= '2019-08-31';
	
UPDATE CottageWeek SET RentDay = 133 
	WHERE bShortBreaksAllowed = 1 
	and CottageNum = 2
	and DateSat >= '2019-08-31';
	
SELECT * FROM cottagebook ORDER BY 1 DESC;