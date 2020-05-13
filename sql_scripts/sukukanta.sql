-- phpMyAdmin SQL Dump
-- version 2.8.2.4
-- http://www.phpmyadmin.net
-- 
-- Palvelin: localhost:3306
-- Luontiaika: 19.01.2013 klo 09:43
-- Palvelimen versio: 5.1.56
-- PHP:n versio: 5.2.6
-- 
-- Tietokanta: `database`
-- 

-- --------------------------------------------------------

-- 
-- Rakenne taululle `forum_posts`
-- 

CREATE TABLE `forum_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) NOT NULL,
  `creator_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `post_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

-- 
-- Vedostetaan dataa taulusta `forum_posts`
-- 
-- --------------------------------------------------------

-- 
-- Rakenne taululle `forum_topics`
-- 

CREATE TABLE `forum_topics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic` varchar(100) NOT NULL,
  `creator_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

-- 
-- Vedostetaan dataa taulusta `forum_topics`
-- 

-- --------------------------------------------------------

-- 
-- Rakenne taululle `users`
-- 

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(30) NOT NULL,
  `real_name` varchar(50) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` char(128) NOT NULL,
  `salt` char(128) NOT NULL,
  `admin` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- 
-- Vedostetaan dataa taulusta `users`
-- 


