INSERT INTO users (first_name, last_name, email, password_hash, role) VALUES
('Marc', 'Quizon', 'marc_lesley_quizon@dlsu.edu.ph', '$2y$10$XFJqfJ2SZouRRDskL7g28uhcrS9Z6oh4J2KamzmFGBt9f7CJ/tPTy', 'Student'),
('Angelo', 'Almeda', 'angelo_almeda@dlsu.edu.ph', '$2y$10$XFJqfJ2SZouRRDskL7g28uhcrS9Z6oh4J2KamzmFGBt9f7CJ/tPTy', 'Student'),
('Carl', 'Crespo', 'carl_crespo@dlsu.edu.ph', '$2y$10$ww9SiZnuQrmlSY.a8Zb8w.wrLIF1PjARGSD1oh1YY.Ibop1UobG.S', 'Staff'),
('Nathan', 'Saclolo', 'nathan_saclolo@dlsu.edu.ph', '$2y$10$ww9SiZnuQrmlSY.a8Zb8w.wrLIF1PjARGSD1oh1YY.Ibop1UobG.S', 'Staff'),
('Daniel', 'Pamintuan', 'daniel_pamintuan@dlsu.edu.ph', '$2y$10$eFHiSLsoGVnD9CpYWHDrGuOWe1WmPTIKhvOFNbf.oNzGJUq73n1.6', 'Admin');

INSERT INTO categories (name) VALUES
('Electronics'),
('Tumblers & Bottles'),
('Wallets'),
('Documents'),
('Apparel & Bags'),
('Others');

INSERT INTO brands (name) VALUES
('Apple'),
('Samsung'),
('Huawei'),
('JisuLife'),
('Goojodoq'),
('Anker'),
('AquaFlask'),
('Hydro Flask'),
('Gucci'),
('Louis Vuitton'),
('Coach'),
('Uniqlo'),
('Zara'),
('H&M'),
('none');

INSERT INTO category_brands (category_id, brand_id) VALUES
-- Electronics (category_id: 1) -> Apple, Samsung, Huawei, JisuLife, Goojodoq, Anker
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 6), (1,15),
-- Tumblers & Bottles (category_id: 2) -> AquaFlask, Hydro Flask, none
(2, 7), (2, 8), (2, 15),
-- Wallets (category_id: 3) -> Gucci, Louis Vuitton, Coach, none
(3, 9), (3, 10), (3, 11), (3, 15),
-- Documents (category_id: 4) -> none
(4, 15),
-- Apparel & Bags (category_id: 5) -> Gucci, Louis Vuitton, Coach, Uniqlo, Zara, H&M, none
(5, 9), (5, 10), (5, 11), (5, 12), (5, 13), (5, 14), (5, 15),
-- Others (category_id: 6) -> Maps to all for flexibility, or just none
(6, 15);

INSERT INTO buildings (name, abbreviation, max_level) VALUES
('St. La Salle Hall', 'LS', 4),
('Yuchengco Hall', 'Y', 9),
('Br. Connon Hall', 'BC', 5),
('St. Joseph Hall', 'SJ', 6),
('St. Miguel Hall', 'SM', 5),
('St. Mutien Marie Hall', 'MM', 4),
('William Hall', 'WH', 4),
('Henry Sy Sr. Hall', 'HSSH', 14),
('Velasco Hall', 'V', 5),
('Science and Technology Center', 'STR', 5),
('Gokongwei Hall', 'G', 4),
('Andrew Gonzalez Hall', 'AG', 21),
('Enrique Razon Sports Center', 'ER', 10),
('Bloemen Hall', 'BH', 4);


INSERT INTO rooms (name, building_id, level) VALUES
-- =========================================================================
-- St. La Salle Hall (building_id: 1, max_level: 4) 
-- =========================================================================
('LS101', 1, 1), ('LS102', 1, 1), ('LS103', 1, 1), ('LS104', 1, 1), ('LS105', 1, 1),
('LS201', 1, 2), ('LS202', 1, 2), ('LS203', 1, 2), ('LS204', 1, 2), ('LS205', 1, 2),
('LS301', 1, 3), ('LS302', 1, 3), ('LS303', 1, 3), ('LS304', 1, 3), ('LS305', 1, 3),

-- =========================================================================
-- Yuchengco Hall (building_id: 2, max_level: 9) 
-- =========================================================================
('Y201', 2, 2), ('Y202', 2, 2), ('Y203', 2, 2), ('Y204', 2, 2), ('Y205', 2, 2),
('Y301', 2, 3), ('Y302', 2, 3), ('Y303', 2, 3), ('Y304', 2, 3), ('Y305', 2, 3),
('Y401', 2, 4), ('Y402', 2, 4), ('Y403', 2, 4), ('Y404', 2, 4), ('Y405', 2, 4),
('Y501', 2, 5), ('Y502', 2, 5), ('Y503', 2, 5), ('Y504', 2, 5), ('Y505', 2, 5),
('Y601', 2, 6), ('Y602', 2, 6), ('Y603', 2, 6), ('Y604', 2, 6), ('Y605', 2, 6),
('Y701', 2, 7), ('Y702', 2, 7), ('Y703', 2, 7), ('Y704', 2, 7), ('Y705', 2, 7),

-- Br. Connon Hall (building_id: 3)
('Waldo Perfecto Seminar Room', 3, 1),
('Green Media Group Member Lounge', 3, 5),

-- =========================================================================
-- St. Joseph Hall (building_id: 4, max_level: 6) 
-- =========================================================================
('SJ101', 4, 1), ('SJ102', 4, 1), ('SJ103', 4, 1), ('SJ104', 4, 1), ('SJ105', 4, 1),
('SJ201', 4, 2), ('SJ202', 4, 2), ('SJ203', 4, 2), ('SJ204', 4, 2), ('SJ205', 4, 2),
('SJ301', 4, 3), ('SJ302', 4, 3), ('SJ303', 4, 3), ('SJ304', 4, 3), ('SJ305', 4, 3),
('SJ401', 4, 4), ('SJ402', 4, 4), ('SJ403', 4, 4), ('SJ404', 4, 4), ('SJ405', 4, 4),
('SJ501', 4, 5), ('SJ502', 4, 5), ('SJ503', 4, 5), ('SJ504', 4, 5), ('SJ505', 4, 5),
('SJ601', 4, 6), ('SJ602', 4, 6), ('SJ603', 4, 6), ('SJ604', 4, 6), ('SJ605', 4, 6),

-- =========================================================================
-- St. Miguel Hall (building_id: 5, max_level: 5) 
-- =========================================================================
('SM101', 5, 1), ('SM102', 5, 1), ('SM103', 5, 1), ('SM104', 5, 1), ('SM105', 5, 1),
('SM201', 5, 2), ('SM202', 5, 2), ('SM203', 5, 2), ('SM204', 5, 2), ('SM205', 5, 2),
('SM301', 5, 3), ('SM302', 5, 3), ('SM303', 5, 3), ('SM304', 5, 3), ('SM305', 5, 3),
('SM401', 5, 4), ('SM402', 5, 4), ('SM403', 5, 4), ('SM404', 5, 4), ('SM405', 5, 4),
('SM501', 5, 5), ('SM502', 5, 5), ('SM503', 5, 5), ('SM504', 5, 5), ('SM505', 5, 5),

-- =========================================================================
-- St. Mutien Marie Hall (building_id: 6, max_level: 4) 
-- =========================================================================
('MM101', 6, 1), ('MM102', 6, 1), ('MM103', 6, 1), ('MM104', 6, 1), ('MM105', 6, 1),
('MM201', 6, 2), ('MM202', 6, 2), ('MM203', 6, 2), ('MM204', 6, 2), ('MM205', 6, 2),
('MM301', 6, 3), ('MM302', 6, 3), ('MM303', 6, 3), ('MM304', 6, 3), ('MM305', 6, 3),
('MM401', 6, 4), ('MM402', 6, 4), ('MM403', 6, 4), ('MM404', 6, 4), ('MM405', 6, 4),

-- =========================================================================
-- William Hall (building_id: 7, max_level: 4) 
-- =========================================================================
('WH101', 7, 1), ('WH102', 7, 1), ('WH103', 7, 1), ('WH104', 7, 1), ('WH105', 7, 1),
('WH201', 7, 2), ('WH202', 7, 2), ('WH203', 7, 2), ('WH204', 7, 2), ('WH205', 7, 2),
('WH301', 7, 3), ('WH302', 7, 3), ('WH303', 7, 3), ('WH304', 7, 3), ('WH305', 7, 3),
('WH401', 7, 4), ('WH402', 7, 4), ('WH403', 7, 4), ('WH404', 7, 4), ('WH405', 7, 4),

-- =========================================================================
-- Henry Sy Sr. Hall (building_id: 8)
-- =========================================================================
('Pilar Ferrero Lim Room 1', 8, 7), ('Pilar Ferrero Lim Room 2', 8, 7), ('Pilar Ferrero Lim Room 3', 8, 7), ('Pilar Ferrero Lim Room 4', 8, 7), 
('Viewing Room 7E', 8, 7), ('Viewing Room 7F', 8, 7), ('Viewing Room 7G', 8, 7), ('Viewing Room 7H', 8, 7),
('Discussion Room 8A', 8, 8), ('Discussion Room 8B', 8, 8), ('Discussion Room 8G', 8, 8), ('Discussion Room 8H', 8, 8),
('Discussion Room 8C', 8, 8), ('Discussion Room 8D', 8, 8), ('Discussion Room 8E', 8, 8), ('Discussion Room 8F', 8, 8),
('Discussion Room 9A', 8, 9), ('Discussion Room 9B', 8, 9), ('Discussion Room 9C', 8, 9), ('Discussion Room 9D', 8, 9),
('Discussion Room 9E', 8, 9), ('Discussion Room 9F', 8, 9), ('Discussion Room 9G', 8, 9), ('Discussion Room 9H', 8, 9),
('Discussion Room 10A', 8, 10), ('Discussion Room 10B', 8, 10), ('Discussion Room 10G', 8, 10), ('Discussion Room 10C', 8, 10),
('Discussion Room 10D', 8, 10), ('Discussion Room 10E', 8, 10),
('Discussion Room 12A', 8, 12), ('Discussion Room 12B', 8, 12), ('Discussion Room 12G', 8, 12), ('Discussion Room 12H', 8, 12),
('Discussion Room 12C', 8, 12), ('Discussion Room 12D', 8, 12), ('Discussion Room 12E', 8, 12), ('Discussion Room 12F', 8, 12),

-- =========================================================================
-- Velasco Hall (building_id: 9, max_level: 5) 
-- =========================================================================
('V101', 9, 1), ('V102', 9, 1), ('V103', 9, 1), ('V104', 9, 1), ('V105', 9, 1),
('V201', 9, 2), ('V202', 9, 2), ('V203', 9, 2), ('V204', 9, 2), ('V205', 9, 2),
('V301', 9, 3), ('V302', 9, 3), ('V303', 9, 3), ('V304', 9, 3), ('V305', 9, 3),
('V401', 9, 4), ('V402', 9, 4), ('V403', 9, 4), ('V404', 9, 4), ('V405', 9, 4),
('V501', 9, 5), ('V502', 9, 5), ('V503', 9, 5), ('V504', 9, 5), ('V505', 9, 5),

-- =========================================================================
-- Science and Technology Center (building_id: 10, max_level: 5) 
-- =========================================================================
('STR101', 10, 1), ('STR102', 10, 1), ('STR103', 10, 1), ('STR104', 10, 1), ('STR105', 10, 1),
('STR201', 10, 2), ('STR202', 10, 2), ('STR203', 10, 2), ('STR204', 10, 2), ('STR205', 10, 2),
('STR301', 10, 3), ('STR302', 10, 3), ('STR303', 10, 3), ('STR304', 10, 3), ('STR305', 10, 3),
('STR401', 10, 4), ('STR402', 10, 4), ('STR403', 10, 4), ('STR404', 10, 4), ('STR405', 10, 4),
('STR501', 10, 5), ('STR502', 10, 5), ('STR503', 10, 5), ('STR504', 10, 5), ('STR505', 10, 5),

-- =========================================================================
-- Gokongwei Hall (building_id: 11, max_level: 4) 
-- =========================================================================
('G101', 11, 1), ('G102', 11, 1), ('G103', 11, 1), ('G104', 11, 1), ('G105', 11, 1),
('G201', 11, 2), ('G202', 11, 2), ('G203', 11, 2), ('G204', 11, 2), ('G205', 11, 2),
('G301', 11, 3), ('G302', 11, 3), ('G303', 11, 3), ('G304', 11, 3), ('G305', 11, 3),
('G401', 11, 4), ('G402', 11, 4), ('G403', 11, 4), ('G404', 11, 4), ('G405', 11, 4),

-- =========================================================================
-- Andrew Gonzalez Hall (building_id: 12, max_level: 21) 
-- =========================================================================
('AG401', 12, 4), ('AG402', 12, 4), ('AG403', 12, 4), ('AG404', 12, 4), ('AG405', 12, 4),
('AG501', 12, 5), ('AG502', 12, 5), ('AG503', 12, 5), ('AG504', 12, 5), ('AG505', 12, 5),
('AG601', 12, 6), ('AG602', 12, 6), ('AG603', 12, 6), ('AG604', 12, 6), ('AG605', 12, 6),
('AG701', 12, 7), ('AG702', 12, 7), ('AG703', 12, 7), ('AG704', 12, 7), ('AG705', 12, 7),
('AG801', 12, 8), ('AG802', 12, 8), ('AG803', 12, 8), ('AG804', 12, 8), ('AG805', 12, 8),
('AG901', 12, 9), ('AG902', 12, 9), ('AG903', 12, 9), ('AG904', 12, 9), ('AG905', 12, 9),
('AG1001', 12, 10), ('AG1002', 12, 10), ('AG1003', 12, 10), ('AG1004', 12, 10), ('AG1005', 12, 10),
('AG1101', 12, 11), ('AG1102', 12, 11), ('AG1103', 12, 11), ('AG1104', 12, 11), ('AG1105', 12, 11),
('AG1201', 12, 12), ('AG1202', 12, 12), ('AG1203', 12, 12), ('AG1204', 12, 12), ('AG1205', 12, 12),
('AG1301', 12, 13), ('AG1302', 12, 13), ('AG1303', 12, 13), ('AG1304', 12, 13), ('AG1305', 12, 13),
('AG1401', 12, 14), ('AG1402', 12, 14), ('AG1403', 12, 14), ('AG1404', 12, 14), ('AG1405', 12, 14),
('AG1501', 12, 15), ('AG1502', 12, 15), ('AG1503', 12, 15), ('AG1504', 12, 15), ('AG1505', 12, 15),
('AG1601', 12, 16), ('AG1602', 12, 16), ('AG1603', 12, 16), ('AG1604', 12, 16), ('AG1605', 12, 16),
('AG1701', 12, 17), ('AG1702', 12, 17), ('AG1703', 12, 17), ('AG1704', 12, 17), ('AG1705', 12, 17),
('AG1801', 12, 18), ('AG1802', 12, 18), ('AG1803', 12, 18), ('AG1804', 12, 18), ('AG1805', 12, 18),
('AG1901', 12, 19), ('AG1902', 12, 19), ('AG1903', 12, 19), ('AG1904', 12, 19), ('AG1905', 12, 19),
('AG2001', 12, 20), ('AG2002', 12, 20), ('AG2003', 12, 20), ('AG2004', 12, 20), ('AG2005', 12, 20),
('AG2101', 12, 21), ('AG2102', 12, 21), ('AG2103', 12, 21), ('AG2104', 12, 21), ('AG2105', 12, 21),

-- Enrique Razon Sports Center (building_id: 13)
('ER2F', 13, 2), ('ER606', 13, 6), ('ER607', 13, 6), ('ER7A', 13, 7),
('ER7B', 13, 7), ('ER804', 13, 8), ('ER1001', 13, 10), ('ERPOOL', 13, 1), ('Pericos Canteen', 13, 2),

-- Bloemen Hall (building_id: 14)
('Radio Room', 14, 1);


INSERT INTO areas (name, building_id, level) VALUES
-- St. La Salle Hall (building_id: 1)
('Amphitheater', 1, 1),
('LS Benches', 1, 1),
('Pearl of Great Price Chapel', 1, 1),
('Kitchen City', 1, 1),
('Medrano Hall', 1, 2),
('Chapel of the Most Blessed Sacrament', 1, 2),
('Hallway (1st Floor)', 1, 1),
('Hallway (2nd Floor)', 1, 2),
('Hallway (3rd Floor)', 1, 3),
('Male Toilet (1st Floor)', 1, 1), ('Female Toilet (1st Floor)', 1, 1),
('Male Toilet (2nd Floor)', 1, 2), ('Female Toilet (2nd Floor)', 1, 2),
('Male Toilet (3rd Floor)', 1, 3), ('Female Toilet (3rd Floor)', 1, 3),
('Male Toilet (4th Floor)', 1, 4), ('Female Toilet (4th Floor)', 1, 4),

-- Yuchengco Hall (building_id: 2)
('Yuchengco Museum', 2, 2),
('Yuchengco Benches', 2, 1),
('Hallway (2nd Floor)', 2, 2),
('Hallway (3rd Floor)', 2, 3),
('Hallway (4th Floor)', 2, 4),
('Hallway (5th Floor)', 2, 5),
('Hallway (6th Floor)', 2, 6),
('Hallway (7th Floor)', 2, 7),
('Male Toilet (1st Floor)', 2, 1), ('Female Toilet (1st Floor)', 2, 1),
('Male Toilet (2nd Floor)', 2, 2), ('Female Toilet (2nd Floor)', 2, 2),
('Male Toilet (3rd Floor)', 2, 3), ('Female Toilet (3rd Floor)', 2, 3),
('Male Toilet (4th Floor)', 2, 4), ('Female Toilet (4th Floor)', 2, 4),
('Male Toilet (5th Floor)', 2, 5), ('Female Toilet (5th Floor)', 2, 5),
('Male Toilet (6th Floor)', 2, 6), ('Female Toilet (6th Floor)', 2, 6),
('Male Toilet (7th Floor)', 2, 7), ('Female Toilet (7th Floor)', 2, 7),
('Male Toilet (8th Floor)', 2, 8), ('Female Toilet (8th Floor)', 2, 8),
('Male Toilet (9th Floor)', 2, 9), ('Female Toilet (9th Floor)', 2, 9),

-- Br. Connon Hall (building_id: 3)
('Hallway (1st Floor)', 3, 1),
('Hallway (2nd Floor)', 3, 2),
('Hallway (3rd Floor)', 3, 3),
('Hallway (4th Floor)', 3, 4),
('Hallway (5th Floor)', 3, 5),
('Male Toilet (1st Floor)', 3, 1), ('Female Toilet (1st Floor)', 3, 1),
('Male Toilet (2nd Floor)', 3, 2), ('Female Toilet (2nd Floor)', 3, 2),
('Male Toilet (3rd Floor)', 3, 3), ('Female Toilet (3rd Floor)', 3, 3),
('Male Toilet (4th Floor)', 3, 4), ('Female Toilet (4th Floor)', 3, 4),
('Male Toilet (5th Floor)', 3, 5), ('Female Toilet (5th Floor)', 3, 5),

-- St. Joseph Hall (building_id: 4)
('SJ Bench Area (Front)', 4, 1),
('SJ Bench Area (Back)', 4, 1),
('Hallway (1st Floor)', 4, 1),
('Hallway (2nd Floor)', 4, 2),
('Hallway (3rd Floor)', 4, 3),
('Hallway (4th Floor)', 4, 4),
('Hallway (5th Floor)', 4, 5),
('Hallway (6th Floor)', 4, 6),
('Male Toilet (1st Floor)', 4, 1), ('Female Toilet (1st Floor)', 4, 1),
('Male Toilet (2nd Floor)', 4, 2), ('Female Toilet (2nd Floor)', 4, 2),
('Male Toilet (3rd Floor)', 4, 3), ('Female Toilet (3rd Floor)', 4, 3),
('Male Toilet (4th Floor)', 4, 4), ('Female Toilet (4th Floor)', 4, 4),
('Male Toilet (5th Floor)', 4, 5), ('Female Toilet (5th Floor)', 4, 5),
('Male Toilet (6th Floor)', 4, 6), ('Female Toilet (6th Floor)', 4, 6),

-- St. Miguel Hall (building_id: 5)
('Hallway (1st Floor)', 5, 1),
('Hallway (2nd Floor)', 5, 2),
('Hallway (3rd Floor)', 5, 3),
('Hallway (4th Floor)', 5, 4),
('Hallway (5th Floor)', 5, 5),
('Male Toilet (1st Floor)', 5, 1), ('Female Toilet (1st Floor)', 5, 1),
('Male Toilet (2nd Floor)', 5, 2), ('Female Toilet (2nd Floor)', 5, 2),
('Male Toilet (3rd Floor)', 5, 3), ('Female Toilet (3rd Floor)', 5, 3),
('Male Toilet (4th Floor)', 5, 4), ('Female Toilet (4th Floor)', 5, 4),
('Male Toilet (5th Floor)', 5, 5), ('Female Toilet (5th Floor)', 5, 5),

-- St. Mutien Marie Hall (building_id: 6)
('Mutien Ground Floor Lobby', 6, 1),
('Mutien Rear Garden Walkway', 6, 1),
('Hallway (2nd Floor)', 6, 2),
('Hallway (3rd Floor)', 6, 3),
('Hallway (4th Floor)', 6, 4),
('Male Toilet (1st Floor)', 6, 1), ('Female Toilet (1st Floor)', 6, 1),
('Male Toilet (2nd Floor)', 6, 2), ('Female Toilet (2nd Floor)', 6, 2),
('Male Toilet (3rd Floor)', 6, 3), ('Female Toilet (3rd Floor)', 6, 3),
('Male Toilet (4th Floor)', 6, 4), ('Female Toilet (4th Floor)', 6, 4),

-- William Hall (building_id: 7)
('William Hall Ground Floor Lobby', 7, 1),
('Hallway (2nd Floor)', 7, 2),
('Hallway (3rd Floor)', 7, 3),
('Hallway (4th Floor)', 7, 4),
('Male Toilet (1st Floor)', 7, 1), ('Female Toilet (1st Floor)', 7, 1),
('Male Toilet (2nd Floor)', 7, 2), ('Female Toilet (2nd Floor)', 7, 2),
('Male Toilet (3rd Floor)', 7, 3), ('Female Toilet (3rd Floor)', 7, 3),
('Male Toilet (4th Floor)', 7, 4), ('Female Toilet (4th Floor)', 7, 4),

-- Henry Sy Sr. Hall (building_id: 8)
('Cory Aquino Democratic Space (CADS)', 8, 1),
('Outdoor Escalator Area (2nd Floor)', 8, 2),
('Outdoor Escalator Area (3rd Floor)', 8, 3),
('Outdoor Escalator Area (4th Floor)', 8, 4),
('Outdoor Escalator Area (5th Floor)', 8, 5),
('Outdoor Escalator Area (6th Floor)', 8, 6),
('Study Area (6th Floor)', 8, 6),
('Study Area (7th Floor)', 8, 7),
('Study Area (8th Floor)', 8, 8),
('Study Area (9th Floor)', 8, 9),
('Study Area (10th Floor)', 8, 10),
('Study Area (12th Floor)', 8, 12),
('Male Toilet (1st Floor)', 8, 1), ('Female Toilet (1st Floor)', 8, 1),
('Male Toilet (2nd Floor)', 8, 2), ('Female Toilet (2nd Floor)', 8, 2),
('Male Toilet (3rd Floor)', 8, 3), ('Female Toilet (3rd Floor)', 8, 3),
('Male Toilet (4th Floor)', 8, 4), ('Female Toilet (4th Floor)', 8, 4),
('Male Toilet (5th Floor)', 8, 5), ('Female Toilet (5th Floor)', 8, 5),
('Male Toilet (6th Floor)', 8, 6), ('Female Toilet (6th Floor)', 8, 6),
('Male Toilet (7th Floor)', 8, 7), ('Female Toilet (7th Floor)', 8, 7),
('Male Toilet (8th Floor)', 8, 8), ('Female Toilet (8th Floor)', 8, 8),
('Male Toilet (9th Floor)', 8, 9), ('Female Toilet (9th Floor)', 8, 9),
('Male Toilet (10th Floor)', 8, 10), ('Female Toilet (10th Floor)', 8, 10),
('Male Toilet (11th Floor)', 8, 11), ('Female Toilet (11th Floor)', 8, 11),
('Male Toilet (12th Floor)', 8, 12), ('Female Toilet (12th Floor)', 8, 12),

-- Velasco Hall (building_id: 9)
('Outdoor Benches', 9, 1),
('Hallway (1st Floor)', 9, 1),
('Hallway (2nd Floor)', 9, 2),
('Hallway (3rd Floor)', 9, 3),
('Hallway (4th Floor)', 9, 4),
('Hallway (5th Floor)', 9, 5),
('Male Toilet (1st Floor)', 9, 1), ('Female Toilet (1st Floor)', 9, 1),
('Male Toilet (2nd Floor)', 9, 2), ('Female Toilet (2nd Floor)', 9, 2),
('Male Toilet (3rd Floor)', 9, 3), ('Female Toilet (3rd Floor)', 9, 3),
('Male Toilet (4th Floor)', 9, 4), ('Female Toilet (4th Floor)', 9, 4),
('Male Toilet (5th Floor)', 9, 5), ('Female Toilet (5th Floor)', 9, 5),

-- Science and Technology Center (building_id: 10)
('Hallway (1st Floor)', 10, 1),
('Hallway (2nd Floor)', 10, 2),
('Hallway (3rd Floor)', 10, 3),
('Hallway (4th Floor)', 10, 4),
('Hallway (5th Floor)', 10, 5),
('Male Toilet (1st Floor)', 10, 1), ('Female Toilet (1st Floor)', 10, 1),
('Male Toilet (2nd Floor)', 10, 2), ('Female Toilet (2nd Floor)', 10, 2),
('Male Toilet (3rd Floor)', 10, 3), ('Female Toilet (3rd Floor)', 10, 3),
('Male Toilet (4th Floor)', 10, 4), ('Female Toilet (4th Floor)', 10, 4),
('Male Toilet (5th Floor)', 10, 5), ('Female Toilet (5th Floor)', 10, 5),

-- Gokongwei Hall (building_id: 11)
('Gokongwei Ground Floor Lobby', 11, 1),
('Gokongwei Connecting Bridge', 11, 3),
('Hallway (2nd Floor)', 11, 2),
('Hallway (3rd Floor)', 11, 3),
('Hallway (4th Floor)', 11, 4),
('Male Toilet (1st Floor)', 11, 1), ('Female Toilet (1st Floor)', 11, 1),
('Male Toilet (2nd Floor)', 11, 2), ('Female Toilet (2nd Floor)', 11, 2),
('Male Toilet (3rd Floor)', 11, 3), ('Female Toilet (3rd Floor)', 11, 3),
('Male Toilet (4th Floor)', 11, 4), ('Female Toilet (4th Floor)', 11, 4),

-- Andrew Gonzalez Hall (building_id: 12)
('Pericos Canteen', 12, 6),
('Study Lobby Area', 12, 1),
('Hallway (1st Floor)', 12, 1), ('Hallway (6th Floor)', 12, 6), ('Hallway (7th Floor)', 12, 7),
('Hallway (8th Floor)', 12, 8), ('Hallway (9th Floor)', 12, 9), ('Hallway (10th Floor)', 12, 10),
('Hallway (11th Floor)', 12, 11), ('Hallway (12th Floor)', 12, 12), ('Hallway (13th Floor)', 12, 13),
('Hallway (14th Floor)', 12, 14), ('Hallway (15th Floor)', 12, 15), ('Hallway (16th Floor)', 12, 16),
('Hallway (17th Floor)', 12, 17), ('Hallway (18th Floor)', 12, 18),
('Hallway (19th Floor)', 12, 19), ('Hallway (20th Floor)', 12, 20),
('Male Toilet (1st Floor)', 12, 1), ('Female Toilet (1st Floor)', 12, 1),
('Male Toilet (2nd Floor)', 12, 2), ('Female Toilet (2nd Floor)', 12, 2),
('Male Toilet (6th Floor)', 12, 6), ('Female Toilet (6th Floor)', 12, 6),
('Male Toilet (7th Floor)', 12, 7), ('Female Toilet (7th Floor)', 12, 7),
('Male Toilet (8th Floor)', 12, 8), ('Female Toilet (8th Floor)', 12, 8),
('Male Toilet (9th Floor)', 12, 9), ('Female Toilet (9th Floor)', 12, 9),
('Male Toilet (10th Floor)', 12, 10), ('Female Toilet (10th Floor)', 12, 10),
('Male Toilet (11th Floor)', 12, 11), ('Female Toilet (11th Floor)', 12, 11),
('Male Toilet (12th Floor)', 12, 12), ('Female Toilet (12th Floor)', 12, 12),
('Male Toilet (13th Floor)', 12, 13), ('Female Toilet (13th Floor)', 12, 13),
('Male Toilet (14th Floor)', 12, 14), ('Female Toilet (14th Floor)', 12, 14),
('Male Toilet (15th Floor)', 12, 15), ('Female Toilet (15th Floor)', 12, 15),
('Male Toilet (16th Floor)', 12, 16), ('Female Toilet (16th Floor)', 12, 16),
('Male Toilet (17th Floor)', 12, 17), ('Female Toilet (17th Floor)', 12, 17),
('Male Toilet (18th Floor)', 12, 18), ('Female Toilet (18th Floor)', 12, 18),
('Male Toilet (19th Floor)', 12, 19), ('Female Toilet (19th Floor)', 12, 19),
('Male Toilet (20th Floor)', 12, 20), ('Female Toilet (20th Floor)', 12, 20),

-- Enrique Razon Sports Center (building_id: 13)
('9th Floor Basketball Courts', 13, 9),
('9th Floor Bleachers', 13, 9),
('Running Oval', 13, 8),
('7th Floor Basketball Courts', 13, 7),
('7th Floor Table Tennis Area', 13, 7),
('Athletes Gym', 13, 9),
('Male Locker Room (1st Floor)', 13, 1), ('Female Locker Room (1st Floor)', 13, 1),
('Male Toilet (2nd Floor)', 13, 2), ('Female Toilet (2nd Floor)', 13, 2),
('Male Toilet (3rd Floor)', 13, 3), ('Female Toilet (3rd Floor)', 13, 3),
('Male Toilet (4th Floor)', 13, 4), ('Female Toilet (4th Floor)', 13, 4),
('Male Toilet (5th Floor)', 13, 5), ('Female Toilet (5th Floor)', 13, 5),
('Male Toilet (6th Floor)', 13, 6), ('Female Toilet (6th Floor)', 13, 6),
('Male Toilet (7th Floor)', 13, 7), ('Female Toilet (7th Floor)', 13, 7),
('Male Toilet (8th Floor)', 13, 8), ('Female Toilet (8th Floor)', 13, 8),
('Male Toilet (9th Floor)', 13, 9), ('Female Toilet (9th Floor)', 13, 9),
('Male Locker Room (9th Floor)', 13, 9), ('Female Locker Room (9th Floor)', 13, 9),

-- Bloemen Hall (building_id: 14)
('Bloemen Cafeteria Seating Area', 14, 1);

INSERT INTO items (name, description, category_id, brand_id, surrendered_by, claimed_by, room_id, area_id, when_found, status) VALUES
-- =========================================================================
-- BRAND: Apple (brand_id: 1, category_id: 1 - Electronics)
-- =========================================================================
('iPhone 15 Pro', 'Natural titanium finish with a clear silicone case.', 1, 1, 1, NULL, 6, NULL, '2026-06-15 09:30:00', 'In Storage'),
('MacBook Air M2', 'Midnight blue color, left under a desk. Has a sticker on top.', 1, 1, 2, 1, NULL, 18, '2026-06-16 14:15:00', 'Claimed'),
('AirPods Pro Gen 2', 'Found inside a white charging case with a custom knitted sleeve.', 1, 1, 1, NULL, 15, NULL, '2026-06-18 11:00:00', 'In Storage'),
('Apple Pencil 2', 'Spotted rolling near the podium. No initials engraved.', 1, 1, 2, NULL, 48, NULL, '2026-06-19 16:45:00', 'In Storage'),
('iPad Pro 11-inch', 'Space gray color with a black smart folio cover.', 1, 1, 1, NULL, NULL, 51, '2026-06-20 08:20:00', 'In Storage'),

-- =========================================================================
-- BRAND: Samsung (brand_id: 2, category_id: 1 - Electronics)
-- =========================================================================
('Galaxy S24 Ultra', 'Titanium yellow, has a small scratch on the bottom left corner.', 1, 2, 2, NULL, 11, NULL, '2026-06-14 10:00:00', 'In Storage'),
('Galaxy Buds2 Pro', 'Purple charging case, found left on a student arm bench.', 1, 2, 1, 2, NULL, 2, '2026-06-15 13:00:00', 'Claimed'),
('Samsung Power Bank', '10,000mAh capacity, metallic silver variant.', 1, 2, 2, NULL, 78, NULL, '2026-06-17 15:30:00', 'In Storage'),
('Galaxy Watch 6', 'Black aluminum casing with a dark green sports band.', 1, 2, 1, NULL, NULL, 80, '2026-06-21 17:10:00', 'In Storage'),
('Galaxy Tab S9', 'Left inside a classroom pouch with its S-Pen attached.', 1, 2, 2, NULL, 34, NULL, '2026-06-22 12:25:00', 'In Storage'),

-- =========================================================================
-- BRAND: Huawei (brand_id: 3, category_id: 1 - Electronics)
-- =========================================================================
('Huawei Mate 60 Pro', 'Black variant with a rugged carbon fiber protective case.', 1, 3, 1, NULL, 112, NULL, '2026-06-12 11:45:00', 'In Storage'),
('FreeBuds 5i', 'Nebula black color variant, charging case feels slightly scuffed.', 1, 3, 2, NULL, NULL, 37, '2026-06-14 09:15:00', 'In Storage'),
('Huawei MatePad', 'Grey tablet running HarmonyOS, found near the front blackboard.', 1, 3, 1, NULL, 27, NULL, '2026-06-18 14:00:00', 'In Storage'),
('Huawei Watch Fit 3', 'Pink strap, metallic rose-gold buckle framework.', 1, 3, 2, 1, NULL, 64, '2026-06-20 10:30:00', 'Claimed'),
('Huawei Portable Router', 'White pocket Wi-Fi device found slipped under a plastic chair.', 1, 3, 1, NULL, 115, NULL, '2026-06-23 16:00:00', 'In Storage'),

-- =========================================================================
-- BRAND: JisuLife (brand_id: 4, category_id: 1 - Electronics)
-- =========================================================================
('JisuLife Handheld Fan Pro', 'Brown/tan color with a digital display showing battery levels.', 1, 4, 2, NULL, 1, NULL, '2026-06-15 12:00:00', 'In Storage'),
('JisuLife Neck Fan', 'White flexible wearable neck fan, found near a table pillar.', 1, 4, 1, NULL, NULL, 3, '2026-06-16 11:20:00', 'In Storage'),
('JisuLife Mini Fan Ultra', 'Dark blue handheld turbo fan with a metal carabiner base.', 1, 4, 2, 2, 45, NULL, '2026-06-19 14:55:00', 'Claimed'),
('JisuLife Desk Fan', 'Small green oscillating desk fan left sitting on a corner desk.', 1, 4, 1, NULL, 86, NULL, '2026-06-21 09:40:00', 'In Storage'),
('JisuLife Pocket Fan', 'Pink pocketable travel fan with integrated flashlight element.', 1, 4, 2, NULL, NULL, 68, '2026-06-24 15:10:00', 'In Storage'),

-- =========================================================================
-- BRAND: Goojodoq (brand_id: 5, category_id: 1 - Electronics)
-- =========================================================================
('Goojodoq Stylus Pen GD13', 'White iPad alternative active pencil, charges magnetically.', 1, 5, 1, NULL, 50, NULL, '2026-06-13 10:15:00', 'In Storage'),
('Goojodoq Wireless Keyboard', 'Ultra-thin white Bluetooth keyboard with a built-in touchpad slot.', 1, 5, 2, NULL, NULL, 47, '2026-06-15 16:30:00', 'In Storage'),
('Goojodoq Multiport Hub', 'Space grey USB-C hub adapter featuring 3 USB ports and an HDMI rail.', 1, 5, 1, 2, 60, NULL, '2026-06-17 13:10:00', 'Claimed'),
('Goojodoq Stylus Pen GD12', 'Matte black digital pen with a replacement tip stored inside.', 1, 5, 2, NULL, 103, NULL, '2026-06-20 11:45:00', 'In Storage'),
('Goojodoq Bluetooth Mouse', 'Slim ergonomic lavender clicker mouse, lightweight plastic body.', 1, 5, 1, NULL, NULL, 77, '2026-06-25 09:00:00', 'In Storage'),

-- =========================================================================
-- BRAND: Anker (brand_id: 6, category_id: 1 - Electronics)
-- =========================================================================
('Anker PowerCore 24K', 'Large brick power bank with built-in smart display screen tracking.', 1, 6, 2, NULL, 55, NULL, '2026-06-14 14:20:00', 'In Storage'),
('Anker Soundcore Space One', 'Over-ear noise-cancelling headphones in matte black finish tier.', 1, 6, 1, NULL, NULL, 49, '2026-06-16 08:35:00', 'In Storage'),
('Anker Nano USB-C Charger', 'Small mint green cube wall adapter plug module.', 1, 6, 2, 1, 91, NULL, '2026-06-18 12:50:00', 'Claimed'),
('Anker Soundcore 2 Speaker', 'Black rectangular waterproof speaker with red branding print text.', 1, 6, 1, NULL, 116, NULL, '2026-06-21 16:15:00', 'In Storage'),
('Anker 3-in-1 Charging Cable', 'Braided nylon multi-connector lead cord wire system wrapped tight.', 1, 6, 2, NULL, NULL, 90, '2026-06-24 10:40:00', 'In Storage'),

-- =========================================================================
-- BRAND: AquaFlask (brand_id: 7, category_id: 2 - Tumblers & Bottles)
-- =========================================================================
('AquaFlask 22oz Marine Blue', 'Equipped with a teal silicone protective boot sleeve element.', 2, 7, 1, NULL, 2, NULL, '2026-06-14 11:00:00', 'In Storage'),
('AquaFlask 32oz Arctic White', 'Features black paracord handle accessory strap locked securely.', 2, 7, 2, NULL, NULL, 21, '2026-06-15 15:40:00', 'In Storage'),
('AquaFlask 40oz Onyx Black', 'Massive capacity flask bottle with noticeable silver surface scratch.', 2, 7, 1, 2, 70, NULL, '2026-06-18 09:30:00', 'Claimed'),
('AquaFlask 18oz Sorbet Pink', 'Small slender personal drinking bottle profile config setting.', 2, 7, 2, NULL, 80, NULL, '2026-06-19 13:20:00', 'In Storage'),
('AquaFlask 22oz Grape Purple', 'Fitted with a white replacement wide-mouth straw lid caps assembly.', 2, 7, 1, NULL, NULL, 5, '2026-06-22 10:10:00', 'In Storage'),

-- =========================================================================
-- BRAND: Hydro Flask (brand_id: 8, category_id: 2 - Tumblers & Bottles)
-- =========================================================================
('Hydro Flask 24oz Cobalt', 'Standard wide-mouth insulated drinking flask container component.', 2, 8, 2, NULL, 11, NULL, '2026-06-13 14:00:00', 'In Storage'),
('Hydro Flask 32oz Pacific Blue', 'Features a prominent flex cap style handle system structure asset.', 2, 8, 1, NULL, NULL, 48, '2026-06-15 10:50:00', 'In Storage'),
('Hydro Flask 18oz Olive', 'Earth-tone olive green aesthetic model, slightly dented baseline rim.', 2, 8, 2, 1, 110, NULL, '2026-06-17 11:15:00', 'Claimed'),
('Hydro Flask 40oz Sunflower Yellow', 'Bright yellow paint color coating with a custom vinyl flower sticker.', 2, 8, 1, NULL, 102, NULL, '2026-06-20 15:00:00', 'In Storage'),
('Hydro Flask 21oz Seagrass', 'Light pastel mint-green exterior shell with standard twist cap.', 2, 8, 2, NULL, NULL, 74, '2026-06-23 08:45:00', 'In Storage'),

-- =========================================================================
-- BRAND: Gucci (brand_id: 9, category_id: 3/5 - Wallets / Apparel & Bags)
-- =========================================================================
('Gucci GG Marmont Wallet', 'Black leather bi-fold layout sporting gold interlaced logo design.', 3, 9, 1, NULL, 21, NULL, '2026-06-14 16:30:00', 'In Storage'),
('Gucci Ophidia Card Case', 'Signature beige canvas profile displaying red/green center canvas stripe.', 3, 9, 2, NULL, NULL, 4, '2026-06-16 11:00:00', 'In Storage'),
('Gucci Canvas Backpack', 'Monogram print pattern luxury pack compartment build framework layout.', 5, 9, 1, 2, 115, NULL, '2026-06-17 14:25:00', 'Claimed'),
('Gucci Black Leather Belt', 'Gold-plated interlocking double G logo buckle system attachment mechanism.', 5, 9, 2, NULL, NULL, 79, '2026-06-19 12:00:00', 'In Storage'),
('Gucci Kingsnake Wallet', 'Features the classic iconic printed coral snake graphic layout artwork matrix.', 3, 9, 1, NULL, 111, NULL, '2026-06-21 15:40:00', 'In Storage'),

-- =========================================================================
-- BRAND: Louis Vuitton (brand_id: 10, category_id: 3/5 - Wallets / Apparel & Bags)
-- =========================================================================
('LV Monogram Pocket Organizer', 'Classic dark brown luxury checkerboard leather styling matrix pattern.', 3, 10, 2, NULL, 46, NULL, '2026-06-12 09:20:00', 'In Storage'),
('LV Damier Ebene Cardholder', 'Distinct geometric brown gridded profile, contains ID card inside.', 3, 10, 1, NULL, NULL, 34, '2026-06-14 14:10:00', 'In Storage'),
('LV Speedy 25 Handbag', 'Signature luxury purse handle piece, found tucked under cafeteria bench.', 5, 10, 2, 1, NULL, 92, '2026-06-16 13:50:00', 'Claimed'),
('LV Zippy Coin Purse', 'Round zipper layout design enclosing inner multi-pocket accordion separator.', 3, 10, 1, NULL, 106, NULL, '2026-06-19 10:00:00', 'In Storage'),
('LV Eclipse Key Pouch', 'Charcoal black and dark gray monogrammed canvas tool chain loop holder.', 5, 10, 2, NULL, NULL, 23, '2026-06-22 16:45:00', 'In Storage'),

-- =========================================================================
-- BRAND: Coach (brand_id: 11, category_id: 3/5 - Wallets / Apparel & Bags)
-- =========================================================================
('Coach Slim Accordion Zip', 'Crossgrain black premium leather outer skin configuration template tier.', 3, 11, 1, NULL, 3, NULL, '2026-06-13 11:30:00', 'In Storage'),
('Coach Wyn Small Wallet', 'Envelope flap button closure utility format style casing profile.', 3, 11, 2, NULL, NULL, 35, '2026-06-15 15:20:00', 'In Storage'),
('Coach Court Backpack', 'White pebble surface treatment style material print with brown trim borders.', 5, 11, 1, 2, NULL, 50, '2026-06-18 10:45:00', 'Claimed'),
('Coach Signature Lanyard', 'Canvas neck loop badge module bracket fixture string accessory package.', 5, 11, 2, NULL, 76, NULL, '2026-06-20 12:15:00', 'In Storage'),
('Coach Leather Card Slot', 'Dark navy blue dye coat tone premium finish wallet strip line block.', 3, 11, 1, NULL, NULL, 24, '2026-06-23 14:00:00', 'In Storage'),

-- =========================================================================
-- BRAND: Uniqlo (brand_id: 12, category_id: 5 - Apparel & Bags)
-- =========================================================================
('Uniqlo Round Mini Shoulder Bag', 'Cream colored crescent moon shape cross-body canvas sling bag.', 5, 12, 2, NULL, 5, NULL, '2026-06-14 12:15:00', 'In Storage'),
('Uniqlo AIRism Mesh Jacket', 'Light grey athletic active hoodie jacket zip-up piece jacket.', 5, 12, 1, NULL, NULL, 67, '2026-06-15 16:40:00', 'In Storage'),
('Uniqlo Utility Sling Bag', 'Dark olive green cargo tactical chest pack bundle setup assembly.', 5, 12, 2, 1, NULL, 7, '2026-06-17 11:30:00', 'Claimed'),
('Uniqlo Flannel Shirt', 'Red and black checkered pattern button-up warm overshirt cloth.', 5, 12, 1, NULL, 111, NULL, '2026-06-19 14:20:00', 'In Storage'),
('Uniqlo Foldable Umbrella', 'Black compact travel umbrella tube tucked inside its matching sheath pouch.', 5, 12, 2, NULL, NULL, 69, '2026-06-21 08:50:00', 'In Storage'),

-- =========================================================================
-- BRAND: Zara (brand_id: 13, category_id: 5 - Apparel & Bags)
-- =========================================================================
('Zara Faux Leather Tote', 'Large brown open tote bag compartment structure frame system unit.', 5, 13, 1, NULL, 12, NULL, '2026-06-12 15:30:00', 'In Storage'),
('Zara Puffer Jacket', 'Thick dark navy thermal insulation winter-style outer shell wear coat.', 5, 13, 2, NULL, NULL, 22, '2026-06-14 10:10:00', 'In Storage'),
('Zara Quilted Crossbody Bag', 'Black stitched square pattern bag layout with heavy gold chain drop straps.', 5, 13, 1, 2, 45, NULL, '2026-06-16 13:00:00', 'Claimed'),
('Zara Bomber Jacket', 'Khaki green satin surface aviator flight wear outfit uniform matrix.', 5, 13, 2, NULL, 64, NULL, '2026-06-19 11:45:00', 'In Storage'),
('Zara Canvas Shopping Bag', 'Eco aesthetic natural cloth container canvas sack print layout banner.', 5, 13, 1, NULL, NULL, 52, '2026-06-22 16:10:00', 'In Storage'),

-- =========================================================================
-- BRAND: H&M (brand_id: 14, category_id: 5 - Apparel & Bags)
-- =========================================================================
('H&M Canvas Backpack', 'Simple minimal school knapsack in light pink washed fabric weave texturize.', 5, 14, 2, NULL, 2, NULL, '2026-06-13 13:40:00', 'In Storage'),
('H&M Denim Jacket', 'Classic blue vintage wash cotton button denim worker wear layout layer.', 5, 14, 1, NULL, NULL, 4, '2026-06-15 09:20:00', 'In Storage'),
('H&M Ribbed Knit Beanie', 'Soft gray elastic winter cold-weather skull cap accessory block piece.', 5, 14, 2, 1, 104, NULL, '2026-06-17 15:55:00', 'Claimed'),
('H&M Waist Bag Belt', 'Sporty neon yellow running pouch fanny pack container compartment.', 5, 14, 1, NULL, NULL, 6, '2026-06-20 11:10:00', 'In Storage'),
('H&M Clear Tote Bag', 'Transparent see-through PVC plastic security screening stadium carry-all bag.', 5, 14, 2, NULL, 114, NULL, '2026-06-23 14:30:00', 'In Storage'),

-- =========================================================================
-- BRAND: none (brand_id: 15, category_id: 4/6 - Documents / Others)
-- =========================================================================
('DLSU ID Card', 'Found inside a generic clear plastic badge sleeve. Belongs to a student.', 4, 15, 1, NULL, 1, NULL, '2026-06-14 08:30:00', 'In Storage'),
('Brown Leather Keyring', 'Holds three silver metal door entry keys and a mini flashlight fob item.', 6, 15, 2, NULL, NULL, 22, '2026-06-15 11:20:00', 'In Storage'),
('Calculus Textbook', 'TC7 single variable math book edition volume, name page missing entirely.', 6, 15, 1, 2, 77, NULL, '2026-06-16 16:45:00', 'Claimed'),
('Black Umbrella', 'Long standard long-stem umbrella with an elegant wooden J-hook handle end.', 6, 15, 2, NULL, NULL, 5, '2026-06-19 13:15:00', 'In Storage'),
('Clear Long Envelope', 'Contains printed photocopied reading materials and loose leaf lecture papers.', 4, 15, 1, NULL, 62, NULL, '2026-06-21 10:05:00', 'In Storage');

-- =========================================================================
-- IMAGE LINKS TO EACH ITEM IN THE ITEMS FOLDER
-- =========================================================================
INSERT INTO items_images (item_id, img_filepath) VALUES
(1, '../../assets/ITEMS/1.png'), (1, '../../assets/ITEMS/2.png'), (1, '../../assets/ITEMS/3.png'), (1, '../../assets/ITEMS/4.png'),
(2, '../../assets/ITEMS/5.png'), (2, '../../assets/ITEMS/6.png'), (2, '../../assets/ITEMS/7.png'), (2, '../../assets/ITEMS/8.png'),
(3, '../../assets/ITEMS/9.png'), (3, '../../assets/ITEMS/10.png'), (3, '../../assets/ITEMS/11.png'), (3, '../../assets/ITEMS/12.png'),
(4, '../../assets/ITEMS/13.png'), (4, '../../assets/ITEMS/14.png'), (4, '../../assets/ITEMS/15.png'), (4, '../../assets/ITEMS/16.png'),
(5, '../../assets/ITEMS/17.png'), (5, '../../assets/ITEMS/18.png'), (5, '../../assets/ITEMS/19.png'), (5, '../../assets/ITEMS/20.png'),
(6, '../../assets/ITEMS/21.png'), (6, '../../assets/ITEMS/22.png'), (6, '../../assets/ITEMS/23.png'), (6, '../../assets/ITEMS/24.png'),
(7, '../../assets/ITEMS/25.png'), (7, '../../assets/ITEMS/26.png'), (7, '../../assets/ITEMS/27.png'), (7, '../../assets/ITEMS/28.png'),
(8, '../../assets/ITEMS/29.png'), (8, '../../assets/ITEMS/30.png'), (8, '../../assets/ITEMS/31.png'), (8, '../../assets/ITEMS/32.png'),
(9, '../../assets/ITEMS/33.png'), (9, '../../assets/ITEMS/34.png'), (9, '../../assets/ITEMS/35.png'), (9, '../../assets/ITEMS/36.png'),
(10, '../../assets/ITEMS/37.png'), (10, '../../assets/ITEMS/38.png'), (10, '../../assets/ITEMS/39.png'), (10, '../../assets/ITEMS/40.png'),
(11, '../../assets/ITEMS/41.png'), (11, '../../assets/ITEMS/42.png'), (11, '../../assets/ITEMS/43.png'), (11, '../../assets/ITEMS/44.png'),
(12, '../../assets/ITEMS/45.png'), (12, '../../assets/ITEMS/46.png'), (12, '../../assets/ITEMS/47.png'), (12, '../../assets/ITEMS/48.png'),
(13, '../../assets/ITEMS/49.png'), (13, '../../assets/ITEMS/50.png'), (13, '../../assets/ITEMS/51.png'), (13, '../../assets/ITEMS/52.png'),
(14, '../../assets/ITEMS/53.png'), (14, '../../assets/ITEMS/54.png'), (14, '../../assets/ITEMS/55.png'), (14, '../../assets/ITEMS/56.png'),
(15, '../../assets/ITEMS/57.png'), (15, '../../assets/ITEMS/58.png'), (15, '../../assets/ITEMS/59.png'), (15, '../../assets/ITEMS/60.png'),
(16, '../../assets/ITEMS/61.png'), (16, '../../assets/ITEMS/62.png'), (16, '../../assets/ITEMS/63.png'), (16, '../../assets/ITEMS/64.png'),
(17, '../../assets/ITEMS/65.png'), (17, '../../assets/ITEMS/66.png'), (17, '../../assets/ITEMS/67.png'), (17, '../../assets/ITEMS/68.png'),
(18, '../../assets/ITEMS/69.png'), (18, '../../assets/ITEMS/70.png'), (18, '../../assets/ITEMS/71.png'), (18, '../../assets/ITEMS/72.png'),
(19, '../../assets/ITEMS/73.png'), (19, '../../assets/ITEMS/74.png'), (19, '../../assets/ITEMS/75.png'), (19, '../../assets/ITEMS/76.png'),
(20, '../../assets/ITEMS/77.png'), (20, '../../assets/ITEMS/78.png'), (20, '../../assets/ITEMS/79.png'), (20, '../../assets/ITEMS/80.png'),
(21, '../../assets/ITEMS/81.png'), (21, '../../assets/ITEMS/82.png'), (21, '../../assets/ITEMS/83.png'), (21, '../../assets/ITEMS/84.png'),
(22, '../../assets/ITEMS/85.png'), (22, '../../assets/ITEMS/86.png'), (22, '../../assets/ITEMS/87.png'), (22, '../../assets/ITEMS/88.png'),
(23, '../../assets/ITEMS/89.png'), (23, '../../assets/ITEMS/90.png'), (23, '../../assets/ITEMS/91.png'), (23, '../../assets/ITEMS/92.png'),
(24, '../../assets/ITEMS/93.png'), (24, '../../assets/ITEMS/94.png'), (24, '../../assets/ITEMS/95.png'), (24, '../../assets/ITEMS/96.png'),
(25, '../../assets/ITEMS/97.png'), (25, '../../assets/ITEMS/98.png'), (25, '../../assets/ITEMS/99.png'), (25, '../../assets/ITEMS/100.png'),
(26, '../../assets/ITEMS/101.png'), (26, '../../assets/ITEMS/102.png'), (26, '../../assets/ITEMS/103.png'), (26, '../../assets/ITEMS/104.png'),
(27, '../../assets/ITEMS/105.png'), (27, '../../assets/ITEMS/106.png'), (27, '../../assets/ITEMS/107.png'), (27, '../../assets/ITEMS/108.png'),
(28, '../../assets/ITEMS/109.png'), (28, '../../assets/ITEMS/110.png'), (28, '../../assets/ITEMS/111.png'), (28, '../../assets/ITEMS/112.png'),
(29, '../../assets/ITEMS/113.png'), (29, '../../assets/ITEMS/114.png'), (29, '../../assets/ITEMS/115.png'), (29, '../../assets/ITEMS/116.png'),
(30, '../../assets/ITEMS/117.png'), (30, '../../assets/ITEMS/118.png'), (30, '../../assets/ITEMS/119.png'), (30, '../../assets/ITEMS/120.png'),
(31, '../../assets/ITEMS/121.png'), (31, '../../assets/ITEMS/122.png'), (31, '../../assets/ITEMS/123.png'), (31, '../../assets/ITEMS/124.png'),
(32, '../../assets/ITEMS/125.png'), (32, '../../assets/ITEMS/126.png'), (32, '../../assets/ITEMS/127.png'), (32, '../../assets/ITEMS/128.png'),
(33, '../../assets/ITEMS/129.png'), (33, '../../assets/ITEMS/130.png'), (33, '../../assets/ITEMS/131.png'), (33, '../../assets/ITEMS/132.png'),
(34, '../../assets/ITEMS/133.png'), (34, '../../assets/ITEMS/134.png'), (34, '../../assets/ITEMS/135.png'), (34, '../../assets/ITEMS/136.png'),
(35, '../../assets/ITEMS/137.png'), (35, '../../assets/ITEMS/138.png'), (35, '../../assets/ITEMS/139.png'), (35, '../../assets/ITEMS/140.png'),
(36, '../../assets/ITEMS/141.png'), (36, '../../assets/ITEMS/142.png'), (36, '../../assets/ITEMS/143.png'), (36, '../../assets/ITEMS/144.png'),
(37, '../../assets/ITEMS/145.png'), (37, '../../assets/ITEMS/146.png'), (37, '../../assets/ITEMS/147.png'), (37, '../../assets/ITEMS/148.png'), (37, '../../assets/ITEMS/149.png'),
(38, '../../assets/ITEMS/150.png'), (38, '../../assets/ITEMS/151.png'), (38, '../../assets/ITEMS/152.png'), (38, '../../assets/ITEMS/153.png'),
(39, '../../assets/ITEMS/154.png'), (39, '../../assets/ITEMS/155.png'), (39, '../../assets/ITEMS/156.png'), (39, '../../assets/ITEMS/157.png'),
(40, '../../assets/ITEMS/158.png'), (40, '../../assets/ITEMS/159.png'), (40, '../../assets/ITEMS/160.png'), (40, '../../assets/ITEMS/161.png'),
(41, '../../assets/ITEMS/162.png'), (41, '../../assets/ITEMS/163.png'), (41, '../../assets/ITEMS/164.png'), (41, '../../assets/ITEMS/165.png'),
(42, '../../assets/ITEMS/166.png'), (42, '../../assets/ITEMS/167.png'), (42, '../../assets/ITEMS/168.png'), (42, '../../assets/ITEMS/169.png'),
(43, '../../assets/ITEMS/170.png'), (43, '../../assets/ITEMS/171.png'), (43, '../../assets/ITEMS/172.png'), (43, '../../assets/ITEMS/173.png'),
(44, '../../assets/ITEMS/174.png'), (44, '../../assets/ITEMS/175.png'), (44, '../../assets/ITEMS/176.png'), (44, '../../assets/ITEMS/177.png'),
(45, '../../assets/ITEMS/178.png'), (45, '../../assets/ITEMS/179.png'), (45, '../../assets/ITEMS/180.png'), (45, '../../assets/ITEMS/181.png'),
(46, '../../assets/ITEMS/182.png'), (46, '../../assets/ITEMS/183.png'), (46, '../../assets/ITEMS/184.png'), (46, '../../assets/ITEMS/185.png'),
(47, '../../assets/ITEMS/186.png'), (47, '../../assets/ITEMS/187.png'), (47, '../../assets/ITEMS/188.png'), (47, '../../assets/ITEMS/189.png'),
(48, '../../assets/ITEMS/190.png'), (48, '../../assets/ITEMS/191.png'), (48, '../../assets/ITEMS/192.png'), (48, '../../assets/ITEMS/193.png'),
(49, '../../assets/ITEMS/194.png'), (49, '../../assets/ITEMS/195.png'), (49, '../../assets/ITEMS/196.png'), (49, '../../assets/ITEMS/197.png'),
(50, '../../assets/ITEMS/198.png'), (50, '../../assets/ITEMS/199.png'), (50, '../../assets/ITEMS/200.png'), (50, '../../assets/ITEMS/201.png'),
(51, '../../assets/ITEMS/202.png'), (51, '../../assets/ITEMS/203.png'), (51, '../../assets/ITEMS/204.png'), (51, '../../assets/ITEMS/205.png'),
(52, '../../assets/ITEMS/206.png'), (52, '../../assets/ITEMS/207.png'), (52, '../../assets/ITEMS/208.png'),
(53, '../../assets/ITEMS/209.png'), (53, '../../assets/ITEMS/210.png'), (53, '../../assets/ITEMS/211.png'),
(54, '../../assets/ITEMS/212.png'), (54, '../../assets/ITEMS/213.png'), (54, '../../assets/ITEMS/214.png'),
(55, '../../assets/ITEMS/215.png'), (55, '../../assets/ITEMS/216.png'), (55, '../../assets/ITEMS/217.png'), (55, '../../assets/ITEMS/218.png'),
(56, '../../assets/ITEMS/219.png'), (56, '../../assets/ITEMS/220.png'), (56, '../../assets/ITEMS/221.png'),
(57, '../../assets/ITEMS/222.png'), (57, '../../assets/ITEMS/223.png'), (57, '../../assets/ITEMS/224.png'),
(58, '../../assets/ITEMS/225.png'), (58, '../../assets/ITEMS/226.png'), (58, '../../assets/ITEMS/227.png'),
(59, '../../assets/ITEMS/228.png'), (59, '../../assets/ITEMS/229.png'), (59, '../../assets/ITEMS/230.png'),
(60, '../../assets/ITEMS/231.png'), (60, '../../assets/ITEMS/232.png'), (60, '../../assets/ITEMS/233.png'),
(61, '../../assets/ITEMS/234.png'), (61, '../../assets/ITEMS/235.png'), (61, '../../assets/ITEMS/236.png'),
(62, '../../assets/ITEMS/237.png'), (62, '../../assets/ITEMS/238.png'), (62, '../../assets/ITEMS/239.png'),
(63, '../../assets/ITEMS/240.png'), (63, '../../assets/ITEMS/241.png'), (63, '../../assets/ITEMS/242.png'),
(64, '../../assets/ITEMS/243.png'), (64, '../../assets/ITEMS/244.png'), (64, '../../assets/ITEMS/245.png'),
(65, '../../assets/ITEMS/246.png'), (65, '../../assets/ITEMS/247.png'), (65, '../../assets/ITEMS/248.png'),
(66, '../../assets/ITEMS/249.png'), (66, '../../assets/ITEMS/250.png'), (66, '../../assets/ITEMS/251.png'),
(67, '../../assets/ITEMS/252.png'), (67, '../../assets/ITEMS/253.png'), (67, '../../assets/ITEMS/254.png'),
(68, '../../assets/ITEMS/255.png'), (68, '../../assets/ITEMS/256.png'), (68, '../../assets/ITEMS/257.png'),
(69, '../../assets/ITEMS/258.png'), (69, '../../assets/ITEMS/259.png'), (69, '../../assets/ITEMS/260.png'),
(70, '../../assets/ITEMS/261.png'), (70, '../../assets/ITEMS/262.png'), (70, '../../assets/ITEMS/263.png'),
(71, '../../assets/ITEMS/264.png'), (71, '../../assets/ITEMS/265.png'), (71, '../../assets/ITEMS/266.png'),
(72, '../../assets/ITEMS/267.png'), (72, '../../assets/ITEMS/268.png'), (72, '../../assets/ITEMS/269.png'),
(73, '../../assets/ITEMS/270.png'), (73, '../../assets/ITEMS/271.png'), (73, '../../assets/ITEMS/272.png'),
(74, '../../assets/ITEMS/273.png'), (74, '../../assets/ITEMS/274.png'), (74, '../../assets/ITEMS/275.png'),
(75, '../../assets/ITEMS/276.png'), (75, '../../assets/ITEMS/277.png'), (75, '../../assets/ITEMS/278.png');

-- =========================================================================
-- 1. CLAIM REQUESTS (5 Records)
-- Mapped directly from the items table to ensure data consistency
-- =========================================================================
INSERT INTO reports (
    student_id, item_name, item_description, category_id, 
    brand_id, item_id, room_id, area_id, extra_details, when_found, 
    when_lost, reviewed_by, status, type
)
SELECT 1, name, description, category_id, brand_id, item_id, room_id, area_id, 'The pink fan is mine. Here is a selfie with me wearing it', NULL, when_found, NULL, 'Active', 'Claim request' FROM items WHERE item_id = 20 UNION ALL
SELECT 2, name, description, category_id, brand_id, item_id, room_id, area_id, 'I lost my Macbook because I went to the toilet, it is mine. I can unlock it with my password', NULL, when_found, NULL, 'Active', 'Claim request' FROM items WHERE item_id = 2 UNION ALL
SELECT 1, name, description, category_id, brand_id, item_id, room_id, area_id, 'The color is the same it is mine, Give me back my bottle', NULL, when_found, NULL, 'Active', 'Claim request' FROM items WHERE item_id = 36 UNION ALL
SELECT 2, name, description, category_id, brand_id, item_id, room_id, area_id, 'The umbrella is mine, here is apic of it with my bag. Check my personal keychains', NULL, when_found, NULL, 'Active', 'Claim request' FROM items WHERE item_id = 60 UNION ALL
SELECT 1, name, description, category_id, brand_id, item_id, room_id, area_id, 'The bag is mine, I wore it on Animo Christmas day and left it in school', NULL, when_found, NULL, 'Active', 'Claim request' FROM items WHERE item_id = 56;


-- =========================================================================
-- 2. LOSS REPORTS (4 Records) & SURRENDER FORMS (5 Records)
-- Text fields use '*'. ID fields use 0 to avoid numeric conflicts.
-- =========================================================================
INSERT INTO reports (
    student_id, item_name, item_description, category_id, brand_id, 
    item_id, room_id, area_id, extra_details, when_found, 
    when_lost, reviewed_by, status, type
) VALUES
-- --- Loss Reports ---
(1, 'BYD Seal 5 Car key', 'Black EV Car Key', 1, 15, NULL, 9, NULL, NULL, NULL, '2026-07-01 10:00:00', NULL, 'Active', 'Loss Report'),
(2, 'Hua Mulan Comb', '15cm Peach Wood Comb in Case', 6, 15, NULL, NULL, 10, NULL, NULL, '2026-07-02 14:30:00', NULL, 'Active', 'Loss Report'),
(1, 'Black Nike Shorts', '12 inch basketball shorts', 5, 15, NULL, 8, NULL, NULL, NULL, '2026-07-03 09:15:00', NULL, 'Active', 'Loss Report'),
(2, 'Sunnies Glasses Pouch', 'Leather beige color soft pouch', 6, 15, NULL, NULL, 11, NULL, NULL, '2026-07-05 16:45:00', NULL, 'Active', 'Loss Report'),

-- --- Surrender Forms ---
(1, 'Airpods 2', 'White airpods with some scratches below', 1, 1, NULL, 1, NULL, NULL, '2026-07-06 08:00:00', NULL, NULL, 'Active', 'Surrender Form'),
(2, 'Black Laptop case', 'Black case with 1 zipper, no pockets', 5, 15, NULL, 1, NULL, NULL, '2026-07-06 11:20:00', NULL, NULL, 'Active', 'Surrender Form'),
(1, 'DLSU ID lace', 'Frosh id lace, some dirt and looks old', 5, 15, NULL, 3, NULL, NULL, '2026-07-07 13:10:00', NULL, NULL, 'Active', 'Surrender Form'),
(2, 'Sunnies Eyeglasses', 'Black Square shaped glasses', 5, 15, NULL, NULL, 4, NULL, '2026-07-08 10:05:00', NULL, NULL, 'Active', 'Surrender Form'),
(1, 'Student Beep Card', 'Beep Card for Juan De La Cruz', 4, 15, NULL, NULL, 9, NULL, '2026-07-08 15:50:00', NULL, NULL, 'Active', 'Surrender Form');

-- Clear old image mapping data to prevent duplicates
TRUNCATE TABLE reports_images;

-- =========================================================================
-- 1. CLAIM REQUESTS (1 picture per record)
-- Dynamically matches the report_id based on the specific items requested
-- =========================================================================
INSERT INTO reports_images (report_id, img_filepath)
SELECT report_id, '../../assets/IMG_ClaimRequest/1.png' FROM reports WHERE item_id = 20 AND type = 'Claim request' UNION ALL
SELECT report_id, '../../assets/IMG_ClaimRequest/2.png' FROM reports WHERE item_id = 2  AND type = 'Claim request' UNION ALL
SELECT report_id, '../../assets/IMG_ClaimRequest/3.png' FROM reports WHERE item_id = 36 AND type = 'Claim request' UNION ALL
SELECT report_id, '../../assets/IMG_ClaimRequest/4.png' FROM reports WHERE item_id = 60 AND type = 'Claim request' UNION ALL
SELECT report_id, '../../assets/IMG_ClaimRequest/5.png' FROM reports WHERE item_id = 56 AND type = 'Claim request';

-- =========================================================================
-- 2. LOSS REPORTS (2 pictures per record)
-- Dynamically finds the right report_id using your unique item_names
-- =========================================================================
INSERT INTO reports_images (report_id, img_filepath)
SELECT report_id, '../../assets/IMG_LostReport/1.png' FROM reports WHERE item_name = 'BYD Seal 5 Car key' UNION ALL
SELECT report_id, '../../assets/IMG_LostReport/2.png' FROM reports WHERE item_name = 'BYD Seal 5 Car key' UNION ALL

SELECT report_id, '../../assets/IMG_LostReport/3.png' FROM reports WHERE item_name = 'Hua Mulan Comb' UNION ALL
SELECT report_id, '../../assets/IMG_LostReport/4.png' FROM reports WHERE item_name = 'Hua Mulan Comb' UNION ALL

SELECT report_id, '../../assets/IMG_LostReport/5.png' FROM reports WHERE item_name = 'Black Nike Shorts' UNION ALL
SELECT report_id, '../../assets/IMG_LostReport/6.png' FROM reports WHERE item_name = 'Black Nike Shorts' UNION ALL

SELECT report_id, '../../assets/IMG_LostReport/7.png' FROM reports WHERE item_name = 'Sunnies Glasses Pouch' UNION ALL
SELECT report_id, '../../assets/IMG_LostReport/8.png' FROM reports WHERE item_name = 'Sunnies Glasses Pouch';

-- =========================================================================
-- 3. SURRENDER FORMS (2 pictures per record)
-- Dynamically finds the right report_id using your unique item_names
-- =========================================================================
INSERT INTO reports_images (report_id, img_filepath)
SELECT report_id, '../../assets/IMG_SurrenderForm/surrender1.png' FROM reports WHERE item_name = 'Airpods 2' UNION ALL
SELECT report_id, '../../assets/IMG_SurrenderForm/surrender2.png' FROM reports WHERE item_name = 'Airpods 2' UNION ALL

SELECT report_id, '../../assets/IMG_SurrenderForm/surrender3.png' FROM reports WHERE item_name = 'Black Laptop case' UNION ALL
SELECT report_id, '../../assets/IMG_SurrenderForm/surrender4.png' FROM reports WHERE item_name = 'Black Laptop case' UNION ALL

SELECT report_id, '../../assets/IMG_SurrenderForm/surrender5.png' FROM reports WHERE item_name = 'DLSU ID lace' UNION ALL
SELECT report_id, '../../assets/IMG_SurrenderForm/surrender6.png' FROM reports WHERE item_name = 'DLSU ID lace' UNION ALL

SELECT report_id, '../../assets/IMG_SurrenderForm/surrender7.png' FROM reports WHERE item_name = 'Sunnies Eyeglasses' UNION ALL
SELECT report_id, '../../assets/IMG_SurrenderForm/surrender8.png' FROM reports WHERE item_name = 'Sunnies Eyeglasses' UNION ALL

SELECT report_id, '../../assets/IMG_SurrenderForm/surrender9.png'  FROM reports WHERE item_name = 'Student Beep Card' UNION ALL
SELECT report_id, '../../assets/IMG_SurrenderForm/surrender10.png' FROM reports WHERE item_name = 'Student Beep Card';