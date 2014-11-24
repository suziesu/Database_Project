#post/edit concert using application to test:
select score from User where username = ?;
#if user have the permission/privilege score >= 10: super user and company and artist then
#first search if the cname is already exits.
select cname from Concert where cname = ?;
#post person can choose which band can play
#if exists show link that concert link they can choose update or not
update Concert set cname=?,cdatetime=?, locname=?, price=?, availability=?,cdescription=?,cpostby=?, cposttime=?,ticketlink=?
where cname=?; 
update PlayBand set baname=? where cname = ?;#if band info being updated
#else
insert into Concert(cname,cdatetime, locname, price, availability,cdescription,cpostby, cposttime,ticketlink) 
values (?,?,?,?,0,?,?,?,?);
#post person can choose which band can play
insert into PlayBand(cname, baname) values (?, ?);
#else if the user score is <10 (I would not allow common user to set the ticketlink, even they have 2 more score>7 users' support)
#if exists show link that concert link they can choose update or not
select cname from Concert where cname = ?;
#if they want to update get the cname, 
insert into ConcertProcess(cname,posttime,editby, cstatus,cdatetime,locname, price,cdescription)
values (?,now(),?,'pending',?,?,?,?);
insert into PlayBandProcess(cname, baname) values (?,?);

# or using mysql query to test :
#if user is score >=10 and no cname exists in Concert:
insert into Concert(cname,cdatetime, locname, price, availability,cdescription,cpostby, cposttime,ticketlink) 
select ?,?,?,?,?,?,username,now(),?
from User 
where exists (select username from User where username = ? and score >= 10 );
insert into PlayBand(cname,baname) values (?,?);
#if concert exists,use update

#else if user score < 10
insert into ConcertProcess(cname,posttime,editby, cstatus,cdatetime,locname, price,cdescription)
select ?,now(),username,'pending',?,?,?,?
from User
where not exists (select username from User where username = ? and score > 10);
insert into PlayBandProcess(cname,baname) values (?,?);


#trigger/function set availabolity
#select capacity from Venues where locname = ?;
#select count(*) as ordercount from Userticket where cname = ?;
#update Concert set availability = capacity - ordercount where cname = ?;
#trigger/function if the concert date is past,we will delete the update process table.
#delete ConcertProcess.*, PlayBandProcess.* from ConcertProcess, PlayBandProcess 
#where ConcertProcess.cname = PlayBandProcess.cname and cdatetime < now();
##trigger/function for Concertstatus
#if concertprocess status is complete, then delete tuple from Process update or insert into Concert
#if exists in concert, update, if not insert

#create recommend list:
insert into UserRecommendList(listname, username, lcreatetime, ldescription) values (?,?,now(),?);

#add one more concert:
insert into RecommencList(listname, cname) values (?,?);