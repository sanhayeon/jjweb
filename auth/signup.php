<?php
   include __DIR__ ."/../includes/header.php";
   include __DIR__ ."/../includes/db.php";
?>
<div class="container mt-3">
  <h2>회원가입</h2>
  <p></p>
 <form action="/auth/auth.php" method="post">
 <input type="hidden" name="mode" value="signup">
  <div class="mb-3 mt-3">
    <label for="username" class="form-label">username:</label>
    <input type="username" class="form-control" id="username" placeholder="Enter username" name="username">
  </div>
  <div class="mb-3">
    <label for="password" class="form-label">Password:</label>
    <input type="password" class="form-control" id="password" placeholder="Enter password" name="password">
  </div>
  <button type="submit" class="btn btn-primary">회원가입</button>
</form> 
</div>
