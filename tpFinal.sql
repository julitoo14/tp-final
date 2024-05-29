-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: May 29, 2024 at 01:59 AM
-- Server version: 5.7.39
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tpFinal`
--

-- --------------------------------------------------------

--
-- Table structure for table `USUARIOS`
--

CREATE TABLE `USUARIOS` (
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `_id` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `surname` varchar(255) DEFAULT NULL,
  `EMAIL_VALIDATED` tinyint(1) NOT NULL DEFAULT '0',
  `hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `USUARIOS`
--

INSERT INTO `USUARIOS` (`username`, `password`, `_id`, `email`, `name`, `surname`, `EMAIL_VALIDATED`, `hash`) VALUES
('julito', '1234', 17, 'juuligarcia2208@gmail.com', 'Julian', 'Garcia', 1, '41f6fcf410fb86342af2e3311342c79c');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `USUARIOS`
--
ALTER TABLE `USUARIOS`
  ADD PRIMARY KEY (`_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `USUARIOS`
--
ALTER TABLE `USUARIOS`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
