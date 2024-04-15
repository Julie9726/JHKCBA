create table event (
	name varchar(100) not null,
    datetime datetime not null,
    details text,
    availability int not null,
    id int AUTO_INCREMENT not null,
    ticketprice decimal(5,2),
    location varchar(15),
    primary key (id)
);

create table user (  
firstname varchar(20) not null,  
lastname varchar(20) not null,  
email varchar(50) unique not null,  
password varchar(64) not null,  
type varchar(10) not null,  
id int AUTO_INCREMENT not null,
primary key (id)
);

create table eventreg (
userid int not null,
email varchar(50) not null,
eventid int not null,
numberticket int not null,
regid int AUTO_INCREMENT,
totalprice decimal(6,2),
status varchar(10),
primary key (regid),
foreign key (userid) references user(id),
foreign key (email) references user(email),
foreign key (eventid) references event(id)
);

create table contact (
email varchar(50) not null,
type varchar(10),
execname varchar(20),
execemail varchar(50),
question text,
id int AUTO_INCREMENT,
primary key (id),
foreign key (email) references user(email)
);

create table feedback (
userid int,
eventid int, 
feedback text,
id int AUTO_INCREMENT,
primary key (id),
foreign key (userid) references user(id),
foreign key (eventid) references event(id)
);

