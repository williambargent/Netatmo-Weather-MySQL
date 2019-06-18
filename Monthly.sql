CREATE TABLE `sh1_monthly` (
	  `LogDateTime` datetime NOT NULL,
	  `Temp` decimal(4,1) NOT NULL,
	  `Humidity` decimal(4,0) NOT NULL,
	  `Dewpoint` decimal(4,1) NOT NULL,
	  `Windspeed` decimal(4,1) NOT NULL,
	  `Windgust` decimal(4,1) NOT NULL,
	  `Windbearing` varchar(3) NOT NULL,
	  `RainRate` decimal(4,1) NOT NULL,
	  `TodayRainSoFar` decimal(4,1) NOT NULL,
	  `Pressure` decimal(6,1) NOT NULL,
	  `Raincounter` decimal(6,1) NOT NULL,
	  `InsideTemp` decimal(4,1) NOT NULL,
	  `InsideHumidity` decimal(4,0) NOT NULL,
	  `LatestWindGust` decimal(5,1) NOT NULL,
	  `WindChill` decimal(4,1) NOT NULL,
	  `HeatIndex` decimal(4,1) NOT NULL,
	  `UVindex` decimal(4,1) DEFAULT NULL,
	  `SolarRad` decimal(5,1) DEFAULT NULL,
	  `Evapotrans` decimal(4,1) DEFAULT NULL,
	  `AnnualEvapTran` decimal(5,1) DEFAULT NULL,
	  `ApparentTemp` decimal(4,1) DEFAULT NULL,
	  `MaxSolarRad` decimal(5,1) DEFAULT NULL,
	  `HrsSunShine` decimal(3,1) DEFAULT NULL,
	  `CurrWindBearing` varchar(3) DEFAULT NULL,
	  `RG11rain` decimal(4,1) DEFAULT NULL,
	  `RainSinceMidnight` decimal(4,1) DEFAULT NULL,
	  `WindbearingSym` varchar(3) DEFAULT NULL,
	  `CurrWindBearingSym` varchar(3) DEFAULT NULL,
	  PRIMARY KEY (`LogDateTime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Monthly logs from Cumulus'
