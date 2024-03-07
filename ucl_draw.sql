-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 07 Mar 2024, 18:20
-- Wersja serwera: 10.4.24-MariaDB
-- Wersja PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `ucl_draw`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `country`
--

CREATE TABLE `country` (
  `id` int(11) NOT NULL,
  `name` varchar(75) DEFAULT NULL,
  `short` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `country`
--

INSERT INTO `country` (`id`, `name`, `short`) VALUES
(1, 'Albania', 'ALB'),
(2, 'Andorra', 'AND'),
(3, 'Armenia', 'ARM'),
(4, 'Austria', 'AUT'),
(5, 'Azerbaijan', 'AZE'),
(6, 'Belarus', 'BLR'),
(7, 'Belgium', 'BEL'),
(8, 'Bosnia and Herzegovina', 'BIH'),
(9, 'Bulgaria', 'BUL'),
(10, 'Croatia', 'CRO'),
(11, 'Cyprus', 'CYP'),
(12, 'Czech Republic', 'CZE'),
(13, 'Denmark', 'DEN'),
(14, 'England', 'ENG'),
(15, 'Estonia', 'EST'),
(16, 'Faroe Islands', 'FRO'),
(17, 'Finland', 'FIN'),
(18, 'France', 'FRA'),
(19, 'Georgia', 'GEO'),
(20, 'Germany', 'GER'),
(21, 'Gibraltar', 'GIB'),
(22, 'Greece', 'GRE'),
(23, 'Hungary', 'HUN'),
(24, 'Iceland', 'ISL'),
(25, 'Israel', 'ISR'),
(26, 'Italy', 'ITA'),
(27, 'Kazakhstan', 'KAZ'),
(28, 'Kosovo', 'KOS'),
(29, 'Latvia', 'LVA'),
(30, 'Liechtenstein', 'LIE'),
(31, 'Lithuania', 'LTU'),
(32, 'Luxembourg', 'LUX'),
(33, 'Malta', 'MLT'),
(34, 'Moldova', 'MDA'),
(35, 'Montenegro', 'MNE'),
(36, 'Netherlands', 'NED'),
(37, 'North Macedonia', 'MKD'),
(38, 'Northern Ireland', 'NIR'),
(39, 'Norway', 'NOR'),
(40, 'Poland', 'POL'),
(41, 'Portugal', 'POR'),
(42, 'Republic of Ireland', 'IRL'),
(43, 'Romania', 'ROU'),
(44, 'Russia', 'RUS'),
(45, 'San Marino', 'SMR'),
(46, 'Scotland', 'SCO'),
(47, 'Serbia', 'SRB'),
(48, 'Slovakia', 'SVK'),
(49, 'Slovenia', 'SVN'),
(50, 'Spain', 'ESP'),
(51, 'Sweden', 'SWE'),
(52, 'Switzerland', 'SUI'),
(53, 'Turkey', 'TUR'),
(54, 'Ukraine', 'UKR'),
(55, 'Wales', 'WAL');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `teams`
--

CREATE TABLE `teams` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `country_id` int(2) DEFAULT NULL,
  `pot` int(2) DEFAULT NULL,
  `pair` int(2) DEFAULT NULL,
  `groups` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `teams`
--

INSERT INTO `teams` (`id`, `name`, `country_id`, `pot`, `pair`, `groups`) VALUES
(1, '1. FC Union Berlin', 20, 4, 10, 0),
(2, 'AC Milan', 26, 3, 9, 0),
(3, 'Arsenal FC', 14, 2, 11, 0),
(4, 'BSC Young Boys', 52, 4, 0, 0),
(5, 'Borussia Dortmund', 20, 2, 5, 0),
(6, 'Celtic FC', 46, 4, 0, 0),
(7, 'Club Atletico de Madrid', 50, 2, 2, 0),
(8, 'F.C. Copenhagen', 13, 3, 0, 0),
(9, 'FC Barcelona', 50, 1, 3, 0),
(10, 'FC Bayern München', 20, 1, 5, 0),
(11, 'FC Internazionale Milano', 26, 2, 9, 0),
(12, 'FC Porto', 41, 2, 7, 0),
(13, 'FC Salzburg', 4, 3, 0, 0),
(14, 'FC Shakhtar Donetsk', 54, 3, 0, 0),
(15, 'FK Crvena Zvezda', 47, 3, 0, 0),
(16, 'Feyenoord', 36, 1, 8, 0),
(17, 'Galatasaray A.Ş.', 53, 4, 0, 0),
(18, 'Manchester City', 14, 1, 1, 0),
(19, 'Manchester United', 14, 2, 1, 0),
(20, 'Newcastle United FC', 14, 4, 11, 0),
(21, 'PSV Eindhoven', 36, 3, 8, 0),
(22, 'Paris Saint-Germain', 18, 1, 6, 0),
(23, 'RB Leipzig', 20, 2, 10, 0),
(24, 'RC Lens', 18, 4, 6, 0),
(25, 'Real Madrid', 50, 2, 3, 0),
(26, 'Real Sociedad de Fútbol', 50, 4, 0, 0),
(27, 'Royal Antwerp FC', 7, 4, 0, 0),
(28, 'S.S. Lazio', 26, 3, 4, 0),
(29, 'SC Braga', 41, 3, 0, 0),
(30, 'SL Benfica', 41, 1, 7, 0),
(31, 'SSC Napoli', 26, 1, 4, 0),
(32, 'Sevilla FC', 50, 1, 2, 0);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `country`
--
ALTER TABLE `country`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT dla tabeli `teams`
--
ALTER TABLE `teams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
