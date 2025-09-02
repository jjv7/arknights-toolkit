DROP TABLE IF EXISTS `post_likes`;
DROP TABLE IF EXISTS `posts`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    `first_name` VARCHAR(50) NOT NULL,
    `last_name` VARCHAR(50) NOT NULL,
    `email` VARCHAR(50) NOT NULL,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(50) NOT NULL
);

CREATE TABLE `posts` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `author` VARCHAR(50) NOT NULL,
    `title` VARCHAR(60) NOT NULL,
    `content` TEXT NOT NULL,
    `category` VARCHAR(20) NOT NULL,
    `timestamp` DATETIME NOT NULL
);

CREATE TABLE `post_likes` (
    `post_id` INT NOT NULL,
    `user` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`post_id`, `user`),
    FOREIGN KEY (`post_id`) REFERENCES `posts`(`id`) ON DELETE CASCADE
);


INSERT INTO `users` (`first_name`, `last_name`, `email`, `username`, `password`) VALUES
('Amiya', 'Cautus', 'amiya@rhodesisland.com', 'amiya', 'rhodes1'),
("Chen", 'Dragon', 'chen@lgy.com', 'chen', 'dragon2'),
('SilverAsh', 'Karlan', 'silverash@karlan.com', 'silverash', 'mountain3'),
('Exusiai', 'Angel', 'exusiai@penguin.com', 'exusiai', 'gun4'),
('admin', 'Admin', 'admin@example.com', 'admin', 'admin');

INSERT INTO `posts` (`author`, `title`, `content`, `category`, `timestamp`) VALUES
('amiya', 'Operator Promotion Guide', "Here's how you should prioritize your E2 promotions to clear harder content.", 'Guide', NOW()),
('chen', 'Lungmen Incident Retrospective', 'Reflecting on the events during the Lungmen Riots and lessons for team synergy.', 'Lore', NOW()),
('silverash', 'Kjerag Winter Ops', 'Tips for maintaining operational efficiency in snow-covered terrains.', 'Strategy', NOW()),
('exusiai', 'Top 5 Sniper Ops in Arknights', "They may not all use guns, but let's rank the top DPS operators anyway!", 'Strategy', NOW()),
('admin', 'My first post', 'Hi, I am new here', 'General', NOW());

INSERT INTO `post_likes` (`post_id`, `user`) VALUES
(1, 'chen'),
(1, 'silverash'),
(2, 'exusiai'),
(3, 'amiya'),
(4, 'chen'),
(5, 'silverash');