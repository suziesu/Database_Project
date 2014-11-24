DELIMITER $$
create procedure loginrecord(IN username varchar(30))
begin
	insert into  Loginrecord(username, logintime, logouttime) values (username,now(),null);
end$$
create procedure logoutrecord(IN username varchar(30))
begin
	update Loginrecord
    set logouttime = now()
    where username = username and logouttime is null;
end$$
create procedure calculate_login_score(un varchar(30))
begin
	declare Ulogintime datetime;
    select timestampdiff(hour,max(logintime),max(logouttime)) into Ulogintime from Loginrecord where username = un ; 
	update User set score = if(score + Ulogintime/10 +0.1< 10,score + Ulogintime/10 +0.1,10) where username = un;
    
end$$
create procedure find_user_byname(username varchar(30))
begin
	select * from User where username = username;
end$$

create procedure onetypeallsubtype(typename varchar(50))
begin
	select * from Subtype where typename = typename;
end$$ 
create procedure insert_usertaste(username varchar(30),typename varchar(50), subtypename varchar(50))
begin
	insert into UserTaste(username, typename, subtypename) values (username,typename,subtypename);
end $$
create procedure insert_user(un varchar(30), n varchar(20), pw varchar(50),d date,e varchar(50),c varchar(50))
begin
	insert into User(username, name, password, dob, email, city, registime,score)
    values(un,n, pw,d,e,c,now(),0);
end $$

create procedure insert_artist(un varchar(30), vID varchar(10), baname varchar(50), allowpost boolean)
begin
	insert into Artist(username, verifystatus, verifytime, verifiedID,baname, allowpost)
    values (un,0,null,vID,baname,allowpost);
end$$
create procedure verify_artist(un varchar(30))
begin
	update Artist set verifytime = now(), verifystatus = 1 where username = un;
    update User set score = 20 where username = un;
end$$
create procedure dis_verify_artist(un varchar(30))
begin
	delete from Artist where username = un;
end$$

-- new band concert  
create procedure new_concert_band_user_follow(un varchar(30))
begin 
	select FC.cname as cname, FC.cposttime as cposttime,pb.baname as baname
	from Concert FC natural join PlayBand pb natural join FansOf fo
	where fo.username = un and FC.cpostime > (select max(logouttime) from Loginrecord where username =un);
end$$
create procedure new_recommen_list_by_follow(un varchar(30))
begin
	select * from UserRecommendList where username in (select username from Follow where fusername = un) and lcreatetime > (select max(logouttime) from Loginrecord where username =un);
end$$ 
create procedure new_registe_artist(un varchar(30))
begin
	select * from Artist where verifytime > (select max(logouttime) from Loginrecord where username =un); 
end$$
create procedure new_band(un varchar(30))
begin
	select * from Band where pbtime > (select max(logouttime) from Loginrecord where username =un);
end$$
create procedure follower_attend_concert(un varchar(30))
begin
	select * from AttendConcert where username in(select username from Follow where fusername = un) and actime > (select max(logouttime) from Loginrecord where username =un);
end$$
-- recommentpage 
create procedure recommend_list_most_by_taste(un varchar(30))
begin
select cname
from Concert natural join RecommencList
where cname in(
	select distinct cname
	from futureconcert as FNC natural join PlayBand natural join BandType
	where BandType.typename in (select typename from UserTaste where username=un ))
 group by cname
 order by count(*);
end$$

create procedure band_concert_user_followed(un varchar(30))
begin
select FC.cname
from Concert FC natural join PlayBand pb natural join FansOf fo
where fo.username = un and FC.cdatetime > now();
end$$

create procedure highrate_band_otheruser_sametaste(un varchar(30))
begin
select FC.cname
from Concert FC natural join PlayBand PB1 inner join
(select PB2.baname as baname, avg(CR.rating) as bandscore from UserTaste UT natural join ConcertRating CR natural join PlayBand PB2 
where UT.typename in (select typename from UserTaste where username = un)group by PB2.baname) as BS on PB1.baname = BS.baname
group by FC.cname
order by avg(BS.bandscore);
end$$

-- User
create procedure following_list(un varchar(30))
begin
	select username from FansOf where fusername = un;
end$$
create procedure follower_list(un varchar(30))
begin
	select fusername from FansOf where username = un;
end$$
create procedure insert_follow(un varchar(30),followerun varchar(30))
begin
	insert into Follow(username,fusername,ftime) values (un,followerun,now());
end$$
create procedure check_followed(un varchar(30),followerun varchar(30))
begin
	select * from Follow where username = un and fusername = followerun; 
end$$
create procedure plan_to_concert(un varchar(30))
begin
	select cname from AttendConcert natural join Concert where username = un and decision = "planto" and cdatetime > now();
end$$
create procedure going_concert(un varchar(30))
begin
	select cname from AttendConcert natural join Concert where username = un and decision = "going" and cdatetime > now();
end$$
create procedure attended_concert(un varchar(30))
begin
	select cname from AttendConcert natural join Concert where username = un and decision = "going" and cdatetime < now();
end$$
create procedure followed_band(un varchar(30))
begin
	select baname from FansOf where username = un;
end$$
create procedure my_recommend_list(un varchar(30))
begin
	select * from UserRecommendList where username = un order by lcreatetime;
end$$
create procedure followed_recommend_list(un varchar(30))
begin
	select * from ListFollower natural join UserRecommendList where follower = un;
end$$

-- concert rating/review
create procedure insert_review(un varchar(30),cname varchar(50),review text)
begin
	insert into ConcertReview(username, cname, review, reviewtime) values (un,cname,review, now());
end $$
create procedure insert_rating(un varchar(30),cname varchar(50),stars int)
begin
	if exists(select * from ConcertRating where username=un and cname = cname ) then
		update ConcertRating set ratine = stars where username = un and cname = cname;
	else
		insert into ConcertRating(username, cname, rating, ratetime) values (un,cname, stars,now());
	end if;
end$$
create procedure rating_by_user(un varchar(30), cname varchar(50))
begin
	select rating from ConcertRating where username=un and cname = cname;
end$$

create procedure concert_review(cn varchar(50))
begin
	select * from ConcertReview where cname = cn;

end$$

-- get list info recommendconcertlist
create procedure get_recommend_list_by_name(ln varchar(30))
begin
	select * from UserRecommendList where listname = ln;
end$$
create procedure get_recommend_list_concert(ln varchar(30))
begin
	select * from  RecommendList natural join Concert where listname = ln;
end$$      
create procedure follow_recommend_list(ln varchar(30),un varchar(30))
begin
	insert into ListFollower(listname, follower) values (ln,un);
end$$
create procedure is_followed(ln varchar(30),un varchar(30))
begin
	select * from ListFollower where listname = ln and follower = un;
end$$
create procedure add_to_recommendlist(ln varchar(30), cname varchar(50))
begin
	insert into RecommendList(listname,cname) values (ln, cname);
end$$
create procedure create_userrecommendlist(ln varchar(30), un varchar(30), descrip text)
begin
	insert into UserRecommendList(listname, username, lcreatetime,ldescription) values (ln, un, now(),descrip);
end$$
-- bandlist with type
create procedure get_all_band()
begin
	select * from Band; 
end$$
create procedure get_type_band(tp varchar(50))
begin
	select distinct * from Band natural join BandType where typename = tp;
end$$
create procedure get_subtype_band(subtp varchar(50))
begin
	select * from Band natural join BandType where subtypename = subtp;
end$$
create  procedure recommend_band_highrated_by_simitaste(un varchar(30))
begin
select PB2.baname as baname,B.babio from UserTaste UT natural join ConcertRating CR natural join PlayBand PB2 natural join Band B
where UT.typename in (select U.typename from UserTaste U where U.username = un) and B.baname not in (select F.baname from FansOf where username = un)
group by PB2.baname
order by avg(CR.rating);
end$$
-- recommendlist with type
create procedure get_all_list()
begin
	select * from UserRecommendList;
end$$
create procedure get_type_list(tp varchar(50))
begin
	select distinct listname ,ldescription, username ,lcreatetime
    from UserRecommendList natural join RecommendList natural join PlayBand natural join BandTast
    where typename = tp;
end$$
create procedure get_subtype_list(subtp varchar(50))
begin
	select distinct listname, ldescription, username, lcreatetime
    from UserRecommendList natural join RecommendList natural join PlayBand natural join BandTast 
    where subtypename = subtp;
end$$
create procedure recommend_list_most_follower_similar_taste(un varchar(30))
begin
	select URL.listname as listname,URL.username as username,URL.lcreatetime as lcreatetime, URL.ldescription as ldescription
    from UserRecommendList URL natural join ListFollower LF inner join UserTaste UT on LF.follower = UT.username
    where UT.typename in(select U.typename from UserTaste U where U.username = un)
    group by listname
    order by count(distinct listname) desc;
end$$
-- unfollow recommendlist
create procedure unfollow_recommenlist(un varchar(30),ln varchar(30))
begin
	delete from ListFollower where follower = un and listname = ln;

end$$
-- remove the userrecommendlist
create procedure delete_userrecommendlist(un varchar(30),ln varchar(30))
begin
	delete from UserRecommendList where username = un and listname = ln;
end$$
create procedure remove_concert_from_list(ln varchar(50),cn varchar(30))
begin
	delete from RecommendList where listname = ln and cname = cn;
end$$
DELIMITER ;





