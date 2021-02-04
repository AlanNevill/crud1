# MGFv4.0

**Change log**:

-   Moved to PHP
-   New availability page based on database
-   Include files for header, footer & libraries

_2021-02-03_
# Process to add BandYear table and update CottageWeek prices for a new year

1. Logon to CPANEL using [www.meadowgreenfarm.co.uk/cpanel](www.meadowgreenfarm.co.uk/cpanel)
   
2. Create BandYear table using PriceBand.TAB.sql. Only once.
   
3. Populate BandYear table for 2021 with Insert commands. Values from BookingYYYY.XLXS.

```
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
```


3. Alter DateSat table to add column PriceBand.
   `ALTER table DateSat ADD COLUMN PriceBand TINYINT DEFAULT 0;`
   

4. Update DateSat column PriceBand using multiple Update commands from Booking spreadsheet.

5. Run an update on CottageWeek to populate RentWeek and RentDay. See \_useful.sql.
```
Update CottageWeek CW
LEFT JOIN DateSat DS  ON DS.DateSat = CW.DateSat
LEFT JOIN BandYear YB ON  YB.BandYear   = YEAR(DS.DateSat)
                      AND YB.CottageNum = CW.CottageNum
                      and YB.PriceBand  = DS.PriceBand
SET CW.RentWeek = YB.RentWeek, CW.RentDay = YB.RentWeek / 5
WHERE YEAR(CW.DateSat) = 2021;
```

6. Test in Production by printing Booking & prices page then manually check to BOOKING.YYYY.XLXS pages.
