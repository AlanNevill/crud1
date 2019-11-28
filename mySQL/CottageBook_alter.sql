-- CottageBook_alter.sql

ALTER TABLE `CottageBook` 
ADD COLUMN `BookingSource` CHAR(1) NOT NULL DEFAULT 'O' AFTER `Notes`,
ADD COLUMN `ExternalReference` VARCHAR(20) not NULL DEFAULT '' AFTER `BookingSource`,
add column `ContactEmail` varchar(50) not null DEFAULT '' after `ExternalReference`,
add column `NumAdults` tinyint unsigned not null DEFAULT 0 after `ContactEmail`,
add column `NumChildren` tinyint unsigned not null DEFAULT 0 after `NumAdults`,
add column `Children` varchar(50) not null DEFAULT '' after `NumChildren`,
add column `NumDogs` tinyint unsigned not null DEFAULT 0 after `Children`,
add column `NumNights` tinyint unsigned as (datediff(LastNight, FirstNight)+1) virtual after `LastNight`,
ADD COLUMN `BookingName` VARCHAR(50) NOT NULL DEFAULT '' AFTER `NumNights`,
MODIFY COLUMN `Notes` VARCHAR(100) NOT NULL DEFAULT '',
MODIFY COLUMN `BookingRef` VARCHAR(5) NOT NULL DEFAULT '*****'
;

ALTER TABLE `CottageBook_hist` 
ADD COLUMN `BookingSource` CHAR(1) not NULL DEFAULT 'O' AFTER `Notes`,
ADD COLUMN `ExternalReference` VARCHAR(20) not NULL DEFAULT '' AFTER `BookingSource`,
add column `ContactEmail` varchar(50) not null DEFAULT '' after `ExternalReference`,
add column `NumAdults` tinyint unsigned not null DEFAULT 0 after `ContactEmail`,
add column `NumChildren` tinyint unsigned not null DEFAULT 0 after `NumAdults`,
add column `Children` varchar(50) not null DEFAULT '' after `NumChildren`,
add column `NumDogs` tinyint unsigned not null DEFAULT 0 after `Children`,
add column `NumNights` tinyint unsigned as (datediff(LastNight, FirstNight)+1) virtual after `LastNight`,
ADD COLUMN `BookingName` VARCHAR(50) NOT NULL DEFAULT '' AFTER `NumNights`,
MODIFY COLUMN `BookingRef` VARCHAR(5)
;

update StatusCodes SET category ='bookingStatus' WHERE category ='booking';