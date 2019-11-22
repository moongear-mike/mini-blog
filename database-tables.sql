-- Lic: AMARX9NIDhSS
-- thesandbox_mini-blog
--
-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 21, 2019 at 12:23 PM
-- Server version: 10.0.38-MariaDB
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "-05:00";

--
-- Database: `thesandbox_mini-blog`
--

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL COMMENT 'A unique ID for this blog post',
  `title` varchar(255) NOT NULL COMMENT 'The title of the blog post.',
  `content` text NOT NULL COMMENT 'The content of the blog post.',
  `creator_name` varchar(255) NOT NULL COMMENT 'The creator name of the blog post.',
  `creator_email` varchar(255) NOT NULL COMMENT 'The creator email of the blog post.',
  `date_posted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date/Time this post was made.',
  `date_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Date/Time this post was updated.',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'The status identifier for this blog post'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'A unique ID for this blog post';
COMMIT;

