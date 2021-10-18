<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>


<?php
        
        //データベース接続
        $dsn='データベース名';
        $user='ユーザー名';
        $password='パスワード';
        $pdo=new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        //日付
        $date=date("Y/m/d H:i:s");
        //テーブル
        $sql = "CREATE TABLE IF NOT EXISTS tbtest"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT,"
        . "date char(32),"
        . "pass char(32)"
        .");";
        $stmt = $pdo->query($sql);

       
   
        //削除・編集ともに空
        if(empty($_POST["delete"])&&empty($_POST["edit"]))
          {
            //名前・コメント入ってる
            if(!empty($_POST["name"])&&!empty($_POST["comment"])&&!empty($_POST["pass"]))
              {
                $sql= $pdo->prepare("INSERT INTO tbtest (name, comment , date , pass) VALUES (:name, :comment, :date, :pass )");
                $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
                $name=$_POST["name"];
                $comment=$_POST["comment"];
                $date=date("Y/m/d H:i:s");
                $pass=$_POST["pass"];
                $sql -> execute();
                $pass="";
              }
          }

    
         //編集フォームだけ入力がある場合
        if(!empty($_POST["edit_n"]) && !empty($_POST["pass_e"]) && empty($_POST["delete"]))
        {
            $sql = 'SELECT * FROM tbtest';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            $edit_n2 = $_POST["edit_n"]; //編集番号

            foreach ($results as $row)
            { 
            if($row["id"] == $edit_n2 && $row["pass"] == $_POST["pass_e"]) //投稿番号と一致したときに画面に表示
            {
                $name = $_POST["name"]; 
                $comment = $_POST["comment"];
                if(empty($_POST["name"]) && empty($_POST["comment"])) //指定した投稿番号の名前とコメントを変数に代入する
                {
                    $name_e = $row["name"];
                    $comment_e = $row["comment"];
                }
            }
            else if($row["id"]==$edit_n2 && $row["pass"]!=$_POST["pass_e"])//パスワードが一致していなかった場合
            {
                $edit_n2 = "";
                $name = "";
                $comment = "";
            }
        }
    }
    
    if((empty($_POST["pass"]) && empty($_POST["pass_d"]) && empty($_POST["pass_e"])) || (!empty($_POST["delete"]) && empty($_POST["pass_d"])) 
    ||(!empty($_POST["edit_n"]) && empty($_POST["pass_e"])) || (!empty($_POST["pass"]) && !empty($_POST["pass_e"])))//フォームに必要な情報が入力されていない場合
        {
            $name = "";
            $comment = "";
            $edit_n2 = "";
        }
 ?>
    
    
    
    <form action="" method="post">
        <input type="text" name="name" value="<?php if(!empty($_POST["edit_n"])){echo $name_e;}?>" placeholder="名前">
        <input type="text" name="comment" value="<?php if(!empty($_POST["edit_n"])){echo $comment_e;}?>" placeholder="コメント">
        <input type="text" name="pass" placeholder="パスワード">
        <input type="hidden" name="edit_n2" value="<?php if(!empty($_POST["edit_n"])){echo $edit_n2;}?>" placeholder="編集番号">
        <input type="submit" name="submit"><br> 
        
        <input type="number" name="delete" placeholder="削除番号">
        <input type="text" name="pass_d" placeholder="パスワード">
        <input type="submit" name="submit_d" value="削除"> <br>
        
        <input type="number" name="edit_n" placeholder="編集対象番号">
        <input type="text" name="pass_e" placeholder="パスワード">
        <input type="submit" name="edit" value="編集">
    </form>     

    
</body>

<?php
    //コメント・編集が空
    if(empty($_POST["name"])&&empty($_POST["comment"])&&empty($_POST["pass"])&&empty($_POST["edit"])&&empty($_POST["pass_e"])){
    //消去フォーム(投稿・編集が空)
        if(!empty($_POST["delete"])&&!empty($_POST["pass_d"])){
            $id=$_POST["delete"];
            $pass=$_POST["pass_d"];
            $sql = 'delete from tbtest where id=:id AND pass=:pass'; 
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
            $stmt->execute();
            echo "<hr>";
        }
    }

    //コメント・消去が空
    if(empty($_POST["delete"])&&empty($_POST["pass_d"])&&empty($_POST["edit_n"])){
        if(!empty($_POST["name"])&&!empty($_POST["comment"])&&!empty($_POST["pass"])&&!empty($_POST["edit_n2"])){
            //数える
            $sql = 'SELECT * FROM tbtest';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            $allid=count($results);
            $id=$_POST["edit_n2"]; //変更する投稿番号
            if($allid>=$id){
                $name=$_POST["name"];
                $comment=$_POST["comment"]; 
                $pass=$_POST["pass"];
                $sql = 'UPDATE tbtest SET name=:name,comment=:comment,date=:date, pass=:pass WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();

                
            }else{
                $name=$row['name'];
                $comment=$row['comment'];
                $date=$row['date'];
                $pass=$row['pass'];   
                $sql = $pdo -> prepare("INSERT INTO tbtest (id,name, comment , date , pass) VALUES (:id,:name, :comment, :date, :pass)");
                $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
                $sql ->bindParam(':id', $id, PDO::PARAM_INT);
                $sql -> execute();
                $name="";
                $comment="";
                $date="";
                $pass="";
                }
            }
        }
    
        //名前・コメント・パスワード
        if(empty($_POST["delete"])&&empty($_POST["delete_n"])&&empty($_POST["edit_n"])&&empty($_POST["pass_e"])&&empty($_POST["edit_n2"])){
            if(empty($_POST["name"])&&empty($_POST["comment"])&&empty($_POST["pass"])){
                echo "名前・コメント・パスワードを入力してください<hr>";
                //データベースの表示
                $sql = 'SELECT * FROM tbtest';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].',';
                    echo $row['name'].',';
                    echo $row['comment'].',';
                    echo $row['date'].'<br>';
                    echo '<hr>';
                }
            }elseif(!empty($_POST["name"])&&empty($_POST["comment"])&&empty($_POST["pass"])){
                echo "コメント・パスワードを入力してください<hr>";
                //データベースの表示
                $sql = 'SELECT * FROM tbtest';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].',';
                    echo $row['name'].',';
                    echo $row['comment'].',';
                    echo $row['date'].'<br>';
                    echo '<hr>';
                }
            }elseif(!empty($_POST["name"])&&!empty($_POST["comment"])&&empty($_POST["pass"])){
                echo "パスワードを入力してください<hr>";
                //データベースの表示
                $sql = 'SELECT * FROM tbtest';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].',';
                    echo $row['name'].',';
                    echo $row['comment'].',';
                    echo $row['date'].'<br>';
                    echo '<hr>';
            }
        }elseif(empty($_POST["name"])&&!empty($_POST["comment"])&&empty($_POST["pass"])){
            echo "名前・パスワードを入力してください<hr>";
            //データベースの表示
            $sql = 'SELECT * FROM tbtest';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                //$rowの中にはテーブルのカラム名が入る
                echo $row['id'].',';
                echo $row['name'].',';
                echo $row['comment'].',';
                echo $row['date'].'<br>';
                echo '<hr>';
            }
        }elseif(empty($_POST["name"])&&!empty($_POST["comment"])&&!empty($_POST["pass"])){
            echo "名前を入力してください<hr>";
             //データベースの表示
             $sql = 'SELECT * FROM tbtest';
             $stmt = $pdo->query($sql);
             $results = $stmt->fetchAll();
             foreach ($results as $row){
                 //$rowの中にはテーブルのカラム名が入る
                 echo $row['id'].',';
                 echo $row['name'].',';
                 echo $row['comment'].',';
                 echo $row['date'].'<br>';
                 echo '<hr>';
             }
         }elseif(!empty($_POST["name"])&&empty($_POST["comment"])&&empty($_POST["pass"])){
             echo "コメントを入力してください<hr>";
              //データベースの表示
              $sql = 'SELECT * FROM tbtest';
              $stmt = $pdo->query($sql);
              $results = $stmt->fetchAll();
              foreach ($results as $row){
                  //$rowの中にはテーブルのカラム名が入る
                  echo $row['id'].',';
                  echo $row['name'].',';
                  echo $row['comment'].',';
                  echo $row['date'].'<br>';
                  echo '<hr>';
              }
            }elseif(!empty($_POST["name"])&&!empty($_POST["comment"])&&!empty($_POST["pass"])){
                $name=$_POST["name"];
                $comment=$_POST["comment"];
                echo $name."さん「".$comment."」を受け付けました<hr>";
                 //データベースの表示
                 $sql = 'SELECT * FROM tbtest';
                 $stmt = $pdo->query($sql);
                 $results = $stmt->fetchAll();
                 foreach ($results as $row){
                     //$rowの中にはテーブルのカラム名が入る
                     echo $row['id'].',';
                     echo $row['name'].',';
                     echo $row['comment'].',';
                     echo $row['date'].'<br>';
                     echo '<hr>';
                 }
            }
        }elseif(empty($_POST["delete"])&&empty($_POST["delete_n"])&&empty($_POST["edit_n"])&&empty($_POST["pass_e"])){
            if(!empty($_POST["name"])&&!empty($_POST["comment"])&&!empty($_POST["pass"])&&!empty($_POST["edit_n2"])){
                $name=$_POST["name"];
                $comment=$_POST["comment"];
                echo $name."さん「".$comment."」と編集しました<hr>";
                 //データベースの表示
                 $sql = 'SELECT * FROM tbtest';
                 $stmt = $pdo->query($sql);
                 $results = $stmt->fetchAll();
                 foreach ($results as $row){
                     //$rowの中にはテーブルのカラム名が入る
                     echo $row['id'].',';
                     echo $row['name'].',';
                     echo $row['comment'].',';
                     echo $row['date'].'<br>';
                     echo '<hr>';
                 }
            }
        }elseif(empty($_POST["name"])&&empty($_POST["comment"])&&empty($_POST["pass"])&&empty($_POST["edit_n2"])&&empty($_POST["edit_n"])&&empty($_POST["pass_e"])){
            if(!empty($_POST["delete"])&&empty($_POST["pass_e"])){
                echo "パスワードを入力してください<hr>";
                 //データベースの表示
                 $sql = 'SELECT * FROM tbtest';
                 $stmt = $pdo->query($sql);
                 $results = $stmt->fetchAll();
                 foreach ($results as $row){
                     //$rowの中にはテーブルのカラム名が入る
                     echo $row['id'].',';
                     echo $row['name'].',';
                     echo $row['comment'].',';
                     echo $row['date'].'<br>';
                     echo '<hr>';
                 }
            }elseif(empty($_POST["delete"])&&!empty($_POST["pass_e"])){
                echo "投稿番号を指定してください<hr>";
                //データベースの表示
                $sql = 'SELECT * FROM tbtest';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].',';
                    echo $row['name'].',';
                    echo $row['comment'].',';
                    echo $row['date'].'<br>';
                    echo '<hr>';
                }
            }elseif(!empty($_POST["delete"])&&!empty($_POST["pass_e"])){
                echo "コメントを消去しました<hr>";
                //データベースの表示
                $sql = 'SELECT * FROM tbtest';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].',';
                    echo $row['name'].',';
                    echo $row['comment'].',';
                    echo $row['date'].'<br>';
                    echo '<hr>';
                }
            }
        }elseif(empty($_POST["delete"])&&empty($_POST["delete_n"])&&empty($_POST["name"])&&empty($_POST["comment"])&&empty($_POST["pass"])&&empty($_POST["edit_n2"])){
            if(empty($_POST["edit_n"])&&!empty($_POST["pass_e"])){
                echo "編集対象番号を入力してください<hr>";
                //データベースの表示
                $sql = 'SELECT * FROM tbtest';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].',';
                    echo $row['name'].',';
                    echo $row['comment'].',';
                    echo $row['date'].'<br>';
                    echo '<hr>';
                }
            }elseif(!empty($_POST["edit_n"])&&empty($_POST["pass_e"])){
                echo "パスワードを入力してください<hr>";
                //データベースの表示
                $sql = 'SELECT * FROM tbtest';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].',';
                    echo $row['name'].',';
                    echo $row['comment'].',';
                    echo $row['date'].'<br>';
                    echo '<hr>';
                }
            }elseif(!empty($_POST["edit_n"])&&!empty($_POST["pass_e"])){
                echo "コメントを編集してください<hr>";
                //データベースの表示
                $sql = 'SELECT * FROM tbtest';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].',';
                    echo $row['name'].',';
                    echo $row['comment'].',';
                    echo $row['date'].'<br>';
                    echo '<hr>';
                }
            }
        }
   ?>

</body>
</html>