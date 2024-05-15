CREATE TABLE `contact` (
    `id_contact` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(80) NOT NULL,
    `email` varchar(40) NOT NULL,
    `feedback` varchar(150) NOT NULL,
    PRIMARY KEY (`id_contact`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `visits` (
  `visit_id` int(11) NOT NULL AUTO_INCREMENT,
  `person_id` int(11) NOT NULL,
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `relationship` varchar(45) NOT NULL,
  `visit_nature` varchar(45) NOT NULL,
  `photo` mediumblob NOT NULL,
  `source_of_income` varchar(45) DEFAULT NULL,
  `date` date NOT NULL,
  `visit_start` time NOT NULL,
  `visit_end` time NOT NULL,
  `is_active` int(1) NOT NULL,
  PRIMARY KEY (`visit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- insert visits

INSERT INTO `visits` (`visit_id`, `person_id`, `first_name`, `last_name`, `relationship`, `visit_nature`, `photo`, `source_of_income`, `date`, `visit_start`, `visit_end`, `is_active`) 
VALUES (1, 1, 'Gigel', 'Phrone', 'relative', 'friendship', 0x433a5c78616d70705c746d705c706870363830382e746d70, 'self-employed', '2023-05-11', '12:00:00', '13:00:00', 1);


CREATE TABLE `users` (
    `user_id` int(11) NOT NULL AUTO_INCREMENT,
    `email` varchar(40) NOT NULL,
    `password` varchar(150) NOT NULL,
    `first_name` varchar(45) NOT NULL,
    `last_name` varchar(45) NOT NULL,
    `photo` mediumblob DEFAULT NULL,
    `function` varchar(45) NOT NULL,
    `reset_token_hash` VARCHAR(64) NULL DEFAULT NULL,
    `reset_token_expires_at` DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- insert users

INSERT INTO `users` (`user_id`, `email`, `password`, `first_name`, `last_name`, `photo`, `function`, `reset_token_hash`, `reset_token_expires_at`) VALUES
(1,'popescu.dan@email.com', '$2y$10$HoUXL6Zmd8cTdus75jntRekF18p68SJ5Rs/0VP1yCJ8VNqSYvI7.K', 'Dan', 'Popescu', NULL, 'user', NULL, NULL);

CREATE TABLE `inmates` (
  `inmate_id` int(11) NOT NULL AUTO_INCREMENT,
  `person_id` int(11) NOT NULL,
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `sentence_start_date` date NOT NULL,
  `sentence_duration` int(11) NOT NULL,
  `sentence_category` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`inmate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- insert inmates

INSERT INTO `inmates` (`inmate_id`,`person_id`,`first_name`, `last_name`, `sentence_start_date`, `sentence_duration`, `sentence_category`) VALUES
(1,1,'Gigel', 'Phrone', '2023-03-09', 730, 'Violent crime');

CREATE TABLE `visits_info` (
  `visit_info_id` int(11) NOT NULL AUTO_INCREMENT,
  `visitor_id` int(11) NOT NULL,
  `inmate_id` int(11) NOT NULL,
  `visit_date` date NOT NULL,
  `witnesses` varchar(45) NOT NULL,
  `visit_nature` varchar(45) NOT NULL,
  `items_provided_to_inmate` varchar(255) DEFAULT NULL,
  `items_offered_by_inmate` varchar(255) DEFAULT NULL,
  `health_status` varchar(45) DEFAULT NULL,
  `summary` varchar(255) NOT NULL,
  `visit_refID` int(99) NOT NULL,
  PRIMARY KEY (`visit_info_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--insert visits_info

INSERT INTO `visits_info` (`visit_info_id`, `visitor_id`, `inmate_id`, `visit_date`, `witnesses`, `visit_nature`, `items_provided_to_inmate`, `items_offered_by_inmate`, `health_status`, `summary`, `visit_refID`) 
VALUES
(1, 1, 1, '2024-05-01', 'nurse', 'Friendship', 'money', 'book', 'good', 'we just chated', 1);


ALTER TABLE `visits`
  ADD UNIQUE KEY `appointment_id_UNIQUE` (`visit_id`),
  ADD KEY `person_id_from_appointments_idx` (`person_id`);

ALTER TABLE `inmates`
  ADD UNIQUE KEY `inmate_id_UNIQUE` (`inmate_id`);

ALTER TABLE `users`
  ADD UNIQUE KEY `user_id_UNIQUE` (`user_id`);


ALTER TABLE `visits_info`
  ADD UNIQUE KEY `visit_id_UNIQUE` (`visit_info_id`),
  ADD KEY `inmate_id_idx` (`inmate_id`),
  ADD KEY `visit` (`visit_refID`);

ALTER TABLE `visits`
    ADD CONSTRAINT `person_id_from_visits` FOREIGN KEY (`person_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `inmates`
    ADD CONSTRAINT `person_id_from_inmates` FOREIGN KEY (`person_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `visits_info`
    ADD CONSTRAINT `visit` FOREIGN KEY (`visit_refID`) REFERENCES `visits` (`visit_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inmate_id` FOREIGN KEY (`inmate_id`) REFERENCES `inmates` (`inmate_id`) ON DELETE CASCADE ON UPDATE CASCADE;
