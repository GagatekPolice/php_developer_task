
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE shoper.`Headquarter` (
  `id` varchar(64) NOT NULL,
  `city` text CHARACTER SET utf8 NOT NULL,
  `street` text CHARACTER SET utf8 NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin2;

INSERT INTO `Headquarter` (`id`, `city`, `street`, `latitude`, `longitude`) VALUES
('a3a2793d-1e8e-44a9-a881-99ddf5807b68', 'Kraków', 'Pawia 9', '50.07048609', '19.94635587'),
('eb5dcf4e-96e2-4692-8ab9-b58b97010ab1', 'Szczecin', 'Cyfrowa 8', '53.45086095', '14.53644447');

ALTER TABLE `Headquarter`
  ADD PRIMARY KEY (`id`);
COMMIT;
