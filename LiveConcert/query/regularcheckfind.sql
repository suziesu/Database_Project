#jazz concert
select cname from Venues natural join Concert natural join PlayBand natural join BandType
where typename = 'Jazz' and city= ? and date(cdatetime) - curdate() < 7 and date(cdatetime) - curdate() > 0;
#folloering people recommend concert
select cname from Concert natural join RecommencList natural join UserRecommendList natural join Follow
where fusername = ? and date(cdatetime)-curdate() < 30 and date(cdatetime) - curdate() > 0;
#all newly posted concnerts since the last time they loggedin
#for high score user to see all modified concert and new posted one in both process table and concert table
select cname from Concert left outer join ConcertProcess 
on Concert.cname = ConcertProcess.cname where posttime > 
	(select max(logouttime) from Loginrecord where username = ?)
union 
select cname from Concert right outer join ConcertProcess 
on Concert.cname = ConcertProcess.cname where cposttime > 
	(select max(logouttime) from Loginrecord where username = ?);
#for normal user 
select cname from Concert where cpostime > (select max(logouttime) from Loginrecord where username = ?);
