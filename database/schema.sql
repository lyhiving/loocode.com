create database `lc` CHARSET=utf8mb4;

use `lc`;

-- commentmeta: table
CREATE TABLE `commentmeta`
(
    `meta_id`    bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `comment_id` bigint(20) unsigned NOT NULL                DEFAULT '0',
    `meta_key`   varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
    `meta_value` longtext COLLATE utf8mb4_unicode_520_ci,
    PRIMARY KEY (`meta_id`),
    KEY `comment_id` (`comment_id`),
    KEY `meta_key` (`meta_key`(191))
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_520_ci;

-- comments: table
CREATE TABLE `comments`
(
    `comment_ID`           bigint(20) unsigned                         NOT NULL AUTO_INCREMENT,
    `comment_post_ID`      bigint(20) unsigned                         NOT NULL DEFAULT '0',
    `comment_author`       tinytext COLLATE utf8mb4_unicode_520_ci     NOT NULL,
    `comment_author_email` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
    `comment_author_url`   varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
    `comment_author_IP`    varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
    `comment_date`         datetime                                    NOT NULL DEFAULT '1970-01-01 00:00:00',
    `comment_date_gmt`     datetime                                    NOT NULL DEFAULT '1970-01-01 00:00:00',
    `comment_content`      text COLLATE utf8mb4_unicode_520_ci         NOT NULL,
    `comment_karma`        int(11)                                     NOT NULL DEFAULT '0',
    `comment_approved`     varchar(20) COLLATE utf8mb4_unicode_520_ci  NOT NULL DEFAULT '1',
    `comment_agent`        varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
    `comment_type`         varchar(20) COLLATE utf8mb4_unicode_520_ci  NOT NULL DEFAULT 'comment',
    `comment_parent`       bigint(20) unsigned                         NOT NULL DEFAULT '0',
    `user_id`              bigint(20) unsigned                         NOT NULL DEFAULT '0',
    PRIMARY KEY (`comment_ID`),
    KEY `comment_post_ID` (`comment_post_ID`),
    KEY `comment_approved_date_gmt` (`comment_approved`, `comment_date_gmt`),
    KEY `comment_date_gmt` (`comment_date_gmt`),
    KEY `comment_parent` (`comment_parent`),
    KEY `comment_author_email` (`comment_author_email`(10))
) ENGINE = InnoDB
  AUTO_INCREMENT = 2
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_520_ci;

-- links: table
CREATE TABLE `links`
(
    `link_id`          bigint(20) unsigned                         NOT NULL AUTO_INCREMENT,
    `link_url`         varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
    `link_name`        varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
    `link_image`       varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
    `link_target`      varchar(25) COLLATE utf8mb4_unicode_520_ci  NOT NULL DEFAULT '',
    `link_description` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
    `link_visible`     varchar(20) COLLATE utf8mb4_unicode_520_ci  NOT NULL DEFAULT 'Y',
    `link_owner`       bigint(20) unsigned                         NOT NULL DEFAULT '1',
    `link_rating`      int(11)                                     NOT NULL DEFAULT '0',
    `link_updated`     datetime                                    NOT NULL DEFAULT '1970-01-01 00:00:00',
    `link_rel`         varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
    `link_notes`       mediumtext COLLATE utf8mb4_unicode_520_ci   NOT NULL,
    `link_rss`         varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
    PRIMARY KEY (`link_id`),
    KEY `link_visible` (`link_visible`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_520_ci;

-- options: table
CREATE TABLE `options`
(
    `option_id`    bigint(20) unsigned                         NOT NULL AUTO_INCREMENT,
    `option_name`  varchar(191) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
    `option_value` longtext COLLATE utf8mb4_unicode_520_ci     NOT NULL,
    `autoload`     varchar(20) COLLATE utf8mb4_unicode_520_ci  NOT NULL DEFAULT 'yes',
    PRIMARY KEY (`option_id`),
    UNIQUE KEY `option_name` (`option_name`),
    KEY `autoload` (`autoload`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 130
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_520_ci;

-- postmeta: table
CREATE TABLE `postmeta`
(
    `meta_id`    bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `post_id`    bigint(20) unsigned NOT NULL                DEFAULT '0',
    `meta_key`   varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
    `meta_value` longtext COLLATE utf8mb4_unicode_520_ci,
    PRIMARY KEY (`meta_id`),
    KEY `post_id` (`post_id`),
    KEY `meta_key` (`meta_key`(191))
) ENGINE = InnoDB
  AUTO_INCREMENT = 3
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_520_ci;

-- posts: table
CREATE TABLE `posts`
(
    `ID`                    bigint(20) unsigned                         NOT NULL AUTO_INCREMENT,
    `post_author`           bigint(20) unsigned                         NOT NULL DEFAULT '0',
    `post_date`             datetime                                    NOT NULL DEFAULT '1970-01-01 00:00:00',
    `post_date_gmt`         datetime                                    NOT NULL DEFAULT '1970-01-01 00:00:00',
    `post_content`          longtext COLLATE utf8mb4_unicode_520_ci     NOT NULL,
    `post_title`            text COLLATE utf8mb4_unicode_520_ci         NOT NULL,
    `post_excerpt`          text COLLATE utf8mb4_unicode_520_ci         NOT NULL,
    `post_status`           varchar(20) COLLATE utf8mb4_unicode_520_ci  NOT NULL DEFAULT 'publish',
    `comment_status`        varchar(20) COLLATE utf8mb4_unicode_520_ci  NOT NULL DEFAULT 'open',
    `ping_status`           varchar(20) COLLATE utf8mb4_unicode_520_ci  NOT NULL DEFAULT 'open',
    `post_password`         varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
    `post_name`             varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
    `to_ping`               text COLLATE utf8mb4_unicode_520_ci         NOT NULL,
    `pinged`                text COLLATE utf8mb4_unicode_520_ci         NOT NULL,
    `post_modified`         datetime                                    NOT NULL DEFAULT '1970-01-01 00:00:00',
    `post_modified_gmt`     datetime                                    NOT NULL DEFAULT '1970-01-01 00:00:00',
    `post_content_filtered` longtext COLLATE utf8mb4_unicode_520_ci     NOT NULL,
    `post_parent`           bigint(20) unsigned                         NOT NULL DEFAULT '0',
    `guid`                  varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
    `menu_order`            int(11)                                     NOT NULL DEFAULT '0',
    `post_type`             varchar(20) COLLATE utf8mb4_unicode_520_ci  NOT NULL DEFAULT 'post',
    `post_mime_type`        varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
    `comment_count`         bigint(20)                                  NOT NULL DEFAULT '0',
    PRIMARY KEY (`ID`),
    KEY `post_name` (`post_name`(191)),
    KEY `type_status_date` (`post_type`, `post_status`, `post_date`, `ID`),
    KEY `post_parent` (`post_parent`),
    KEY `post_author` (`post_author`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 4
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_520_ci;

-- term_relationships: table
CREATE TABLE `term_relationships`
(
    `object_id`        bigint(20) unsigned NOT NULL DEFAULT '0',
    `term_taxonomy_id` bigint(20) unsigned NOT NULL DEFAULT '0',
    `term_order`       int(11)             NOT NULL DEFAULT '0',
    PRIMARY KEY (`object_id`, `term_taxonomy_id`),
    KEY `term_taxonomy_id` (`term_taxonomy_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_520_ci;

-- term_taxonomy: table
CREATE TABLE `term_taxonomy`
(
    `term_taxonomy_id` bigint(20) unsigned                        NOT NULL AUTO_INCREMENT,
    `term_id`          bigint(20) unsigned                        NOT NULL DEFAULT '0',
    `taxonomy`         varchar(32) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
    `description`      longtext COLLATE utf8mb4_unicode_520_ci    NOT NULL,
    `parent`           bigint(20) unsigned                        NOT NULL DEFAULT '0',
    `count`            bigint(20)                                 NOT NULL DEFAULT '0',
    PRIMARY KEY (`term_taxonomy_id`),
    UNIQUE KEY `term_id_taxonomy` (`term_id`, `taxonomy`),
    KEY `taxonomy` (`taxonomy`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 2
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_520_ci;

-- termmeta: table
CREATE TABLE `termmeta`
(
    `meta_id`    bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `term_id`    bigint(20) unsigned NOT NULL                DEFAULT '0',
    `meta_key`   varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
    `meta_value` longtext COLLATE utf8mb4_unicode_520_ci,
    PRIMARY KEY (`meta_id`),
    KEY `term_id` (`term_id`),
    KEY `meta_key` (`meta_key`(191))
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_520_ci;

-- terms: table
CREATE TABLE `terms`
(
    `term_id`    bigint(20) unsigned                         NOT NULL AUTO_INCREMENT,
    `name`       varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
    `slug`       varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
    `term_group` bigint(10)                                  NOT NULL DEFAULT '0',
    PRIMARY KEY (`term_id`),
    KEY `slug` (`slug`(191)),
    KEY `name` (`name`(191))
) ENGINE = InnoDB
  AUTO_INCREMENT = 2
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_520_ci;

-- usermeta: table
CREATE TABLE `usermeta`
(
    `umeta_id`   bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `user_id`    bigint(20) unsigned NOT NULL                DEFAULT '0',
    `meta_key`   varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
    `meta_value` longtext COLLATE utf8mb4_unicode_520_ci,
    PRIMARY KEY (`umeta_id`),
    KEY `user_id` (`user_id`),
    KEY `meta_key` (`meta_key`(191))
) ENGINE = InnoDB
  AUTO_INCREMENT = 16
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_520_ci;

-- users: table
CREATE TABLE `users`
(
    `ID`                  bigint(20) unsigned                         NOT NULL AUTO_INCREMENT,
    `user_login`          varchar(60) COLLATE utf8mb4_unicode_520_ci  NOT NULL DEFAULT '',
    `user_pass`           varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
    `user_nicename`       varchar(50) COLLATE utf8mb4_unicode_520_ci  NOT NULL DEFAULT '',
    `user_email`          varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
    `user_url`            varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
    `user_registered`     datetime                                    NOT NULL DEFAULT '1970-01-01 00:00:00',
    `user_activation_key` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
    `user_status`         int(11)                                     NOT NULL DEFAULT '0',
    `display_name`        varchar(250) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
    PRIMARY KEY (`ID`),
    KEY `user_login_key` (`user_login`),
    KEY `user_nicename` (`user_nicename`),
    KEY `user_email` (`user_email`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 2
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_520_ci;

-- social_accounts: table
create table social_accounts
(
    id          int unsigned auto_increment,
    user_id     bigint unsigned not null,
    provider    varchar(32)  not null,
    provider_id varchar(191) not null,
    token       varchar(191) null,
    avatar      varchar(191) null,
    created_at  timestamp    null,
    updated_at  timestamp    null,
    constraint social_accounts_user_id_foreign
        foreign key (user_id) references users (ID)
            on delete cascade,
    primary key (`id`),
    key `social_u_p_p` (user_id, provider, provider_id)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_520_ci;

alter table `users` add column `avatar` varchar(255) COLLATE utf8mb4_unicode_520_ci  NOT NULL DEFAULT '' after `display_name`;
alter table `users` add column `remember_token` varchar(255) COLLATE utf8mb4_unicode_520_ci  NOT NULL DEFAULT '' after `avatar`;

CREATE TABLE IF NOT EXISTS `menus` (
   `id` mediumint unsigned not null  auto_increment,
   `parent_id` mediumint unsigned not null  default 0,
   `name` varchar(128) not null COMMENT '名称',
   `hidden` tinyint(3) not null default 0 COMMENT '显示状态, 0显示, 1隐藏',
   `weight` mediumint(8) unsigned not null COMMENT '权重',
   `class` varchar(64) not null  default  '' COMMENT '对应的css class',
   `url` varchar(255) not null default '' COMMENT '后端路由地址',
   `link` varchar(128) not null default '' COMMENT '对应的前端路由',
   `created_date` datetime COMMENT '创建时间',
   PRIMARY KEY (`id`),
   KEY `idx_parent_id` (`parent_id`)
) ENGINE=INNODB
  CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_520_ci
  COMMENT '菜单';

CREATE TABLE IF NOT EXISTS `roles` (
   `id` smallint unsigned not null auto_increment,
   `name` varchar(64) not null COMMENT '角色名称',
   `created_date` datetime not null ,
   `updated_date` datetime not null ,
   PRIMARY KEY (`id`)
) ENGINE=INNODB
  CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_520_ci
  COMMENT '角色';


CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int unsigned not null auto_increment,
  `role_id` smallint unsigned not null COMMENT '角色ID',
  `menu_id` mediumint unsigned not null COMMENT '菜单ID',
  `created_date` datetime not null ,
  PRIMARY KEY (`id`),
  KEY `idx_role_id` (`role_id`)
) ENGINE=INNODB
  CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_520_ci
  COMMENT '角色权限';

CREATE TABLE IF NOT EXISTS `log_operations` (
    `id` int(11) unsigned not null auto_increment,
    `manager_id` mediumint(8)  unsigned  not null ,
    `menu_id` int(11) unsigned  not null ,
    `info` text,
    `created_date` datetime,
    primary key (`id`),
    key `idx_mid` (`manager_id`)
) ENGINE=INNODB
  CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_520_ci
  COMMENT '操作日志';

