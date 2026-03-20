CREATE TABLE `news_messages` (
         `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
         `title` VARCHAR(255) NOT NULL,
         `intro` TEXT NOT NULL,
         `description` TEXT NOT NULL,
         `active` TINYINT(1) NOT NULL DEFAULT 1,
         `publication_date` DATETIME NOT NULL,
         PRIMARY KEY (`id`)
);

INSERT INTO `news_messages` (`id`, `title`, `intro`, `description`, `active`, `publication_date`)
VALUES
    (1,'Vertical Forest Tower Opens in Singapore','A lush residential tower covered in greenery officially opens.','Singapore unveiled its newest residential landmark: the Vertical Forest Tower. Featuring over 9,000 plants and 120 species of trees, the building integrates eco-design with premium urban living, reducing heat absorption and improving air quality.',1,'2026-03-04 10:00:00'),
    (2,'Oslo Unveils Timber Cultural Center','A modern cultural hub built almost entirely out of timber.','Oslo has presented its new Timber Cultural Center, designed to showcase sustainable construction methods. The structure uses cross-laminated timber and massive glass façades, merging Scandinavian minimalism with future-proof engineering.',1,'2026-04-10 14:30:00'),
    (3,'Futuristic Library Opens in Seoul','Seoul invests in knowledge with a remarkable new architectural statement.','The Seoul Future Library introduces rolling curved walls, AI-supported wayfinding, and an immersive learning hall. The interior includes flexible event spaces and floating mezzanines designed for both quiet study and social interaction.',1,'2026-03-15 09:15:00'),
    (4,'Rotterdam’s River Museum Redesign Completed','Major renovation of Rotterdam’s riverfront museum finalized.','Rotterdam revealed the redesigned River Museum, featuring a transparent floating gallery that extends over the Maas. Architects focused on integrating water dynamics, LED-enhanced structural beams, and panoramic viewing decks.',0,'2026-03-20 11:45:00'),
    (5,'Tokyo Launches Adaptive Stadium Concept','A multi-purpose stadium that adapts to weather and audience size.','Tokyo introduced an adaptive stadium concept with rotating façade panels and a retractable roof that optimizes airflow. Seating modules can expand or compact, enabling efficient use during concerts, sports, and cultural festivals.',1,'2026-04-28 18:20:00'),
    (6,'New York Highline Annex Opens to Public','A new extension of the elevated park revitalizes Manhattan’s west side.','New York’s Highline Annex extends the iconic park with new bridges, skywalks and sculptural seating areas. The design highlights industrial heritage while integrating sustainable plant ecosystems and solar lighting.',1,'2026-04-12 13:00:00'),
    (7,'Dubai Showcase Sphere Hotel Prototype','A spherical hotel concept pushes boundaries of hospitality architecture.','Dubai has revealed a prototype for a globe-shaped luxury hotel featuring 360° interior rooms, autonomous shuttle docks, and interactive façade lighting. The concept blends futuristic geometry with cutting-edge AI guest services.',1,'2026-05-02 16:45:00');
