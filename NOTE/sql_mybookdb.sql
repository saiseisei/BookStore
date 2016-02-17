create table if not exists bookstoredb.bookinfo(
	NO integer auto_increment primary key,,
	ISBN varchar(30) unique key not null,
	TITLE varchar(50),
	SUBTITLE varchar(100),
	WRITER varchar(60),
	PRICE int(11),
	CATEGORY varchar(10),
	COMMENT varchar(300),
	DELFLAG int(1)
)ENGINE = InnoDB, CHARSET = utf8, COMMENT = 'èëóﬁèÓïÒ';   


create table if not exists bookstoredb.userinfo(
	EMAIL varchar(100) primary key not null,
	USERNAME varchar(20) unique key not null,
	PASSWORD varchar(20),
	AGE int(2),
	DELFLAG int(1)
)ENGINE=InnoDB, CHARSET = utf8, COMMENT = 'ÉÜÅ[ÉUèÓïÒ';


create table if not exists bookstoredb.orderinfo(
	NO integer auto_increment primary key not null,
	USERNAME varchar(20),
	ISBN varchar(30),
	QUANTITY integer,
	BUYDATE date
)ENGINE=InnoDB, CHARSET = utf8, COMMENT = 'èëóﬁçwîÉóöó';


ALTER TABLE `bookstoredb`.`info_order` 
ADD INDEX `FK_USERNAME_idx` (`USERNAME` ASC),
ADD INDEX `FK_BOOKISBN_idx` (`ISBN` ASC);
ALTER TABLE `bookstoredb`.`info_order` 
ADD CONSTRAINT `FK_USERNAME`
  FOREIGN KEY (`USERNAME`)
  REFERENCES `bookstoredb`.`info_user` (`USERNAME`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
ADD CONSTRAINT `FK_BOOKISBN`
  FOREIGN KEY (`ISBN`)
  REFERENCES `bookstoredb`.`info_book` (`ISBN`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

