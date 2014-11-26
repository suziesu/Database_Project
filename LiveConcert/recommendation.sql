-- Calculate Pearson Similarity
-- we can use trigger or function to calculate the recommendation 
-- This recommendation is based on rating for band then predict upcoming concert rating by user
create table PearsonSimilarity(
	baname1 varchar(50),
    baname2 varchar(50),
    sumR1 int(10),
    sumR2 int(10),
    sumR1square int(10),
    sumR2square int(10),
    sumR1multiR2 int(10),
    count int(10),
    pearsonsimi decimal(8,3),
    PRIMARY KEY (baname1,baname2),
	FOREIGN KEY (baname1) REFERENCES Band(baname),
    FOREIGN KEY (baname2) REFERENCES Band(baname)
);
-- create view of the band rating score based on the concert user rated
create view UserRatingBand(username,baname,rating) as
select username, baname, avg(rating) from ConcertRating natural join PlayBand group by username,baname;
-- then calculate the pearson operand of the two bands based on the band score gaven by user
create view PearsonSimilarity(baname1, baname2, sumR1, sumR2,sumR1square,sumR2square,sumR1multiR2,count,pearsonsimi) as
	select R1.baname, R2.baname, sum(R1.rating),sum(R2.rating), 
    sum(R1.rating * R1.rating), sum(R2.rating*R2.rating), sum(R1.rating *R2.rating),count(*),0   
    from UserRatingBand as R1, UserRatingBand as R2
    where R1.username = R2.username and R1.baname <> R2.baname
    group by R1.baname, R2.baname;
-- create the trigger and stored procedure for Pearson Similarity calculation
-- when someone insert into the Concertrating table, then,call this procedure. 
drop procedure if exists calcu_PeasonSimilarity;
DELIMITER $$
create procedure calcu_PearsonSimilarity(IN c1 char(50), IN c2 char(50),IN x int(10),IN y int(10),IN xx int(10),IN yy int(10),IN xy int(10),IN count int(10))
begin
	set @numerator = xy - (x * y / count);
	set @denominator = sqrt(xx - pow(x, 2)) * sqrt(yy - pow(y, 2));
	update PearsonSimilarity set pearsonsimi = @numerator/@denominator where cname1=c1 and cname2 = c2;

end
$$
DELIMITER ;
-- then for each row inserted into PearsonSimilarity use trigger to update similarity, call calcu_PearsonSimilarity function;
drop trigger if exists afterInsertPS;
DELIMITER $$
create trigger afterInsertPS after insert on PearsonSimilarity
for each row
begin
	call calcu_PearsonSimilarity(new.baname1,new.baname2,new.sumR1,new.sumR2,
				new.sumR1square, new.sumR2square, new.sumR1multiR2,new.count);
end $$
DELIMITER ;
-- for update PearsonSimilarity instead of insert
drop trigger if exists afterUpdatePS;
DELIMITER $$
create trigger afterUpdatePS after update on PearsonSimilarity
for each row
begin
	if new.sumR1multiR2 <> old.sumR1multiR2 then
		call calcu_PearsonSimilarity(new.baname1,new.baname2,new.sumR1,new.sumR2,
				new.sumR1square, new.sumR2square, new.sumR1multiR2,new.count);
	end if;
end $$
DELIMITER ;
-- after data initialized, then when the user insert one rating into the Concert rating,use trigger to update to the PS table
-- then for each row inserted into Concert rating, use trigger to update PStable, call calcu_PearsonSimilarity function;
-- drop trigger if exists afterInsertConcertRating;
-- DELIMITER $$
-- create trigger afterInsertConcertRating after insert on ConcertRating
-- for each row
-- begin
-- 
--     if exists(select * from PearsonSimilarity where cname2 = new.cname) then
-- 		update PearsonSimilarity as PS inner join ConcertRating CR on PS.cname1 = CR.cname
-- 			set sumR1=sumR1+CR.rating,sumR2=sumR2+new.rating,
--             sumR1square=sumR1square+CR.rating* CR.rating,sumR2square=sumR2square+new.rating * new.rating,sumR1multiR2=sumR1multiR2+CR.rating*new.rating,count=count+1 
--         where PS.cname2 = new.cname and CR.username = new.username and CR.cname <> new.cname;
--         update PearsonSimilarity as P inner join ConcertRating C on P.cname2 = CR.cname
-- 			set sumR1=sumR1+new.rating,sumR2=sumR2+CR.rating,
--             sumR1square=sumR1square+new.rating * new.rating,sumR2square=sumR2square+CR.rating* CR.rating,sumR1multiR2=sumR1multiR2+CR.rating*new.rating,count=count+1 
--         where PS.cname1 = new.cname and CR.username = new.username and CR.cname <> new.cname;
-- 	else
-- 		insert into PearsonSimilarity 
-- 			select R1.cname, new.cname, R1.rating,new.rating, R1.rating * R1.rating, 
-- 					new.rating*new.rating, R1.rating *new.rating,1,0   
-- 			from ConcertRating R1
-- 			where R1.username = new.username and R1.cname <>new.cname;
-- 		insert into PearsonSimilarity 
-- 			select new.cname, R1.cname,new.rating, R1.rating, 
-- 					new.rating*new.rating, R1.rating * R1.rating, R1.rating *new.rating,1,0   
-- 			from ConcertRating R1
-- 			where R1.username = new.username and R1.cname <>new.cname;
-- 	end if;
-- 		
-- end $$
-- DELIMITER ;

-- Get predict score for upcoming concert user might rate:
-- select NR.cname as cname,sum(PS.pearsonsimi*CR.rating)/sum(PS.pearsonsimi) as guessscore
-- from (select * from furureconcert) as NR 
-- inner join PearsonSimilarity as PS on NR.cname = PS.cname1,ConcertRating CR
-- where PS.pearsonsimi > 0 and CR.username = ? and PS.cname2 = CR.cname
-- group by NR.cname
-- order by guessscore;




-- Change to rating upcoming concert by Band similarity and band rating:
-- because we cannot calculate the upcoming concert similarity because no one rate this,instead we calculate the band similarity 
-- and calculate the upcoming concert based on band similarity and rating score
-- to decrease the table we only calculate the band has no rating by user
create view noRatingBandByUser(username,baname) as
select username, baname 
from Band natural join UserRatingBand URB
where URB.rating is null;

-- create view of all band rating score by user
create view predictBandRate(username, baname, predicBandScore ) as
(select B.username, B,baname, sum(PS.pearsonsimi*URB.rating)/sum(PS.pearsonsimi)
from noRatingBandByUser B inner join PearsonSimilarity PS on B.baname = PS.baname1, UserRatingBand URB
where PS.pearsonsimi > 0 and URB.username = B.username and PS.baname2 = URB.baname
group by B.username, B.baname)
UNION
(select *
from UserRatingBand);


-- write query to find the concert score based on the bandrate by user 
select FC.cname as cname, ave(PBR.predicBandScore)
from futureconcert FC natural join PlayBand natural join predictBandRate PBR
where PBR.username = ? 
group by FC.cname;


-- other querys could be used. 
--  the system could recommend to the user those concerts in the categories the user likes that 
-- were recommended in many lists by other usersthose can divide to 2 query in application
select cname
from Concert natural join RecommencList
where cname in(
	select distinct cname
	from futureconcert as FNC natural join PlayBand natural join BandType
	where BandType.typename in (select typename from UserTaste where username=? ))
 group by cname
 order by count(*);
 

-- the system could suggest bands that were liked or who concerts were highly rated by other users that 
-- had similar tastes to this user in the past 
select FC.cname
from Concert FC natural join PlayBand pb natural join FansOf fo
where fo.username = ? and FC.cdatetime > now();

select FC.cname
from Concert FC natural join PlayBand PB1 inner join
(select PB2.baname as baname, avg(CR.rating) as bandscore from UserTaste UT natural join ConcertRating CR natural join PlayBand PB2 
where UT.typename in (select typename from UserTaste where username = ?)group by PB2.baname) as BS on PB1.baname = BS.baname
group by FC.cname
order by avg(BS.bandscore)

-- trigger/function or query to update Pearsonsimi;
-- when user rate one concert, insert value into PearsonSimilarity if not exists
-- insert into ConcertRating(username, cname,rating, ratetime) values (?,?,?,now());
-- insert into PearsonSimilarity(cname1,cname2,sumR1,sumR2,sumR1square, sumR2square, sumR1multipleR2,count,pearsonsimi) 
-- 	select R1.cname, cname, R1.rating, rating, R1.rating * R1.rating, rating * rating, R1.rating  * rating,1,0
--     from ConcertRating R1
--     where R1.username = ?;#username
		




