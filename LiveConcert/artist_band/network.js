


var width = 960,
    height = 500;

var force = d3.layout.force()
    	.size([width,height])
    	.charge(-200)
    	.linkDistance(50);
var svg = d3.select('#svg_network').append('svg')
	.attr('width',width)
	.attr('height',height);	

var color = d3.scale.category20();
// function showDetails(d){

// }
// function hideDetails(d){

// }
var network = {'nodes':[],'links':[] };
var nodeMap= {} ;
var t = 0;
d3.json("bandtype_json.php", function(error,d) {
    console.log("!23");
        console.log(d);
    
        if(!nodeMap[d.typename]){
            nodeMap[d.typename] = t;
            network.nodes.push({"name":d.typename,"group":t});
            network.links.push({"source":0,"target":nodeMap[d.typename]});
            t++;
        }
        if(!nodeMap[d.subtypename]){
            nodeMap[d.subtypename] = t;
            network.nodes.push({"name":d.subtypename,"group":nodeMap[d.typename]});
            network.links.push({"source":nodeMap[d.typename],"target":t});
            t++;
        }
    	if(!band_name[d.baname]){
            nodeMap[d.baname] = t;
            network.nodes.push({"name":d.baname,"group":nodeMap[d.typename]});
        }
        network.links.push({"souce":nodeMap[d.baname],"target":nodeMap[d.subtypename]});
        return d;
    },function(d){
        network.nodes.push({"name":"root","group":t});
        t++;
        network.nodes.forEach(function(d){
            d.x = randomnumber=Math.floor(Math.random()*width);
            d.y = randomnumber=Math.floor(Math.random()*height);
            // d.radius = circleRadius(d.playcount);
        });

    
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


    var svg_ndoe = svg.selectAll('.node')
    	.data(network.nodes)
    	.enter()
    	.append('circle')
    	.attr('class','node')
    	.attr('r',function(d){return Math.floor(Math.random()*4+3);})
    	.style("fill", function(d) { return color(nodeMap[d.group]); });

    

    force.on("tick", function() {
        svg_link.attr("x1", function(d) { return d.source.x; })
            .attr("y1", function(d) { return d.source.y; })
            .attr("x2", function(d) { return d.target.x; })
            .attr("y2", function(d) { return d.target.y; });

        svg_ndoe.attr("cx", function(d) { return d.x; })
            .attr("cy", function(d) { return d.y; });

        svg_node.on("mouseover", showDetails)
            .on("mouseout", hideDetails);
    
	});
	

});

