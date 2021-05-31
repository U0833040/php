<!doctype html>
<html>
  <head>
    <meta charset = "utf-8">
    <title>線上點餐兼實名制系統</title>
  </head>
  <body>
    <?php
      $link = mysqli_connect("localhost","userA","aaa12345","menu") or die("connection error");
      $data_per_page = 7;//每頁顯示4筆資料
      if (isset($_GET["page"]))
        $page = $_GET["page"];
      else {
        $page = 1;
      }//取得要顯示第幾頁的紀錄
      $sql = "SELECT order_name AS '品名',price AS '單價' FROM nobita";
      $result = mysqli_query($link,$sql) or die("query erroe");
      $total_fields = mysqli_num_fields($result);//取得欄位數
      $total_data = mysqli_num_rows($result);
      $total_page = ceil($total_data / $data_per_page);
      $start_data = $data_per_page * ($page -1);
      $page_start = $start_data +1;//該頁的起始筆數
      $page_end = $start_data + $data_per_page;//該頁最後一筆筆數
      if($page_end>$total_data){  //最後頁的最後筆數=總筆數
        $page_end = $total_data;//最後頁的最後筆數=總筆數
      }
      mysqli_data_seek($result,$start_data);//將記錄指標移至該頁第一筆的序號
      echo "<table border = '1' width = '1024' align = 'center'>";
      echo "<tr align = '1024' height = '30' align = 'center'>";
      for ($i=0;$i<$total_fields;$i++) echo "<td width = '256' height = '30' align = 'center'>".mysqli_fetch_field_direct($result,$i)->name."</td>";
      echo "<td width = '256' height = '30' align = 'center'>訂購數量</td>";
      echo "<td width = '256' height = '30' align = 'center'>確認訂購</td>";
      echo "</tr>";//顯示欄位名稱
      $j=1;
      while($row = mysqli_fetch_array($result,MYSQLI_BOTH) and $j<= $data_per_page){//顯示紀錄
        echo "<tr align = 'center'>";
        echo "<form method='post' action='add_to_car.php?order_name = ".urlencode($row["order_name"])."&price =".$row["price"]."'>";
				for ($i=0;$i<$total_fields;$i++) echo "<td>$row[$i]</td>";
        echo "<td><input type = 'text' name = 'quantity' value = '0' size = '15'></td>";
        echo "<td><input type = 'submit' value = '確認訂購'></td>";
        $j++;
        echo "</tr>";
      }
			mysqli_free_result($result);
			mysqli_close($link);
      echo "</table>";
			?>  
      <div class="row">
      <div class="show" align = "center"> <?php echo "顯示$page_start ~ $page_end 筆， 共 $total_data 筆。 目前在第 $page 頁，共 $total_page 頁";//每頁顯示筆數明細?></div>
	    <div class="show" align = "center"> <?php if($total_page>1){  //總頁數>1才顯示分頁選單
        if($page=='1'){//分頁頁碼；在第一頁時,該頁就不超連結,可連結就送出$_GET['page'],
          echo "首頁 ";
          echo "上一頁 ";		
        }else{
          echo "<a href=?page=1>首頁 </a> ";
          echo "<a href=?page=".($page-1).">上一頁 </a> ";		
        }
         //此分頁頁籤以左、右頁數來控制總顯示頁籤數，例如顯示5個分頁數且將當下分頁位於中間，則設2+1+2 即可。若要當下頁位於第1個，則設0+1+4。也就是總合就是要顯示分頁數。如要顯示10頁，則為 4+1+5 或 0+1+9，以此類推。	
        for($i=1 ; $i<=$total_page ;$i++){ 
          $lnum = 2;  //顯示左分頁數，直接修改就可增減顯示左頁數
          $rnum = 2;  //顯示右分頁數，直接修改就可增減顯示右頁數
         //判斷左(右)頁籤數是否足夠設定的分頁數，不夠就增加右(左)頁數，以保持總顯示分頁數目。
           if($page <= $lnum){
               $rnum = $rnum + ($lnum-$page+1);
           }
           if($page+$rnum > $total_page){
               $lnum = $lnum + ($rnum - ($total_page-$page));
           }
            if($page-$lnum <= $i && $i <= $page+$rnum){ //分頁部份處於該頁就不超連結,不是就連結送出$_GET['page']
              if($i==$page){
                  echo $i.' ';
              }else{
                echo '<a href=?page='.$i.'>'.$i.'</a> ';
                }
                  }
                }
        //在最後頁時,該頁就不超連結,可連結就送出$_GET['page']	
        if($page==$total_page){
          echo " 下一頁";
          echo " 末頁";	
        }else{
          echo "<a href=?page=".($page+1)."> 下一頁</a>";
          echo "<a href=?page=".$total_page."> 末頁</a>";		
        }
        }
    ?>
    </div>
    </div>
  </body>
</html>