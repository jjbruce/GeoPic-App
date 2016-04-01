-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 31, 2016 at 09:08 PM
-- Server version: 10.1.8-MariaDB
-- PHP Version: 5.6.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `geopic`
--
CREATE DATABASE IF NOT EXISTS `geopic` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `geopic`;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `categories_ID` int(7) NOT NULL,
  `description` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `geopic`
--

DROP TABLE IF EXISTS `geopic`;
CREATE TABLE `geopic` (
  `geopic_ID` int(11) NOT NULL,
  `locality` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `thumbname` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `junc_categories_geopic`
--

DROP TABLE IF EXISTS `junc_categories_geopic`;
CREATE TABLE `junc_categories_geopic` (
  `categories_geopic_ID` int(11) NOT NULL,
  `categories_FK` int(11) NOT NULL,
  `geopic_FK` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categories_ID`);

--
-- Indexes for table `geopic`
--
ALTER TABLE `geopic`
  ADD PRIMARY KEY (`geopic_ID`);

--
-- Indexes for table `junc_categories_geopic`
--
ALTER TABLE `junc_categories_geopic`
  ADD PRIMARY KEY (`categories_geopic_ID`),
  ADD KEY `geopic_FK` (`geopic_FK`),
  ADD KEY `categories_FK` (`categories_FK`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `categories_ID` int(7) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `geopic`
--
ALTER TABLE `geopic`
  MODIFY `geopic_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;
--
-- AUTO_INCREMENT for table `junc_categories_geopic`
--
ALTER TABLE `junc_categories_geopic`
  MODIFY `categories_geopic_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=214;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `junc_categories_geopic`
--
ALTER TABLE `junc_categories_geopic`
  ADD CONSTRAINT `junc_categories_geopic_ibfk_1` FOREIGN KEY (`categories_FK`) REFERENCES `categories` (`categories_ID`),
  ADD CONSTRAINT `junc_categories_geopic_ibfk_2` FOREIGN KEY (`geopic_FK`) REFERENCES `geopic` (`geopic_ID`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
