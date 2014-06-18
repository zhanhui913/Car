//Handles Add Entry Submit button
$(function(){
	$("#addForm").submit(function(){
		$.ajax({
			url : "controller/controller.php",
			type: "POST",
			data: $("#addForm").serialize()+"&action=add",
			success: function(data){
				$("#entryTable").html(data);
			}
		});
		clearInput("#addForm");
		return false;
	});
});

//Handles List Entry button
$(function(){
	$("#list").click(function(){
		$.ajax({
			url : "controller/controller.php",
			type: "POST",
			data: "action=list",
			success: function(data){
				$("#entryTable").html(data);
			}
		});
		return false;
	});
});

//Handles Remove Entry button
$(document).ready(function(){
    $("#entryTable").on("click", ".delete",function() {    	
    	var id = $(this).closest("tr").attr("id");
    	
    	$.ajax({
    		url: "controller/controller.php",
    		type: "POST",
    		data: "id="+id+"&action=remove", 
    		success: function(data){
    			$("#entryTable").html(data);
    		}
    	});
		return false;
	});
});

//Handles Edit Entry Button
$(document).ready(function(){
	$("#entryTable").on("click",".edit",function(){
		$("#dialog").dialog( "open" );
		
		var id = $(this).closest("tr").attr("id");
    	var make = null;
    	var model= null;
    	var year = null;
    	var odo  = null;
    	var main = null;

    	//Storing the ID of the entry in the page but users dont need to know this info
    	$("#editEntryId").prop("title",id);

		$.ajax({
			url : "controller/controller.php",
			type: "POST",
			data: "id="+id+"&action=update", 
			dataType : 'json',
			success : function(data){
    	   		make  = data['make'];
    			model = data['model'];
    			year  = data['year'];
    			odo   = data['odo'];
    			main  = data['main'];
				
				//Set entry values into the field
				$("#dropdownEditMake").val(make);
				
				getModel(make,"editModel");
				$("#dropdownEditModel").val(model);	
				
				$("#textEditYear").val(year);
				$("#textEditOdo").val(odo);
			}
		});
		return false;
	});
	$( "#dialog" ).dialog({ 
		autoOpen: false,
		width : "500px",
		resizable : false,
		modal : true,
		close : function(){
			clearInput("#editForm");	
		} 
	});
	$("#dropdownEditModel").val("X6");
});

//When clicking the save button in the update modal
$(document).ready(function(){
	$("#editForm").submit(function(){
		var id = $("#editEntryId").prop("title");
		$.ajax({
			url : "controller/controller.php",
			type: "POST",
			data: $("#editForm").serialize()+"&id="+id+"&action=updateAdd",
			success: function(data){
				$("#entryTable").html(data);
				$("#dialog").dialog("close");
			}
		});
		return false;
	});
});

//Resets all entry
function clearInput(Source) {
	$(Source)[0].reset();
}

//Instructions for the user to delete an entry
$(function(){
	$("#delete").click(function(){
		alert("To delete an entry, click the X button to the right of the entry");
	});
});

//Instructions for the user to delete an entry
$(function(){
	$("#update").click(function(){
		alert("To update an entry, click the E button to the right of the entry.\nThen you must select the model and the maintenance you wish to update to.");
	});
});

//Handles the change of the Model dropdownList
function getModel(MakeId,Source){
	var mainSource=null;
	if(Source=="addModel"){
		mainSource="addMain";
	}else if(Source=="editModel"){
		mainSource="editMain";
	}
	$.ajax({
		url : "controller/dropdown.php",
		type : "GET",
		data : "make="+MakeId+"&source="+mainSource+"&action=findModel",
		success : function(data){
			$("#"+Source).html(data);
		}
	});
	return false;
}

//Handles the change of the Maintenance dropdownList
function getMaintenance(ModelId,Source){
	$.ajax({
		url : "controller/dropdown.php",
		type : "GET",
		data : "model="+ModelId+"&source="+Source+"&action=findMaintenance",
		success : function(data){
			$("#"+Source).html(data);
		}
	});
	return false;
}


