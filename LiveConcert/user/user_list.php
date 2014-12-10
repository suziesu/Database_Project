
<!DOCTYPE html>
<html>
<head>
	<?php include "../includes/concert_list_head.html";
	include "../includes/regular_page_head.php";?>
	<title>All User List</title>
	</head>
<body>
	
<section id="content">
			<div id="sidebar">
				<?php
				$user_result = array();
				if(isset($_POST['search_user'])){
					$search_name = $_POST['search_user'];
					if(strlen($search_name) > 30){
						echo "search name to long";
					}else{
						if($search_un = $mysqli->query("call search_user('$search_name')") or die($mysqli->error)){
							if($search_un->num_rows > 0){
								while($row = $search_un->fetch_object()){
									$user_result[] = $row->username;
								}
							}
							$search_un->close();
							$mysqli->next_result();
						}
					}
					
				}
				

				?>

					<div class="content-padding-2">
						<div class="zerogrid">
							<div class="row">
								<div class="col-full">
									<div class="padding-grid-1">
										<h3 class="letter">USER'&nbsp;&nbsp;&nbsp;<strong> List </strong></h3>
									</div>

									<?php 
									foreach ($user_result as $key ) {
										
										echo "<div class='wrapper p3'>
												<article class='col-1-3'>
													<div class='padding-grid-2'>
														<div class='wrapper'>
															<figure class='style-img-2 fleft'><a href='/LiveConcert/user/user_page.php?username=$key'>
															<img height='250px' width='250px' src='/LiveConcert/assets/images/$key.jpg' ></a></figure>
														</div>
													</div>
												</article>
												<article class='col-2-3'>
													<div class='padding-grid-2'>
														<a href='/LiveConcert/user/user_page.php?username=$key'>
														<h4 class='margin-none indent-top1'><strong>$key&nbsp;&nbsp;&nbsp;</strong> </h4></a>";
														
										
										echo "</ul></div>
											</div>
										</article>
									</div>";
									}
								?>
								</div>
								
					</div>
				</div>
				<div class="block"></div>
			</section>
		</div>

		<script type="text/javascript"> Cufon.now(); </script>
	</body>
</html>
