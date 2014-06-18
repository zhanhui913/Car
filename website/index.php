<!DOCTYPE html>
<html>
	<head>
		<script src="script/jquery-1.8.1.min.js" type="text/javascript"></script>
		<script src="script/myScript.js" type="text/javascript"></script>
		<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
	</head>
	<body>
		<div id="add">
			<h1>Zhan H. Yap</h1>
			<hr>
			<h1>Adding new entry</h1>
			<h6>*  Required field</h6>
			<form method="post" id="addForm" action="addEntry.php">
				<table>
					<tr>
						<td>Make : </td>
						<td>
							<?php
								require_once '../backEnd/Car.php';
								$car = new Car();

								$makeList = $car->getAllCarMake();
								$car->closeConnection();
							?>
							<select name="make" id="dropdownAddMake" onchange="getModel(this.value,'addModel')">
								<option>Select a Make</option>
								<?php for($i=0;$i<sizeof($makeList);$i++){ ?>
								<option value=<?php echo "\"".$makeList[$i]['make']."\"" ?>><?php echo $makeList[$i]['make']?></option>
								<?php } ?>
							</select>
						</td>
						<td>*</td>
					</tr>
					<tr>
						<td>Model : </td>
						<td>
							<div id="addModel">
								<select name="model" id="dropdownAddModel">
									<option>Select Make First</option>
								</select>
							</div>
						</td>
						<td>*</td>
					</tr>
					<tr>
						<td>Year : </td>
						<td><input type="text" name="year" id="textAddYear"></td>
						<td>*</td>
					</tr>
					<tr>
						<td>Odometer : </td>
						<td><input type="text" name="odo" id="textAddOdo"></td>
						<td>*</td>
					</tr>
					<tr>
						<td>Maintenance : </td>
						<td>
							<div id="addMain">
								<select name="maintenance" id="dropdownAddMain">
									<option>Select Model First</option>
								</select>
							</div>
						</td>
						<td>*</td>
					</tr>
				</table>
				<input type="submit" id="sub" value="Add Entry">
			</form>
			<span id="result"></span>
		</div>
		<hr>
		<button type="button" id="list">List Entry</button>
		<button type="button" id="update" >Update Entry</button>
		<button type="button" id="delete">Delete Entry</button>
		<hr>
		<div id="dialog" title="Edit Entry">
			<form method="post" id="editForm" action="">
				<table>
					<tr>
						<td><p id="editEntryId" title="" name="id"></p></td>
					</tr>
					<tr>
						<td>Make : </td>
						<td>
							<select name="make" id="dropdownEditMake" onchange="getModel(this.value,'editModel')">
								<option>Select a Make</option>
								<?php for($i=0;$i<sizeof($makeList);$i++){ ?>
								<option value=<?php echo "\"".$makeList[$i]['make']."\"" ?>><?php echo $makeList[$i]['make']?></option>
								<?php } ?>
							</select>
						</td>
						<td>*</td>
					</tr>
					<tr>
						<td>Model : </td>
						<td>
							<div id="editModel">
								<select name="model" id="dropdownEditModel">
									<option>Select Make First</option>
								</select>
							</div>
						</td>
						<td>*</td>
					</tr>
					<tr>
						<td>Year : </td>
						<td><input type="text" name="year" id ="textEditYear"></td>
						<td>*</td>
					</tr>
					<tr>
						<td>Odometer : </td>
						<td><input type="text" name="odo" id ="textEditOdo"></td>
						<td>*</td>
					</tr>
					<tr>
						<td>Maintenance : </td>
						<td>
							<div id="editMain">
								<select name="maintenance" id="dropdownEditMain">
									<option>Select Model First</option>
								</select>
							</div>
						</td>
						<td>*</td>
					</tr>
					<tr>
						<td><input type="submit" id="saveEdit" value="Save"></td>
					</tr>
				</table>
			</form>
		</div>

		<div id="display">
			<table border='1' id='entryTable'>
				<thead>
					<tr>
						<th>Year</th>
						<th>Make</th>
						<th>Model</th>
						<th>Type</th>
						<th>Odometer</th>
						<th>Maintenance</th>
						<th>Edit</th>
						<th>Delete</th>
					</tr>
				</thead>
			</table>
		</div>
	</body>
</html>