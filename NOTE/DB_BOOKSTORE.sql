-データベース:bookstore


--テーブル構造：
---書類情報：BOOKINFO
---
CREATE TABLE IF NOT EXISTS BOOKINFO(
	ISBN VARCHAR(20) PRIMARY KEY,
	TITLE VARCHAR(100),
	SUBTITLE VARCHAR(200),
	PRICE INTEGER
	)ENGINE = INNODB DEFAULT CHARSET=utf8 COMMENT='書類情報', 

--テーブル構造：
---ユーザー情報：USERINFO
---
CREATE TABLE IF NOT EXISTS USERINFO(
	USER VARCHAR(20) PRIMARY KEY,
	PASSWORD VARCHAR(20),
	EMAIL VARCHAR(100)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ユーザー情報', 


--テーブル構造：
---購入情報：ORDERINFO
---
CREATE TABLE IF NOT EXISTS ORDERINFO(
	ORDERNO INTEGER AUTO_INCREMENT PRIMARY KEY,
	USER VARCHAR(20),
	ISBN VARCHAR(20),
	QUANTITY INTEGER,
	DATE DATE,
	FOREIGN KEY(ISBN) REFERENCES BOOKINFO(ISBN) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY(USER) REFERENCES USERINFO(USER) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='購入情報', 



INSERT INTO BOOKINFO(ISBN,TITLE,PRICE) VALUES
('00001','JAVA',1001),
('00002','C++',1002),
('00003','RUBY',1003),
('00004','PERL',1004),
('00005','DATABASE',1005);

INSERT INTO USERINFO(USER,PASSWORD,EMAIL) VALUES
('akiba','12345678','s_sai@rakudou.co.jp'),
('ebisu','12345678','s_sai@rakudou.co.jp'),
('kanda','12345678','s_sai@rakudou.co.jp'),
('meguro','12345678','s_sai@rakudou.co.jp'),
('osaki','12345678','s_sai@rakudou.co.jp'),
('shibuya','12345678','s_sai@rakudou.co.jp'),
('sugamo','12345678','s_sai@rakudou.co.jp'),
('tamachi','12345678','s_sai@rakudou.co.jp'),
('ueno','12345678','s_sai@rakudou.co.jp'); 

INSERT INTO ORDERINFO(USER,ISBN,QUANTITY,DATE) VALUES
('kanda','00001',1,'2010-07-01'),
('shibuya','00001',2,'2010-07-15'),
('akiba','00001',1,'2011-08-02'),
('meguro','00001',3,'2010-07-17'),
('kanda','00002',1,'2012-08-22'),
('shibuya','00002',3,'2010-09-03'),
('kanda','00002',1,'2013-07-25'),
('meguro','00003',4,'2010-07-30'),
('ueno','00003',1,'2010-08-12'),
('shibuya','00003',1,'2011-08-21'),
('tamachi','00004',2,'2010-09-14'),
('meguro','00004',3,'2011-07-11'),
('shibuya','00004',6,'2010-07-19'),
('akiba','00004',1,'2012-08-19'),
('kanda','00005',4,'2012-09-01'),
('tamachi','00005',2,'2010-08-22'),
('shibuya','00005',1,'2010-07-01'),
('kanda','00005',1,'2014-07-15'),
('osaki','00005',1,'2014-08-20'),
('shibuya','00005',3,'2014-07-30'),
('meguro','00003',2,'2010-09-01'),
('tamachi','00003',6,'2015-09-30'),
('kanda','00003',1,'2015-07-11'),
('shibuya','00004',3,'2015-07-21'),
('kanda','00004',2,'2015-08-15'),
('meguro','00004',1,'2015-07-02'),
('akiba','00004',4,'2015-07-25'),
('ueno','00004',1,'2010-08-15'),
('ueno','00001',1,'2010-08-14'),
('ueno','00002',1,'2010-07-15'); 