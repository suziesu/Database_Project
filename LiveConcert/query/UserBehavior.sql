#login 
select * from User where username = ?;
#if user is common user not artist,score<=10,
insert into Loginrecord(username, logintime, logouttime) values (?,now(),null);

#First Sign up
insert into User(username,name,password, dob,email,city,registime,score) values (?,?,?,?,?,?,now(),0);
#if user is common user
insert into Loginrecord(username, logintime,logouttime) values (?,now(),null);
#if user is an artist :
#artist look up band from system:
select baname from Band;
#if user has no band in system he can type a band name or checkbox (just yourself) 
insert into Band(baname, bbio, postby,bptime) values (?,?,?,now());
insert into Artist(username, verifystatus,verifytime,verifyID,baname,allowpost) values (?,0,null,?,?,?);
#after company approve the identity, when verifystatus = 1
#trigger/function:
update User set score = 20 where username= ? ;
#if the user choose some music taste:
select typename, subtypename from Subtype;
insert into UserTaste(username, typename, subtypename) values (?,?,?);

#accumulogintime need trigger when not artist user logout
#logout
update Loginrecord set logouttime = now() where username = ? and logouttime is null;
#trigger/function 
update User set score = score + ? where username = ?;

#edit profile
update User set username = ?, name = ?, password = ?,dob = ?, email = ?, city = ? where username = ?;
delete from UserTaste where username = ?;
insert into UserTaste(username, typename, subtypename) values (?,?,?);

#follow another user:
select username from User where username = ?;
insert into Follow(username, fusername, ftime) values (?,?,now());
#become fan of band:
select baname from Band where baname = ?;
insert into FansOf(username, baname, fobtime) values (?, ?, now());
#rate concert:
insert into ConcertRating(username, cname, rating, ratetime) values (?,?,?,now());
# if user want to change the rate
update ConcertRating set rating = ?, ratetime = now() where username = ? and cname = ?;
#review  concert:
insert into ConcertReview(username, cname, review, reviewtime) values (?,?,?,now());
# if user want to change the rate
update ConcertReview set review = ?, reviewtime = now() where username = ? and cname = ?;