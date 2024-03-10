<?php

$dbservername = 'localhost';
$dbname='test';
$dbusername='root';
$dbpassword='asdf1234';
$conn = new PDO("mysql:host=$dbservername; dbname=$dbname", $dbusername, $dbpassword);
$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

$uname=$_SESSION['Username'];

$stmt=$conn->prepare("select * from orders where orderer=:orderer");
$stmt->execute(array('orderer' =>$uname));
$myorder = $stmt->fetch();

if(ISSET($_POST['search_order'])){ ?>
<table class="table table-bordered">
        <thead class="alert-info">
                <tr>
                    <th>OID</th>
                    <th>Status</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Shop</th>
                    <th>Total Price</th>
                    <th>Action</th>
                </tr>
            </thead>
		<tbody>
        <?php
                $stmt=$conn->prepare("select user_id from users where username=:username");
                $stmt->execute(array('username' =>$uname));
                $uid=$stmt->fetch();

                $sql="select * from orders join shop on orders.shop_id=shop.shop_id 
                    join employees on shop.shop_id=employees.shop_id 
                    where employees.user_id='{$uid[0]}' ";
                $status=$_POST['status'];
                if($status!="All"){
                    $sql.=" and orders.status='{$status}' ";
                }
                $sname=$_POST['shopname'];
                if($sname!="All"){
                    $sql.=" and shop.shopname='{$sname}' ";
                }


                $sql2=$conn->prepare($sql);
                $sql2->execute();
				while($table = $sql2->fetch()){
			?>
			<tr>
                    <td><?php echo $table['order_id']?></td>
                    <td><?php echo $table['status']?></td>
                    <td><?php echo $table['start']?><br><?php echo $table['orderer']?></td>
                    <td><?php
                    if(!empty($table['end'])) {
                        echo $table['end'];
                        echo '<br>';
                        echo $table['finisher'];
                    }
                    else{
                        echo '-';
                    }
                    ?></td>
                    <td><?php echo $table['shopname']?></td>
                    <td><?php
                        echo '$',$table['total'],'<br>';
                        echo  $table['order_num'],'*$',$table['order_price'];?>
                    </td>
                    <td>
                    <?php
                    if($table['status']=='Not Finished') {?>
                    <form action="done.php?id=<?php echo $table['order_id'] ?>" method="post">
                    <input type="submit" class="btn btn-success" value="Done">
                    </form>
                    <?php }
                    if($table['status']=='Not Finished') {?>
                    <form action="cancel.php?id=<?php echo $table['order_id'] ?>" method="post">
                    <input type="submit" class="btn btn-danger" value="X">
                    </form>
                    <?php }?>
                    </td>
                <tr>
            <?php }?>
            </tbody>
        </table>
    <?php }?> 