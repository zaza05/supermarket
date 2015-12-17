-- phpMyAdmin SQL Dump
-- version 2.10.1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Nov 23, 2013 at 03:53 PM
-- Server version: 5.0.37
-- PHP Version: 5.2.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Database: `supermarket`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `login`
-- 

CREATE TABLE `login` (
  `id` int(4) NOT NULL auto_increment,
  `username` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `names` varchar(30) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `login`
-- 

INSERT INTO `login` (`id`, `username`, `password`, `email`, `names`) VALUES 
(1, 'admin', 'admin', 'lieton12@gmail.com', 'BYIRINGIRO Emma'),
(2, 'admin1', 'admin1', 'crawfolly@yahoo.fr', 'Emma Lennox');

-- --------------------------------------------------------

-- 
-- Table structure for table `messages`
-- 

CREATE TABLE `messages` (
  `id` int(4) NOT NULL auto_increment,
  `sender_name` varchar(25) NOT NULL,
  `receiver_name` varchar(30) NOT NULL,
  `sender_email` varchar(25) NOT NULL,
  `receiver_email` varchar(30) NOT NULL,
  `object` varchar(20) NOT NULL,
  `message` varchar(1000) NOT NULL,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `messages`
-- 

INSERT INTO `messages` (`id`, `sender_name`, `receiver_name`, `sender_email`, `receiver_email`, `object`, `message`, `date`) VALUES 
(1, 'admin', 'admin1', 'lieton12@gmail.com', 'crawfolly@yahoo.fr', 'Hello', 'Bite  man amakuru yiminsi nizere ko umeze bon kabisa ndagukumbuye ', '2013-11-14 09:20:48'),
(2, 'admin', 'admin', 'lieton12@gmail.com', 'crawfolly@yahoo.fr', 'Amakuru', 'Haaaaa yewe  sinarinziko ukomeye  gutyo kabisa !', '2013-11-14 09:21:44');

-- --------------------------------------------------------

-- 
-- Table structure for table `product`
-- 

CREATE TABLE `product` (
  `id` int(3) NOT NULL auto_increment,
  `product_name` varchar(30) NOT NULL,
  `product_price` int(10) NOT NULL,
  `quantity` int(10) NOT NULL,
  `barcode` varchar(15) NOT NULL,
  `made_date` varchar(13) NOT NULL,
  `expiring_date` varchar(13) NOT NULL,
  `date_of_entry` timestamp NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `supplier` varchar(30) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- 
-- Dumping data for table `product`
-- 

INSERT INTO `product` (`id`, `product_name`, `product_price`, `quantity`, `barcode`, `made_date`, `expiring_date`, `date_of_entry`, `supplier`) VALUES 
(1, 'Sugar', 2000, 955, '12345', '01-01-2012', '01-01-2013', '2013-11-23 10:00:45', 'Luzira'),
(5, 'Rice', 1500, 993, '654321', '09-02-2000', '08-01-2004', '2013-11-19 14:53:40', 'Paksitan');

-- --------------------------------------------------------

-- 
-- Table structure for table `sold`
-- 

CREATE TABLE `sold` (
  `id` int(3) NOT NULL auto_increment,
  `Teller` varchar(30) NOT NULL,
  `barcode` int(10) NOT NULL,
  `product_name` varchar(15) NOT NULL,
  `product_price` varchar(15) NOT NULL,
  `quantity` int(10) NOT NULL,
  `total` int(10) NOT NULL,
  `paid` int(10) NOT NULL,
  `balance` int(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

-- 
-- Dumping data for table `sold`
-- 

INSERT INTO `sold` (`id`, `Teller`, `barcode`, `product_name`, `product_price`, `quantity`, `total`, `paid`, `balance`) VALUES 
(1, 'Teller1', 654321, 'Rice', '1500', 4, 6000, 10000, 4000),
(2, 'Teller1', 12345, 'Sugar', '2000', 4, 8000, 10000, 2000),
(3, 'Teller1', 12345, 'Sugar', '2000', 3, 6000, 10000, 4000),
(4, 'Teller2', 12345, 'Sugar', '2000', 3, 6000, 10000, 4000),
(5, 'Teller1', 12345, 'Sugar', '2000', 200, 400000, 10000, -390000),
(6, 'Teller1', 654321, 'Rice', '1500', 3, 4500, 50000, 45500),
(7, 'Teller1', 12345, 'Sugar', '2000', 2, 4000, 5000, 1000),
(8, 'Teller1', 12345, 'Sugar', '2000', 9, 18000, 50000, 32000),
(9, 'Teller1', 12345, 'Sugar', '2000', 3, 6000, 10000, 4000),
(10, 'Teller1', 12345, 'Sugar', '2000', 2, 4000, 5000, 1000),
(11, 'Teller1', 12345, 'Sugar', '2000', 1, 2000, 2000, 0),
(12, 'Teller2', 12345, 'Sugar', '2000', 2, 4000, 5000, 1000),
(13, 'Teller2', 12345, 'Sugar', '2000', 2, 4000, 5000, 1000),
(14, 'Teller1', 12345, 'Sugar', '2000', 5, 10000, 20000, 10000),
(15, 'Teller1', 654321, 'Rice', '1500', 4, 6000, 10000, 4000),
(16, 'Teller1', 12345, 'Sugar', '2000', 6, 12000, 25000, 13000),
(17, 'Teller1', 12345, 'Sugar', '2000', 4, 8000, 50000, 42000);

-- --------------------------------------------------------

-- 
-- Table structure for table `teller`
-- 

CREATE TABLE `teller` (
  `id` int(3) NOT NULL auto_increment,
  `teller_id` varchar(10) NOT NULL,
  `teller_names` varchar(30) NOT NULL,
  `teller_pnumber` varchar(20) NOT NULL,
  `teller_email` varchar(20) NOT NULL,
  `teller_username` varchar(20) NOT NULL,
  `teller_password` varchar(25) NOT NULL,
  `teller_location` varchar(30) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `teller_username` (`teller_username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

-- 
-- Dumping data for table `teller`
-- 

INSERT INTO `teller` (`id`, `teller_id`, `teller_names`, `teller_pnumber`, `teller_email`, `teller_username`, `teller_password`, `teller_location`) VALUES 
(10, '001', 'BYIRINGIRO Emma', '0789072234', 'lieton12@gmail.com', 'Teller1', 'Teller', 'KANSANGA'),
(11, '2', 'Umutoni Carine', '0789072243', 'carine@gmail.com', 'Teller2', 'Teller', 'KANSANGA');
