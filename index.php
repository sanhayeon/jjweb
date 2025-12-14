<?php
   include __DIR__ . "/../includes/header.php";
   include __DIR__ . "/../includes/db.php";
   
   $query = "select * from board";
   $result = mysqli_query($conn, $query);
   
?>
                <!-- Page content-->
                <div class="container-fluid">
                    <h2 class="mt-4">질문</h2>
               <hr>
                    <p>   #게시판 #입력 #자바스크립트 #전송방식</p>
                 <table class="table table-hover">
                  <thead>
                    <tr>
                     <th>번호</th>
                     <th>제목</th>
					 <th>사진</th>
                     <th>날짜</th>
                    </tr>
                  </thead>
                  <tbody>

                  <?php
                  if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {

                  ?>
                       <tr>
                        <td><a href="/board/read.php?id=<?=$row['id']?>"><?=$row['id']?></a></td>
                        <td><?=$row['subject']?></td>
						<td>
						<?php if (!($row['filename']=="없음")) {
					?>
						<a href="/board/upload/<?=$row['filename']?>"><?=$row['filename']?></td>
                        <?php
							}?>
						<td><?=$row['reg_date']?></td>
                       </tr>
					   
                  <?php
                     } 
                   }else {
                  ?>
      <tr>
        <td colspan=4>0</td>
      </tr>
<?php
   }
?>


                  </tbody>
                 </table>



               <a href="create.php">쓰기</a>
                </div>
            
<?php
   include __DIR__ . "/../includes/footer.php";
?>            