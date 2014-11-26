DROP PROCEDURE IF EXISTS find_user_byname;
DROP PROCEDURE IF EXISTS calculate_login_score;
DROP PROCEDURE IF EXISTS logoutrecord;
DROP PROCEDURE IF EXISTS loginrecord;
DROP PROCEDURE IF EXISTS new_concert_band_user_follow;
DROP PROCEDURE IF EXISTS verify_artist;
DROP PROCEDURE IF EXISTS insert_artist;
DROP PROCEDURE IF EXISTS insert_user;
DROP PROCEDURE IF EXISTS insert_usertaste;
DROP PROCEDURE IF EXISTS onetypeallsubtype;
DROP PROCEDURE IF EXISTS band_concert_user_followed;
DROP PROCEDURE IF EXISTS recommend_list_most_by_taste;
DROP PROCEDURE IF EXISTS follower_attend_concert;
DROP PROCEDURE IF EXISTS new_band;
DROP PROCEDURE IF EXISTS new_registe_artist;
DROP PROCEDURE IF EXISTS new_recommen_list_by_follow;
DROP PROCEDURE IF EXISTS highrate_band_otheruser_sametaste;
DROP PROCEDURE IF EXISTS follower_list; 
DROP PROCEDURE IF EXISTS insert_follow;
DROP PROCEDURE IF EXISTS check_followed;
DROP PROCEDURE IF EXISTS plan_to_concert;
DROP PROCEDURE IF EXISTS going_concert; 
DROP PROCEDURE IF EXISTS attended_concert;
DROP PROCEDURE IF EXISTS followed_band;
DROP PROCEDURE IF EXISTS my_recommend_list;
DROP PROCEDURE IF EXISTS followed_recommend_list;
DROP PROCEDURE IF EXISTS get_subtype_future_concert;
DROP PROCEDURE IF EXISTS get_type_future_concert;
DROP PROCEDURE IF EXISTS get_all_future_concert;
DROP PROCEDURE IF EXISTS remove_concert_from_list;
DROP PROCEDURE IF EXISTS delete_userrecommendlist;
DROP PROCEDURE IF EXISTS unfollow_recommenlist;
DROP PROCEDURE IF EXISTS recommend_list_most_follower_similar_taste;
DROP PROCEDURE IF EXISTS get_subtype_list;
DROP PROCEDURE IF EXISTS get_type_list;
DROP PROCEDURE IF EXISTS get_all_list;
DROP PROCEDURE IF EXISTS recommend_band_highrated_by_simitaste;
DROP PROCEDURE IF EXISTS get_subtype_band;
DROP PROCEDURE IF EXISTS get_type_band;
DROP PROCEDURE IF EXISTS get_all_band;
DROP PROCEDURE IF EXISTS create_userrecommendlist;
DROP PROCEDURE IF EXISTS add_to_recommendlist;
DROP PROCEDURE IF EXISTS is_followed;
DROP PROCEDURE IF EXISTS follow_recommend_list;
DROP PROCEDURE IF EXISTS get_recommend_list_concert;
DROP PROCEDURE IF EXISTS get_recommend_list_by_name;
DROP PROCEDURE IF EXISTS concert_review;
DROP PROCEDURE IF EXISTS rating_by_user;
DROP PROCEDURE IF EXISTS insert_rating;
DROP PROCEDURE IF EXISTS insert_review;
DROP PROCEDURE IF EXISTS dis_verify_artist;
DROP PROCEDURE IF EXISTS following_list;
DROP PROCEDURE IF EXISTS get_type_describ;
DROP PROCEDURE IF EXISTS get_subtype_describ;
DROP PROCEDURE IF EXISTS get_band_info;
DROP PROCEDURE IF EXISTS get_band_future_concert;
DROP PROCEDURE IF EXISTS get_band_past_concert;
DROP PROCEDURE IF EXISTS fan_of_band;
DROP PROCEDURE IF EXISTS get_band_member;
DROP PROCEDURE IF EXISTS get_band_type;
DROP PROCEDURE IF EXISTS delete_band;
DROP PROCEDURE IF EXISTS be_fan;
DROP PROCEDURE IF EXISTS un_fan;
DROP PROCEDURE IF EXISTS remove_playband;
DROP PROCEDURE IF EXISTS remove_whole_concert;
DROP PROCEDURE IF EXISTS is_past_concert;
DROP PROCEDURE IF EXISTS is_in_concert_process;
DROP PROCEDURE IF EXISTS user_decision;
DROP PROCEDURE IF EXISTS rated_concert_score;
DROP PROCEDURE IF EXISTS get_recommend_list_from_cname;
DROP PROCEDURE IF EXISTS process_concert_basic_info;
DROP PROCEDURE IF EXISTS concert_loc_info;
DROP PROCEDURE IF EXISTS concert_basic_info;
DROP PROCEDURE IF EXISTS is_attended;
DROP PROCEDURE IF EXISTS get_band_by_cname;
DROP PROCEDURE IF EXISTS get_band_by_process_cname;
DROP PROCEDURE IF EXISTS get_all_ticket_count_cn;
DROP PROCEDURE IF EXISTS get_all_concert;
DROP PROCEDURE IF EXISTS get_type_all_concert;
DROP PROCEDURE IF EXISTS get_subtype_all_concert;
DROP PROCEDURE IF EXISTS insert_to_attendconcert;
DROP PROCEDURE IF EXISTS get_location_info;
DROP PROCEDURE IF EXISTS create_concert;
DROP PROCEDURE IF EXISTS create_concert_process;
DROP PROCEDURE IF EXISTS create_play_band;
DROP PROCEDURE IF EXISTS create_play_band_process;

DELIMITER $$

create procedure loginrecord(IN un varchar(30))
begin
	insert into  Loginrecord(username, logintime, logouttime) values (un,now(),null);
end$$
create procedure logoutrecord(IN un varchar(30))
begin
	update Loginrecord
    set logouttime = now()
    where username = un and logouttime is null;
end$$
create procedure calculate_login_score(un varchar(30))
begin
	declare Ulogintime int;
    select timestampdiff(hour,max(logintime),max(logouttime)) into Ulogintime from Loginrecord where username = un ; 
	update User set score = if(score + Ulogintime/10 +0.1< 10,score + Ulogintime/10 +0.1,10) where username = un;
    
end$$
create procedure find_user_byname(un varchar(30))
begin
	select * from User where username = un;
end$$




create procedure onetypeallsubtype(tp varchar(50))
begin
	select * from Subtype where typename = tp;
end$$ 
create procedure insert_usertaste(un varchar(30),ty varchar(50), subty varchar(50))
begin
	insert into UserTaste(username, typename, subtypename) values (un,ty,subty);
end $$
create procedure insert_user(un varchar(30), n varchar(20), pw varchar(150),d date,e varchar(50),c varchar(50))
begin
	insert into User(username, name, password, dob, email, city, registime,score)
    values (un,n, pw,d,e,c,now(),0);
end $$

create procedure insert_artist(un varchar(30), vID varchar(10), ba varchar(50), allow boolean)
begin
	insert into Artist(username, verifystatus, verifytime, verifiedID,baname, allowpost)
    values (un,0,null,vID,ba,allow);
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
	from futureconcert FC natural join PlayBand pb natural join FansOf fo
	where fo.username = un and FC.cposttime > (select max(logouttime) from Loginrecord where username =un);
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
	select * from Band where bptime > (select max(logouttime) from Loginrecord where username =un);
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
	select username from Follow where fusername = un;
end$$
create procedure follower_list(un varchar(30))
begin
	select fusername from Follow where username = un;
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
create procedure insert_review(un varchar(30),cn varchar(50),rev text)
begin
	insert into ConcertReview(username, cname, review, reviewtime) values (un,cn,rev, now());
end $$
create procedure insert_rating(un varchar(30),cn varchar(50),stars int)
begin
	if exists(select * from ConcertRating where username=un and cname = cname ) then
		update ConcertRating set ratine = stars where username = un and cname = cn;
	else
		insert into ConcertRating(username, cname, rating, ratetime) values (un,cn, stars,now());
	end if;
end$$
create procedure rating_by_user(un varchar(30), cn varchar(50))
begin
	select rating from ConcertRating where username=un and cname = cn;
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
create procedure add_to_recommendlist(ln varchar(30), cn varchar(50))
begin
	insert into RecommendList(listname,cname) values (ln, cn);
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
select PB2.baname as baname, B.bbio as bbio from UserTaste UT natural join ConcertRating CR natural join PlayBand PB2 natural join Band B
where UT.typename in (select U.typename from UserTaste U where U.username = un) and B.baname not in (select baname from FansOf where username = un)
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
    from UserRecommendList natural join RecommendList natural join PlayBand natural join BandType
    where typename = tp;
end$$
create procedure get_subtype_list(subtp varchar(50))
begin
	select distinct listname, ldescription, username, lcreatetime
    from UserRecommendList natural join RecommendList natural join PlayBand natural join BandType 
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

create procedure get_all_future_concert()
begin
	select * from futureconcert;
end$$

create procedure get_type_future_concert(tp varchar(50))
begin
	select * from futureconcert where cname in (select cname from PlayBand natural join BandType where typename = tp); 
end$$

create procedure get_subtype_future_concert(subtp varchar(50))
begin
	select * from futureconcert where cname in (select cname from PlayBand natural join BandType where subtypename = subtp);
end$$
-- get music type list
create procedure get_type_describ(tp varchar(50))
begin
	select * from Type where typename = tp;
end$$
create procedure get_subtype_describ(subtp varchar(50))
begin
	select * from Subtype where subtypename = subtp;
end$$
-- get band infomation

create procedure get_band_info(ba varchar(50))
begin
	select * from Band  where baname = ba;
end$$
create procedure get_band_future_concert(ba varchar(50))
begin
	select * from Band natural join futureconcert where baname = ba;
end$$

create procedure get_band_past_concert(ba varchar(50))
begin
	select * from Band natural join pastconcert where baname = ba;
end$$
create procedure fan_of_band(un varchar(30), ba varchar(50))
begin
	select * from FansOf where username= un and baname = ba;
end$$

create procedure get_band_member(ba varchar(50))
begin
	select * from BandMember where baname = ba;
end$$
create procedure get_band_type(ba varchar(50))
begin
	select * from BandType where baname = ba;
end$$
-- delete band

create procedure delete_band(ba varchar(50))
begin
	delete from Band where baname = ba;
end$$
create procedure be_fan(un varchar(30),ba varchar(50))
begin
	insert into FansOf(username, baname, fobtime) values (un,ba,now());
end$$
create procedure un_fan(un varchar(30), ba varchar(50))
begin
	delete from FansOf where username = un and baname = ba;
end$$
create procedure remove_playband(cn varchar(50),ba varchar(50))
begin
	delete from PlayBand where cname = cn and baname = ba;
end$$
create procedure remove_whole_concert(cn varchar(50))
begin
	if exists(select * from ConcertProcess where cname = cn) then
		delete from ConcertProcess where cname = cn;
	end if;
    delete from Concert where cname = cn;
end$$
create procedure is_past_concert(cn varchar(50))
begin
	select * from Concert where cname =cn and cdatetime < now();
end$$
create procedure is_in_concert_process(cn varchar(50))
begin
	select * from ConcertProcess  where cname = cn;
end$$
create procedure user_decision(un varchar(30), cn varchar(50))
begin
	select * from AttendConcert where username = un and cname = cn;
end$$
create procedure rated_concert_score(un varchar(30), cn varchar(50))
begin
	select * from ConcertRating where username = un and cname = cn;
end$$
-- concert_info
create procedure get_recommend_list_from_cname(cn varchar(50))
begin

	select * from RecommendList natural join UserRecommendList where cname = cn;
end$$
create procedure process_concert_basic_info(cn varchar(50))
begin
	select * from ConcertProcess where cname = cn;
end$$
create procedure concert_basic_info(cn varchar(50))
begin
	select * from Concert where cname = cn;
end$$
create procedure is_attended(un varchar(30),cn varchar(50))
begin
	select * from AttendConcert natural join pastconcert where cname = cn and decision = 'going';
end$$
create procedure concert_loc_info(loc varchar(50))
begin
	select * from Venues where locname = loc;
end$$
create procedure get_band_by_cname(cn varchar(50))
begin
	select * from PlayBand where cname = cn;
end$$
create procedure get_band_by_process_cname(cn varchar(50))
begin
	select * from PlayBandProcess where cname = cn;
end$$
create procedure get_all_ticket_count_cn(cn varchar(50))
begin 
	select sum(quantity) as count from Userticket where cname = cn;
end$$

create procedure get_all_concert()
begin
	select * from Concert;
end$$

create procedure get_type_all_concert(tp varchar(50))
begin
	select * from Concert where cname in (select cname from PlayBand natural join BandType where typename = tp); 
end$$

create procedure get_subtype_all_concert(subtp varchar(50))
begin
	select * from Concert where cname in (select cname from PlayBand natural join BandType where subtypename = subtp);
end$$
create procedure insert_to_attendconcert(un varchar(30), cn varchar(50),deci varchar(10))
begin
	insert into AttendConcert(username, cname, decision, actime) values (un, cn, deci,now());
end$$
create procedure get_location_info(loc varchar(50))
begin
	select * from Venues where locname = loc;
end$$

create procedure create_concert(cn varchar(50), cd datetime, loc varchar(50), pr int(4),avai int(5),cdes text,cp varchar(30),tick varchar(255) )
begin 
	insert into Concert (cname, cdatetime, locname, price, availability, cdescription, cpostby, cposttime, ticketlink) 
    values (cn, cd, loc, pr, avai, cdes,cp,now(),tick);
end$$

create procedure create_concert_process(cn varchar(50), edit varchar(30),cd datetime,loc varchar(50),pr int(4), avai int(5), cdes text)
begin
	insert into ConcertProcess (cname, posttime,editby, cstatus,cdatetime,locname,price,availability,cdescription)
    values (cn,now(),edit,'pending',cd,loc,pr,avai,cdes);
end$$

create procedure create_play_band(cn varchar(50),ba varchar(50))
begin
	insert into PlayBand(cname, baname) values (cn,ba);
end$$
create procedure create_play_band_process(cn varchar(50),ba varchar(50))
begin
	insert into PlayBandProcess(cname, baname) values (cn,ba);
end$$



DELIMITER ;







