CREATE TABLE `sh1_realtime` (
	  `LogDateTime` datetime NOT NULL,
	  `temp` decimal(4,1) NOT NULL,
	  `hum` decimal(4,0) NOT NULL,
	  `dew` decimal(4,1) NOT NULL,
	  `wspeed` decimal(4,1) NOT NULL,
	  `wlatest` decimal(4,1) NOT NULL,
	  `bearing` varchar(3) NOT NULL,
	  `rrate` decimal(4,2) NOT NULL,
	  `rfall` decimal(4,2) NOT NULL,
	  `press` decimal(6,1) NOT NULL,
	  `currentwdir` varchar(3) NOT NULL,
	  `beaufortnumber` varchar(2) NOT NULL,
	  `windunit` varchar(4) NOT NULL,
	  `tempunitnodeg` varchar(1) NOT NULL,
	  `pressunit` varchar(3) NOT NULL,
	  `rainunit` varchar(2) NOT NULL,
	  `windrun` decimal(4,1) NOT NULL,
	  `presstrendval` varchar(6) NOT NULL,
	  `rmonth` decimal(4,2) NOT NULL,
	  `ryear` decimal(4,2) NOT NULL,
	  `rfallY` decimal(4,2) NOT NULL,
	  `intemp` decimal(4,1) NOT NULL,
	  `inhum` decimal(4,0) NOT NULL,
	  `wchill` decimal(4,1) NOT NULL,
	  `temptrend` varchar(5) NOT NULL,
	  `tempTH` decimal(4,1) NOT NULL,
	  `TtempTH` varchar(5) NOT NULL,
	  `tempTL` decimal(4,1) NOT NULL,
	  `TtempTL` varchar(5) NOT NULL,
	  `windTM` decimal(4,1) NOT NULL,
	  `TwindTM` varchar(5) NOT NULL,
	  `wgustTM` decimal(4,1) NOT NULL,
	  `TwgustTM` varchar(5) NOT NULL,
	  `pressTH` decimal(6,1) NOT NULL,
	  `TpressTH` varchar(5) NOT NULL,
	  `pressTL` decimal(6,1) NOT NULL,
	  `TpressTL` varchar(5) NOT NULL,
	  `version` varchar(8) NOT NULL,
	  `build` varchar(5) NOT NULL,
	  `wgust` decimal(4,1) NOT NULL,
	  `heatindex` decimal(4,1) NOT NULL,
	  `humidex` decimal(4,1) NOT NULL,
	  `UV` decimal(3,1) NOT NULL,
	  `ET` decimal(4,2) NOT NULL,
	  `SolarRad` decimal(5,1) NOT NULL,
	  `avgbearing` varchar(3) NOT NULL,
	  `rhour` decimal(4,2) NOT NULL,
	  `forecastnumber` varchar(2) NOT NULL,
	  `isdaylight` varchar(1) NOT NULL,
	  `SensorContactLost` varchar(1) NOT NULL,
	  `wdir` varchar(3) NOT NULL,
	  `cloudbasevalue` varchar(5) NOT NULL,
	  `cloudbaseunit` varchar(2) NOT NULL,
	  `apptemp` decimal(4,1) NOT NULL,
	  `SunshineHours` decimal(3,1) NOT NULL,
	  `CurrentSolarMax` decimal(5,1) NOT NULL,
	  `IsSunny` varchar(1) NOT NULL,
	  PRIMARY KEY (`LogDateTime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 MAX_ROWS=10 COMMENT='Realtime log'
