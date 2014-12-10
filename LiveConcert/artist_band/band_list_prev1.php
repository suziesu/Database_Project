<!DOCTYPE html>
<html>
<meta charset="utf-8">
<head>
    <script src="/LiveConcert/assets/js/d3/d3.min.js" charset="utf-8"></script>
    <!-- // <script type="text/javascript" src="/LiveConcert/assets/js/jquery/jquery.js"></script> -->
<!-- 'Content-Type: application/json' -->
<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js'></script>


    <title>Band List</title>
</head>
<body>
<?php 
include "../includes/regular_page_head.php";
include "../includes/concert_list_head.html";
?>
<div id="sidebar">
    <?php
        if($alltype = $mysqli->prepare("select typename from Type")){
            $alltype->execute();
            $alltype->bind_result($typename);
            $getAllType = array();
            while($alltype->fetch()){
                array_push($getAllType,$typename);
            }
            $alltype->close();
            foreach ($getAllType as $key) {
                echo "<ul><a href='band_list.php?type=$key'>$key</a></ul>";
                if($allsubtype = $mysqli->query("call onetypeallsubtype('$key')")){
                    // echo "<ul>";
                    while($row = $allsubtype->fetch_object()){
                        $subtypename = $row->subtypename;
                        echo "<li><a href='band_list.php?type=$key&subtype=$subtypename'>$subtypename</li>";
                    }
                    $allsubtype->close();
                    $mysqli->next_result();
                }
                // echo "</ul>";
            }
        }

    ?>
</div>
<div id="concent-wrap">
<center><div id='svg_network'></div></center>

<style>
*{color:rgba(211,211,211, 1);}
body{background:#2E343E;}
.node {stroke: #ffffff;stroke-width: 2px;}
.link {stroke: #808080;stroke-opacity: .6;}
</style>

<?php 
$username = $_SESSION['username'];
//get all typelist
$band_array=array();
$type = "";
$subtype = "";
if(isset($_GET['type']) && isset($_GET['subtype'])){
    $type = $_GET['type'];
    $subtype = $_GET['subtype'];
    // include 'band_subtype_json.php';
    // $band = json_encode($d,true);
    if($subtypeband = $mysqli->query("call get_subtype_band('$subtype')") or die($mysqli->error)){
        while($row = $subtypeband->fetch_object()){
            $band_result = array();
            $band_result['baname'] = $row->baname;
            $band_result['bbio'] = $row->bbio;
            $band_array[] = $band_result;
        }
        $subtypeband->close();
        $mysqli->next_result();
    }
//get type band
}else if(isset($_GET['type'])){
    $type = $_GET['type'];
    // include 'band_type_json.php';
    // $band = json_encode($d,true);
    if($typeband = $mysqli->query("call get_type_band('$type')") or die($mysqli->error)){
        while($row = $typeband->fetch_object()){
            $band_result = array();
            $band_result['baname'] = $row->baname;
            $band_result['bbio'] = $row->bbio;
            $band_array[] = $band_result;
            
        }
        $typeband->close();
        $mysqli->next_result();
    }
//get all band
}else{
    // include 'band_json.php';
    // $band = json_encode($d,true);
    if($allband = $mysqli->query("call get_all_band()") or die($mysqli->error)){
        while($row = $allband->fetch_object()){
            $band_result = array();
            $band_result['baname'] = $row->baname;
            $band_result['bbio'] = $row->bbio;
            $band_array[] = $band_result;
        }
        $allband->close();
        $mysqli->next_result();
    }
}

$you_may_like = array();
if($recommendBand = $mysqli->query("call recommend_band_highrated_by_simitaste('$username')") or die($mysqli->error)){
    while($row = $recommendBand->fetch_object()){
        $band_result = array();
        $band_result['baname'] = $row->baname;
        $band_result['bbio'] = $row->bbio;
        $you_may_like[] = $band_result;
    }
    $recommendBand->close();
    $mysqli->next_result();
}
?>

<script type="text/javascript">
    var type = <?php echo json_encode($type); ?>;
    var subtype = <?php echo json_encode($subtype) ?>;

console.log(type);

var width = 960,
    height = 700;

var force = d3.layout.force()
        .size([width,height])
        .charge(-200)
        .linkDistance(80);

var svg = d3.select('#svg_network').append('svg')
    .attr('width',width)
    .attr('height',height); 

var color = d3.scale.category20c();


var network = {'nodes':[],'links':[] };
var nodeMap= {} ;
var t = 0;
nodeMap["root"] = t;
network.nodes.push({"name":"root","group":t});
t++;
if(type && subtype){
    console.log(type+subtype);
    d3.json("band_subtype_json.php?subtype="+subtype, showNetwork);
}else if(type){
    console.log(type);
    d3.json("band_type_json.php?type="+type, showNetwork);
}else{
    console.log("123");
    d3.json("band_json.php",showNetwork);
}


function showNetwork(error,data) {
    console.log(data);
    data.forEach(function(d){
        if(d.typename && d.subtypename && d.baname){
        if(!nodeMap[d.typename]){
            nodeMap[d.typename] = t;
            network.nodes.push({"name":d.typename,"group":t,"url":"/LiveConcert/artist_band/band_list.php?type="+d.typename,"size":4});
            // network.links.push({"source":0,"target":nodeMap[d.typename]});

            // console.log(nodeMap[d.typename]);
            // console.log(network.nodes.indexOf(d.typename));
            t++;
        }
        if(!nodeMap[d.subtypename]){
            nodeMap[d.subtypename] = t;
            network.nodes.push({"name":d.subtypename,"group":t,"url":"/LiveConcert/artist_band/band_list.php?subtype="+d.subtypename,"size":6});
            network.links.push({"source":nodeMap[d.typename],"target":nodeMap[d.subtypename]});
            t++;
        }
        if(!nodeMap[d.baname]){
            nodeMap[d.baname] = t;
            network.nodes.push({"name":d.baname,"group":t,"url":"/LiveConcert/artist_band/band_page.php?baname="+d.baname,"size":10});
            t++;
        }
        network.links.push({"source":nodeMap[d.subtypename],"target":nodeMap[d.baname]});
        }
    });
    // network.links.forEach(function (d, i) {
    //    network.links[i].source = network.nodes[network.links[i].source];
    //    network.links[i].target = network.nodes[network.links[i].target];
    //  });

    // return d;
    // },function(d){
        
        network.nodes.forEach(function(d){
            d.x = randomnumber=Math.floor(Math.random()*width);
            d.y = randomnumber=Math.floor(Math.random()*height);
            // d.radius = circleRadius(d.playcount);
        });

        console.log(network.links);
    
    force.nodes(network.nodes)
        .links(network.links)
        .start();

    var svg_link = svg.selectAll('.link')
        .data(network.links)
        .enter()
        .append('line')
        .attr('class','link')
        .attr("stroke-opacity", 0.8)
        .attr("stroke", "#ddd");
    // var g_frame = svg.append('g').attr('class','node');
    var svy_node = svg.selectAll('.node')
        .data(network.nodes)
        .enter()
        .append('circle')
        .attr('class','node')
        .attr('r',function(d){return d.size;})
        .style("fill", function(d) { return color(d.group); })
        .style('opacity','0.7');
        
        // .style("opacity",0.8);
        // .style('fill','none')
        // .style("stroke", function(d) { return color(d.group); });

        svy_node.on("mouseover", function(d){
            d3.select(this).transition().duration(200).style("stroke-width",'4px').style("opacity",1);
            // .style("stroke",function(d){
            //     return color(d.group);});

            svg.append('rect').attr('x',function(){return d.x+10;}).attr('y',function(){return d.y +10;})
            .attr('width',function(){return d.name.length*7 + 50}).attr('height',40).attr('rx','20').attr('ry','20').style('fill','rgba(169,169,169, 0.5)')
            .style('stroke','rgba(169,169,169, 1)').style("stroke-width",'2px');
           
            svg.append('text').transition().duration(300).attr('x',function(){return d.x+30;}).attr('y',function(){return d.y +30;})
            .attr('font-family', 'Helvetica Neue').attr('fill','rgba(211,211,211,1)').attr('font-size','13px').text(d.name);

            svg_link.each(function(l){
                if(d === l.source || d === l.target){
                     d3.select(this).transition().duration(300).style("stroke-width",'2px')
                     .style("stroke-opacity",1).style("stroke","#FFFFFF");

                }
            });
            // svg_link.attr("stroke-width",function(l){
            //     if(d === l.source || d === l.target){return '4px';}
            // });
        })
            .on("mouseout", function(d){
                d3.select(this).transition().duration(500).style("stroke-width",'2px').style('stroke','#ffffff').style("opacity",0.7);
                svg_link.each(function(l){
                    if(d === l.source || d === l.target){
                         d3.select(this).transition().duration(700).style("stroke-width",'0.5px')
                         .style("stroke-opacity",0.8).style("stroke","#ddd");
                    }
                });
                svg.selectAll('rect').transition().duration(100).remove();
                svg.selectAll('text').transition().duration(300).remove();
            })
            .on("click",function(d){
                var url = d.url;
                window.location = url;
            });
    

        force.on("tick", function() {
            svg_link.attr("x1", function(d) { return d.source.x; })
                .attr("y1", function(d) { return d.source.y; })
                .attr("x2", function(d) { return d.target.x; })
                .attr("y2", function(d) { return d.target.y; });

            svy_node.attr("cx", function(d) { return d.x; })
                .attr("cy", function(d) { return d.y; });


        });

};


</script>

        <div class="band_main">
            <div class="content-padding-2">
                <div class="zerogrid">
                    <div class="row">
                        <div class="col-full">
                            <div class="padding-grid-1">
                            <h3 class="letter"> <strong></strong></h3>
                            </div>
                            <?php 
                            foreach ($band_array as $key ) {
                                $baname = $key['baname'];
                                echo "<div class='wrapper p3'>
                                        <article class='col-1-3'>
                                            <div class='padding-grid-2'>
                                                <div class='wrapper'>
                                                    <figure class='style-img-2 fleft'><a href='/LiveConcert/artist_band/band_page.php?baname=$baname'>
                                                    <img height='250px' width='250px' src='/LiveConcert/assets/images/$baname.jpg' ></a></figure>
                                                </div>
                                            </div>
                                        </article>
                                        <article class='col-2-3'>
                                            <div class='padding-grid-2'>
                                                <a href='/LiveConcert/artist_band/band_page.php?baname=$baname'>
                                                <h4 class='margin-none indent-top1'><strong>$baname</strong></h4></a>
                                                <p class='prev-indent-bot'>".$key['bbio']."</p>
                                                <div class='wrapper'>
                                                    <ul class='list-1 fleft'>";
                                                    echo "<ul><h3>Band Member</h3></ul>";
                                if($bandmem = $mysqli->query("call get_band_member('$baname')") or die($mysqli->error)){
                                    while($row = $bandmem->fetch_object()){
                                        $member = $row->bandmember;
                                        echo "<li><a href='/LiveConcert/user/user_page.php?username=$member'>$member</a></li>";
                                    }
                                    $bandmem->close();
                                    $mysqli->next_result();
                                       
                                }
                                echo "</ul></div>
                                    </div>
                                </article>
                            </div>";
                        }
                        ?>
                        </div>
                        <div class="col-full">
                            <div class="padding-grid-2">
                                <h3 class="letter">YOU&nbsp;&nbsp;&nbsp;<strong>May Like </strong></h3>
                            </div>

                        <?php 
                            foreach ($you_may_like as $key ) {
                                $listname = $key['baname'];
                                echo "<div class='wrapper p3'>
                                        <article class='col-1-3'>
                                            <div class='padding-grid-2'>
                                                <div class='wrapper'>
                                                    <figure class='style-img-2 fleft'><a href='/LiveConcert/artist_band/band_page.php?baname=$baname'>
                                                    <img height='250px' width='250px' src='/LiveConcert/assets/images/$baname.jpg' ></a></figure>
                                                </div>
                                            </div>
                                        </article>
                                        <article class='col-2-3'>
                                            <div class='padding-grid-2'>
                                                <a href='/LiveConcert/artist_band/band_page.php?baname=$baname'>
                                                <h4 class='margin-none indent-top1'><strong>$baname</strong></h4></a>
                                                <p class='prev-indent-bot'>".$key['bbio']."</p>
                                                <div class='wrapper'>
                                                    <ul class='list-1 fleft'>";
                                                    echo "<ul><h3>Band Member</h3></ul>";
                                
                                if($bandmem = $mysqli->query("call get_band_member('$baname')") or die($mysqli->error)){
                                    while($row = $bandmem->fetch_object()){
                                        $member = $row->bandmember;
                                        echo "<li><a href='/LiveConcert/user/user_page.php?username=$member'>$member</a></li>";
                                    }
                                    $bandmem->close();
                                    $mysqli->next_result();
                                }
                                echo "</ul></div>
                                    </div>
                                </article>
                            </div>";
                        }
                        $mysqli->close();

                        ?>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="block"></div>
    </section>
</div>

        <script type="text/javascript"> Cufon.now(); </script>
    </body>
</html>