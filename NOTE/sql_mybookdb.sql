create table if not exists bookstoredb.bookinfo(
	NO integer auto_increment primary key,
	ISBN varchar(30) unique key not null,
	TITLE varchar(50),
	SUBTITLE varchar(100),
	WRITER varchar(60),
	PRICE int(11),
	CATEGORY varchar(10),
	COMMENT varchar(300),
	DELFLAG int(1)
)ENGINE = InnoDB, CHARSET = utf8, COMMENT = '���ޏ��';   


create table if not exists bookstoredb.userinfo(
	EMAIL varchar(100) primary key not null,
	USERNAME varchar(20) unique key not null,
	PASSWORD varchar(20),
	AGE int(2),
	DELFLAG int(1)
)ENGINE=InnoDB, CHARSET = utf8, COMMENT = '���[�U���';


create table if not exists bookstoredb.orderinfo(
	NO integer auto_increment primary key not null,
	USERNAME varchar(20),
	ISBN varchar(30),
	QUANTITY integer,
	BUYDATE date
)ENGINE=InnoDB, CHARSET = utf8, COMMENT = '���ލw������';

create table if not exists bookstoredb.category(
	CATEGORYID integer auto_increment primary key,
	CATEGORY varchar(30) unique key not null,
	COMMENT varchar(300),
	DELFLAG int(1)
)ENGINE = InnoDB, CHARSET = utf8, COMMENT = '���ރJ�e�S�����';   



ALTER TABLE `bookstoredb`.`orderinfo` 
ADD INDEX `FK_USERNAME_idx` (`USERNAME` ASC),
ADD INDEX `FK_BOOKISBN_idx` (`ISBN` ASC);
ALTER TABLE `bookstoredb`.`orderinfo` 
ADD CONSTRAINT `FK_USERNAME`
  FOREIGN KEY (`USERNAME`)
  REFERENCES `bookstoredb`.`userinfo` (`USERNAME`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
ADD CONSTRAINT `FK_BOOKISBN`
  FOREIGN KEY (`ISBN`)
  REFERENCES `bookstoredb`.`bookinfo` (`ISBN`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;
