<!-- In the table section -->
<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Tender Type</th>
            <th scope="col">Budget</th>
            <th scope="col">Location</th>
            <th scope="col">Contact Person</th>
            <th scope="col">Contact Number</th>
            <th scope="col">Tender Details</th>
            <th scope="col">Status</th>
            <th scope="col">Awarded Vendor</th>
            <th scope="col">Actions</th>                            
        </tr>
    </thead>
    <tbody>
        <?php
            $results = mysqli_query($con, "SELECT * FROM tbl_tender WHERE t_status != 4");
            $counter = 1;
            while($row = mysqli_fetch_row($results)) {
        ?>
        <tr>
            <th scope="row"><?= $counter ?></th>
            <td>
                <?php
                    if($row[1]=='1') echo "Construction<br>";
                    if($row[2] == '1') echo "Supply<br>";
                    if($row[3] == '1') echo "Service";
                ?>
            </td>
            <td>$<?= number_format($row[4], 2) ?></td>
            <td><?= $row[5] ?></td>
            <td><?= $row[6] ?></td>
            <td><?= $row[7] ?></td>
            <td><?= $row[8] ?></td>
            <td>
                <?php
                    if($row[10] == '1') echo "Pending";
                    else if($row[10] == '2') echo "Bids Received";
                    else if($row[10] == '3') echo "Awarded";
                    else if($row[10] == '4') echo "Completed";
                ?>    
            </td>
            <td>
                <?php
                    if ($row[9] == 0) {
                        echo "Not Awarded";
                    } else {
                        $vendor = mysqli_query($con, "SELECT u_company FROM tbl_user WHERE u_id = '$row[9]'");
                        $vendor_row = mysqli_fetch_row($vendor);
                        echo $vendor_row[0];
                    }
                ?>
            </td>
            <td>
                <a href="bid.php?tender_id=<?= $row[0] ?>" class="btn btn-primary">Submit Bid</a>
            </td>
        </tr>
        <?php $counter++; } ?>
    </tbody>
</table>