<!DOCTYPE html>
<html>
<head>
	<?php include "../includes/login_head.php"; 
include "../includes/regular_page_head.php";
  ?>
	<script src="/LiveConcert/assets/js/d3-cloud/lib/d3/d3.js"></script>
	<script src="/LiveConcert/assets/js/d3-cloud/d3.layout.cloud.js"></script>	
	<title>Music Genre List</title>
</head>
<body>
<style>
* {
 font-size: 100%;
 font-family: Helvetica Neue;
 color:rgba(211,211,211,1);
}
body{
    background:#2E343E;
}
#type{
	 margin-left: auto ;
  margin-right: auto ;
}
</style>
<div id = 'type'></div>
<?php
$username = $_SESSION['username'];
//get all typelist
$typearray = array();
$getAllType = array();

if($alltype = $mysqli->prepare("select typename from Type")){
	$alltype->execute();
	$alltype->bind_result($typename);
	while($alltype->fetch()){
		array_push($getAllType,$typename);
	}
	// echo "<table>";
	$alltype->close();
	foreach ($getAllType as $key) {
		// echo "<tr>";
		// echo "<td><a href='/LiveConcert/genre/genre_type_page.php?type=$key'>$key</a></td>";
		if($allsubtype = $mysqli->query("call onetypeallsubtype('$key')")){
			while($row = $allsubtype->fetch_object()){
				// $subtypename = $row->subtypename;
				// echo "<td><a href='/LiveConcert/genre/genre_type_page.php?subtype=$subtypename'>$subtypename</td>";
				$typearray[] = $row->subtypename;
			}
			// echo "</tr>";
			$allsubtype->close();
			$mysqli->next_result();
		}
	}
	// echo implode(',', $typearray);
	$AllType = array_merge($getAllType,$typearray);
	$mysqli->close();
	// echo "</table>";	
}?>


<script type="text/javascript">
var all_type = <?php echo json_encode($AllType); ?>;
var click = 0;
  // var fill = d3.scale.category20();
  var fill = ['#dbdb8d',' #17becf','#00b0bb','#bcbd22','#9edae5','#1f77b4','#f7b6d2','#7f7f7f']
  d3.layout.cloud().size([1400, 800])
      .words(all_type.map(function(d) {
        return {text: d, size:  Math.random() * 40 + 30};
      }))
      .padding(4)
      .rotate(function() { return ~~(Math.random() * 60) * 2; })
      .font("Impact")
      .fontSize(function(d) { return d.size-2; })
      .on("end", draw)
      .start();
  function draw(words) {
    d3.select("#type").append("svg")
        .attr("width", 1400)
        .attr("height", 800)

      .append("g")
        .attr("transform", "translate(700,300)")
      .selectAll("text")
        .data(words)
      .enter().append("text")
        .style("font-size", function(d) { return d.size*1.1 + "px"; })
        .style("font-family", "Impact")
        .style("fill", function(d, i) { return fill[i%7]; })
        .attr("text-anchor", "middle")
        .attr("transform", function(d) {
          return "translate(" + [d.x, d.y] + ")rotate(" + d.rotate + ")";
        })
        .text(function(d) { return d.text; })
        .on('mouseover',function(d,i){
        	d3.select(this).style("stroke","#FFFFFF").style("stroke-width","2px");
        })
        .on('mouseout',function(d,i){
        	d3.select(this).style("stroke","none");
        })
        .on('click',function(d){
          
          if(click++ % 2 == 0){
            
            d3.select('g').selectAll('text').style('visibility','hidden');
            d3.select(this).style('visibility','visible');
            d3.select(this).transition().duration(1000).attr("transform","translate(100,100)scale(4)rotate(0)").transition().delay(1000).duration(1300).attr("transform","translate(-350,-200)scale(0.7)rotate(330)");
            var text_box= d3.select('g').append('g').attr('class','describ');
            var text_value = text_box.append('text').style('visibility','visible')
            // .attr('class','describ')
              // 
              .attr('fill','#93ceee').style('font-size','50px');
              // .text(
              //   function(){
              d3.json("genre_json.php?subtype="+d.text,function(data){
                    console.log(data);
              text_value.text(data).attr('x','-200').attr('y','0').attr('dy','0.003').call(wrap,'500')
              .attr("transform","translate(-200,500)").transition().delay(2000).duration(2000).attr("transform","translate(-200,-50)");
                  });
                function wrap(text, width) {
                  text.each(function() {
                    var text = d3.select(this),
                        words = text.text().split(/\s+/).reverse(),
                        word,
                        line = [],
                        lineNumber = 0,
                        lineHeight = 1.1, // ems
                        y = text.attr("y"),
                        dy = parseFloat(text.attr("dy")),
                        tspan = text.text(null).append("tspan").attr("x", 0).attr("y", y).attr("dy", dy + "em");
                    while (word = words.pop()) {
                      line.push(word);
                      tspan.text(line.join(" "));
                      if (tspan.node().getComputedTextLength() > width) {
                        line.pop();
                        tspan.text(line.join(" "));
                        line = [word];
                        tspan = text.append("tspan").attr("x", 0).attr("y", y).attr("dy", ++lineNumber * lineHeight + dy + "em").text(word);
                      }
                    }
                  });
                }
              // text.select('.describ').text(
              // '123');
              // function(){
                // d3.json("genre_json.php?subtype="+d.text,function(data){
                //     console.log(data);
                //     return data;
                //   });
          }else{
            d3.select('g').selectAll('text').style('visibility','visible');
            d3.select(this).transition().duration(1000).attr("transform",function(d){
              return "translate(" + [d.x, d.y] + ")rotate(" + d.rotate + ")";
            });
            d3.select('.describ').remove();
          }
          
        	// window.location = "/LiveConcert/genre/genre_type_page.php?subtype="+d.text;
        });
  }
</script>
</body>
</html>