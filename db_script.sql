CREATE TABLE `quick_exam_online`.`exams` (
  `examID` INT NOT NULL AUTO_INCREMENT,
  `examName` VARCHAR(45) NOT NULL,
  `examVisibility` VARCHAR(45) NULL,
  `hasDuration` VARCHAR(45) NULL,
  `examDuration` INT NULL,
  `hasExpiration` VARCHAR(45) NULL,
  `expirationDateTime` VARCHAR(45) NULL,
  `showAnswersAfterExam` VARCHAR(45) NULL,
  `examQuestionsCount` INT NULL,
  `examType` VARCHAR(45) NULL,
  `choicesCount` INT NULL,
  `userID` INT NOT NULL,
  PRIMARY KEY (`examID`),
  UNIQUE INDEX `exam_id_UNIQUE` (`examID` ASC) VISIBLE);

CREATE TABLE `quick_exam_online`.`questions` (
  `questionID` INT NOT NULL AUTO_INCREMENT,
  `examID` INT NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  `orderNumber` INT NOT NULL,
  PRIMARY KEY (`questionID`));

CREATE TABLE `quick_exam_online`.`choices` (
  `choiceID` INT NOT NULL AUTO_INCREMENT,
  `examID` INT NOT NULL,
  `questionID` INT NOT NULL,
  `isAnswer` INT NULL,
  `description` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`choiceID`));

CREATE TABLE `quick_exam_online`.`exam_results` (
  `examResultID` INT NOT NULL AUTO_INCREMENT,
  `userID` INT NOT NULL,
  `examID` INT NOT NULL,
  `dateOfExamSubmission` VARCHAR(45)  NULL,
  `correctAnswersCount` INT  NULL,
  `correctAnswers` VARCHAR(500)  NULL,
  `wrongAnswersCount` INT  NULL,
  `wrongAnswers` VARCHAR(500)  NULL,
  `percentage` VARCHAR(45)  NULL,
  `isStarted` INT NULL DEFAULT 0,
  `isFinished` INT NULL DEFAULT 0,
  `examStartDate` VARCHAR(45)  NULL,
  PRIMARY KEY (`examResultID`));

CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(25) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(32) NOT NULL,
  `signUpDate` datetime NOT NULL,
  `profilePic` varchar(500) NOT NULL,
   PRIMARY KEY (`id`));

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `firstName`, `lastName`, `email`, `password`, `signUpDate`, `profilePic`) VALUES
(1, 'reece-kenney', 'Reece', 'Kenney', 'Reece@gmail.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2017-06-28 00:00:00', 'assets/images/profile-pics/head_emerald.png'),
(2, 'donkey-kong', 'Donkey', 'Kong', 'Dk@yahoo.com', '7c6a180b36896a0a8c02787eeafb0e4c', '2017-06-28 00:00:00', 'assets/images/profile-pics/head_emerald.png');
