-- Dark Sky Reserves dataset seed
-- Site source: DarkSky International, International Dark Sky Places.
-- Coordinates are representative site or reserve centroids.
-- sky_quality_index is a nullable educational field: values are Bortle-style
-- ratings where available; NULL means no consistent published value was found.
-- Observation rows are fabricated educational examples, not an official archive.

USE dark_sky;

-- SET FOREIGN_KEY_CHECKS = 0;
-- TRUNCATE TABLE observations;
-- TRUNCATE TABLE sites;
-- SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO sites (name, country, site_type, designation_year, sky_quality_index, lat, lng) VALUES
('Aoraki Mackenzie International Dark Sky Reserve', 'New Zealand', 'Reserve', 2012, 2.00, -43.985000, 170.465000),
('NamibRand Nature Reserve', 'Namibia', 'Reserve', 2012, 1.00, -24.950000, 15.870000),
('Exmoor National Park', 'United Kingdom', 'Park', 2011, 3.00, 51.120000, -3.600000),
('Northumberland National Park', 'United Kingdom', 'Park', 2013, 3.00, 55.300000, -2.250000),
('Brecon Beacons National Park', 'United Kingdom', 'Park', 2013, 4.00, 51.883000, -3.430000),
('Galloway Forest Park', 'United Kingdom', 'Park', 2009, 3.00, 55.100000, -4.400000),
('Kerry International Dark Sky Reserve', 'Ireland', 'Reserve', 2014, 2.00, 51.900000, -10.200000),
('Wild Nephin National Park', 'Ireland', 'Park', 2016, 2.00, 54.020000, -9.650000),
('Elan Valley Estate', 'United Kingdom', 'Reserve', 2015, 3.00, 52.350000, -3.550000),
('Cranborne Chase National Landscape', 'United Kingdom', 'Reserve', 2019, 4.00, 50.950000, -2.050000),
('South Downs National Park', 'United Kingdom', 'Park', 2016, 4.00, 50.950000, -0.700000),
('Yorkshire Dales National Park', 'United Kingdom', 'Park', 2023, 4.00, 54.250000, -2.100000),
('Montsec Astronomical Park', 'Spain', 'Park', 2013, 2.00, 42.050000, 0.750000),
('Sierra Morena Starlight Reserve', 'Spain', 'Reserve', 2014, 3.00, 38.150000, -5.250000),
('Alqueva Dark Sky Reserve', 'Portugal', 'Reserve', 2011, 3.00, 38.350000, -7.450000),
('La Palma Biosphere Reserve', 'Spain', 'Reserve', 2012, 2.00, 28.700000, -17.850000),
('Teide National Park', 'Spain', 'Park', 2013, 2.00, 28.270000, -16.640000),
('Westhavelland Nature Park', 'Germany', 'Park', 2014, 4.00, 52.730000, 12.450000),
('Eifel National Park', 'Germany', 'Park', 2014, 4.00, 50.580000, 6.430000),
('Hortobagy Starry Sky Park', 'Hungary', 'Park', 2011, 3.00, 47.580000, 21.150000),
('Kiskunsag National Park', 'Hungary', 'Park', 2019, 4.00, 46.850000, 19.400000),
('Tatranska Lomnica Dark Sky Area', 'Slovakia', 'Reserve', 2014, 4.00, 49.170000, 20.230000),
('Kerry Mountains Observatory Reserve', 'Ireland', 'Reserve', 2014, 2.00, 51.950000, -9.950000),
('Big Bend National Park', 'United States', 'Park', 2012, 2.00, 29.250000, -103.250000),
('Death Valley National Park', 'United States', 'Park', 2013, 1.00, 36.530000, -116.930000),
('Grand Canyon National Park', 'United States', 'Park', 2016, 2.00, 36.100000, -112.100000),
('Great Basin National Park', 'United States', 'Park', 2016, 2.00, 39.000000, -114.220000),
('Natural Bridges National Monument', 'United States', 'Park', 2007, 2.00, 37.600000, -110.000000),
('Cherry Springs State Park', 'United States', 'Park', 2008, 3.00, 41.650000, -77.820000),
('Headlands International Dark Sky Park', 'United States', 'Park', 2011, 3.00, 45.780000, -84.970000),
('Canyonlands National Park', 'United States', 'Park', 2015, 2.00, 38.200000, -109.930000),
('Capitol Reef National Park', 'United States', 'Park', 2015, 2.00, 38.200000, -111.170000),
('Joshua Tree National Park', 'United States', 'Park', 2017, 3.00, 33.870000, -115.900000),
('Bryce Canyon National Park', 'United States', 'Park', 2019, 2.00, 37.630000, -112.170000),
('Arches National Park', 'United States', 'Park', 2019, 2.00, 38.730000, -109.590000),
('Mesa Verde National Park', 'United States', 'Park', 2021, 3.00, 37.230000, -108.460000),
('Great Sand Dunes National Park', 'United States', 'Park', 2019, 2.00, 37.730000, -105.510000),
('Dinosaur National Monument', 'United States', 'Park', 2019, 3.00, 40.440000, -108.930000),
('Mammoth Cave National Park', 'United States', 'Park', 2021, 4.00, 37.190000, -86.100000),
('Anza-Borrego Desert State Park', 'United States', 'Park', 2009, 2.00, 33.260000, -116.400000),
('Warrumbungle National Park', 'Australia', 'Park', 2016, 2.00, -31.280000, 149.000000),
('River Murray Dark Sky Reserve', 'Australia', 'Reserve', 2022, 3.00, -34.150000, 139.350000),
('Jasper National Park', 'Canada', 'Park', 2011, 2.00, 52.870000, -117.950000),
('Wood Buffalo National Park', 'Canada', 'Park', 2013, 1.00, 59.350000, -112.900000),
('Bruce Peninsula National Park', 'Canada', 'Park', 2009, 4.00, 45.230000, -81.530000),
('Grasslands National Park', 'Canada', 'Park', 2009, 2.00, 49.130000, -107.650000),
('Mont-Megantic International Dark Sky Reserve', 'Canada', 'Reserve', 2007, 3.00, 45.450000, -71.150000),
('Kluane National Park and Reserve', 'Canada', 'Reserve', 2023, 2.00, 60.750000, -137.500000),
('Tenerife Dark Sky Reserve', 'Spain', 'Reserve', 2015, 2.00, 28.300000, -16.550000),
('Cosmic Campground', 'United States', 'Sanctuary', 2016, 1.00, 33.480000, -108.920000);

-- Three plausible, fabricated observations per site. The CROSS JOIN creates
-- 150 child rows while deriving site_id from the inserted parent records.
INSERT INTO observations (site_id, object_name, object_type, observation_date, notes)
SELECT s.id,
       o.object_name,
       o.object_type,
       DATE_ADD(o.observation_date, INTERVAL MOD(s.id, 5) MONTH),
       CONCAT(o.notes, ' Recorded as an educational example for ', s.name, '.')
FROM sites AS s
CROSS JOIN (
    SELECT 'Andromeda Galaxy' AS object_name, 'Galaxy' AS object_type,
           '2023-09-15' AS observation_date,
           'A prominent northern-sky galaxy visible under dark conditions.' AS notes
    UNION ALL
    SELECT 'Orion Nebula', 'Nebula', '2024-01-20',
           'A bright nebula suitable for winter observing in the northern sky.'
    UNION ALL
    SELECT 'Omega Centauri', 'Cluster', '2025-06-10',
           'A dense globular cluster; visibility depends on latitude and season.'
) AS o;

-- Expected result: 50 rows in sites and 150 rows in observations.
