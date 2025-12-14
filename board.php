<?php

   include __DIR__ . "/../includes/db.php";
   #include __DIR__ . "/../includes/header.php";


   #step1
   $mode="";
   if(isset($_POST['mode'])){
      $mode = $_POST['mode'];
   }

   if(isset($_GET['mode'])){
      $mode = $_GET['mode'];
   }

   if(!$mode){
      echo "직접접근은 불가능합니다";
      exit;
   }

   #echo $mode;

   #step2


   //echo $subject."<br>";
   //echo $content."<br>";

   #step3(엔진) 페이지 < index.php
   #create,read,update,delete,detail
   class Board
   {
      
      private $conn;
      private $db;
      function __construct($conn,$db)
      {
         //echo "데이터베이스와 연결합니다.";
         $this->conn = $conn;
         $this->db = $db;
         //exit;
      }
      
      
      
      function create($subject,$content)
      {

         $target_dir = "upload/";
         $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
         

         // File information(확장자 찾기)
         $uploaded_name = $_FILES[ 'fileToUpload' ][ 'name' ];
         $uploaded_ext  = substr( $uploaded_name, strrpos( $uploaded_name, '.' ) + 1);
         $uploaded_size = $_FILES[ 'fileToUpload' ][ 'size' ];
         $uploaded_type = $_FILES[ 'fileToUpload' ][ 'type' ];
         $uploaded_tmp  = $_FILES[ 'fileToUpload' ][ 'tmp_name' ];


         echo $target_file."<br>";
         echo $uploaded_ext."<br>";

         if((strtolower( $uploaded_ext ) == 'GIF' || strtolower( $uploaded_ext ) == 'jpg' || strtolower( $uploaded_ext ) == 'jpeg' || strtolower( $uploaded_ext ) == 'png' ) && ( $uploaded_size < 100000 ) && ( $uploaded_type == 'image/jpeg' || $uploaded_type == 'image/png' ) && getimagesize( $uploaded_tmp ) ) {
            //echo "업로드 가능";
		if (True) {
		
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
               echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";

               $filename="$uploaded_name";
               $query = "insert into board(subject,content,filename) values('$subject','$content','$filename')";
               $result = mysqli_query($this->conn,$query);
               
               if($result){
                     echo "<script>alert('게시판에 글이 등록되었습니다.')</script>";
                     echo "<script>location.href='/board';</script>";
               }else {
					echo "<script>alert('게시판 등록실패했습니다.')</script>";
                     echo "<script>location.href='/board';</script>";
               }

            }
		}
         } else  {
            echo "<script>alert('업로드가 불가능한 확장자입니다.')</script>";
            echo "<script>location.href='/board';</script>";
         }

         exit;
         /*
         $uploadOk = 1;
         $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

         if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";

            $filename="없음";
            $query = "insert into board(subject,content,filename) values('$subject','$content','$filename')";
            $result = mysqli_query($this->conn,$query);
            
            if($result){
                  echo "<script>alert('게시판에 글이 등록되었습니다.')</script>";
                  echo "<script>location.href='/board';</script>";
            }


         } else {
            echo "Sorry, there was an error uploading your file.";
         }

         $filename="없음";
         $query = "insert into board(subject,content,filename) values('$subject','$content','$filename')";
         echo $query;
         $result = mysqli_query($this->conn,$query);
         
         if($result){
               echo "<script>alert('게시판에 글이 등록되었습니다.')</script>";
               echo "<script>location.href='/board';</script>";
         } else {
            echo "등록실패";
         }
         */


      }

     function delete($id)
    {
        $id = (int)$id; // 보안용 숫자 변환

        // 삭제할 글의 첨부파일명 조회
        $query = "SELECT filename FROM board WHERE id = $id";
        $result = mysqli_query($this->conn, $query);

        if(!$result || mysqli_num_rows($result) == 0){
            echo "<script>alert('삭제할 글이 없습니다.'); history.back();</script>";
            exit;
        }

        $row = mysqli_fetch_assoc($result);
        $filename = $row['filename'];

        // DB에서 글 삭제
        $deleteQuery = "DELETE FROM board WHERE id = $id";
        $deleteResult = mysqli_query($this->conn, $deleteQuery);

        if($deleteResult){
            // 첨부파일도 있으면 삭제
            if($filename != "없음" && file_exists(__DIR__ . "/../board/upload/" . $filename)){
                unlink(__DIR__ . "/../board/upload/" . $filename);
            }

            echo "<script>alert('글이 삭제되었습니다.'); location.href='/board';</script>";
        } else {
            echo "<script>alert('글 삭제 실패했습니다.'); history.back();</script>";
        }
        exit;
    }
	

  
   }
   #객체()
   $b = new Board($conn,$db);

   if($mode == "create"){
      $subject = $_POST['subject'];
      $content = $_POST['content'];
      $b->create($subject,$content);
   } else if($mode == "delete") {
      $id = $_POST['id'];
	  $b->delete($id);
   }








?>




   