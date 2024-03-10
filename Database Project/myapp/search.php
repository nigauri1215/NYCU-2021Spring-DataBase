<?php
$conn = new PDO("mysql:host=$dbservername; dbname=$dbname", $dbusername, $dbpassword);
$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

if(ISSET($_POST['search'])){ ?>
<table class="table table-bordered">
		<thead class="alert-info">
			<tr>
				<th>shop</th>
				<th>city</th>
				<th>maskprice</th>
                <th>maskamount</th>
                <th> </th>
			</tr>
		</thead>
		<tbody>
        <?php
                $sname=$_POST['sname'];
                $city=$_POST['city'];
                $pricemin=$_POST['pricemin'];
                $pricemax=$_POST['pricemax'];
                $amount=$_POST['amount'];
                $sql="select * from shop where 1=1 ";
                
                if(!empty($sname)){
                    $sql.=" and shopname like '%{$sname}%'";
                }

                if($city!="all"){
                    $sql.=" and city='{$city}'";
                }
                
                if(!empty($pricemin)){
                    $sql.=" and '{$pricemin}'<=price";
                }
                if(!empty($pricemax)){
                    $sql.=" and price<='{$pricemax}'";
                }
                
                if($amount!="all"){
                    if($amount=="售完(0)"){
                        $sql.=" and amount=0";
                    }
                    else if($amount=="稀少(1~99)"){
                        $sql.=" and amount>=1 and amount<=99 ";
                    }
                    else{
                        $sql.=" and amount>=100";
                    }
                }
                
                $sql2=$conn->prepare($sql);
                $sql2->execute();
				while($row = $sql2->fetch()){
			?>
			<tr>
				<td><?php echo $row['shopname']?></td>
				<td><?php echo $row['city']?></td>
				<td><?php echo $row['price']?></td>
                <td><?php echo $row['amount']?></td>
                <td><form action="order.php?id=<?php echo $row['shopname'] ?>" method="post">
                    <input type="number" name="amount">
                    <input type="submit" class="btn btn-success" value="Buy!">
                </form>
                </td>
			</tr>
            <?php }?>
        </tbody>
    </table>
       <?php }?> 
