--
-- Database: `bestof`
--

-- --------------------------------------------------------

--
-- Table structure for table `post_type`
--

CREATE TABLE IF NOT EXISTS `post_type` (
`seq` int(11) NOT NULL,
  `ord` int(11) NOT NULL,
  `name` varchar(150) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;


--
-- Indexes for table `post_type`
--
ALTER TABLE `post_type`
 ADD PRIMARY KEY (`seq`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `post_type`
--
ALTER TABLE `post_type`
MODIFY `seq` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
