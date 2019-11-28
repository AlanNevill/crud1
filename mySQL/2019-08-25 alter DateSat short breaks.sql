SELECT * FROM datesat ORDER BY 1;
UPDATE DateSat SET bShortBreaksAllowed = 0 WHERE DateSat >= '2019-08-31';
SELECT * FROM datesat WHERE DateSat BETWEEN '2019-11-02' AND '2020-03-28' ORDER BY 1;
UPDATE DateSat SET bShortBreaksAllowed = 1 WHERE DateSat BETWEEN '2019-11-02' AND '2020-03-28';
UPDATE DateSat SET bShortBreaksAllowed = 1 WHERE DateSat >= '2020-10-31';


UPDATE CottageWeek SET bShortBreaksAllowed = 0 WHERE DateSat >= '2019-08-31';
SELECT * FROM CottageWeek WHERE DateSat BETWEEN '2019-11-02' AND '2020-03-28' ORDER BY 1;
UPDATE CottageWeek SET bShortBreaksAllowed = 1 
WHERE DateSat BETWEEN '2019-11-02' AND '2020-03-28'
AND CottageNum != 1;
UPDATE CottageWeek SET bShortBreaksAllowed = 1 
WHERE DateSat >= '2020-10-31'
AND CottageNum != 1;