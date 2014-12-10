
drop table IF EXISTS RecommendList;
drop table IF EXISTS ListFollower;
drop table IF EXISTS UserRecommendList;
drop table IF EXISTS ConcertRating;
drop table IF EXISTS ConcertReview;
drop table IF EXISTS AttendConcert;
drop table IF EXISTS FansOf;
drop table IF EXISTS BandType;
drop table IF EXISTS UserTaste;
drop table IF EXISTS Subtype;
drop table IF EXISTS Type;
drop table IF EXISTS PlayBandProcess;
drop table IF EXISTS PlayBand;
drop table IF EXISTS Userticket;
drop table IF EXISTS ConcertProcess;
drop table IF EXISTS Concert;
drop table IF EXISTS Venues;
drop table IF EXISTS BandMember;
drop table IF EXISTS Artist;
drop table IF EXISTS Band;
drop table IF EXISTS Loginrecord;
drop table IF EXISTS Follow;
drop table IF EXISTS User;
drop view IF EXISTS futureconcert; 
drop view IF EXISTS pastconcert;

CREATE table User(
	username varchar(30) PRIMARY KEY,
	name varchar(30),
	password varchar(150) not NULL,
	dob date default NULL,
	email varchar(50) default NULL,
	city varchar(50) default NULL,
	registime datetime not NULL,
	score DECIMAL(3,1)
);

CREATE table Follow(
	username varchar(30), 
	fusername varchar(30),
	ftime datetime not NULL,
	PRIMARY KEY (username,fusername),
	FOREIGN KEY (username) REFERENCES User(username) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (fusername) REFERENCES User(username) ON DELETE CASCADE ON UPDATE CASCADE

);


create table Loginrecord(
	username varchar(30),
	logintime datetime,
	logouttime datetime,
	PRIMARY KEY (username, logintime),
	FOREIGN KEY (username) REFERENCES User(username) ON DELETE CASCADE ON UPDATE CASCADE
);

create table Band(
	baname varchar(50),
	bbio text,
	postby varchar(30) not null,
	bptime datetime not null,
	PRIMARY KEY (baname),
	FOREIGN KEY (postby) REFERENCES User(username) ON DELETE CASCADE ON UPDATE CASCADE
);

create table Artist(
	username varchar(30),
	verifystatus boolean not null default 0,
	verifytime datetime,
	verifyID varchar(10),
	baname varchar(50), 
	allowpost boolean not null default 1,
	PRIMARY KEY (username),
	FOREIGN KEY (username) REFERENCES User(username) ON DELETE CASCADE ON UPDATE CASCADE
	-- FOREIGN KEY (baname) REFERENCES Band(baname) ON DELETE CASCADE
);


create table BandMember(
	baname varchar(50),
	bandmember varchar(30),
	PRIMARY KEY (baname,bandmember),
	FOREIGN KEY (baname) REFERENCES Band(baname) ON DELETE CASCADE ON UPDATE CASCADE
);

create table Venues(
	locname varchar(50),
	address varchar(50),
	city varchar(50),
	state varchar(50),
	capacity int(5),
	web varchar(255),
	PRIMARY KEY (locname)
);

create table Concert(
	cname varchar(50),
	cdatetime datetime,
	locname varchar(50),
	price int(4),
	availability int(5),
	cdescription text,
	cpostby varchar(30),
	cposttime datetime not null,
	ticketlink varchar(255),
	PRIMARY KEY (cname),
	FOREIGN KEY (locname) REFERENCES Venues(locname) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (cpostby) REFERENCES User(username) ON DELETE CASCADE ON UPDATE CASCADE
);

create table ConcertProcess(
	cname varchar(50),
	posttime datetime not null,
	editby varchar(30),
	cstatus varchar(10),
	cdatetime datetime,
	locname varchar(50),
	price int(4),
    availability int(5),
	cdescription text,
	PRIMARY KEY (cname),
	FOREIGN KEY (editby) REFERENCES User(username) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (locname) REFERENCES Venues(locname) ON DELETE CASCADE ON UPDATE CASCADE
);

create table Userticket(
	username varchar(30),
	cname varchar(50),
	buytime datetime,
	quantity int(3),
	PRIMARY KEY (username,cname,buytime),
	FOREIGN KEY (username) REFERENCES User(username) ON DELETE CASCADE ON UPDATE CASCADE
);

create table PlayBand(
	cname varchar(50),
	baname varchar(50),
	PRIMARY KEY (baname, cname),
	FOREIGN KEY (baname) REFERENCES Band(baname) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (cname) REFERENCES Concert(cname) ON DELETE CASCADE ON UPDATE CASCADE
);
create table PlayBandProcess(
	cname varchar(50),
	baname varchar(50),
	PRIMARY KEY (baname, cname),
	FOREIGN KEY (baname) REFERENCES Band(baname) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (cname) REFERENCES ConcertProcess(cname) ON DELETE CASCADE ON UPDATE CASCADE
);
create table Type(
	typename varchar(50),
	typedecrip text,
	PRIMARY KEY (typename)
);


create table Subtype(
	typename varchar(50),
	subtypename varchar(50),
	subtypedescrip text,
	PRIMARY KEY (typename, subtypename),
	FOREIGN KEY (typename) REFERENCES Type(typename) ON DELETE CASCADE ON UPDATE CASCADE
);

create table UserTaste(
	username varchar(30),
	typename varchar(50),
	subtypename varchar(50),
	PRIMARY KEY (username,typename,subtypename),
	FOREIGN KEY (username) REFERENCES User(username) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (typename,subtypename) REFERENCES Subtype(typename,subtypename) ON DELETE CASCADE ON UPDATE CASCADE
);


create table BandType(
	baname varchar(50),
	typename varchar(50),
	subtypename varchar(50),
	PRIMARY KEY (baname,typename,subtypename),
	FOREIGN KEY (baname) REFERENCES Band(baname) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (typename,subtypename) REFERENCES Subtype(typename,subtypename) ON DELETE CASCADE ON UPDATE CASCADE
);


create table FansOf(
	username varchar(30),
	baname varchar(50),
	fobtime datetime,
	PRIMARY KEY (username, baname),
	FOREIGN KEY (username) REFERENCES User(username) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (baname) REFERENCES Band(baname) ON DELETE CASCADE ON UPDATE CASCADE
);

create table AttendConcert(
	username varchar(30),
	cname varchar(50),
	decision varchar(10),
	actime datetime,
	PRIMARY KEY (username, cname),
	FOREIGN KEY (username) REFERENCES User(username) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (cname) REFERENCES Concert(cname) ON DELETE CASCADE ON UPDATE CASCADE
);


create table ConcertRating(
	username varchar(30),
	cname varchar(50),
	rating int(2),
	ratetime datetime,
	PRIMARY KEY (username, cname, ratetime),
	FOREIGN KEY (username) REFERENCES User(username) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (cname) REFERENCES Concert(cname) ON DELETE CASCADE ON UPDATE CASCADE
);
create table ConcertReview(
	username varchar(30),
	cname varchar(50),
	review text,
	reviewtime datetime,
	PRIMARY KEY (username, cname, reviewtime),
	FOREIGN KEY (username) REFERENCES User(username) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (cname) REFERENCES Concert(cname) ON DELETE CASCADE ON UPDATE CASCADE 
);

create table UserRecommendList(
	listname varchar(30),
	username varchar(30),
	lcreatetime datetime,
	ldescription text,
	PRIMARY KEY (listname),
	FOREIGN KEY (username) REFERENCES User(username) ON DELETE CASCADE ON UPDATE CASCADE
);

create table ListFollower(
	listname varchar(30),
	follower varchar(30),
	PRIMARY KEY (listname,follower),
	FOREIGN KEY (follower) REFERENCES User(username) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (listname) REFERENCES UserRecommendList(listname) ON DELETE CASCADE ON UPDATE CASCADE
);


create table RecommendList(
	listname varchar(30),
	cname varchar(50),
	PRIMARY KEY (listname, cname),
	FOREIGN KEY (listname) REFERENCES UserRecommendList(listname) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (cname) REFERENCES Concert(cname) ON DELETE CASCADE ON UPDATE CASCADE
);

create view FutureConcert as 
	select * from Concert where cdatetime > now();
create view PastConcert as 
	select * from Concert where cdatetime < now();



INSERT INTO User VALUES ('admin','admin','admin','2000-08-30','admin@liveconcert.com','NY','2013-08-01 19:05:00',30);
INSERT INTO User VALUES ('suzie','suzie','suzie','2000-08-30','suzie@liveconcert.com','NY','2013-08-02 19:05:00',10);
INSERT INTO User VALUES ('wendy','wendy','wendy','2000-08-30','wendy@liveconcert.com','NY','2013-08-03 19:05:00',10);
INSERT INTO User VALUES ('A','A','A','2000-08-30','A@liveconcert.com','NY','2013-08-03 19:05:00',2);
INSERT INTO User VALUES ('B','B','B','2000-08-30','B@liveconcert.com','NY','2013-08-03 19:05:00',3);
INSERT INTO User VALUES ('C','C','C','2000-08-30','C@liveconcert.com','SA','2013-08-03 19:05:00',5);
INSERT INTO User VALUES ('D','D','D','2000-08-30','D@liveconcert.com','WA','2013-08-03 19:05:00',8);
INSERT INTO User VALUES ('E','E','E','2000-08-30','E@liveconcert.com','DC','2013-08-03 19:05:00',0);
INSERT INTO User VALUES ('Cat Power','Cat Power','catpower','2000-08-30','catpower@liveconcert.com','NY','2013-08-04 19:05:00',20);
INSERT INTO User VALUES ('Explosions In The Sky','Explosions In The Sky','explosionsinthesky','2008-11-11','explosionsinthesky@liveconcert.com','NY','2013-11-11 07:05:00',11);
INSERT INTO User VALUES ('The Kooks','The Kooks','thekooks','2010-02-15','thekooks@liveconcert.com','NY','2013-04-11 11:05:00',15);
INSERT INTO User VALUES ('Peter Denton','Peter Denton','peterdenton','1990-02-15','peterdenton@liveconcert.com','NY','2012-05-06 21:12:00',7);
INSERT INTO User VALUES ('Davey Latter','Davey Latter','davetlatter','1978-12-13','daveylatter@liveconcert.com','NY','2014-05-06 09:33:00',13);



INSERT INTO Loginrecord VALUES ('suzie','2014-11-01 19:05:00','2014-11-19 19:05:00');
INSERT INTO Loginrecord VALUES ('suzie','2014-06-01 19:05:00','2014-07-03 19:05:00');
INSERT INTO Loginrecord VALUES ('suzie','2013-08-01 19:05:00','2013-09-02 19:05:00');
INSERT INTO Loginrecord VALUES ('wendy','2014-11-01 19:05:00','2014-11-19 19:05:00');
INSERT INTO Loginrecord VALUES ('wendy','2014-06-01 19:05:00','2014-07-03 19:05:00');
INSERT INTO Loginrecord VALUES ('wendy','2013-08-01 19:05:00','2013-09-02 19:05:00');
INSERT INTO Loginrecord VALUES ('A','2014-11-01 19:05:00','2014-11-19 19:05:00');
INSERT INTO Loginrecord VALUES ('B','2014-06-01 19:05:00','2014-07-03 19:05:00');
INSERT INTO Loginrecord VALUES ('C','2014-11-01 19:05:00','2014-11-19 19:05:00');
INSERT INTO Loginrecord VALUES ('D','2014-11-01 19:05:00','2014-11-19 19:05:00');
INSERT INTO Loginrecord VALUES ('D','2013-08-01 19:05:00','2013-09-02 19:05:00');
INSERT INTO Loginrecord VALUES ('E','2014-08-01 19:05:00','2014-11-01 19:05:00');

INSERT INTO Follow VALUES ('suzie','wendy','2014-06-01 19:05:00');
INSERT INTO Follow VALUES ('wendy','suzie','2014-06-01 19:05:00');
INSERT INTO Follow VALUES ('A','B','2014-06-01 19:05:00');
INSERT INTO Follow VALUES ('B','C','2014-06-01 19:05:00');
INSERT INTO Follow VALUES ('A','C','2014-06-01 19:05:00');
INSERT INTO Follow VALUES ('D','E','2014-06-01 19:05:00');
INSERT INTO Follow VALUES ('A','E','2014-06-01 19:05:00');


INSERT INTO Band VALUES ('Lykke Li','Lykke Li (Swedish pronunciation: [l.k li]; born Li Lykke Timotej Svensson Zachrisson; 18 March 1986) is a Swedish Indie Pop singer-songwriter. Her music often blends elements of Indie Pop, alternative, and electronic; instruments found in her songs include violins, synthesizers, tambourines, trumpets, saxophones, and cellos. Li possesses the vocal range of a soprano. Her debut album, Youth Novels, was released in 2008, her second album, Wounded Rhymes, was released in 2011, and her most recent album, I Never Learn, was released in 2014.\n','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('The 1975','The 1975 are an English indie rock band formed based in Manchester. The group consists of Matt Healy (vocals, guitar), Adam Hann (guitar), George Daniel (drums, backing vocals) and Ross MacDonald (bass).
They have released four EPs, while their self-titled debut album was released on 2 September 2013 through Dirty Hit/Polydor. The album debuted at No. 1 in the UK Albums Chart on 8 September 2013. The band have toured internationally and also played the 2014 Coachella festival.','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('If These Trees Could Talk', 'If These Trees Could Talk is an instrumental post-rock band from Akron, Ohio. The band self-released their self-titled debut EP in 2006. Independent record label The Mylene Sheath re-released the EP in 2007, and went on to release the band\'s debut studio album, Above the Earth, Below the Sky, in 2009. The band self-released their second album Red Forest in March 2012, whilst the album\'s vinyl release went through Science of Silence Records.','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('The Flashbulb','Benn Lee Jordan (born October 28, 1979) is an American modern jazz and IDM musician operating under many pseudonyms. Since 1999 his most widely distributed and electric music has been released under the name of The Flashbulb. Other popular names Benn has released as are Acidwolf, Human Action Network, and FlexE.\n 
Jordan was born in 1979 and raised in Chicago by his grandparents. Influenced by the local jazz scene, Jordan was an accomplished self-taught guitarist as a child. Due to being left-handed and not having lessons, he learned to play a right-handed guitar upside down, which he continues to do. He began his music career releasing instrumental music on small labels in the United States and Europe in 1996 under various aliases, most notably The Flashbulb. Years later he began to work as a freelance composer for various television and film agencies. In 2006, The Flashbulb toured and worked with The Dillinger Escape Plan which helped diversify his listeners. Benn is still an active jazz guitar player and drummer.','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('Saxon Shore','Saxon Shore is an American post-rock band consisting of members from Philadelphia, Pennsylvania and Brooklyn, New York. The group has thus far released five albums: one packaged as an EP (Luck Will Not Save Us...) and four as full-lengths. Earlier records featured only five to seven songs, while more recent albums have had several more.
Saxon Shore\'s overall sound has been compared to Caspian, Explosions in the Sky, Joy Wants Eternity, This Will Destroy You, and God is an Astronaut.','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('Explosions In The Sky', 'Explosions in the Sky is an American post-rock band from Texas. The quartet originally played under the name Breaker Morant, then changed to the current name in 1999. The band has garnered popularity beyond the post-rock scene for their elaborately developed guitar work, narratively styled instrumentals, what they refer to as "cathartic mini-symphonies," and their enthusiastic and emotional live shows. They primarily play with three electric guitars and a drum kit, although band member Michael James will at times exchange his electric guitar for a bass guitar. Recently the band has added a fifth member to their live performances. The band\'s music is almost purely instrumental.
','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('This Will Destroy You','This Will Destroy You, often abbreviated to TWDY, is an American post-rock band from San Marcos, Texas, formed in 2005. The band consists of guitarists Jeremy Galindo and Chris King, bass player and keyboardist Donovan Jones and drummer Alex Bhore. They typically compose lengthy atmospheric instrumental pieces, featuring layers of effects-laden guitar and a heavy usage of dynamics.
Their album, Tunnel Blanket, was released in May 2011. It entered the Billboard Heatseekers Album Chart at number 25.','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('The xx','The xx are an English Indie Pop band formed in 2005 in Wandsworth, London. The group released their debut album, xx, in August 2009. The album ranked highly on many best of 2009 lists, placed at number one on the list compiled by The Guardian and second for NME. In 2010, the band won the Mercury Music Prize for their debut album. Their second album, Coexist, was released on 10 September 2012.','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('Belleruche','Belleruche was a three-piece electronic/soul band from the United Kingdom. They were signed on the Brighton based Tru Thoughts label.
The band was formed in North London in 2005.
Belleruche released a series of three limited 7" records on their own Hippoflex Recording Industries label before signing with Tru Thoughts, which sold out in British independent record stores and attracted a cult following in Europe, having been hand-distributed by the band at their gigs.
In 2007 Belleruche signed to Tru Thoughts and their debut album Turntable Soul Music was released in July of the same year, becoming the fastest-selling debut album in the label\'s history. They have played at venues as diverse as Montreux Jazz Festival, Glastonbury Festival, and many more.
In October 2008 The Express, the band\'s second album, was released. Their first single, "Anything You Want (Not That)", was awarded the Single Of The Week spot on iTunes. The Express has gained major daytime radio support, being played in rotation by Nemone on BBC 6Music radio.','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('The Kooks','The Kooks are a British rock band formed in Brighton, East Sussex, in 2001. The band currently consists of Luke Pritchard (vocals/guitar), Hugh Harris (lead guitar/synth), Alexis Nunez (drums), and Peter Denton (bass guitar). The original bassist was Max Rafferty. The lineup of the band remained constant until the departure of Rafferty in 2008. Dan Logan served as a temporary replacement, until Peter Denton joined the band permanently in October 2008. Early in 2010, Pritchard announced the departure of drummer Paul Garred, due to a nerve problem in his arm. Late in the year, Garred rejoined for studio sessions, however Chris Prendergast played drums when the band played live. In 2012, the band was accompanied on drums by Alexis Nunez (from Golden Silvers), who eventually joined the band full-time.
A self-described "pop" band, their music is primarily influenced by the 1960s British Invasion movement and post-punk revival of the new millennium. With songs described as "catchy as hell", The Kooks have experimented in several genres including rock, Britpop, pop, reggae, ska, and more recently, funk and hip-hop, being described once as a "more energetic Thrills or a looser Sam Roberts Band, maybe even a less severe Arctic Monkeys at times".
','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('CHVRCHES','Chvrches (pronounced as "churches" and stylised as CHVRCHS) are a Scottish electronic band, formed in 2011. The group consists of Lauren Mayberry (lead vocals, additional synthesisers, and samplers), Iain Cook (synthesisers, guitar, bass, vocals), and Martin Doherty (synthesisers, samplers, vocals).
Chvrches came fifth on the BBC\'s Sound of 2013 list of the most promising new music talent. In March 2013, they released Recover EP. Their debut studio album The Bones of What You Believe was released on 20 September 2013.
Their song "The Mother We Share" was featured in the opening video for the 2014 Commonwealth Games Opening Ceremony in Glasgow, Scotland, on the 23rd July 2014.','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('Arctic Monkeys','Arctic Monkeys are an English indie rock band formed in 2002 in High Green, a suburb of Sheffield. The band consists of Alex Turner (lead vocals, rhythm guitar), Jamie Cook (lead guitar, backing vocals in the early days), Nick O\'Malley (bass, backing vocals), and Matt Helders (drums, backing vocals). Former band member Andy Nicholson (bass guitar, backing vocals) left the band in 2006 shortly after its debut album was released.
They have released five studio albums: Whatever People Say I Am, That\'s What I\'m Not (2006), Favourite Worst Nightmare (2007), Humbug (2009), Suck It and See (2011) and AM (2013), as well as one live album, At the Apollo (2008). Their debut album is the fastest-selling debut album by a band in British chart history, and in 2013, Rolling Stone ranked it the 30th greatest debut album of all time.','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('Bombay Bicycle Club','Bombay Bicycle Club are an English indie rock band from Crouch End, London, consisting of Jack Steadman (lead vocals, guitar, piano), Jamie MacColl (guitar), Suren de Saram (drums) and Ed Nash (bass). They are guitar-fronted and have experimented with different genres, including folk, electronica, world music and indie rock.
The band were given the opening slot on 2006\'s V Festival after winning a competition. They subsequently released two EPs and their debut single "Evening/Morning". Since then, the band has released four albums including So Long, See You Tomorrow which topped the album charts in February 2014. The band has toured worldwide as a headlining act, playing North America, Australia, Europe and the Far East.','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('You Me At Six','You Me at Six are a British rock band from Weybridge, Surrey. Formed in 2004, the group rose to fame in 2008 with the success of their debut album, Take Off Your Colours, which included the singles "Save It for the Bedroom", "Finders Keepers" and "Kiss and Tell", with the latter two peaking at No. 33 and No. 42 respectively in the official UK Singles Chart. The band\'s main influences include American rock bands Blink-182, Incubus and Thrice.','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('Collapse Under The Empire','Chris Burda and Martin Grimm found themselves together for the first time in summer 2007 in Hamburg. In 2008, under the name Collapse Under The Empire, four songs were composed for their first EP Paintball.
The debut album System Breakdown was initially released independently in 2009. A year later the successor Find a Place to be Safe was released through the label Sister Jack Records and began to make the band known internationally. Magazines such as Q, Clash and Rock Sound praised the cinematic sound of the band.
The first psychedelic inspired EP appeared with The Sirens Sound and shortly after, the Split-EP Black Moon Empire in collaboration with the Russian band Mooncake. The band also contributed to the track Anthem of 44 for the Emo Diaries Compilation I Love You But in the End I Will Destroy You by Deep Elm Records.
','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('God Is An Astronaut','God Is an Astronaut is a band from the Glen of the Downs, County Wicklow, Ireland. Their music is suggested to combine epic melodies of post-rock, the precision of electronic-fuelled Krautrock ‡ la Tangerine Dream and elements of space rock. 
The band was formed in 2002 by twin brothers Niels and Torsten Kinsella, who took the inspiration for its name from a quote in the movie Nightbreed. God is an Astronaut\'s debut album The End of the Beginning was released in (2002) on the Revive Records label which is independently owned by the band. The album was intended to be a farewell to the industry. Two music videos for "The End of the Beginning" and "From Dust to the Beyond" produced by the band received airplay on MTV UK and on other MTV Europe networks.','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('65daysofstatic','65daysofstatic (often abbreviated as 65dos, 65days, or simply 65) are an instrumental electronic post/math rock band. Formed in Sheffield, England, in 2001, the band is composed of Paul Wolinski, Joe Shrewsbury, Rob Jones and Simon Wright.
The bands music has been described as heavy, progressive, guitar-driven instrumental post rock, interspersed with live drums and off-beat sampled drums akin to those of Aphex Twin, although they have continued to evolve their sound by incorporating electronic music, drum and bass and glitch music. They have been described as, a soundtrack to a new dimension, where rock, dance and electronica are equals.','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('Aphex Twin','Richard David James (born 18 August 1971), best known by his stage name Aphex Twin, is a British electronic musician and composer. He has been described by The Guardian as "the most inventive and influential figure in contemporary electronic music", and is the co-founder of Rephlex Records with Grant Wilson-Claridge. Aphex Twin\'s album Selected Ambient Works 85-92 was called the best album of the 1990s by FACT Magazine.
James has also released a number of EPs as AFX from 1991 to 2005 including the Analogue Bubblebath series of EPs. In 2007, James also released some materials anonymously under the name The Tuss leading to a lot of speculations. The Tuss materials included Confederation Trough EP and Rushup Edge. He eventually admitted to being the artist behind it.','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('Nick Cave and the Bad Seeds','Nicholas Edward "Nick" Cave (born 22 September 1957) is an Australian musician, songwriter, author, screenwriter, composer and occasional film actor.
He is best known for his work as lead singer of the rock band Nick Cave and the Bad Seeds, established in 1983, a group known for its eclectic influences and musical styles. Before that, he had fronted the group The Birthday Party in the early 1980s, a band renowned for its highly gothic, challenging lyrics and violent sound influenced by free jazz, blues, and punk. In 2006, he formed the garage rock band Grinderman, releasing its debut album the following year. Cave\'s music is generally characterised by emotional intensity, a wide variety of influences, and lyrical obsessions with religion, death, love and violence. In the early 2010s, he was dubbed by the NME as "the grand lord of gothic lushness".
Upon Cave\'s induction into the ARIA Hall of Fame, ARIA Awards committee chairman Ed St John said, "Nick Cave has enjoyedand continues to enjoyone of the most extraordinary careers in the annals of popular music. He is an Australian artist like Sidney Nolan is an Australian artistbeyond comparison, beyond genre, beyond dispute."','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('Mogwai','Mogwaiare a Scottish post-rock band, formed in 1995 in Glasgow. The band consists of Stuart Braithwaite (guitar, vocals), John Cummings (guitar, vocals), Barry Burns (guitar, piano, synthesizer, vocals), Dominic Aitchison (bass guitar), and Martin Bulloch (drums). The band typically compose lengthy guitar-based instrumental pieces that feature dynamic contrast, melodic bass guitar lines, and heavy use of distortion and effects. The band were for several years signed to renowned Glasgow indie label Chemikal Underground, and have been distributed by different labels such as Matador in the US and Play It Again Sam in the UK, but now use their own label Rock Action Records in the UK, and Sub Pop in North America. The band were frequently championed by John Peel from their early days, and recorded no fewer than seven Peel Sessions between 1996 and 2004. Peel also recorded a brief introduction for the compilation Government Commissions: BBC Sessions 19962003.','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('Maybeshewill','Maybeshewill was formed by guitarists Robin Southby and John Helps whilst the pair were studying together at university in 2005. They released their first record Japanese Spy Transcript on the band\'s own label, Robot Needs Home Records in 2006 with Tanya Byrne on bass guitar and Lawrie Malen on drums. The 4-track EP was well received by the press and attracted the attention of Nottingham\'s Field Records (also home to Public Relations Exercise) who released "The Paris Hilton Sex Tape" (taken from the record) as part of a split 7" single with Ann Arbor later that year. In August 2006 a re-mastered version of Japanese Spy Transcript was released in Japan on the XTAL label (also home to Yndi halda and You Slut!) which was set up specifically for the release by The Media Factory Group. Shortly after this release the band dissolved temporarily.','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('The Abbasi Brothers','The Abrams Brothers are a Canadian band composed of fourth-generation musicians John Abrams and James Abrams.  Their music is a combination of bluegrass, country, and folk-rock with story-telling lyrics that has been called "newgrass." They have performed with acts such as John Hammond, Feist, and Dean Brody.','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('Hammock','Hammock is an American two-member ambient/post-rock band from Nashville, Tennessee. With music initially created in between production and songwriting projects, Hammock combines live instrumentation, electronic beats, and droning guitar into atmospheric music similar in style to the work of Boards of Canada, Explosions in the Sky, and Stars of the Lid.','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('S. Carey','S. Carey is the moniker of musician Sean Carey of Eau Claire, Wisconsin. Carey is best known as the drummer and supporting vocalist of indie folk band Bon Iver. In August 2010, Carey released his first solo album, All We Grow, which he began working on in 2008 during hiatuses from performing with the band.
Comparisons have been drawn between Carey\'s harmonies and those of Brian Wilson in his 2004 album Smile. His music has also been likened to that of Sufjan Stevens, Fleet Foxes, Iron Wine, Steve Reich, and Talk Talk','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('Caroline Smith and the Good Night Sleeps','Caroline Smith is originally from Detroit Lakes, Minnesota,[1] a town of about 8,600 according to the 2010 Census, She began playing music at an early age, reportedly learning guitar from her father. At 16 she started performing publicly at Zorbaz Pizza in her hometown and opened for B.B. King as well as releasing a self-titled album. Caroline has been quoted saying she prefers the guitar, more than other instruments, because of her ability to control the dynamics. She describes her voice as a "conglomerate" with countless influences ranging from Billie Holiday to Peter, Paul and Mary among others.','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('The Cinematic Orchestra','The Cinematic Orchestra is a British nu-jazz and electronic music group, created in 1999 by Jason Swinscoe. The group is signed to independent record label Ninja Tune. In addition to Swinscoe, the band includes former DJ Food member PC (Patrick Carpenter) on turntables, Luke Flowers (drums), Tom Chant (saxophone), Nick Ramm (piano), Stuart McCallum (guitar) and Phil France (double bass). Former members include Jamie Coleman (trumpet), T. Daniel Howard (drums), Federico Ughi (drums), Alex James (piano), and Clean Sadness (synthesizer, programming). The most recent addition to the band is Mancunian guitarist Stuart McCallum. Swinscoe and Carpenter have also recorded together under the band name Neptune.
','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('Sleepmakeswaves','Sleepmakeswaves (typeset as sleepmakeswaves) are an ARIA-nominated Australian post rock band who formed in Sydney, New South Wales in December 2006. The group is currently composed of guitarist Jonathan Khor, guitarist Otto Wicks-green, drummer Tim Adderley and bassist Alex Wilson. To date they have released one extended play (2008\'s In Today Already Walks Tomorrow) and two full length studio albums (2011\'s and so we destroyed everything & 2014\'s "Love Of Cartography"), as well as a split EP with Perth band Tangled Thoughts of Leaving and a remix album. The band have reached notable success internationally for their energetic live performances and modern approach to post rock. They are currently released through Australian independent record label Bird\'s Robe Records, which is distributed through MGM in Australia and independently worldwide. In 2013, UK label Monotreme Records licensed their debut album for an international release across the UK, Europe and North America.','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('Ulrich Schnauss','Ulrich Schnauss was born in the northern German seaport of Kiel in 1977. He became interested in a range of music: My Bloody Valentine, Slowdive, Tangerine Dream, Chapterhouse, and early bleep & breakbeat tracks. There was not much opportunity to see his musical influences in Kiel, so he moved to Berlin in 1996.
Ulrich\'s musical output began under the pseudonyms of View to the Future and Ethereal 77. These electronica and drum-driven pieces were noticed by Berlin electronica label CCO (City Centre Offices), to which Schnauss sent CD\'s on a regular basis. Ulrich developed these submissions to CCO into his first album under his own name, Far Away Trains Passing By, released in Europe in 2001, and in the United States in 2005.','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('Epic45','epic45 are a British indie/post-rock band. Core members Rob Glover and Benjamin Holton, who grew up in Wheaton Aston, Staffordshire, formed the band in 1995 when the two school friends were only 13 years old. The band have released albums across various labels including Where Are My Records, Make Mine Music and their own Wayside and Woodland Recordings label.','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('Cat Power','Charlyn Marie Marshall (born January 21, 1972), also known as Chan Marshall or by her stage name Cat Power, is an American singer-songwriter, musician, occasional actress and model. Cat Power was originally the name of Marshall\'s first band, but has come to refer to her musical projects with various backing bands','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('Feist','Leslie Feist (born 13 February 1976), known professionally as Feist, is a Canadian Indie Pop singer-songwriter, performing both as a solo artist and as a member of the indie rock group Broken Social Scene.
Feist launched her solo music career in 1999 with the release Monarch (Lay Your Jewelled Head Down). Her subsequent studio albums, Let It Die, released in 2004, and The Reminder, released in 2007, were critically acclaimed and commercially successful, selling over 2.5 million copies. The Reminder earned Feist four Grammy nominations, including a nomination for Best New Artist. She was the top winner at the 2008 Juno Awards in Calgary with five awards, including Songwriter of the Year, Artist of the Year, Pop Album of the Year, Album of the Year and Single of the Year. Her fourth studio album, Metals, was released on 30 September 2011. In 2012, Feist collaborated on a split EP with metal group Mastodon, releasing an interactive music video in the process.[2]','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('Sharon Van Etten','Van Etten released a number of hand-designed and self-released recordings prior to her debut studio recording.These were hand-painted designs she would sell on her website, as well as postcards, and sometimes t-shirts. She also worked as a publicist at Ba Da Bing Records in order to learn how the music industry worked, but didn\'t tell them that she was writing and performing music. She got the job via a friend, Alicia Savoy, who she went to college with in Tennessee. She started out as an intern and worked her way up to being a full-time publicist.
Van Etten said that Jeffrey Davison, a deejay on WFMU who has a show called Shrunken Planet, was the first person to play one of her homemade CDs on his show. Van Etten has become close to Davison and his wife, who have her over for record listening evenings','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('Blonde Redhead','Blonde Redhead is an American alternative rock band composed of Kazu Makino (vocals, rhythm guitar) and twin brothers Simone and Amedeo Pace (drums/vocals and lead guitar, respectively) that formed in New York City in 1993.
The band\'s earliest albums were noted for their noise rock influences, though their sound evolved by the early 2000s with the releases of Misery is a Butterfly (2004) and 23 (2007), which both incorporated elements of dream pop, shoegaze and other genres.','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('Beach House','Beach House is a dream pop duo from Baltimore, Maryland, formed in 2004. The band consists of French-born Victoria Legrand and Baltimore native Alex Scally. Their self-titled debut album was released in 2006 to critical acclaim and has been followed by Devotion in 2008, Teen Dream in 2010, and Bloom in 2012.','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('Fiona Apple','Fiona Apple McAfee Maggart (born September 13, 1977) is an American singer-songwriter, pianist, and poet.[citation needed] Her debut album, Tidal, was released in 1996 and received a Grammy Award for Best Female Rock Vocal Performance (with an additional six nominations) for the single "Criminal". Born in New York, Apple is the daughter of singer Diane McAfee and actor Brandon Maggart.Her maternal grandparents were dancer Millicent Green and big band vocalist Johnny McAfee. Her sister sings cabaret under the stage name Maude Maggart, and actor Garett Maggart is her half brother. Growing up, Apple spent her school years in New York City, but spent summers with her father in Los Angeles. Apple was classically trained on piano as a child, and began composing her own pieces by the age of eight.[7] When learning to play piano, she would often take sheet music and translate guitar tablature into the corresponding notes. Apple later began to play along with jazz standard compositions after becoming proficient, through which she discovered Billie Holliday and Ella Fitzgerald, who became major influences on her.','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('band of horses','Band of Horses, originally known briefly as Horses, is an American rock band formed in 2004 in Seattle by Ben Bridwell. The band has released four studio albums, the most successful of which is 2010\'s Grammy-nominated Infinite Arms.The band\'s lineup, which included Mat Brooke for the debut album, has undergone several changes; although, the current members, Bridwell, Ryan Monroe, Tyler Ramsey, Bill Reynolds, and Creighton Barrett, have all been with the band for several years. Band of Horses\' fourth studio album, Mirage Rock, was released in September 2012.','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('Kyte','Kyte comprise Nick Moon (vocals), Tom Lowe (guitars and keyboards), Scott Hislop (drums and percussion).Their earlier musical output was often labelled shoegazing (or neo-shoegazing) and post rock but they have since moved into a more electro and pop influenced sound.Their debut single, "Planet," was released in 2007 on Sonic Cathedral Recordings, the b-side of which, "Boundaries," was used in a trailer for the television series The Sopranos in the United States.[4] Their debut album was released in 2008 on Kids Records (UK) / Erased Tapes Records (EU) to generally positive reviews.In April 2009 they released their second full length release Science For The Living in Japan. The two disc Japan release featured tracks taken from their 2008 Two Sparks, Two Stars EP, with the remaining tracks being new material. The bonus disc included a remix from The Joy Formidable of the opening track on Disc 1, "Eyes Lose Their Fire."','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('Eluvium','Eluvium is the moniker of the American ambient recording artist Matthew Cooper, who currently resides in Portland, Oregon. Cooper, who was born in Tennessee and raised in Louisville, Kentucky, before relocating to the Northwest, is known for blending various genres of experimental music including electronic, minimalism and piano. His albums often feature artwork and photographs by Jeannie Paske.','admin','2014-11-11 12:45:34');
INSERT INTO Band VALUES ('Immanu El','Immanu El (postindie rock) from Gothenburg, Sweden, started as a musical experiment in 2004 by 16-year-old Claes Strängberg soon joined by his twin brother Per and friends David Lillberg, Jonatan Josefsson, and Robin Ausberg. The music have got its roots in the early post-rock scene and the ambition of the band has always been to create something captivating and beautiful, experimenting with other styles and elements with a lot of vocal arrangements. After touring, supporting such bands as Logh and Loney, Dear they got the chance in 2005 to play a set on the Rookie Stage in the Hultsfred Festival; an opportunity for unsigned bands to perform at a major event.[2] They released their first demo EP titled Killerwhale in 2005, before being signed to Swedish independent record label And the Sound Records and Japanese label Thomason Sounds (Inpartmaint) in 2007. The first full-length album They\'ll Come, They Come was released in August 2007 and the band moved from the outskirts of Jönköping to Gothenburg. The second album Moen was released 2009 after several tours in Europe during 2008. The band has gained a growing reputation to be one of the most promising bands from Scandinavia and performed over 300 shows in 30 countries (Europe, Asia and North America since the debut album was released. After four years on the Swedish west coast, their relationship with the sea has left a clear trace upon the latest album, which was released October 29th, 2011. During 2012/2013 Immanu El performed at SXSW in Austin, Reeperbahn Festival in Hamburg, Filter\'s Culture Collide Festival in LA, CMJ Music Marathon in New York, Canadian Music Week in Toronto and Strawberry Festival in Beijing and Shanghai. In May 2013, debut album They\'ll Come, They Come was released in a new edition by And The Sound Records and the German partner label Kapitän Platte. The band will continue touring but also start to work with a new album during 2013.','admin','2014-11-11 12:45:34');


INSERT INTO BandMember VALUES ('If These Trees Could Talk','Tom Fihe');
INSERT INTO BandMember VALUES ('If These Trees Could Talk','Jeff Kalal');
INSERT INTO BandMember VALUES ('If These Trees Could Talk','Cody Kelly');
INSERT INTO BandMember VALUES ('If These Trees Could Talk','Mike Socrates');
INSERT INTO BandMember VALUES ('If These Trees Could Talk','Zack Kelly');
INSERT INTO BandMember VALUES ('Explosions In The Sky','Chris Hrasky');
INSERT INTO BandMember VALUES ('Explosions In The Sky','Michael James');
INSERT INTO BandMember VALUES ('Explosions In The Sky','Munaf Rayani');
INSERT INTO BandMember VALUES ('Explosions In The Sky','Mark Smith');
INSERT INTO BandMember VALUES ('Explosions In The Sky','Touring');
INSERT INTO BandMember VALUES ('Explosions In The Sky','Carlos Torres');
INSERT INTO BandMember VALUES ('God Is An Astronaut','Torsten Kinsella');
INSERT INTO BandMember VALUES ('God Is An Astronaut','Niels Kinsella');
INSERT INTO BandMember VALUES ('God Is An Astronaut','Jamie Dean');
INSERT INTO BandMember VALUES ('God Is An Astronaut','Lloyd Hanney');
INSERT INTO BandMember VALUES ('The xx','Romy Madley');
INSERT INTO BandMember VALUES ('The xx','Oliver Sim');
INSERT INTO BandMember VALUES ('The xx','Jamie Smith');
INSERT INTO BandMember VALUES ('The xx','Past Members');
INSERT INTO BandMember VALUES ('The xx','Baria Qureshi');
INSERT INTO BandMember VALUES ('The Kooks','Luke Pritchard');
INSERT INTO BandMember VALUES ('The Kooks','Hugh Harris');
INSERT INTO BandMember VALUES ('The Kooks','Peter Denton');
INSERT INTO BandMember VALUES ('The Kooks','Alexis Nunez');
INSERT INTO BandMember VALUES ('Arctic Monkeys','Alex Turner');
INSERT INTO BandMember VALUES ('Arctic Monkeys','Matt Helders');
INSERT INTO BandMember VALUES ('Arctic Monkeys','Thomas Rowley');
INSERT INTO BandMember VALUES ('Arctic Monkeys','Davey Latter');
INSERT INTO BandMember VALUES ('You Me At Six','Max Helyer');
INSERT INTO BandMember VALUES ('You Me At Six','Josh Franceschi');
INSERT INTO BandMember VALUES ('You Me At Six','Chris Miller');
INSERT INTO BandMember VALUES ('You Me At Six','Matt Barnes');
INSERT INTO BandMember VALUES ('You Me At Six','Dan Flint');
INSERT INTO BandMember VALUES ('Mogwai','Stuart Braithwaite');
INSERT INTO BandMember VALUES ('Mogwai','Dominic Aitchison');
INSERT INTO BandMember VALUES ('Mogwai','Martin Bulloch');
INSERT INTO BandMember VALUES ('Mogwai','John Cummings');
INSERT INTO BandMember VALUES ('Mogwai','Barry Burns');
INSERT INTO BandMember VALUES ('The Abbasi Brothers','John Abrams');
INSERT INTO BandMember VALUES ('The Abbasi Brothers','James Abrams');
INSERT INTO BandMember VALUES ('Saxon Shore','Matt Doty');
INSERT INTO BandMember VALUES ('Saxon Shore','Oliver Chapoy ');
INSERT INTO BandMember VALUES ('Saxon Shore','Stephen Roessner ');
INSERT INTO BandMember VALUES ('Saxon Shore','Will Stichter');


INSERT INTO Artist VALUES ('Cat Power',1,'2013-08-04 19:05:00','1111111111','Cat Power',1);
INSERT INTO Artist VALUES ('Explosions In The Sky',1,'2013-11-11 07:05:00','1111111111','Explosions In The Sky',1);
INSERT INTO Artist VALUES ('The Kooks',1,'2013-04-11 11:05:00','1111111111','The Kooks',1);
INSERT INTO Artist VALUES ('Peter Denton',0,'2012-05-06 21:12:00','1111111111','The Kooks',0);
INSERT INTO Artist VALUES ('Davey Latter',1,'2014-05-06 09:33:00','1111111111','Arctic Monkeys',1);


INSERT INTO Venues VALUES ('Village Vanguard','178 7th Ave S','Manhattan','NY',1000,'http://www.villagevanguard.com');
INSERT INTO Venues VALUES ('Union Hall','702 Union Street','Manhattan','NY',1000,'http://www.unionhallny.com');
INSERT INTO Venues VALUES ('169 Bar','169 East Broadway','Manhattan','NY',1000,'http://www.169barnyc.com/cmsmadesimple/');
INSERT INTO Venues VALUES ('The Living Room','34 Metropolitan Ave','Manhattan','NY',1000,'http://www.livingroomny.com');
INSERT INTO Venues VALUES ('LE POISSON ROUGE','158 Bleecker Street','Manhattan','NY',1000,'http://guestofaguest.com/new-york/venue/nightclubs/new-york/west-village/le-poisson-rouge/page/3/');
INSERT INTO Venues VALUES ('CAFE WHA','115 MacDougal','Manhattan','NY',1000,'http://cafewha.com');
INSERT INTO Venues VALUES ('CLUB GROOVE','125 MacDougal Street','Manhattan','NY',1000,'http://www.clubgroovenyc.com');


INSERT INTO Type VALUES ('Blues','A kind of jazz that evolved from the music of African-Americans, especially work songs and spirituals, in the early twentieth century.');
INSERT INTO Type VALUES ('Rock','Rock music is a genre of popular music that originated as in the United States in the 1950s, and developed into a range of different styles in the 1960s and later, particularly in the United Kingdom and the United States.');
INSERT INTO Type VALUES ('R&B/Soul','A style of music developed by African Americans that combines blues and jazz, characterized by a strong backbeat and repeated variations on syncopated instrumental phrases.');
INSERT INTO Type VALUES ('Pop','Pop music is a genre of popular music, which originated in its modern form in the 1950s, deriving from rock and roll.');
INSERT INTO Type VALUES ('Jazz','Jazz is a genre of music that originated in African-American communities during the late 19th and early 20th century.');
INSERT INTO Type VALUES ('Indie','Indie is music produced independently from major commercial record labels or their subsidiaries.');
INSERT INTO Type VALUES ('Electronic','Electronic music is music that employs electronic musical instruments and electronic music technology in its production.');
INSERT INTO Type VALUES ('Hip-Hop/Rap','Hip-Hop/Rap is a music genre consisting of a stylized rhythmic music that commonly accompanies rapping, a rhythmic and rhyming speech that is chanted.');
INSERT INTO Type VALUES ('Country','Country music is a genre of American popular music that originated in Southern United States, in Atlanta, Georgia in the 1920s.');
INSERT INTO Type VALUES ('Reggae','Reggae is a music genre that originated in Jamaica in the late 1960s. ');
INSERT INTO Type VALUES ('Folk','Folk music includes both traditional music and the genre that evolved from it during the 20th century folk revival.');



INSERT INTO Subtype VALUES ('Blues','Classic Blues','Classic Blues is a type of city blues performed by a female singer accompanied by a small group.');
INSERT INTO Subtype VALUES ('Blues','Country Blues','Country blues is acoustic, mainly guitar-driven forms of the blues.');
INSERT INTO Subtype VALUES ('Blues','Free Blues','A type of popular music performed by African-Americans that was developed originally by combining elements of blues and jazz.');
INSERT INTO Subtype VALUES ('Rock','Folk Rock','Folk rock is a musical genre combining elements of folk music and rock music.');
INSERT INTO Subtype VALUES ('Rock','Hard Rock/Metal','Hard rock (or heavy rock) is a loosely defined subgenre of rock music.');
INSERT INTO Subtype VALUES ('Rock','Rock & Roll','Rock and roll is a genre of popular music that originated and evolved in the United States.');
INSERT INTO Subtype VALUES ('Rock','Post Rock','Post-rock is a subgenre of rock music characterized by the influence and use of instruments commonly associated with rock.');
INSERT INTO Subtype VALUES ('Rock','Post Puck','Post-punk is a rock music genre that paralleled and emerged from the initial punk rock explosion of the late 1970s.');
INSERT INTO Subtype VALUES ('R&B/Soul','Soul','Soul is a popular style of music expressing deep emotion that was created by African-Americans.');
INSERT INTO Subtype VALUES ('R&B/Soul','Funk','Funk is a music genre that originated in the mid to late 1960s.');
INSERT INTO Subtype VALUES ('R&B/Soul','NeoSoul','Neo soul is a term coined by music industry entrepreneur Kedar Massenburg.');
INSERT INTO Subtype VALUES ('R&B/Soul','R&B','A style of music developed by African Americans that combines blues and jazz.');
INSERT INTO Subtype VALUES ('Pop','Pop Rock','Pop rock is a music genre which mixes a catchy pop style and light lyrics in its (typically) guitar-based rock songs.');
INSERT INTO Subtype VALUES ('Pop','Electro Pop','Electro Pop is a genre of popular music that first became prominent in the 1980s.');
INSERT INTO Subtype VALUES ('Pop','Guitar Pop','Guitar Pop is a popular musical instrument classified with a string instrument.');
INSERT INTO Subtype VALUES ('Jazz','Free Jazz','Free jazz is an approach to jazz music that was first developed in the 1950s and 1960s.');
INSERT INTO Subtype VALUES ('Jazz','Smooth Jazz','Smooth jazz is a genre of music that grew out of jazz fusion and is influenced by jazz.');
INSERT INTO Subtype VALUES ('Jazz','Ragtime','A style of early jazz music written largely for the piano in the early twentieth century.');
INSERT INTO Subtype VALUES ('Jazz','Mainstream Jazz','Mainstream jazz is a genre of jazz music that was first used in reference to the playing styles around the 1950s of musicians like Buck Clayton among others.');
INSERT INTO Subtype VALUES ('Hip-Hop/Rap','Dirty South','Dirty South is a style of hip hop music that originated by Three 6 Mafia in Memphis.');
INSERT INTO Subtype VALUES ('Hip-Hop/Rap','Hardcore Rap','Hardcore hip hop is a form of hip hop music that developed through the East Coast hip hop scene in the 1980s.');
INSERT INTO Subtype VALUES ('Hip-Hop/Rap','Bounce','Bounce music is an energetic style of New Orleans hip hop music which is said to have originated as early as the late 1980s.');
INSERT INTO Subtype VALUES ('Hip-Hop/Rap','Underground Rap','Underground hip hop is an umbrella term for hip hop music outside the general commercial canon.');
INSERT INTO Subtype VALUES ('Hip-Hop/Rap','Old School Rap','Old-school hip hop describes the earliest commercially recorded hip hop music.');
INSERT INTO Subtype VALUES ('Hip-Hop/Rap','Alternative Rap','Alternative hip hop (also known as alternative rap) is a sub-genre of hip hop music.');
INSERT INTO Subtype VALUES ('Electronic','Drum & Bass','Drum & Bass is a type of electronic music also known as Jungle which emerged in England in the early 1990s.');
INSERT INTO Subtype VALUES ('Electronic','Electronic Rock','Electronic Rock is rock music generated with electronic instruments.');
INSERT INTO Subtype VALUES ('Electronic','Bassline','A bassline is the term used in many styles of popular music, such as jazz, blues, funk, dub and electronic, or traditional music.');
INSERT INTO Subtype VALUES ('Electronic','Industrial','Industrial music is a style of experimental music that draws on transgressive and provocative themes.');
INSERT INTO Subtype VALUES ('Country','Bluegrass','Bluegrass music is a form of American roots music, and a subgenre of country music.');
INSERT INTO Subtype VALUES ('Country','Traditional Country','Traditional Country is a music radio format that specializes in playing mainstream country and western music hits from past decades.');
INSERT INTO Subtype VALUES ('Country','Country Rock','Country rock is a subgenre of country music, formed from the fusion of rock with country.');
INSERT INTO Subtype VALUES ('Indie','Indie Pop','Indie pop music is a genre of alternative music along with the other genres such as Brit pop.');
INSERT INTO Subtype VALUES ('Indie','Indie Rock','Indie rock is a genre of alternative rock that originated in the United Kingdom in the 1980s.');
INSERT INTO Subtype VALUES ('Reggae','Ska','Ska is a music genre that originated in Jamaica in the late 1950s.');
INSERT INTO Subtype VALUES ('Reggae','Roots Reggae','Roots reggae is a subgenre of reggae that deals with the everyday lives and aspirations of the artists concerned.');
INSERT INTO Subtype VALUES ('Folk','Folk','Folk music includes both traditional music and the genre that evolved from it during the 20th century folk revival.');


INSERT INTO BandType VALUES ('Explosions In The Sky', 'Pop','Pop Rock');
INSERT INTO BandType VALUES ('Explosions In The Sky', 'Rock','Post Rock');
INSERT INTO BandType VALUES ('Explosions In The Sky','Indie','Indie Rock');
INSERT INTO BandType VALUES ('This Will Destroy You', 'Rock','Post Rock');
INSERT INTO BandType VALUES ('This Will Destroy You','Indie','Indie Rock');
INSERT INTO BandType VALUES ('This Will Destroy You', 'R&B/Soul','Funk');
INSERT INTO BandType VALUES ('The xx', 'Indie','Indie Pop');
INSERT INTO BandType VALUES ('The xx', 'Indie','Indie Rock');
INSERT INTO BandType VALUES ('Belleruche','R&B/Soul','R&B');
INSERT INTO BandType VALUES ('The Kooks','Rock','Post Puck');
INSERT INTO BandType VALUES ('The Kooks','Rock','Rock & Roll');
INSERT INTO BandType VALUES ('The Kooks','R&B/Soul','Funk');
INSERT INTO BandType VALUES ('The Kooks','Hip-Hop/Rap','Bounce');
INSERT INTO BandType VALUES ('The Kooks','Reggae','Ska');
INSERT INTO BandType VALUES ('The Kooks','Indie','Indie Pop');
INSERT INTO BandType VALUES ('CHVRCHES','Electronic','Drum & Bass');
INSERT INTO BandType VALUES ('CHVRCHES','Pop','Electro Pop');
INSERT INTO BandType VALUES ('Arctic Monkeys','Indie','Indie Rock');
INSERT INTO BandType VALUES ('Arctic Monkeys','Hip-Hop/Rap','Alternative Rap');
INSERT INTO BandType VALUES ('Arctic Monkeys','Hip-Hop/Rap','Old School Rap');
INSERT INTO BandType VALUES ('Bombay Bicycle Club','Indie','indie rock');
INSERT INTO BandType VALUES ('Bombay Bicycle Club','Folk','Folk');
INSERT INTO BandType VALUES ('Bombay Bicycle Club','Electronic','Electronic Rock');
INSERT INTO BandType VALUES ('You Me At Six','Rock','Rock & Roll');
INSERT INTO BandType VALUES ('You Me At Six','Electronic','Industrial');
INSERT INTO BandType VALUES ('Collapse Under The Empire','Rock','Post Rock');
INSERT INTO BandType VALUES ('Collapse Under The Empire','Rock','Hard Rock/Metal');
INSERT INTO BandType VALUES ('God Is An Astronaut','Rock','Post Rock');
INSERT INTO BandType VALUES ('God Is An Astronaut','Indie','Indie Rock');
INSERT INTO BandType VALUES ('God Is An Astronaut','Electronic','Drum & Bass');
INSERT INTO BandType VALUES ('65daysofstatic','Rock','Post Rock');
INSERT INTO BandType VALUES ('65daysofstatic','Electronic','Drum & Bass');
INSERT INTO BandType VALUES ('65daysofstatic','Electronic','Industrial');
INSERT INTO BandType VALUES ('Aphex Twin','Electronic','Industrial');
INSERT INTO BandType VALUES ('Aphex Twin','Pop','Pop Rock');
INSERT INTO BandType VALUES ('Aphex Twin','Reggae','Ska');
INSERT INTO BandType VALUES ('Aphex Twin','Rock','Hard Rock/Metal');
INSERT INTO BandType VALUES ('Aphex Twin','Pop','Electro Pop');
INSERT INTO BandType VALUES ('Aphex Twin','Hip-Hop/Rap','Alternative Rap');
INSERT INTO BandType VALUES ('Nick Cave and the Bad Seeds','Rock','Rock & Roll');
INSERT INTO BandType VALUES ('Nick Cave and the Bad Seeds','Rock','Post Puck');
INSERT INTO BandType VALUES ('Nick Cave and the Bad Seeds','Blues','Classic Blues');
INSERT INTO BandType VALUES ('Nick Cave and the Bad Seeds','Blues','Country Blues');
INSERT INTO BandType VALUES ('Nick Cave and the Bad Seeds','Electronic','Electronic Rock');
INSERT INTO BandType VALUES ('Mogwai','Rock','Post Rock');
INSERT INTO BandType VALUES ('Mogwai','Indie','Indie Rock');
INSERT INTO BandType VALUES ('Mogwai','Rock','Hard Rock/Metal');
INSERT INTO BandType VALUES ('Maybeshewill','Rock','Post Rock');
INSERT INTO BandType VALUES ('Maybeshewill','Rock','Hard Rock/Metal');
INSERT INTO BandType VALUES ('Maybeshewill','Electronic','Drum & Bass');
INSERT INTO BandType VALUES ('Maybeshewill','Electronic','Industrial');
INSERT INTO BandType VALUES ('The Abbasi Brothers','Country','Bluegrass');
INSERT INTO BandType VALUES ('The Abbasi Brothers','Folk','Folk');
INSERT INTO BandType VALUES ('The Abbasi Brothers','Rock','Folk Rock');
INSERT INTO BandType VALUES ('Hammock', 'Pop','Pop Rock');
INSERT INTO BandType VALUES ('Hammock', 'Rock','Post Rock');
INSERT INTO BandType VALUES ('Hammock', 'Electronic','Drum & Bass');
INSERT INTO BandType VALUES ('Hammock', 'Indie','Indie Rock');
INSERT INTO BandType VALUES ('S. Carey', 'Folk','Folk');
INSERT INTO BandType VALUES ('Caroline Smith and the Good Night Sleeps', 'Folk','Folk');
INSERT INTO BandType VALUES ('The Cinematic Orchestra', 'Jazz','Smooth Jazz');
INSERT INTO BandType VALUES ('The Cinematic Orchestra', 'Electronic','Bassline');
INSERT INTO BandType VALUES ('The Cinematic Orchestra', 'Electronic','Drum & Bass');
INSERT INTO BandType VALUES ('Sleepmakeswaves','Rock','Post Rock');
INSERT INTO BandType VALUES ('Sleepmakeswaves','Indie','Indie Rock');
INSERT INTO BandType VALUES ('Sleepmakeswaves','Rock','Hard Rock/Metal');
INSERT INTO BandType VALUES ('Ulrich Schnauss','Electronic','Bassline');
INSERT INTO BandType VALUES ('Ulrich Schnauss','Electronic','Electronic Rock');
INSERT INTO BandType VALUES ('Epic45','Rock','Post Rock');
INSERT INTO BandType VALUES ('Epic45','Indie','Indie Rock');
INSERT INTO BandType VALUES ('Cat Power','Folk','Folk');
INSERT INTO BandType VALUES ('Cat Power','Rock','Post Puck');
INSERT INTO BandType VALUES ('Cat Power','Blues','Classic Blues');
INSERT INTO BandType VALUES ('Cat Power','Blues','Free Blues');
INSERT INTO BandType VALUES ('Feist','Folk','Folk');
INSERT INTO BandType VALUES ('Feist','Rock','Post Puck');
INSERT INTO BandType VALUES ('Feist','Indie','Indie Rock');
INSERT INTO BandType VALUES ('Feist','Indie','Indie Pop');
INSERT INTO BandType VALUES ('Sharon Van Etten','Folk','Folk');
INSERT INTO BandType VALUES ('Sharon Van Etten','Rock','Post Puck');
INSERT INTO BandType VALUES ('Sharon Van Etten','Indie','Indie Rock');
INSERT INTO BandType VALUES ('Blonde Redhead','Rock','Folk Rock');
INSERT INTO BandType VALUES ('Blonde Redhead','Indie','Indie Rock');
INSERT INTO BandType VALUES ('Blonde Redhead','Blues','Classic Blues');
INSERT INTO BandType VALUES ('Beach House','Indie','Indie Rock');
INSERT INTO BandType VALUES ('Fiona Apple','Jazz','Free Jazz');
INSERT INTO BandType VALUES ('Fiona Apple','Indie','Indie Rock');
INSERT INTO BandType VALUES ('Fiona Apple','Rock','Post Puck');
INSERT INTO BandType VALUES ('Fiona Apple','Pop','Pop Rock');
INSERT INTO BandType VALUES ('Kyte','Rock','Post Rock');
INSERT INTO BandType VALUES ('Kyte','Indie','Indie Rock');
INSERT INTO BandType VALUES ('Band of Horses','Rock','Post Rock');
INSERT INTO BandType VALUES ('Band of Horses','Indie','Indie Rock');
INSERT INTO BandType VALUES ('Eluvium','Rock','Post Rock');
INSERT INTO BandType VALUES ('Eluvium','Electronic','Electronic Rock');
INSERT INTO BandType VALUES ('Immanu El','Rock','Post Rock');
INSERT INTO BandType VALUES ('The 1975', 'Pop','Guitar Pop');
INSERT INTO BandType VALUES ('The 1975', 'Electronic','Electronic Rock');
INSERT INTO BandType VALUES ('The 1975', 'Pop','Electro Pop');
INSERT INTO BandType VALUES ('The 1975', 'R&B/Soul','R&B');

INSERT INTO BandType VALUES ('Saxon Shore', 'Pop','Pop Rock');
INSERT INTO BandType VALUES ('Saxon Shore', 'Rock','Post Rock');
INSERT INTO BandType VALUES ('Saxon Shore', 'Indie','Indie Pop');

INSERT INTO UserTaste VALUES('A','Pop','Pop Rock');
INSERT INTO UserTaste VALUES('B','Indie','Indie Rock');
INSERT INTO UserTaste VALUES('C','Indie','Indie Rock');
INSERT INTO UserTaste VALUES('D','Blues','Classic Blues');


INSERT INTO FansOf VALUES('A','Hammock','2013-10-19 19:12:00');
INSERT INTO FansOf VALUES('A','Lykke Li','2011-11-19 09:00:00');
INSERT INTO FansOf VALUES('A','Explosions In The Sky','2012-08-09 07:00:00');
INSERT INTO FansOf VALUES('B','Explosions In The Sky','2013-04-09 12:00:00');
INSERT INTO FansOf VALUES('C','Bombay Bicycle Club','2013-11-29 13:00:00');

INSERT INTO Concert VALUES('I love fiona','2013-10-13 19:00:00','LE POISSON ROUGE',4900,159,'Fiona Apple and her longtime collaborator Blake Mills brought their much-anticipated Anything We Want tour to Philadelphia’s Merriam Theatre.
Read more at http://www.phillymag.com/news/2013/10/21/fiona-apple-horrendous-philadelphia/#VjtYssIcuCIAll2R.99','wendy','2013-08-08 09:30:00','http://www.ticketmaster.com/Fiona-Apple-tickets/artist/780749');
INSERT INTO Concert VALUES('Explosions In The Sky Tour','2014-04-11 20:00:00','Village Vanguard',230,78,'Explosions in the Sky is an American post-rock band from Texas. The quartet originally played under the name Breaker Morant, then changed to the current name in 1999.','suzie','2014-01-01 19:30:00','http://www.ticketmaster.com/Explosions-In-the-Sky-tickets/artist/886226');
INSERT INTO Concert VALUES('Lykke Li Night','2011-12-11 20:00:00','CLUB GROOVE',1000,0,'Lykke Li is a Swedish indie pop singer-songwriter.','admin','2011-01-01 08:30:00','http://www.ticketmaster.com/Lykke-Li-tickets/artist/1229260');

INSERT INTO UserRecommendList VALUES('List of Explosions In The Sky','B','2013-05-11 09:30:00','A Slow Dance, A Song for Our Fathers, A Storm Is Coming, An Ugly Fact of Life, Catastrophe And The Cure, Checkpoints, Day Eight');
INSERT INTO UserRecommendList VALUES('List of Lykke Li','A','2012-03-11 19:30:00','Let It Fall, Youth Knows No Pain, Sadness Is A Blessing');
INSERT INTO UserRecommendList VALUES('List of Bombay Bicycle Club','C','2013-03-11 21:30:00','Bad Timing, Beggars, Carry Me, Eyes off You');

INSERT INTO RecommendList VALUES('List of Explosions In The Sky','Explosions In The Sky Tour');
INSERT INTO RecommendList VALUES('List of Lykke Li','Lykke Li Night');

INSERT INTO ListFollower VALUES('List of Explosions In The Sky','A');
INSERT INTO ListFollower VALUES('List of Bombay Bicycle Club','A');
INSERT INTO ListFollower VALUES('List of Bombay Bicycle Club','B');





INSERT INTO PlayBand VALUES('I love fiona','Fiona Apple');
INSERT INTO PlayBand VALUES('Explosions In The Sky Tour','Explosions In The Sky');
INSERT INTO PlayBand VALUES('Lykke Li Night','Lykke Li');


INSERT INTO ConcertProcess VALUES('Arctic Monkeys Blew My Mind!','2014-08-09 11:00:00','Davey Latter','Pending','2014-12-12 19:30:00','169 Bar','200','300','Arctic Monkeys are an English indie rock band formed in 2002 in High Green, a suburb of Sheffield.');

INSERT INTO PlayBandProcess VALUES('Arctic Monkeys Blew My Mind!','Arctic Monkeys');


INSERT INTO AttendConcert VALUES('A','I love fiona','Attended','2013-10-11 19:00:00');
INSERT INTO AttendConcert VALUES('B','Explosions In The Sky Tour','Attended','2014-03-29 20:00:00');
INSERT INTO AttendConcert VALUES('A','Lykke Li Night','Planto','2011-11-12 20:00:00');


INSERT INTO ConcertRating VALUES('A','I love fiona','88','2013-10-18 07:00:00');
INSERT INTO ConcertRating VALUES('B','Explosions In The Sky Tour','60','2014-05-09 21:00:00');

INSERT INTO ConcertReview VALUES('A','I love fiona','Pretty Amazing concert, I never watch a better one than this','2013-10-18 07:00:00');
INSERT INTO ConcertReview VALUES('B','Explosions In The Sky Tour','Just so so !','2014-05-09 21:00:00');

INSERT INTO Userticket VALUES('A','I love fiona','2013-10-09 21:21:00',3);
INSERT INTO Userticket VALUES('B','Explosions In The Sky Tour','2014-03-21 21:33:00',2);





















