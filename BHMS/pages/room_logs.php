<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
ob_start();

require '../php/templates.php';
require '../views/RoomlogsViews.php';

$more_links = '
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.min.css" 
integrity="sha512-wCrId7bUEl7j1H60Jcn4imkkiIYRcoyq5Gcu3bpKAZYBJXHVMmkL4rhtyhelxSFuPMIoQjiVsanrHxcs2euu/w==" crossorigin="anonymous" referrerpolicy="no-referrer"/>';

html_start('room_logs.css', $more_links);

// Sidebar
require '../php/navbar.php';

// Burger Sidebar
RoomlogsViews::burger_sidebar();

?> 

<!-- Room Logs Section -->
<div class="container-fluid">

    <!-- Header -->
    <?php RoomlogsViews::room_logs_header() ?>

    <!-- Room Log Actions -->
    <div class="rm-log-button">
        <button class="btn-var-3 shadow show-avail-rm-btn">Show Available Rooms</button>
        <?php 
            if($_SESSION['sessionType'] === 'admin' || $_SESSION['sessionType'] === 'dev'){
                ?> 
                <button type="button" class="btn-var-3 shadow" data-bs-toggle="modal" data-bs-target="#add-new-rm" onmouseover="document.getElementById('rl-add-new-room').src='/images/icons/Room Logs/add_new_room_dark.png'" onmouseout="document.getElementById('rl-add-new-room').src='/images/icons/Room Logs/add_new_room_light.png'">
                    <img id="rl-add-new-room" src="/images/icons/Room Logs/add_new_room_light.png" alt="">Add New Room</button>
                <?php
            }
        ?>
        <button type="button" class="btn-var-3 shadow" data-bs-toggle="modal" data-bs-target="#addNewRent" onmouseover="document.getElementById('rl-add-new-rent').src='/images/icons/Dashboard/Buttons/add_new_rent_dark.png'" onmouseout="document.getElementById('rl-add-new-rent').src='/images/icons/Dashboard/Buttons/add_new_rent_light.png'">
            <img id="rl-add-new-rent" src="/images/icons/Dashboard/Buttons/add_new_rent_light.png" alt="">Add New Rent</button>
    </div>

    <!-- Room Information -->
    <div class="row rm-container">

    <?php
        // Code for displaying all the Room Cards
        RoomlogsViews::room_info_cards();
    ?>
        
</div> 

<!-- Room Logs Modals -->
<?php 
RoomlogsViews::room_info_modal(); 
RoomlogsViews::deleteOccupancyModal();
RoomlogsViews::editOccupancyModal();
RoomlogsViews::addNewRoomModal();
RoomlogsViews::editRoomModal();
RoomlogsViews::deleteRoomModal();
RoomlogsViews::create_new_rent_modal();
RoomlogsViews::deactOccupancyModal();

if(isset($_GET['addRoomStatus'])){
    if($_GET['addRoomStatus'] === 'success'){
        echo '<script>showSuccessAlert("Room Added Successfully!");</script>';
    } else if($_GET['addRoomStatus'] === 'error'){
        echo '<script>showFailAlert("An unexpected error has happened!");</script>';
    }
}

if(isset($_GET['editRoomStatus'])){
    if($_GET['editRoomStatus'] === 'success'){
        echo '<script>showSuccessAlert("Room Edited Successfully!");</script>';
    } else if($_GET['editRoomStatus'] === 'error'){
        echo '<script>showFailAlert("An unexpected error has happened!");</script>';
    }
}

if(isset($_GET['deleteRoomStatus'])){
    if($_GET['deleteRoomStatus'] === 'success'){
        echo '<script>showSuccessAlert("Room Deleted Successfully!");</script>';
    } else if($_GET['deleteRoomStatus'] === 'error'){
        echo '<script>showFailAlert("An unexpected error has happened!");</script>';
    }
}

if(isset($_GET['addRentStatus'])){
	if($_GET['addRentStatus'] === 'Success - Shared'){
		echo '<script>showSuccessAlert("Shared Room Rent Added Successfully!");</script>';
	} else if($_GET['addRentStatus'] === 'Error - Shared'){
		echo '<script>showFailAlert("Shared Room Rent Error!");</script>';
	} else if($_GET['addRentStatus'] === 'Error - Bed Spacer only'){
		echo '<script>showFailAlert("Bed Spacer only!");</script>';
	} else if($_GET['addRentStatus'] === 'Error - Room Full'){
		echo '<script>showFailAlert("Room is in Full Capacity!");</script>';
	} else if($_GET['addRentStatus'] === 'Success - Rent'){
		echo '<script>showSuccessAlert("Rent Added Successfully!");</script>';
	} else if($_GET['addRentStatus'] === 'Error - Tenant Rent Error'){
		echo '<script>showFailAlert("Tenant is already occupied on the selected date!");</script>';
	} else if ($_GET['addRentStatus'] === 'Error - Empty Fields') {
        echo '<script>showFailAlert("Empty Fields!");</script>';
    }
}

if(isset($_GET['editOccStatus'])){
    if($_GET['editOccStatus'] === 'Success - Edit'){
        echo '<script>showSuccessAlert("Room Edited Successfully!");</script>';
    } else if($_GET['editOccStatus'] === 'Error - Shared Only'){
        echo '<script>showFailAlert("Room can only be shared!");</script>';
    } else if ($_GET['editOccStatus'] === 'Error - Room Full') {
        echo '<script>showFailAlert("Room is in Full Capacity!");</script>';
    } else if($_GET['editOccStatus'] === 'Error - Empty Fields'){
        echo '<script>showFailAlert("Empty Fields!");</script>';
    } else if($_GET['editOccStatus'] === 'Error - Already Renting a Room'){
        echo '<script>showFailAlert("Tenant is already renting a Room!");</script>';
    } else {
        echo '<script>showFailAlert("An unexpected error has happened!");</script>';
    }
}

if(isset($_GET['deleteOccStatus'])){
    if($_GET['deleteOccStatus'] === 'success'){
        echo '<script>showSuccessAlert("Occupancy Deleted Successfully!");</script>';
    } else if($_GET['deleteOccStatus'] === 'error'){
        echo '<script>showFailAlert("An unexpected error has happened!");</script>';
    }
}

if(isset($_GET['deactOccStatus'])){
    if($_GET['deactOccStatus'] === 'success'){
        echo '<script>showSuccessAlert("Occupancy Deactivated Successfully!");</script>';
    } else if($_GET['deactOccStatus'] === 'error'){
        echo '<script>showFailAlert("An unexpected error has happened!");</script>';
    }
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Adding New Room
    if(isset($_POST['add-room-submit'])){
        $newRoomInfo = array(
            'roomID' => $_POST['add-new-rm-code'],
            'capacity' => $_POST['add-new-rm-cap'],
        );

        $result = RoomlogsController::addNewRoom($newRoomInfo);
        if($result){
            // Redirect to the same page or to a confirmation page after successful addition
            header('Location: room_logs.php?addRoomStatus=success');
            exit();
        } else {
            // Handle the error case, potentially redirecting with an error flag or displaying an error message
            header('Location: room_logs.php?addRoomStatus=error');
            exit();
        }
    }

    // Add New Rent Handler
	if (isset($_POST['create-new-rent'])) {
		$create_rent = array(
			"tenID" => $_POST['new-rent-tenant'],
			"shareTenID" => $_POST['share-new-rent-tenant'] ?? '',
			"roomID" => $_POST['new-rent-room'],
			"occTypeID" => $_POST['new-rent-occTypeID'],
			"occDateStart" => $_POST['new-rent-start'],
			"occDateEnd" => $_POST['new-rent-end'],
			"occupancyRate" => $_POST['new-rent-rate']
		);

        $new_billing = array(
			"tenID" => $_POST['new-rent-tenant'],
			"billTotal" => $_POST['new-rent-rate'],
			"endDate" => $_POST['new-rent-end']
		);

		$result = RoomlogsController::create_new_rent($create_rent, $new_billing);
        header('Location: room_logs.php?addRentStatus='.$result);
        exit();
	}

    // Editing Room
    if(isset($_POST['edit-room-submit'])){
        $editRoomInfo = array(
            'roomID' => $_POST['edit-rm-code-hidden'],
            'newRoomID' => $_POST['edit-rm-code'], // 'newRoomID' is the new room code, 'roomID' is the old room code
            'capacity' => $_POST['edit-rm-cap'],
        );

        $result = RoomlogsController::editRoom($editRoomInfo);
        if($result){
            // Redirect to the same page or to a confirmation page after successful edit
            header('Location: room_logs.php?editRoomStatus=success');
            exit();
        } else {
            // Handle the error case, potentially redirecting with an error flag or displaying an error message
            header('Location: room_logs.php?editRoomStatus=error');
            exit();
        }
    }

    // Deleting Room
    if(isset($_POST['delete-room-submit'])){
        $delRoomInfo = $_POST['delete-room-id'];

        $result = RoomlogsController::deleteRoom($delRoomInfo);
        if($result){
            // Redirect to the same page or to a confirmation page after successful deletion
            header('Location: room_logs.php?deleteRoomStatus=success');
            exit();
        } else {
            // Handle the error case, potentially redirecting with an error flag or displaying an error message
            header('Location: room_logs.php?deleteRoomStatus=error');
            exit();
        }
    }

    // Editing Occupancy
    if(isset($_POST['edit-rent-submit'])){
        $editInfo = array(
            'occupancyID' => $_POST['edit-occupancy-id'],
            'roomID' => $_POST['edit-rent-room'],
            'occTypeID' => $_POST['edit-rent-type'],
            'occDateStart' => $_POST['edit-rent-start'],
            'occDateEnd' => $_POST['edit-rent-end'],
        );

        $result = RoomlogsController::editOccupancy($editInfo);
        header('Location: room_logs.php?editOccStatus='.$result);
        exit();
    }
    
    // Deleting Occupancy
    if (isset($_POST['delete-occupancy-id'])) {

        $delOccInfo = $_POST['delete-occupancy-id'];

        $result = RoomlogsController::delete_occupancy($delOccInfo);
        if ($result) {
            // Redirect to the same page or to a confirmation page after successful deletion
            header('Location: room_logs.php?deleteOccStatus=success');
            exit();
        } else {
            // Handle the error case, potentially redirecting with an error flag or displaying an error message
            header('Location: room_logs.php?deleteOccStatus=error');
            exit();
        }
    }

    // Deactivate Occupancy
    if (isset($_POST['deact-occupancy-id'])) {

        $deactOccInfo = $_POST['deact-occupancy-id'];

        $result = RoomlogsController::deact_occupancy($deactOccInfo);
        if ($result) {
            // Redirect to the same page or to a confirmation page after successful deletion
            header('Location: room_logs.php?deactOccStatus=success');
            exit();
        } else {
            // Handle the error case, potentially redirecting with an error flag or displaying an error message
            header('Location: room_logs.php?deactOccStatus=error');
            exit();
        }
    }

}
?>

<script src="../js/roomLogsGeneral.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js" integrity="sha512-IOebNkvA/HZjMM7MxL0NYeLYEalloZ8ckak+NDtOViP7oiYzG5vn6WVXyrJDiJPhl4yRdmNAG49iuLmhkUdVsQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
	$(document).ready(function() {
        $("#new-rent-tenant").selectize();
		$("#share-new-rent-tenant").selectize();
    });

	document.getElementById('new-rent-type').addEventListener('change', function () {
		const selectedValue = this.value;
    
		if (selectedValue) {
			const [occTypeID, occRate] = selectedValue.split('|');

			const inputOccTypeID = document.getElementById('new-rent-occ-typeID');
			inputOccTypeID.value = occTypeID;
			
			// Example: Update elements based on occTypeID and occRate
			const viewOccupancyRate = document.getElementById('new-rent-rate');
			const actualOccupancyRate = document.getElementById('actual-new-rent-rate');
			
			viewOccupancyRate.value = occRate;
			actualOccupancyRate.value = occRate;

			if (inputOccTypeID.value == 6) {
				// Show the share tenant input
				document.getElementById('shared-tenant').style.display = 'block';
			} else {
				// Hide the share tenant input
				document.getElementById('shared-tenant').style.display = 'none';
			}

			
		}
	});


	// End Date Setter for Add New Rent
	document.getElementById('new-rent-start').addEventListener('change', function() {
		const startDate = new Date(this.value);

		if(!isNaN(startDate.getTime())) {
			const endDate = new Date(startDate);
			endDate.setDate(endDate.getDate() + 30);

			const endDateString = endDate.toISOString().split('T')[0];
			document.getElementById('new-rent-end').value = endDateString;
		} else {
			alert("Invalid start date");
		}
	});
</script>

<?php 
ob_end_flush();
html_end(); 
?>

